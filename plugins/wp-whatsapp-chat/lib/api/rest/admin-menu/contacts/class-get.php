<?php
namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Contacts;

use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;

/**
 * API_Rest_Contacts_Get Class
 */
class Get extends Base {

	protected static $route_path = 'contacts';

	public function callback( \WP_REST_Request $request ) {
		try {
			$models_contacts = Models_Contacts::instance();

			$contacts = $models_contacts->get_all();

			if ( null !== $contacts && 0 !== count( $contacts ) ) {
				return $this->handle_response( $contacts );
			}

			return $this->handle_response( array() );
		} catch ( \Exception $e ) {
			$response = array(
				'code'    => $e->getCode(),
				'message' => $e->getMessage(),
			);
			return $this->handle_response( $response );
		}
	}

	public static function get_rest_args() {
		return array(
			'id' => array(
				'validate_callback' => function ( $param, $request, $key ) {
					return is_numeric( $param );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
