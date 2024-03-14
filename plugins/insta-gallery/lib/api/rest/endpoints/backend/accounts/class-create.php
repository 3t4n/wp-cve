<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Accounts;

use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;
use QuadLayers\IGG\Models\Account as Models_Account;

/**
 * Api_Rest_Accounts_Create Class
 */
class Create extends Base {

	protected static $route_path = 'accounts';

	public function callback( \WP_REST_Request $request ) {

		$body = json_decode( $request->get_body() );

		if ( empty( $body->access_token ) ) {
			$response = array(
				'code'    => 412,
				'message' => esc_html__( 'access_token not setted.', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}
		if ( empty( $body->id ) ) {
			$response = array(
				'code'    => 412,
				'message' => esc_html__( 'id not setted.', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		$models_account = new Models_Account();

		$account_data = array(
			'access_token' => $body->access_token,
			'id'           => $body->id,
		);

		$account = $models_account->create( $account_data );

		if ( ! isset( $account['access_token'] ) ) {
			$response = array(
				'code'    => isset( $account['error'] ) ? $account['error'] : 412,
				'message' => isset( $account['message'] ) ? $account['message'] : esc_html__( 'Unable to create account.', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		return $this->handle_response( $account );
	}

	public static function get_rest_args() {
		return array(
			'body' => array(),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::EDITABLE;
	}
}
