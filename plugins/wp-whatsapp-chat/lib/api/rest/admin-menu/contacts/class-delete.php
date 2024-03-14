<?php
namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Contacts;

use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;

/**
 * API_Rest_Contacts_Delete Class
 */
class Delete extends Base {

	protected static $route_path = 'contacts';

	public function callback( \WP_REST_Request $request ) {

		try {
			$contact_id = $request->get_param( 'id' );

			if ( 0 !== $contact_id && '0' !== $contact_id && empty( $contact_id ) ) {
				throw new \Exception( esc_html__( 'Contact id not found.', 'wp-whatsapp-chat' ), 400 );
			}

			$models_contacts = Models_Contacts::instance();
			$success         = $models_contacts->delete( $contact_id );

			if ( ! $success ) {
				throw new \Exception( esc_html__( 'Can\'t delete contact, id not found', 'wp-whatsapp-chat' ), 404 );
			}

			return $this->handle_response( $success );
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
				'required' => true,
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::DELETABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
