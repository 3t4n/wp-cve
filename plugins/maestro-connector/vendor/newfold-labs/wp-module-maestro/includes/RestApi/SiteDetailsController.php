<?php

namespace NewfoldLabs\WP\Module\Maestro\RestApi;

use Exception;
use WP_REST_Server;
use WP_REST_Response;

use NewfoldLabs\WP\Module\Maestro\Models\SiteDetails;
use NewfoldLabs\WP\Module\Maestro\Auth\WebPro;
use NewfoldLabs\WP\Module\Maestro\Util;

/**
 * Class WebsiteOptionsController
 */
class SiteDetailsController extends \WP_REST_Controller {

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
	 * Registers the Website options routes
	 *
	 * @since 1.2
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/website-options',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_website_options' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/website-options/toggle',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'toggle_wp_update_options' ),
					'args'                => array(
						'allow_major_auto_core_updates' => array(
							'required' => true,
							'type'     => 'boolean',
						),
						'allow_minor_auto_core_updates' => array(
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
	 * Callback for the plugins get endpoint
	 *
	 * Returns general info about the current WordPress status like
	 * last_updated, version, updates_available etc.
	 *
	 * @since 1.2
	 *
	 * @return WP_Rest_Response Returns a standard rest response with a list of plugins
	 */
	public function get_website_options() {
		$website_options = new SiteDetails();
		return new WP_Rest_Response( $website_options );
	}

	/**
	 * Callback for setting the auto-update options for WordPress major and minor
	 *
	 * @since 1.2
	 *
	 * @param WP_REST_Request $request details about the theme slug
	 *
	 * @return WP_Rest_Response with the options after the update call
	 */
	public function toggle_wp_update_options( $request ) {

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

		if ( ! function_exists( 'update_site_option' ) ) {
			require_once ABSPATH . 'wp-admin/includes/options.php';
		}

		$allow_major_auto_core_updates = $request['allow_major_auto_core_updates'];
		$allow_minor_auto_core_updates = $request['allow_minor_auto_core_updates'];
		update_site_option( 'allow_major_auto_core_updates', $allow_major_auto_core_updates ? 'true' : 'false' );
		update_site_option( 'allow_minor_auto_core_updates', $allow_minor_auto_core_updates ? 'true' : 'false' );

		return new WP_REST_Response(
			array(
				'allow_major_auto_core_updates' => get_site_option( 'allow_major_auto_core_updates' ) === 'true' ? true : false,
				'allow_minor_auto_core_updates' => get_site_option( 'allow_minor_auto_core_updates' ) === 'true' ? true : false,
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
