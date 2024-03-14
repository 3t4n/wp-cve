<?php

namespace WpifyWoo\Api;

use WP_REST_Response;
use WP_REST_Server;
use WpifyWoo\Plugin;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractRest;

/**
 * @property Plugin $plugin
 */
class SettingsApi extends AbstractRest {

	/**
	 * ExampleApi constructor.
	 */
	public function __construct() {
	}

	public function setup() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			$this->plugin->get_api_manager()->get_rest_namespace(),
			'option',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'save_option' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_woocommerce' );
					},
				),
			)
		);

		register_rest_route(
			$this->plugin->get_api_manager()->get_rest_namespace(),
			'list',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'get_list' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_woocommerce' );
					},
				),
			)
		);
	}

	/**
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Request|\WP_REST_Response | bool
	 */
	public function save_option( $request ) {
		update_option( $request->get_param( 'option' ), $request->get_param( 'data' ), isset($request->get_params()['autoload']) ? $request->get_param( 'autoload' ) : true );

		return new WP_REST_Response( array(), 201 );
	}

	/**
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Request|\WP_REST_Response | bool
	 */
	public function get_list( $request ) {
		$response = apply_filters( 'wpify_woo_settings_get_list', array(), $request->get_params() );

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Check if a given request has access to create items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		return true;
	}

	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed            $item    WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		return array();
	}
}
