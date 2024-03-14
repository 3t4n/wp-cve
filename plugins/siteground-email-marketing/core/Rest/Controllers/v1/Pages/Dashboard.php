<?php
namespace SG_Email_Marketing\Rest\Controllers\v1\Pages;

use SG_Email_Marketing\Traits\Rest_Trait;

/**
 * Class responsible for the Dashboard page.
 */
class Dashboard {
	use Rest_Trait;

	/**
	 * Register the rest routes for the Dashboard.
	 *
	 * @since 1.0.0
	 */
	public function register_rest_routes() {
		// Add the GET request.
		register_rest_route(
			$this->rest_namespace,
			'/dashboard/',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
			)
		);

		// Add the GET request.
		register_rest_route(
			$this->rest_namespace,
			'/dashboard/',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_item' ),
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
			)
		);
	}

	/**
	 * Get the Dashboard page data.
	 *
	 * @since 1.0.0
	 */
	public function get_item() {
		return rest_ensure_response( $this->prepare_data() );
	}

	/**
	 * Update the Dashboard page data.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_REST_Resquest $request The incoming request object.
	 */
	public function update_item( $request ) {
		$body = json_decode( $request->get_body(), true );

		if ( ! isset( $body['seen'] ) ) {
			return new \WP_Error(
				'error',
				__( 'Missing param!', 'siteground-email-marketing' ),
				array( 'status' => 403 ),
			);
		}

		update_option( 'sg_email_marketing_seen', $body['seen'] );

		return rest_ensure_response( $this->prepare_data() );
	}

	/**
	 * Prepare the data for the page.
	 *
	 * @since 1.0.0
	 */
	public function prepare_data() {
		return array( 'seen' => get_option( 'sg_email_marketing_seen', 0 ) );
	}
}
