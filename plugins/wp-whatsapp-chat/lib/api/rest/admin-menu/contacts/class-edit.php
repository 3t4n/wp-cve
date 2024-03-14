<?php
namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Contacts;

use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;

/**
 * API_Rest_Contacts_Edit Class
 */
class Edit extends Base {

	protected static $route_path = 'contacts';

	public function callback( \WP_REST_Request $request ) {

		try {
			$body = json_decode( $request->get_body(), true );

			$models_contacts = Models_Contacts::instance();

			if ( isset( $body['id'] ) ) {

				$contact = $models_contacts->update( $body['id'], $body );

				if ( ! $contact ) {
					throw new \Exception( esc_html__( 'Contact cannot be updated', 'wp-whatsapp-chat' ), 412 );
				}

				return $this->handle_response( $contact );

			} else {

				if ( ! isset( $body[0]['id'] ) ) {
					throw new \Exception( esc_html__( 'Contacts cannot be updated', 'wp-whatsapp-chat' ), 412 );
				}

				$contacts = $models_contacts->update_all( $body );

				if ( ! $contacts ) {
					throw new \Exception( esc_html__( 'Contacts cannot be created', 'wp-whatsapp-chat' ), 412 );
				}

				return $this->handle_response( $contacts );
			}
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
		return \WP_REST_Server::EDITABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
