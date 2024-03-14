<?php

/**
 * class Token
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 */

namespace AppBuilder;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use WP_Error;

defined( 'ABSPATH' ) || exit;

class Token {
	/**
	 * @var array $jwt the secret key using signed JWT token
	 * @since 1.0.0
	 */
	private $jwt;

	public function __construct() {
		$jwt = appBuilder()->settings()->get( 'jwt' );
		if ( $jwt ) {
			$this->jwt = $jwt;
		} else {
			$this->jwt = array(
				'secret_key' => defined( 'AUTH_KEY' ) ? AUTH_KEY : home_url( '/app' ),
				'exp'        => 30,
			);
		}
	}

	/**
	 *
	 * Verify the token
	 *
	 * @param string $token
	 *
	 * @return WP_Error|object
	 * @author ngocdt
	 *
	 * @since 1.0.0
	 */
	public function verify_token( string $token = '' ) {

		/* Get token from header */
		if ( empty( $token ) ) {

			$token = $this->get_authorization_header();

			if ( empty( $token ) ) {
				return new WP_Error(
					'no_auth_header',
					__( 'Authorization header not found.', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			$match = preg_match( '/Bearer\s(\S+)/', $token, $matches );

			if ( ! $match ) {
				return new WP_Error(
					'token_value_not_validate',
					__( 'Token value not validate format.', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			$token = $matches[1];
		}

		/** Try decode the token */
		try {
			$data = JWT::decode( $token, new Key( $this->jwt['secret_key'], 'HS256' ) );

			if ( $data->iss != get_bloginfo( 'url' ) ) {
				return new WP_Error(
					'bad_iss',
					__( 'The iss do not match with this server', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}
			if ( ! isset( $data->data->user_id ) ) {
				return new WP_Error(
					'id_not_found',
					__( 'User ID not found in the token', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			return $data;

		} catch ( ExpiredException | Exception $e ) {
			return new WP_Error(
				'invalid_token',
				$e->getMessage(),
				array(
					'status' => 403,
				)
			);
		}
	}

	/**
	 *  Sign the token
	 *
	 * @param int   $user_id the user id.
	 * @param array $data the data.
	 * @param int   $expired the expired time.
	 *
	 * @return string
	 */
	public function sign_token( int $user_id, array $data = array(), int $expired = 0 ): string {

		$day_exp = (float) $this->jwt['exp'];

		$iat = time();
		$nbf = $iat;
		$exp = $expired > 0 ? $expired : ( DAY_IN_SECONDS * ( $day_exp > 0 ? $day_exp : 30 ) );

		$payload = array(
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $iat,
			'nbf'  => $nbf,
			'exp'  => $iat + $exp,
			'data' => array_merge(
				array(
					'user_id' => $user_id,
				),
				$data
			),
		);

		// Signing token.
		return JWT::encode( $payload, $this->jwt['secret_key'], 'HS256' );
	}

	/**
	 *  Get the authorization header.
	 *
	 * @return string
	 * @author ngocdt
	 *
	 * @since 1.0.0
	 */
	public function get_authorization_header(): string {
		if ( ! empty( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
			return wp_unslash( $_SERVER['HTTP_AUTHORIZATION'] ); // WPCS: sanitization ok.
		}

		if ( function_exists( 'getallheaders' ) ) {
			$headers = getallheaders();
			// Check for the authoization header case-insensitively.
			foreach ( $headers as $key => $value ) {
				if ( 'authorization' === strtolower( $key ) ) {
					return $value;
				}
			}
		}

		return '';
	}
}
