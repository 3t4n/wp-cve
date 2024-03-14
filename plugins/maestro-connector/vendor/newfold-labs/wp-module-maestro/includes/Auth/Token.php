<?php

namespace NewfoldLabs\WP\Module\Maestro\Auth;

use Exception;
use Firebase\JWT\JWT;
use WP_Error;

/**
 * Class for creating and validating BH Maestro JSON web tokens for authentication
 */
class Token {

	/**
	 * Secret key to be used for generating/validating tokens.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	protected $secret_key;

	/**
	 * The webpro for whom the token is associated with
	 *
	 * @since 0.0.1
	 *
	 * @var WebPro
	 */
	protected $webpro;

	/**
	 * String to use as the meta_key for storing a JWT ID in usermeta
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	protected $jti_meta_key = 'bh_maestro_jti';

	/**
	 * Constructor.
	 *
	 * @since 0.0.1
	 */
	public function __construct() {

		// SECURE_AUTH_KEY is should always be defined in wp-config.php or a user wouldn't
		// be able to log into a site normally. Furthermore, if it gets changed, then it
		// would invalidate all tokens, similar to passwords.
		// If it's missing for some reason, then not much we can safely do going forward.
		if ( ! defined( 'SECURE_AUTH_KEY' ) ) {
			return;
		}

		$this->secret_key = SECURE_AUTH_KEY;
	}

	/**
	 * Generate a JWT token for a specific user that can be used for authentication
	 *
	 * @since 0.0.1
	 *
	 * @param WebPro $webpro  The web pro object for whom the token is issued
	 * @param int    $expires Unix timestamp representing time the token expires (optional)
	 * @param bool   $jti     Generate a unique identifier which makes this a single-use token (optional)
	 * @param array  $data    Array of additional data to encode into the token (optional)
	 *
	 * @return string|WP_Error
	 */
	public function generate_token( $webpro, $expires = YEAR_IN_SECONDS, $jti = false, $data = array() ) {

		$this->webpro = $webpro;

		$jwt_id = '';

		// If this is a single-use token, we'll store a user meta value that gets deleted on validation
		if ( $jti ) {
			// @optimize We could ensure the JTI is unique to better comply with RFC7519,
			// but there should almost never be more than one active single-use token
			// at a time, so it probably meets the "negligible probability" standard.
			$jwt_id = wp_generate_password( 32, false );

			// Try to add it, while forcing it to be unique to the user
			$response = add_user_meta( $webpro->user->ID, $this->jti_meta_key, $jwt_id, true );

			// If it exists, lets update the existing one to overwrite it.
			// We should never have 2 active for a single user at one time.
			if ( ! $response ) {
				$response = update_user_meta( $webpro->user->ID, $this->jti_meta_key, $jwt_id );
			}
		}

		// Generate JWT token.
		$payload = $this->generate_payload( $expires, $jwt_id, $data );
		$token   = JWT::encode( $payload, $this->secret_key );

		// Returns the generated token
		return $token;

	}

	/**
	 * Compile information for the the JWT token.
	 *
	 * @param int    $expires    The number of seconds until the token expires.
	 * @param string $jti        Optional unique identifier string. Forces token to be single-use.
	 * @param array  $extra_data Optional array of additional data to encode into the data portion of the token
	 *
	 * @return array|WP_Error
	 */
	public function generate_payload( $expires, $jti = '', $extra_data = array() ) {

		$time = time();

		// JWT Reserved claims.
		$reserved = array(
			'iss' => preg_replace( '|https?://|', '', get_bloginfo( 'url' ) ), // Issuer with protocol stripped
			'iat' => $time, // Token issued at.
			'nbf' => $time, // Token accepted not before.
			'exp' => $time + $expires, // Token expiry.
		);

		// Only add the jti if one has been provided
		if ( ! empty( $jti ) ) {
			$reserved['jti'] = $jti;
		}

		$data = array(
			'magic_key' => $this->webpro->key,
			'user'      => array(
				'id'         => $this->webpro->user->ID,
				'user_login' => $this->webpro->user->user_login,
				'user_email' => $this->webpro->user->user_email,
			),
		);

		$private = array(
			'data' => array_merge( $data, $extra_data ),
		);

		return array_merge( $reserved, $private );
	}


	/**
	 * Decode the JSON Web Token.
	 *
	 * @param string $token The encoded JWT.
	 *
	 * @return object|WP_Error Return the decoded JWT, or WP_Error on failure.
	 */
	public function decode_token( $token ) {
		try {
			return JWT::decode( $token, $this->secret_key, array( 'HS256' ) );
		} catch ( Exception $e ) {
			// Return caught exception as a WP_Error.
			return new WP_Error(
				'token_error',
				__( 'Invalid token.', 'maestro-connector' )
			);
		}
	}

	/**
	 * Determine if a provided token is valid.
	 *
	 * @param string  $token     The JSON Web Token to validate
	 * @param boolean $force_jti Whether to force the validation of a JWT ID
	 *
	 * @return object|WP_Error Return the JSON Web Token object, or WP_Error on failure.
	 */
	public function validate_token( $token, $force_jti = false ) {

		// Decode the token.
		$jwt = $this->decode_token( $token );
		if ( is_wp_error( $jwt ) ) {
			return $jwt;
		}

		if ( ! isset( $jwt->data->user->id ) ) {
			return new WP_Error(
				'missing_token_user_id',
				__( 'Token user must have an ID.', 'maestro-connector' )
			);
		}

		try {
			$this->webpro = new WebPro( $jwt->data->user->id );
		} catch ( Exception $e ) {
			// @todo maybe return exception from web pro creation
			return new WP_Error(
				'invalid_token_webpro',
				__( 'Web Pro is invalid.', 'maestro-connector' )
			);
		}

		// Determine if the user_login is valid.
		if ( $jwt->data->user->user_login !== $this->webpro->user->user_login ) {
			return new WP_Error(
				'invalid_token_user_login',
				__( 'Token user_login is invalid.', 'maestro-connector' )
			);
		}

		// Determine if the email is valid.
		if ( $jwt->data->user->user_email !== $this->webpro->user->user_email ) {
			return new WP_Error(
				'invalid_token_user_email',
				__( 'Token user_email is invalid.', 'maestro-connector' )
			);
		}

		// Determine if the connection key is valid.
		if ( $this->webpro->key !== $jwt->data->magic_key ) {
			return new WP_Error(
				'invalid_token_secret_key',
				__( 'Connection key is invalid.', 'maestro-connector' )
			);
		}

		// Determine if the token issuer matches this site.
		if ( preg_replace( '|https?://|', '', get_bloginfo( 'url' ) ) !== $jwt->iss ) {
			return new WP_Error(
				'invalid_token_issuer',
				__( 'Token issuer is invalid.', 'maestro-connector' )
			);
		}

		// Determine if the token has expired.
		$expiration_valid = $this->validate_expiration( $jwt );
		if ( is_wp_error( $expiration_valid ) ) {
			return $expiration_valid;
		}

		// Only do a JWT ID check if there is one in the token, or if it's specified as required.
		if ( isset( $jwt->jti ) || $force_jti ) {
			// Determine if this is a valid single-use token
			$jti_valid = $this->validate_jti( $jwt );
			if ( is_wp_error( $jti_valid ) ) {
				return $jti_valid;
			}
		}

		// If we make it here, then it's valid. Return the decoded token
		return $jwt;
	}

	/**
	 * Determine if the token has expired.
	 *
	 * @since 0.0.1
	 *
	 * @param object $token The decoded token.
	 *
	 * @return bool|WP_Error
	 */
	public function validate_expiration( $token ) {

		if ( ! isset( $token->exp ) ) {
			return new WP_Error(
				'missing_token_expiration',
				__( 'Token must have an expiration.', 'maestro-connector' )
			);
		}

		if ( time() > $token->exp ) {
			return new WP_Error(
				'token_expired',
				__( 'Token has expired.', 'maestro-connector' )
			);
		}

		return true;
	}

	/**
	 * Checks for and validates a single-use token identifier.
	 *
	 * Removes the value from the database if valid, ensuring a single use.
	 *
	 * @since 0.0.1
	 *
	 * @param object $token The decoded token.
	 *
	 * @return bool|WP_Error
	 */
	public function validate_jti( $token ) {

		// If there is not one included in the token, then it is obviously invalid!
		if ( ! isset( $token->jti ) ) {
			return new WP_Error(
				'missing_token_jti',
				__( 'Token must have a unique identifier.', 'maestro-connector' )
			);
		}

		// Compare to the stored usermeta value
		if ( get_user_meta( $token->data->user->id, $this->jti_meta_key, true ) !== $token->jti ) {
			return new WP_Error(
				'jti_not_valid',
				__( 'Token identifier is not valid.', 'maestro-connector' )
			);
		}

		// If we get here, it's valid. Remove the JTI from the DB so it can't be used again.
		delete_user_meta( $token->data->user->id, $this->jti_meta_key );

		return true;
	}

}
