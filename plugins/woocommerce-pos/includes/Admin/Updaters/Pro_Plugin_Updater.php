<?php
/**
 * Pro plugin updater.
 *
 * @author    Paul Kilmurray <paul@kilbot.com>
 *
 * @see      http://wcpos.com
 * @package WCPOS\WooCommercePOS\Updater
 */

namespace WCPOS\WooCommercePOS\Admin\Updaters;

use Parsedown;
use WCPOS\WooCommercePOS\Logger;
use WP_Error;

/**
 * Handles the update checking for the Pro plugin
 */
class Pro_Plugin_Updater {
	/**
	 * The Pro plugin slug
	 *
	 * @var string $pro_plugin_slug
	 */
	private $pro_plugin_slug = 'woocommerce-pos-pro';

	/**
	 * The path to the Pro plugin
	 *
	 * @var string $pro_plugin_path
	 */
	private $pro_plugin_path = 'woocommerce-pos-pro/woocommerce-pos-pro.php';

	/**
	 * The update server URL
	 *
	 * @var string $update_server
	 */
	private $update_server = 'https://updates.wcpos.com/pro';

	/**
	 * Transient key for the update data
	 *
	 * @var string $update_data_transient_key
	 */
	private $update_data_transient_key = 'woocommerce_pos_pro_update_data';

		/**
		 * Transient key for the update data
		 *
		 * @var string $update_data_transient_key
		 */
	private $license_status_transient_key = 'woocommerce_pos_pro_license_status';

	/**
	 * Whether the Pro plugin is installed
	 *
	 * @var bool $installed
	 */
	private $installed;

	/**
	 * Whether the Pro plugin is active
	 *
	 * @var bool $active
	 */
	private $active;

	/**
	 * The current version of the Pro plugin
	 *
	 * @var string $current_version
	 */
	private $current_version;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Ensure the necessary functions are available
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$status = $this->check_pro_plugin_status();
		$this->installed = $status['installed'];
		$this->active = $status['active'];
		$this->current_version = $status['version'];

		// Allow the update server to be overridden for development.
		if ( isset( $_ENV['WCPOS_PRO_UPDATE_SERVER'] ) ) {
			$this->update_server = $_ENV['WCPOS_PRO_UPDATE_SERVER'];
		}

		if ( $this->installed ) {
			add_filter( 'update_plugins_updates.wcpos.com', array( $this, 'update_plugins' ), 10, 4 );
			add_action( 'upgrader_process_complete', array( $this, 'after_plugin_update' ), 10, 2 );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
			add_action( 'in_plugin_update_message-' . $this->pro_plugin_path, array( $this, 'plugin_update_message' ), 10, 2 );
			add_action( 'install_plugins_pre_plugin-information', array( $this, 'plugin_information' ), 5 );
		}
	}

	/**
	 * Check the status of the Pro plugin
	 *
	 * @return array(
	 *  'installed' => bool,
	 *  'active'    => bool,
	 *  'version'   => string|null,
	 * )
	 */
	public function check_pro_plugin_status() {
		$status = array(
			'installed' => false,
			'active'    => false,
			'version'   => null,
		);

		// Check if the Pro plugin file exists.
		if ( file_exists( WP_PLUGIN_DIR . '/' . $this->pro_plugin_path ) ) {
			$status['installed'] = true;
			$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $this->pro_plugin_path );
			$status['version'] = $plugin_data['Version'];

			// Check if the Pro plugin is active.
			if ( is_plugin_active( $this->pro_plugin_path ) ) {
				$status['active'] = true;
			}
		}

		/**
		 * This is a hack to manually trigger Pro update for version < 1.4.0
		 * TODO: remove this after 1.4.0 is released for a while
		 */
		if ( $status['version'] && version_compare( $status['version'], '1.4.0', '<' ) ) {
			// Manually the update_plugins transient
			$update_plugins = get_site_transient( 'update_plugins' );
			if ( ! is_object( $update_plugins ) ) {
					$update_plugins = new stdClass();
			}

			$license_settings = $this->get_license_settings();
			$key = isset( $license_settings['key'] ) ? $license_settings['key'] : '';
			$instance = isset( $license_settings['instance'] ) ? $license_settings['instance'] : '';

			// Construct the download URL, taking into account the environment
			$package_url = add_query_arg(
				array(
					'key' => urlencode( $key ),
					'instance' => urlencode( $instance ),
				),
				'https://updates.wcpos.com/pro/download/1.4.1'
			);

			$update = array(
				'id'             => 'https://updates.wcpos.com',
				'slug'           => 'woocommerce-pos-pro',
				'plugin'         => $this->pro_plugin_path,
				'new_version'    => '1.4.1',
				'url'            => 'https://wcpos.com/pro',
				'package'        => $package_url,
				'requires'       => '5.6',
				'tested'         => '6.5',
				'requires_php'   => '7.4',
				'icons'          => array(
					'1x' => 'https://wcpos.com/wp-content/uploads/2014/06/woopos-pro.png',
				),
				'upgrade_notice' => $this->maybe_add_upgrade_notice(),
			);

			$update_plugins->response[ $this->pro_plugin_path ] = (object) $update;

			set_site_transient( 'update_plugins', $update_plugins );
		}

		return $status;
	}

	/**
	 * Filters the update response for a given plugin hostname.
	 *
	 * @param array|false $update {
	 *     The plugin update data with the latest details. Default false.
	 *
	 *     @type string $id           Optional. ID of the plugin for update purposes, should be a URI
	 *                                specified in the `Update URI` header field.
	 *     @type string $slug         Slug of the plugin.
	 *     @type string $version      The version of the plugin.
	 *     @type string $url          The URL for details of the plugin.
	 *     @type string $package      Optional. The update ZIP for the plugin.
	 *     @type string $tested       Optional. The version of WordPress the plugin is tested against.
	 *     @type string $requires_php Optional. The version of PHP which the plugin requires.
	 *     @type bool   $autoupdate   Optional. Whether the plugin should automatically update.
	 *     @type array  $icons        Optional. Array of plugin icons.
	 *     @type array  $banners      Optional. Array of plugin banners.
	 *     @type array  $banners_rtl  Optional. Array of plugin RTL banners.
	 *     @type array  $translations {
	 *         Optional. List of translation updates for the plugin.
	 *
	 *         @type string $language   The language the translation update is for.
	 *         @type string $version    The version of the plugin this translation is for.
	 *                                  This is not the version of the language file.
	 *         @type string $updated    The update timestamp of the translation file.
	 *                                  Should be a date in the `YYYY-MM-DD HH:MM:SS` format.
	 *         @type string $package    The ZIP location containing the translation update.
	 *         @type string $autoupdate Whether the translation should be automatically installed.
	 *     }
	 * }
	 * @param array       $plugin_data      Plugin headers.
	 * @param string      $plugin_file      Plugin filename.
	 * @param string[]    $locales          Installed locales to look up translations for.
	 */
	public function update_plugins( $update, $plugin_data, $plugin_file, $locales ) {
		$update_data = $this->check_pro_plugin_updates();
		$is_development = isset( $_ENV['DEVELOPMENT'] ) && $_ENV['DEVELOPMENT'];

		// Check if update data is valid and if a new version is available.
		if ( ! is_wp_error( $update_data ) && is_object( $update_data ) && isset( $update_data->version ) ) {
			$latest_version = $update_data->version;

			// No update needed if the current version is greater than the latest version.
			if ( version_compare( $this->current_version, $latest_version, '>' ) ) {
				return $update;
			}

			$license_settings = $this->get_license_settings();
			$key = isset( $license_settings['key'] ) ? $license_settings['key'] : '';
			$instance = isset( $license_settings['instance'] ) ? $license_settings['instance'] : '';

			// Construct the download URL, taking into account the environment
			$download_url = $is_development
				? 'http://localhost:8080/pro/download/1.4.0'
				: $update_data->download_url;

			$package_url = add_query_arg(
				array(
					'key' => urlencode( $key ),
					'instance' => urlencode( $instance ),
				),
				$download_url
			);

			$update = array(
				'id'           => 'https://updates.wcpos.com',
				'slug'         => 'woocommerce-pos-pro',
				'plugin'       => $this->pro_plugin_path,
				'version'      => $latest_version,
				'url'          => 'https://wcpos.com/pro',
				'package'      => $package_url,
				'requires'     => '5.6',
				'tested'       => '6.5',
				'requires_php' => '7.4',
				'icons' => array(
					'1x' => 'https://wcpos.com/wp-content/uploads/2014/06/woopos-pro.png',
				),
				'upgrade_notice' => $this->maybe_add_upgrade_notice(),
			);
		}

		return $update;
	}

	/**
	 * Check for updates to the Pro plugin
	 *
	 * @param  bool $force Force an update check.
	 */
	public function check_pro_plugin_updates( $force = false ) {
		$update_data = get_transient( $this->update_data_transient_key );

		if ( empty( $update_data ) || $force ) {
			$expiration = 60 * 60 * 12; // 12 hours.
			$url = $this->update_server . '/update/' . $this->current_version;

			// make the api call.
			$response = wp_remote_get(
				$url,
				array(
					'timeout' => wp_doing_cron() ? 10 : 3,
					'headers' => array(
						'Accept' => 'application/json',
					),
				)
			);

			$data = $this->validate_api_response( $response );

			// Ensure $data has version, download_url and notes.
			if ( ! is_wp_error( $data ) ) {
				$expected_properties = array( 'version', 'download_url', 'notes' );
				foreach ( $expected_properties as $property ) {
					if ( ! property_exists( $data, $property ) ) {
						$data = new WP_Error( 'invalid_response_structure', "Missing expected property: $property" );
						break;
					}
				}
			}

			if ( is_wp_error( $data ) ) {
				Logger::log( $data );
				$expiration = 60 * 60 * 1; // try again in an hour if error.
			}

			set_transient( $this->update_data_transient_key, $data, $expiration );
		}

		return $update_data;
	}

	/**
	 * Check the license status
	 *
	 * @param  bool $force Force an update check.
	 */
	private function check_license_status( $force = false ) {
		$license_status = get_transient( $this->license_status_transient_key );
		$expiration = 60 * 60 * 12; // 12 hours.

		/**
		 * TODO: How to allow for multisite?
		 */
		if ( is_multisite() ) {
			$error = new WP_Error( 'multisite_update', 'Please go to http://wcpos.com/my-account to download update.' );
			set_transient( $this->license_status_transient_key, $error, $expiration );
			return $error;
		}

		$license_settings = $this->get_license_settings();
		$key = isset( $license_settings['key'] ) ? $license_settings['key'] : '';
		$instance = isset( $license_settings['instance'] ) ? $license_settings['instance'] : '';

		/**
		 * If the Pro plugin is not activated, add a notice
		 */
		if ( empty( $key ) || empty( $instance ) ) {
			// set the transient to an error.
			$error = new WP_Error( 'missing_license_key', 'License key is not activated.' );
			set_transient( $this->license_status_transient_key, $error, $expiration );
			return $error;
		}

		if ( empty( $license_status ) || $force ) {
			// build the request.
			$url = add_query_arg(
				array(
					'key' => urlencode( $key ),
					'instance' => urlencode( $instance ),
				),
				$this->update_server . '/license/status'
			);

			// make the api call.
			$response = wp_remote_get(
				$url,
				array(
					'timeout' => wp_doing_cron() ? 10 : 3,
					'headers' => array(
						'Accept' => 'application/json',
					),
				)
			);

			$data = $this->validate_api_response( $response );

			if ( is_wp_error( $data ) ) {
				Logger::log( $data );
				$expiration = 60 * 60 * 1; // try again in an hour if error.
			}

			set_transient( $this->license_status_transient_key, $data, $expiration );
		}

		return $license_status;
	}

	/**
	 * Validate the API response
	 *
	 * @param  array|WP_Error $response The API response.
	 *
	 * @return object|WP_Error $response The validated API response.
	 */
	public function validate_api_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$decoded_response = json_decode( $response['body'] );
		if ( null === $decoded_response ) {
			return new WP_Error( 'invalid_json', 'Invalid JSON in response', $response['body'] );
		}

		if ( $response['response']['code'] !== 200 ) {
			$error = isset( $decoded_response->error ) ? $decoded_response->error : 'No error message returned from server';
			return new WP_Error( 'invalid_response_code', $error );
		}

		// Ensure $decoded_response has the expected structure.
		if ( ! isset( $decoded_response->data ) ) {
			return new WP_Error( 'invalid_response_structure', 'Missing expected property: data' );
		}

		return $decoded_response->data;
	}


	/**
	 * Create an update response object for the WordPress transient
	 * NOTE: this transient is different to the one we use to store the update data
	 *
	 * @param  array $args {
	 *  Arguments for creating the update data object.
	 *
	 *  @type string   $new_version  New plugin version.
	 *  @type string   $package      Plugin update package URL.
	 * }
	 * @return object {
	 *     An object of metadata about the available plugin update.
	 *
	 *     @type string   $id           Plugin ID, e.g. `w.org/plugins/[plugin-name]`.
	 *     @type string   $slug         Plugin slug.
	 *     @type string   $plugin       Plugin basename.
	 *     @type string   $new_version  New plugin version.
	 *     @type string   $url          Plugin URL.
	 *     @type string   $package      Plugin update package URL.
	 *     @type string[] $icons        An array of plugin icon URLs.
	 *     @type string[] $banners      An array of plugin banner URLs.
	 *     @type string[] $banners_rtl  An array of plugin RTL banner URLs.
	 *     @type string   $requires     The version of WordPress which the plugin requires.
	 *     @type string   $tested       The version of WordPress the plugin is tested against.
	 *     @type string   $requires_php The version of PHP which the plugin requires.
	 * }
	 */
	private function create_update_response_object( $args = array() ) {
		$defaults = array(
			'id'           => '0',
			'slug'         => 'woocommerce-pos-pro',
			'plugin'       => $this->pro_plugin_path,
			'url'          => 'https://wcpos.com/pro',
			'requires'     => '5.6',
			'tested'       => '6.5',
			'requires_php' => '7.4',
			'icons' => array(
				'1x' => 'https://wcpos.com/wp-content/uploads/2014/06/woopos-pro.png',
			),
		);

		$parsed_args = wp_parse_args( $args, $defaults );

		$update_obj = new \stdClass();
		foreach ( $parsed_args as $key => $value ) {
			$update_obj->$key = $value;
		}

		return $update_obj;
	}

	/**
	 * Force a check for updates on the Pro plugin after the free plugin has been updated
	 *
	 * @param  object $upgrader The upgrader object.
	 * @param  array  $options  The upgrader options.
	 */
	public function after_plugin_update( $upgrader, $options ) {
		if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
			if ( isset( $options['plugins'] ) && in_array( 'woocommerce-pos/woocommerce-pos.php', $options['plugins'] ) ) {
				delete_transient( $this->update_data_transient_key );
				$this->check_pro_plugin_updates();
			}
		}
	}

	/**
	 * Filters the array of row meta for each plugin in the Plugins list table.
	 *
	 * @since 2.8.0
	 *
	 * @param string[] $plugin_meta An array of the plugin's metadata, including
	 *                              the version, author, author URI, and plugin URI.
	 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array    $plugin_data {
	 *     An array of plugin data.
	 *
	 *     @type string   $id               Plugin ID, e.g. `w.org/plugins/[plugin-name]`.
	 *     @type string   $slug             Plugin slug.
	 *     @type string   $plugin           Plugin basename.
	 *     @type string   $new_version      New plugin version.
	 *     @type string   $url              Plugin URL.
	 *     @type string   $package          Plugin update package URL.
	 *     @type string[] $icons            An array of plugin icon URLs.
	 *     @type string[] $banners          An array of plugin banner URLs.
	 *     @type string[] $banners_rtl      An array of plugin RTL banner URLs.
	 *     @type string   $requires         The version of WordPress which the plugin requires.
	 *     @type string   $tested           The version of WordPress the plugin is tested against.
	 *     @type string   $requires_php     The version of PHP which the plugin requires.
	 *     @type string   $upgrade_notice   The upgrade notice for the new plugin version.
	 *     @type bool     $update-supported Whether the plugin supports updates.
	 *     @type string   $Name             The human-readable name of the plugin.
	 *     @type string   $PluginURI        Plugin URI.
	 *     @type string   $Version          Plugin version.
	 *     @type string   $Description      Plugin description.
	 *     @type string   $Author           Plugin author.
	 *     @type string   $AuthorURI        Plugin author URI.
	 *     @type string   $TextDomain       Plugin textdomain.
	 *     @type string   $DomainPath       Relative path to the plugin's .mo file(s).
	 *     @type bool     $Network          Whether the plugin can only be activated network-wide.
	 *     @type string   $RequiresWP       The version of WordPress which the plugin requires.
	 *     @type string   $RequiresPHP      The version of PHP which the plugin requires.
	 *     @type string   $UpdateURI        ID of the plugin for update purposes, should be a URI.
	 *     @type string   $Title            The human-readable title of the plugin.
	 *     @type string   $AuthorName       Plugin author's name.
	 *     @type bool     $update           Whether there's an available update. Default null.
	 * }
	 * @param string   $status      Status filter currently applied to the plugin list. Possible
	 *                              values are: 'all', 'active', 'inactive', 'recently_activated',
	 *                              'upgrade', 'mustuse', 'dropins', 'search', 'paused',
	 *                              'auto-update-enabled', 'auto-update-disabled'.
	 *
	 * @return string[] An array of row meta.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if ( $plugin_file !== $this->pro_plugin_path ) {
			return $plugin_meta;
		}

		// $license_status = 'Your license status here'; // Fetch or generate your license status message
		// $plugin_meta[] = '<span style="color: #d63638;">' . esc_html( $license_status ) . '</span>';

		return $plugin_meta;
	}

	/**
	 * Fires at the end of the update message container in each
	 * row of the plugins list table.
	 *
	 * The dynamic portion of the hook name, `$file`, refers to the path
	 * of the plugin's primary file relative to the plugins directory.
	 *
	 * @since 2.8.0
	 *
	 * @param array  $plugin_data An array of plugin metadata. See get_plugin_data()
	 *                            and the {@see 'plugin_row_meta'} filter for the list
	 *                            of possible values.
	 * @param object $response {
	 *     An object of metadata about the available plugin update.
	 *
	 *     @type string   $id           Plugin ID, e.g. `w.org/plugins/[plugin-name]`.
	 *     @type string   $slug         Plugin slug.
	 *     @type string   $plugin       Plugin basename.
	 *     @type string   $new_version  New plugin version.
	 *     @type string   $url          Plugin URL.
	 *     @type string   $package      Plugin update package URL.
	 *     @type string[] $icons        An array of plugin icon URLs.
	 *     @type string[] $banners      An array of plugin banner URLs.
	 *     @type string[] $banners_rtl  An array of plugin RTL banner URLs.
	 *     @type string   $requires     The version of WordPress which the plugin requires.
	 *     @type string   $tested       The version of WordPress the plugin is tested against.
	 *     @type string   $requires_php The version of PHP which the plugin requires.
	 * }
	 */
	public function plugin_update_message( $plugin_data, $response ) {
		if ( $response->plugin !== $this->pro_plugin_path ) {
			return;
		}

		$license_status = $this->check_license_status();
		if ( ! is_wp_error( $license_status ) ) {
			return;
		}

		$message = 'Your license has expired. <a href="http://wcpos.com/my-account/">Please renew</> to update.';

		if ( $license_status->get_error_code() === 'missing_license_key' ) {
			$message = $license_status->get_error_message();
		}

		echo '<br /><span style="color: #d63638;">' . wp_kses_post( $message ) . '</span>';
	}

	/**
	 * Display the plugin information iframe for the Pro plugin
	 */
	public function plugin_information() {
		global $tab;

		if ( empty( $_REQUEST['plugin'] ) || $this->pro_plugin_slug !== $_REQUEST['plugin'] ) {
			return;
		}

		$update_data = get_transient( $this->update_data_transient_key );
		$message = 'Something went wrong. Please try again later.';

		if ( is_wp_error( $update_data ) ) {
			$message = $update_data->get_error_message();
		}

		if ( is_object( $update_data ) && isset( $update_data->notes ) ) {
			$parsedown = new Parsedown();
			$message = $parsedown->text( $update_data->notes );
		}

		iframe_header( __( 'Plugin Installation' ) );

		/**
		 * Output some custom CSS to make the release notes look nice.
		 */
		echo '<style>
			h1 {padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid #dcdcde;}
			h2 {font-size:1.6em;margin:0;padding:20px 0 10px;}
			.plugin-install-php h3 {margin:0;padding:10px 0 5px;}
			img {max-width:100%;}
		</style>';

		/**
		 * Output release notes from GitHub, markdown -> HTML
		 */
		echo '<div id="plugin-information-scrollable">';
		echo '<div style="padding:10px 26px">';
		echo wp_kses_post( $message );
		echo '</div>';
		echo "</div>\n";

		/**
		 * Make sure any links in the content open in a new tab.
		 */
		echo "<script>
			var links = document.getElementsByTagName('a');
			for (var i = 0; i < links.length; i++) {
					links[i].setAttribute('target', '_blank');
			}
		</script>";

		iframe_footer();
		exit;
	}

	/**
	 * Maybe add a notice to the plugin update table on Dashboard > Updates
	 */
	private function maybe_add_upgrade_notice() {
		$license_status = $this->check_license_status();
		if ( ! is_wp_error( $license_status ) ) {
			return;
		}

		if ( $license_status->get_error_code() === 'missing_license_key' ) {
			return $license_status->get_error_message();
		}

		return 'Your license has expired. Please renew to update.';
	}

	/**
	 * Get the license settings
	 *
	 * NOTE: it's possible the Pro plugin is not activated, so we need to add a bit of a hack to get the settings.
	 */
	private function get_license_settings() {
		if ( ! $this->active ) {
			if ( file_exists( WP_PLUGIN_DIR . '/woocommerce-pos-pro/includes/Services/Settings.php' ) ) {
				include_once WP_PLUGIN_DIR . '/woocommerce-pos-pro/includes/Services/Settings.php';
				if ( method_exists( '\WCPOS\WooCommercePOSPro\Services\Settings', '_get_inactive_license_settings' ) ) {
					return \WCPOS\WooCommercePOSPro\Services\Settings::_get_inactive_license_settings();
				}
			}
		}
		return woocommerce_pos_get_settings( 'license' );
	}
}
