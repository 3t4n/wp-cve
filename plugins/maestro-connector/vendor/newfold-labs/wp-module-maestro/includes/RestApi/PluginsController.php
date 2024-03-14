<?php

namespace NewfoldLabs\WP\Module\Maestro\RestApi;

use Exception;
use WP_REST_Server;
use WP_REST_Response;
use Plugin_Upgrader;
use WP_Ajax_Upgrader_Skin;

use NewfoldLabs\WP\Module\Maestro\Models\Plugin;
use NewfoldLabs\WP\Module\Maestro\Auth\WebPro;
use NewfoldLabs\WP\Module\Maestro\Util;

/**
 * Class PluginsController
 */
class PluginsController extends \WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @since 1.2
	 *
	 * @var string
	 */
	protected $namespace = 'bluehost/maestro/v1';

	/**
	 * The current Web Pro accessing the endpoint
	 *
	 * @since 1.2
	 *
	 * @var WebPro
	 */
	private $webpro;

	/**
	 * Registers the Plugins routes
	 *
	 * @since 1.2
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/plugins',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_plugins' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/plugins/upgrade',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'upgrade_plugin' ),
					'args'                => array(
						'slug' => array(
							'required' => true,
							'type'     => 'string',
						),
					),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/plugins/toggle-auto-update',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'toggle_auto_update' ),
					'args'                => array(
						'slug'        => array(
							'required' => true,
							'type'     => 'string',
						),
						'auto_update' => array(
							'required' => true,
							'type'     => 'boolean',
						),
					),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/plugins/toggle-auto-update-global',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'toggle_auto_update_global' ),
					'args'                => array(
						'auto_updates_enabled' => array(
							'required' => true,
							'type'     => 'boolean',
						),
					),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

	}

	/**
	 * Function to include the required classes and files
	 *
	 * @since 1.2
	 */
	private function load_wp_classes_and_functions() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! function_exists( 'get_option' ) ) {
			include_once ABSPATH . 'wp-includes/options.php';
		}

		if ( ! function_exists( 'plugin_dir_path' ) ) {
			include_once ABSPATH . 'wp-includes/plugin.php';
		}

		if ( ! function_exists( 'request_filesystem_credentials' ) ) {
			include_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! class_exists( 'WP_Upgrader' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		if ( ! class_exists( 'Plugin_Upgrader' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		}

		if ( ! class_exists( 'WP_Ajax_Upgrader_Skin' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
		}

		if ( ! class_exists( 'Plugin_Upgrader_Skin' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader-skin.php';
		}
	}

	/**
	 * Callback for the plugins get endpoint
	 *
	 * Returns a list of installed plugins with details and updates
	 *
	 * @since 1.2
	 *
	 * @return WP_Rest_Response Returns a standard rest response with a list of plugins
	 */
	public function get_plugins() {
		$this->load_wp_classes_and_functions();

		// Make sure we populate the plugins updates transient
		wp_update_plugins();

		$installed_plugins = get_plugins();
		$plugins           = array();
		$plugin_updates    = get_site_transient( 'update_plugins' );
		$auto_updates      = (array) get_site_option( 'auto_update_plugins', array() );

		foreach ( $installed_plugins as $plugin_file => $plugin_details ) {
			$plugin_update = array();
			if ( ! empty( $plugin_updates->response[ $plugin_file ] ) ) {
				$plugin_update = array(
					'update_version'      => $plugin_updates->response[ $plugin_file ]->new_version,
					'requires_wp_version' => $plugin_updates->response[ $plugin_file ]->requires,
					'requires_php'        => $plugin_updates->response[ $plugin_file ]->requires_php,
					'tested_wp_version'   => $plugin_updates->response[ $plugin_file ]->tested,
					'last_updated'        => $plugin_updates->response[ $plugin_file ]->last_updated,
				);
			}
			$plugin = new Plugin( $plugin_file, $plugin_update, $plugin_details, in_array( $plugin_file, $auto_updates, true ) );
			array_push( $plugins, $plugin );
		}

		$util        = new Util();
		$is_bluehost = $util->is_bluehost();

		return new WP_Rest_Response(
			array(
				'plugins'            => $plugins,
				'auto_update_global' => $is_bluehost ? get_option( 'auto_update_plugin' ) : null,
				'last_checked'       => $plugin_updates->last_checked,
			)
		);
	}

	/**
	 * Callback to upgrade a plugin with it's slug
	 *
	 * Returns the plugin's version, status, slug
	 *
	 * @since 1.2
	 *
	 * @param WP_REST_Request $request details about the plugin slug
	 *
	 * @return WP_Rest_Response Returns a standard rest response with the plugin's information
	 */
	public function upgrade_plugin( $request ) {
		$this->load_wp_classes_and_functions();

		wp_update_plugins();

		$util              = new Util();
		$plugin_slug       = $request['slug'];
		$installed_plugins = get_plugins();
		$updates           = get_site_transient( 'update_plugins' );
		$plugin_file       = $util->get_plugin_file_from_slug( $installed_plugins, $plugin_slug );
		$plugin_details    = get_plugin_data( WP_PLUGIN_DIR . "/$plugin_file" );

		if ( array_key_exists( $plugin_file, $updates->response ) ) {
			$update_response = $updates->response[ $plugin_file ];
		}

		if ( ! isset( $update_response ) ) {
			return new WP_Rest_Response(
				array(
					'error' => 'Plugin already up to date',
					'code'  => 'alreadyUpdated',
				),
				400
			);
		} else {
			$plugin_upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
			$upgraded        = $plugin_upgrader->upgrade( $update_response->plugin );
			// Get the upgraded version
			$plugin_details = get_plugin_data( WP_PLUGIN_DIR . "/$plugin_file" );
		}

		return new WP_Rest_Response(
			array(
				'slug'    => $plugin_slug,
				'version' => $plugin_details['Version'],
				'success' => $upgraded,
			)
		);
	}

	/**
	 * Callback to toggle auto updates for a plugin with it's slug
	 *
	 * @since 1.2
	 *
	 * @param WP_REST_Request $request details about the plugin slug
	 *
	 * @return WP_Rest_Response Returns a standard rest response
	 */
	public function toggle_auto_update( $request ) {
		$this->load_wp_classes_and_functions();

		$plugin_slug       = $request['slug'];
		$installed_plugins = get_plugins();
		$auto_update       = $request['auto_update'];
		$util              = new Util();
		$plugin_file       = $util->get_plugin_file_from_slug( $installed_plugins, $plugin_slug );
		$auto_updates      = (array) get_site_option( 'auto_update_plugins', array() );
		$plugin_file       = wp_unslash( $plugin_file );

		if ( $auto_update ) {
			$new_auto_updates = array_merge( $auto_updates, array( $plugin_file ) );
		} else {
			if ( $util->is_bluehost() ) {
				update_site_option( 'auto_update_plugin', 'false' );
			}
			$new_auto_updates = array_diff( $auto_updates, array( $plugin_file ) );
		}
		$new_auto_updates = array_unique( $new_auto_updates );

		if ( $auto_updates !== $new_auto_updates ) {
			update_site_option( 'auto_update_plugins', $new_auto_updates );
		}

		return new WP_REST_Response(
			array(
				'slug'                 => $plugin_slug,
				'auto_updates_enabled' => $auto_update,
			)
		);
	}

	/**
	 * Callback to toggle auto updates for all plugins, only for BH sites
	 *
	 * @since 1.2
	 *
	 * @param WP_REST_Request $request containing a boolean indicating on or off
	 *
	 * @return WP_Rest_Response Returns a standard rest response
	 */
	public function toggle_auto_update_global( $request ) {
		$this->load_wp_classes_and_functions();

		$util = new Util();
		if ( ! $util->is_bluehost() ) {
			return new WP_Rest_Response(
				array(
					'error' => 'Site needs to be on BH for this to work',
					'code'  => 'notABHSite',
				),
				400
			);
		}

		$auto_updates_enabled = $request['auto_updates_enabled'];
		update_site_option( 'auto_update_plugin', $auto_updates_enabled ? 'true' : 'false' );

		return new WP_REST_Response(
			array(
				'auto_updates_enabled' => $auto_updates_enabled,
			)
		);
	}

	/**
	 * Verify permission to access this endpoint
	 *
	 * Authenticating a WebPro user via token
	 *
	 * @since 1.2
	 *
	 * @return boolean Whether to allow access to endpoint.
	 */
	public function check_permission() {

		// We want to SSO into the same user making the current request
		// User is also already verified as a Maestro using the permission callback
		$user_id = get_current_user_id();

		try {
			$this->webpro = new WebPro( $user_id );
		} catch ( Exception $e ) {
			return false;
		}

		return $this->webpro->is_connected();

	}
}
