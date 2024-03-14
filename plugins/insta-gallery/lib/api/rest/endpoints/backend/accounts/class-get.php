<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Accounts;

use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;
use QuadLayers\IGG\Models\Account as Models_Account;

/**
 * Api_Rest_Accounts_Get Class
 */
class Get extends Base {

	protected static $route_path = 'accounts';

	public function callback( \WP_REST_Request $request ) {

		$models_account = new Models_Account();

		$id = trim( $request->get_param( 'id' ) );

		/**
		 *Get all accounts
		 */
		if ( ! $id ) {

			$accounts = $models_account->get();

			return $this->handle_response( $accounts );
		}

		/**
		 * Get accound by id
		 */

		$account = $models_account->get_account( $id );

		if ( ! $account ) {
			$response = array(
				'code'    => 404,
				'message' => sprintf( esc_html__( 'Account %s not found.', 'insta-gallery' ), $id ),
			);
			return $this->handle_response( $response );
		}

		return $this->handle_response( $account );

	}

	public static function get_rest_args() {
		return array(
			'id' => array(
				'required'          => false,
				'validate_callback' => function( $param, $request, $key ) {
					return is_numeric( $param );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}
}
