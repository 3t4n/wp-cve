<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Accounts;

use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;
use QuadLayers\IGG\Models\Account as Models_Account;
use QuadLayers\IGG\Utils\Cache as Cache;

/**
 * Api_Rest_Accounts_Delete Class
 */
class Delete extends Base {

	protected static $route_path = 'accounts';

	protected $cache_engine;

	public function callback( \WP_REST_Request $request ) {

		$models_account = new Models_Account();

		$account_id = trim( $request->get_param( 'id' ) );

		$success = $models_account->delete( $account_id );

		if ( ! $success ) {
			$response = array(
				'code'    => 404,
				'message' => esc_html__( 'Can\'t delete account, account_id not found', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		// Clear cache

		$cache_key = "profile_{$account_id}";

		$cache_engine = new Cache( 6, true, $cache_key );

		$cache_engine->delete_key( $cache_key );

		return $this->handle_response( $success );
	}

	public static function get_rest_args() {
		return array(
			'id' => array(
				'required' => true,

				/*
				'validate_callback' => function( $param, $request, $key ) {
					return is_numeric( $param );
				},
				*/
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::DELETABLE;
	}
}
