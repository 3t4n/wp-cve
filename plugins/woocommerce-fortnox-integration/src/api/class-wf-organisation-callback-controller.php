<?php

namespace src\api;

if ( !defined( 'ABSPATH' ) ) die();

use src\fortnox\api\WF_Request;
use WP_REST_Controller;
use src\Wetail\WF_NG_Fortnox_auth;

class WF_Organisation_Callback_Controller extends WP_REST_Controller {
	static $transient_name = 'fortnox_organisation_auth_secret';

	/**
	 * Inits callback route
	 */
	public function register_routes() {
		if ( ! get_transient( self::$transient_name ) && isset( $_SERVER['HTTPS'] ) ) {
			return;
		}

		register_rest_route( WF_API_NAMESPACE,
			'/organisation_callback/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'set_access_token' ),
				'permission_callback' => '__return_true',
			) );
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 * @throws \Exception
	 */
	public static function set_access_token( $request ) {
		fortnox_write_log( "INCOMING FNX AUTH RESPONSE" );
		fortnox_write_log( $request );

		$expected_secret  = get_transient( self::$transient_name );
		$incoming_secret  = $request['secret'] ?? false;
		$incoming_access_token = $request['access_token'] ?? false;
		$incoming_refresh_token = $request['refresh_token'] ?? false;
		if ( $expected_secret == $incoming_secret && $incoming_access_token ) {
			//validate api key?
			update_option( 'fortnox_access_token_oauth2', $incoming_access_token );
			update_option( 'fortnox_refresh_token', $incoming_refresh_token );
            update_option( 'fortnox_access_token_expiry_time', time() + ( 60 * 60 ) );
			delete_transient( self::$transient_name );

			update_option( 'fortnox_organization_number_auth_result',
				[
					'error'   => false,
					'message' => 'Updated ok',
				] );
			$response = new \WP_REST_Response( 'ok' );

			$response->set_status( 201 );

			return $response;
		} else {

			update_option( 'fortnox_organization_number_auth_result',
				[
					'error'   => true,
					'message' => 'Invalid callback request.',
				] );

			$response = new \WP_REST_Response( 'Invalid request.' );

			$response->set_status( 400 );

			return $response;
		}
	}


}
