<?php
/**
 * Controller for managing WP plugins.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful\Controller;

/**
 * WP REST API Menu routes
 */

use Throwable;
use Watchful\Helpers\Files as FilesHelper;
use Watchful\Helpers\Authentification;
use Watchful\Skins\SkinPluginUpgrader;
use Watchful\Exception;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;
use \Plugin_Upgrader;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful plugins class.
 */
class Plugins implements BaseControllerInterface {
	/**
	 * Potential slugs for plugins.
	 *
	 * @var array
	 */
	private $potential_slugs;

    /**
	 * Register WP REST API routes.
	 */
	public function register_routes() {
		register_rest_route(
			'watchful/v1',
			'/plugin/install',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'install_plugin' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'slug'   => array(
								'default'           => null,
								'sanitize_callback' => 'esc_attr',
							),
							'zip'    => array(
								'default'           => null,
								'sanitize_callback' => 'esc_url',
							),
							'status' => array(
								'default'           => 1,
								'sanitize_callback' => 'wp_validate_boolean',
							),
						)
					),
				),
			)
		);

		register_rest_route(
			'watchful/v1',
			'/plugin/update',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_plugin' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'slug' => array(
								'default'           => null,
								'sanitize_callback' => 'esc_attr',
							),
							'zip'  => array(
								'default'           => null,
								'sanitize_callback' => 'esc_url',
							),
						)
					),
				),
			)
		);

		register_rest_route(
			'watchful/v1',
			'/plugin/activate',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'activate_plugin' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'slug'   => array(
								'default'           => null,
								'sanitize_callback' => 'esc_attr',
							),
							'status' => array(
								'default'           => 1,
								'sanitize_callback' => 'wp_validate_boolean',
							),
						)
					),
				),
			)
		);
	}

	/**
	 * Enable or disable a plugin.
	 *
	 * @param WP_REST_Request $request The request with the plugin info.
	 *
	 * @return WP_REST_Response
	 * @throws Exception If plugin info is not available in request.
	 */
	public function activate_plugin( WP_REST_Request $request ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$plugin_path = $this->get_plugin_path( $request->get_param( 'slug' ) );

		if ( $request->get_param( 'status' ) ) {
			$result = activate_plugin( $plugin_path );
		} else {
			$result = deactivate_plugins( $plugin_path );
		}

		if ( is_wp_error( $result ) ) {
			throw new Exception( 'plugin state could not be changed : ' . $result->get_error_message(), 400 );
		}

        return new WP_REST_Response(true);
	}

	/**
	 * Update a plugin from his slug.
	 *
	 * @param WP_REST_Request $request The request with the plugin info.
	 *
	 * @return mixed
	 * @throws Exception If plugin info is not available in request.
	 */
	public function update_plugin( WP_REST_Request $request ) {
		include_once ABSPATH . 'wp-admin/includes/admin.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		include_once ABSPATH . WPINC . '/update.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

		$body      = $request->get_body();
		$post_data = array();
        $enable_maintenance_mode = false;
		if ( ! empty( $body ) ) {
			$post_data = json_decode( $body );
			if ( ! empty( $post_data ) && ! empty( $post_data->package ) ) {
				$zip = $post_data->package;
			}
            if ( ! empty( $post_data ) && ! empty( $post_data->maintenance_mode ) ) {
                $enable_maintenance_mode = (bool)$post_data->maintenance_mode;
            }
		}

		$slug = $request->get_param( 'slug' );

		if ( empty( $zip ) ) {
			// Has this if coming from install route with zip parameter.
			$zip = $request->get_param( 'zip' );
		}

		if ( empty( $slug ) && empty( $zip ) ) {
			throw new Exception( 'parameter is missing. slug required or zip', 400 );
		}

		if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
			throw new Exception( 'file modification is disabled (DISALLOW_FILE_MODS)', 403 );
		}

		// If slug is missing we need to get it from the zip.
		if ( $zip && ! $slug ) {
			$slug = $this->get_slug_from_zip( $zip );
		}

		// Required to do the update.
		$plugin_path = $this->get_plugin_path( $slug );

		// Get the current state.
		$is_active         = is_plugin_active( $plugin_path );
		$is_active_network = is_plugin_active_for_network( $plugin_path );

		// Force a plugin update check.
		wp_update_plugins();

		$skin     = new SkinPluginUpgrader();
		$upgrader = new Plugin_Upgrader( $skin );

        $min_php_version = $this->next_version_info($plugin_path);

		if ( $zip ) {
			$this->update_from_zip( $zip, $plugin_path );
		}

        if (version_compare(phpversion(), $min_php_version) < 0){
            throw new Exception("The minimum required PHP version for this update is ". $min_php_version, 500);
        }

        if ($enable_maintenance_mode) {
            WP_Filesystem();
            $upgrader->maintenance_mode(true);
        }

        try {
            $result = $upgrader->upgrade( $plugin_path );
            if ($enable_maintenance_mode) {
                $upgrader->maintenance_mode(false);
            }
        } catch (Throwable $e) {
            if ($enable_maintenance_mode) {
                $upgrader->maintenance_mode(false);
            }
            throw $e;
        }

		if ( is_wp_error( $result ) ) {
			throw new Exception( $result->get_error_code(), 500, $result->get_error_data() );
		}
		if ( is_wp_error( $skin->error ) ) {
			throw new Exception( $skin->error->get_error_code(), 500, $skin->error->get_error_data() );
		}

		// This default Exception should not be thrown because WP_Errors should be encountered just above.
		if ( false === $result || is_null( $result ) ) {
			throw new Exception( 'unknown error', 400 );
		}

		// Reactivate the plugin if he was active.
		if ( $is_active ) {
			activate_plugin( $plugin_path, '', $is_active_network, true );
		}

        return new WP_REST_Response([
				'status' => 'success',
				'version' => get_plugin_data(WP_PLUGIN_DIR.'/'.$plugin_path)['Version']
			]);
	}

	/**
	 * Install a plugin from his slug or from a zip.
	 *
	 * @param WP_REST_Request $request The request with the plugin info.
	 *
	 * @return mixed
	 * @throws Exception If plugin info is not available in request.
	 */
	public function install_plugin( WP_REST_Request $request ) {
        include_once ABSPATH . 'wp-admin/includes/admin.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$slug = $request->get_param( 'slug' );
		$zip  = $request->get_param( 'zip' );

		if ( ! $slug && ! $zip ) {
			throw new Exception( 'parameter is missing. slug or zip required', 400 );
		}

		if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
			throw new Exception( 'file modification is disabled (DISALLOW_FILE_MODS)', 403 );
		}

		// Install from slug.
		if ( $slug ) {
			$install_path = $this->download_link_from_slug( $slug );
		}

		// Install from zip.
		if ( $zip ) {
			$install_path = $this->download_link_from_zip( $zip );
		}

		$skin     = new SkinPluginUpgrader();
		$upgrader = new Plugin_Upgrader( $skin );

		$result = $upgrader->install( $install_path );

		if ( is_wp_error( $result ) ) {
			throw new Exception( 'installation of the plugin failed : ' . $result->get_error_message(), 400 );
		}

		if ( false === $result ) {
			throw new Exception( 'unknown error', 500 );
		}

		if ( $request->get_param( 'status' ) ) {
			// Plugin must be installed to get slug from zip.
			if ( $zip ) {
				$slug = $this->get_slug_from_list( $this->potential_slugs );

				// Use the filename if a slug could not be found (can happens if the plugin does not have a subdirectory like akeeba backup).
				if ( ! $slug ) {
					// Get the filename without extension.
					$info = pathinfo( $zip );
					$slug = basename( $zip, '.' . $info['extension'] );
				}
			}

			activate_plugin( $this->get_plugin_path( $slug ) );
		}

        return new WP_REST_Response(true);
	}

	/**
	 * Check that the slug is valid and get the download link
	 *
	 * @param string $slug Download slug.
	 *
	 * @return mixed|string
	 *
	 * @throws Exception If the API returns a WP_Error.
	 */
	private function download_link_from_slug( $slug ) {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		if ( $this->is_installed( $slug ) ) {
			$this->do_update( $slug );
		}

		$api_args = array(
			'slug'   => $slug,
			'fields' => array( 'sections' => false ),
		);
		$api      = plugins_api( 'plugin_information', $api_args );

		// Usually because slug is wrong.
		if ( is_wp_error( $api ) ) {
			throw new Exception( 'plugin not found on wordpress.org : ' . $api->get_error_message(), 400 );
		}

		return $api->download_link;
	}

	/**
	 * Check that the zip is valid.
	 *
	 * @param string $zip The zip file.
	 *
	 * @return mixed|string
	 */
	private function download_link_from_zip( $zip ) {
		$slug = $this->get_slug_from_zip( $zip );

		// Plugin is already installed.
		if ( $slug ) {
			$this->do_update( $slug, $zip );
		}

		return $zip;
	}

	/**
	 * Override the zip file in the plugins list used by the upgrader.
	 *
	 * @param string $zip         The zip file.
	 * @param string $plugin_path The plugin path.
	 */
	private function update_from_zip( $zip, $plugin_path ) {
		$current = get_site_transient( 'update_plugins' );

		if ( ! isset( $current->response[ $plugin_path ] ) ) {
			$current->response[ $plugin_path ] = new \stdClass();
		}

		$current->response[ $plugin_path ]->package = $zip;

		set_site_transient( 'update_plugins', $current );
	}

    private function  next_version_info($plugin_path) {
        $current = get_site_transient( 'update_plugins' );

        if (isset( $current->response[$plugin_path] ) ){
            return $current->response[$plugin_path]->requires_php;
        }
        return phpversion();
    }

	/**
	 * Try to get the plugin path from his slug.
	 *
	 * @param string $slug The plugin slug.
	 *
	 * @return string
	 *
	 * @throws Exception If no path is found.
	 */
	private function get_plugin_path( $slug ) {
		$plugins = get_plugins();

		foreach ( $plugins as $path => $plugin ) {
			if ( $path === $slug || in_array( $slug, explode( '/', $path ), true ) || stristr( $path, $slug ) ) {
				return $path;
			}
		}

		throw new Exception( 'could not find plugin path', 404 );
	}

	/**
	 * Get the slug of a plugin from his zip file.
	 *
	 * @param string $zip The zip file.
	 *
	 * @return bool
	 */
	private function get_slug_from_zip( $zip ) {
		$helper = new FilesHelper();

		$this->potential_slugs = $helper->get_zip_directories( $zip );

		return $this->get_slug_from_list( $this->potential_slugs );
	}

	/**
	 * Get the correct slug from a given list.
	 *
	 * @param array $list List of slugs.
	 *
	 * @return string|bool
	 */
	private function get_slug_from_list( $list ) {
		foreach ( $list as $slug ) {
			if ( $this->is_installed( $slug ) ) {
				return $slug;
			}
		}

		return false;
	}

	/**
	 * Switch to update if the plugin is already installed.
	 *
	 * @param string      $slug The plugin slug.
	 * @param null|string $zip  The zip file.
	 */
	private function do_update( $slug, $zip = null ) {
		$request = new WP_REST_Request();
		$request->set_param( 'slug', $slug );
		$request->set_param( 'zip', $zip );

		$this->update_plugin( $request );
	}

	/**
	 * Check if a plugin is already installed.
	 *
	 * @param string $slug The plugin slug.
	 *
	 * @return bool
	 */
	public function is_installed( $slug ) {
		$plugins = get_plugins();

		foreach ( $plugins as $path => $plugin ) {
			if ( $path === $slug || in_array( $slug, explode( '/', $path ), true ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Get all plugins from the current WP site.
	 *
	 * @param array $plugin_data Plugin data already in Watchful.
	 *
	 * @return array
	 */
	public function get_all_plugins( $plugin_data = array() ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . WPINC . '/update.php';

		if ( ! is_array( $plugin_data ) ) {
			$plugin_data = (array) $plugin_data;
		}

		// Get all plugins.
		$plugins = get_plugins();

		// Delete the transient so wp_update_plugins can get fresh data.
		if ( function_exists( 'get_site_transient' ) ) {
			delete_site_transient( 'update_plugins' );
		} else {
			delete_transient( 'update_plugins' );
		}

		// Force a plugin update check.
		wp_update_plugins();

		$current = get_site_transient( 'update_plugins' );

		// Premium plugins that have adopted the ManageWP API report new plugins by this filter.
		$watchful_updates = apply_filters( 'watchfulUpdateNotification', array() ); // phpcs:ignore WordPress.NamingConventions.ValidHookName

		foreach ( (array) $plugins as $plugin_file => $plugin ) {

			$plugins[ $plugin_file ]['active'] = is_plugin_active( $plugin_file );

			$ext_data = false;
			if ( ! empty( $plugin_data[ $plugin_file ] ) ) {
				$ext_data = ! empty( $plugin_data[ $plugin_file ]->ext_data ) ? $plugin_data[ $plugin_file ]->ext_data : false;

				if ( false !== $ext_data && ! empty( $ext_data->new_version ) ) {
					$plugins[ $plugin_file ]['latest_version'] = $ext_data->new_version;
				}
			}

			if ( ! $ext_data ) {
				$watchful_plugin_update = false;
				foreach ( $watchful_updates as $watchful_update ) {
					if ( ! empty( $watchful_update['Name'] ) && $plugin['Name'] === $watchful_update['Name'] ) {
						$watchful_plugin_update = $watchful_update;
					}
				}

				if ( $watchful_plugin_update ) {
					$plugins[ $plugin_file ]['latest_version'] = $watchful_plugin_update['new_version'];
				} elseif ( isset( $current->response[ $plugin_file ] ) ) {
					$plugins[ $plugin_file ]['latest_version'] = $current->response[ $plugin_file ]->new_version;
					$plugins[ $plugin_file ]['latest_package'] = $current->response[ $plugin_file ]->package;
					if (isset($current->response[ $plugin_file ]->slug)) {
						$plugins[$plugin_file]['slug'] = $current->response[$plugin_file]->slug;
					}
				} else {
					$plugins[ $plugin_file ]['latest_version'] = $plugin['Version'];
				}
			}
		}

		return $this->modify_mapping_plugin( $plugins );
	}

	/**
	 * Create the right mapping for Watchful API.
	 *
	 * @param array $plugins List of plugins.
	 *
	 * @return array
	 */
	private function modify_mapping_plugin( &$plugins ) {

		$output = array();

		foreach ( $plugins as $key => $plugin ) {
			$new_mapping                  = array();
            $new_mapping['name']          = $plugin['Name'];
            $new_mapping['realname']      = $key;
            $new_mapping['active']        = $plugin['active'];
            $new_mapping['authorurl']     = $plugin['PluginURI'];
            $new_mapping['version']       = $plugin['Version'];
            $new_mapping['updateVersion'] = $plugin['latest_version'];
            $new_mapping['vUpdate']       = $plugin['latest_version'] !== $plugin['Version'];
            $new_mapping['type']          = 'plugin';
            $new_mapping['network']       = $plugin['Network'];
            $new_mapping['creationdate']  = null;
            $new_mapping['updateServer']  = null;
            $new_mapping['extId']         = 0;
            $new_mapping['variant']       = null;
			$output[]                     = $new_mapping;
		}

		return $output;
	}

}
