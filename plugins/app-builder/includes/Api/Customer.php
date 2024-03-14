<?php

/**
 * Class Customer
 *
 * @link       https://appcheap.io
 * @since      1.0.17
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 *
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

class Customer extends Base {

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
		 * Update customer
		 *
		 * @author Ngoc Dang
		 * @since 1.0.17
		 */
		if ( class_exists( '\WC_REST_Customers_Controller' ) ) {
			$customer = new \WC_REST_Customers_Controller();

			/**
			 * @since 1.0.17
			 */
			register_rest_route( $this->namespace, 'customers/(?P<id>[\d]+)', array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $customer, 'update_item' ),
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
			) );
		}
	}

	/**
	 * Check if a given request has access to read a customer.
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 *
	 * @return \WP_Error|boolean
	 *
	 * @since 1.0.17
	 */
	public function update_item_permissions_check( $request ) {
		$id = (int) $request['id'];

		if ( get_current_user_id() != $id ) {
			return new \WP_Error( 'app_builder', __( 'Sorry, you cannot change info.', "app-builder" ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}
}