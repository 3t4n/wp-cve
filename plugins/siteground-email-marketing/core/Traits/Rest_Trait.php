<?php
namespace SG_Email_Marketing\Traits;

/**
 * Trait used for REST API related actions in the plugin.
 */
trait Rest_Trait {

	/**
	 * Define the rest base.
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	public $rest_namespace = 'sg-email-marketing/v1';

	/**
	 * Check if a given request has admin access for getting multiple items.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Check if a given request has admin access for creating item.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Check if a given request has admin access for getting single item.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_item_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Check if a given request has admin access for updating item.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function update_item_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Check if a given request has admin access for deleting item.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function delete_item_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get errors from exception.
	 *
	 * @since  1.0.0
	 *
	 * @param  {Exception} $exception Exception instance.
	 */
	public function get_errors( $exception ) {
		return new \WP_REST_Response( array( 'error' => $exception->getMessage() ), $exception->getCode() );
	}
}
