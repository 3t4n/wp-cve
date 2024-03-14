<?php

namespace NewfoldLabs\WP\Module\Maestro\RestApi;

use Exception;
use WP_REST_Server;
use WP_REST_Response;
use Theme_Upgrader;
use WP_Ajax_Upgrader_Skin;

use NewfoldLabs\WP\Module\Maestro\Models\Theme;
use NewfoldLabs\WP\Module\Maestro\Auth\WebPro;
use NewfoldLabs\WP\Module\Maestro\Util;

/**
 * Class REST_Themes_Controller
 */
class ThemesController extends \WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	protected $namespace = 'bluehost/maestro/v1';

	/**
	 * The current Web Pro accessing the endpoint
	 *
	 * @since 0.0.1
	 *
	 * @var WebPro
	 */
	private $webpro;

	/**
	 * Registers the Themes routes
	 *
	 * @since 0.0.1
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/themes',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_themes' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/themes/upgrade',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'upgrade_theme' ),
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
			'/themes/toggle-auto-update',
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
			'/themes/toggle-auto-update-global',
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
	 * @since 0.0.1
	 */
	private function load_wp_classes_and_functions() {

		if ( ! function_exists( 'request_filesystem_credentials' ) ) {
			include_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! function_exists( 'wp_get_themes' ) ) {
			require_once ABSPATH . 'wp-admin/includes/theme.php';
		}

		if ( ! function_exists( 'get_option' ) ) {
			require_once ABSPATH . 'wp-admin/includes/options.php';
		}

		if ( ! class_exists( 'WP_Upgrader' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		if ( ! class_exists( 'Theme_Upgrader' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-theme-upgrader.php';
		}

		if ( ! class_exists( 'WP_Upgrader_Skin' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
		}

		if ( ! class_exists( 'WP_Ajax_Upgrader_Skin' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
		}

		if ( ! class_exists( 'WP_Theme' ) ) {
			require_once ABSPATH . 'wp-includes/class-wp-theme.php';
		}
	}

	/**
	 * A function to get the theme object with slug
	 *
	 * @since 0.0.1
	 *
	 * @param String $slug the theme's slug
	 */
	private function get_theme_from_slug( $slug ) {
		$themes = wp_get_themes();
		foreach ( $themes as $theme_slug => $theme_wp ) {
			if ( $theme_slug === $slug ) {
				return $theme_wp;
			}
		}
	}

	/**
	 * Callback to upgrade a theme with it's slug
	 *
	 * Returns the theme's version, status, slug
	 *
	 * @since 0.0.1
	 *
	 * @param WP_REST_Request $request details about the theme slug
	 *
	 * @return WP_Rest_Response Returns a standard rest response with the plugin's information
	 */
	public function upgrade_theme( $request ) {
		$this->load_wp_classes_and_functions();

		wp_update_themes();

		$theme_slug = $request['slug'];
		$updates    = get_site_transient( 'update_themes' );
		$theme_obj  = $this->get_theme_from_slug( $theme_slug );
		$stylesheet = $theme_obj->get_stylesheet();

		if ( array_key_exists( $stylesheet, $updates->response ) ) {
			$update_response = $updates->response[ $stylesheet ];
		}

		if ( ! isset( $update_response ) ) {
			return new WP_Rest_Response(
				array(
					'error' => 'Theme already up to date',
					'code'  => 'alreadyUpdated',
				),
				400
			);
		} else {
			$theme_upgrader = new Theme_Upgrader( new WP_Ajax_Upgrader_Skin() );
			$upgraded       = $theme_upgrader->upgrade( $theme_slug );
		}

		return new WP_Rest_Response(
			array(
				'slug'    => $theme_slug,
				'version' => $update_response['new_version'],
				'success' => $upgraded,
			)
		);
	}

	/**
	 * Callback for the themes get endpoint
	 *
	 * Returns a list of installed themes with id, name, title
	 * status, version, update, update_version and screenshot
	 *
	 * @since 0.0.1
	 *
	 * @return WP_Rest_Response Returns a standard rest response with a list of themes
	 */
	public function get_themes() {
		$this->load_wp_classes_and_functions();

		// Make sure we populate the themes updates transient
		wp_update_themes();

		$themes_list      = array();
		$themes_installed = wp_get_themes();
		$theme_updates    = get_site_transient( 'update_themes' );
		$auto_updates     = (array) get_site_option( 'auto_update_themes', array() );
		$current_theme    = wp_get_theme();

		foreach ( $themes_installed as $stylesheet => $theme_wp ) {
			$theme_update = array();
			if ( ! empty( $theme_updates->response[ $stylesheet ] ) ) {
				$theme_update = $theme_updates->response[ $stylesheet ];
			}
			$theme = new Theme( $stylesheet, $theme_wp, $theme_update, $current_theme, in_array( $stylesheet, $auto_updates, true ) );
			array_push( $themes_list, $theme );
		}

		$util = new Util();

		return new WP_Rest_Response(
			array(
				'themes'             => $themes_list,
				'auto_update_global' => $util->is_bluehost() ? get_option( 'auto_update_theme' ) : null,
				'last_checked'       => $theme_updates->last_checked,
			)
		);
	}

	/**
	 * Callback to toggle auto updates for a theme with it's slug
	 *
	 * @since 0.0.1
	 *
	 * @param WP_REST_Request $request details about the theme slug
	 *
	 * @return WP_Rest_Response Returns a standard rest response
	 */
	public function toggle_auto_update( $request ) {
		$this->load_wp_classes_and_functions();

		$theme_slug   = $request['slug'];
		$auto_update  = $request['auto_update'];
		$util         = new Util();
		$auto_updates = (array) get_site_option( 'auto_update_themes', array() );

		if ( $auto_update ) {
			$new_auto_updates = array_merge( $auto_updates, array( $theme_slug ) );
		} else {
			if ( $util->is_bluehost() ) {
				update_site_option( 'auto_update_theme', 'false' );
			}
			$new_auto_updates = array_diff( $auto_updates, array( $theme_slug ) );
		}
		$new_auto_updates = array_unique( $new_auto_updates );

		if ( $auto_updates !== $new_auto_updates ) {
			update_site_option( 'auto_update_themes', $new_auto_updates );
		}

		return new WP_REST_Response(
			array(
				'slug'                 => $theme_slug,
				'auto_updates_enabled' => $auto_update,
			)
		);
	}

	/**
	 * Callback to toggle auto updates for all themes, only for BH sites
	 *
	 * @since 0.0.1
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
		update_site_option( 'auto_update_theme', $auto_updates_enabled ? 'true' : 'false' );

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
	 * @since 0.0.1
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
