<?php
namespace SG_Email_Marketing\Rest\Controllers\v1;

use SG_Email_Marketing\Traits\Rest_Trait;
use SG_Email_Marketing\Loader\Loader;

/**
 * Class responsible for the Labels.
 */
class Labels {
	use Rest_Trait;

	/**
	 * Register the rest routes for the Settings Page.
	 *
	 * @since 1.0.0
	 */
	public function register_rest_routes() {
		// Get all labels.
		register_rest_route(
			$this->rest_namespace,
			'/labels/',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
			)
		);
	}

	/**
	 * Get all labels.
	 *
	 * @since 1.0.0
	 */
	public function get_items() {
		try {
			return rest_ensure_response( Loader::get_instance()->mailer_api->get_labels() );
		} catch ( \Exception $e ) {
			return $this->get_errors( $e );
		}
	}
}
