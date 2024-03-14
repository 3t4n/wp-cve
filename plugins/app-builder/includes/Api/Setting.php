<?php


/**
 * Class Setting
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

class Setting extends Base {

	public function __construct() {
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
	}

	/**
	 * Registers a REST API route
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {

		/**
		 * Register settings API
		 *
		 * @author Ngoc Dang
		 * @since 1.0.0
		 */
		register_rest_route(
			$this->namespace, 'app-settings',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'admin_permissions_check' ),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_items' ),
					'permission_callback' => array( $this, 'admin_permissions_check' ),
				),
			)
		);
	}

	/**
	 *
	 * Get Settings
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_items( $request ) {
		$response = appBuilder()->settings()->settings();

		return rest_ensure_response( $response );
	}

	/**
	 * Update or Create Settings
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function update_items( $request ): bool {
		$settings = $request->get_param( 'settings' );

		return appBuilder()->settings()->set( $settings );
	}
}
