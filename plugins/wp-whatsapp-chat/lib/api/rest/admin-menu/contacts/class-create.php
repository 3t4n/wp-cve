<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Contacts;

use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use WP_REST_Server;

class Create extends Base {

	protected static $route_path = 'contacts';

	public function callback( \WP_REST_Request $request ) {
		try {
			$body = json_decode( $request->get_body(), true );

			$models_contacts = Models_Contacts::instance();
			$contact         = $models_contacts->create( $body );

			if ( ! $contact ) {
				throw new \Exception( esc_html__( 'Unknown error', 'wp-whatsapp-chat' ), 500 );
			}

			return $this->handle_response( $contact );

		} catch ( \Exception $e ) {
			$response = array(
				'code'    => $e->getCode(),
				'message' => $e->getMessage(),
			);
			return $this->handle_response( $response );
		}
	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return WP_REST_Server::CREATABLE;
	}

	public function get_rest_permission() {
		return current_user_can( 'manage_options' );
	}
}
