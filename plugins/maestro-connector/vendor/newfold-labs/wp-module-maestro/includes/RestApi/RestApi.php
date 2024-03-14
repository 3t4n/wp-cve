<?php

namespace NewfoldLabs\WP\Module\Maestro\RestApi;

use NewfoldLabs\WP\Module\Maestro\Auth\Token;

/**
 * Primary class for loading REST API endpoints and handling authentication
 */
class RestApi {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		add_action( 'rest_authentication_errors', array( $this, 'authenticate' ) );

	}

	/**
	 * Registers all custom REST API routes
	 *
	 * @since 1.0
	 */
	public function register_routes() {

		$controllers = array(
			'NewfoldLabs\\WP\\Module\\Maestro\\RestApi\\WebProsController',
			'NewfoldLabs\\WP\\Module\\Maestro\\RestApi\\SSOController',
			'NewfoldLabs\\WP\\Module\\Maestro\\RestApi\\ThemesController',
			'NewfoldLabs\\WP\\Module\\Maestro\\RestApi\\PluginsController',
			'NewfoldLabs\\WP\\Module\\Maestro\\RestApi\\SiteDetailsController',
		);

		foreach ( $controllers as $controller ) {
			/**
			 * Get an instance of the WP_REST_Controller.
			 *
			 * @var $instance WP_REST_Controller
			 */
			$instance = new $controller();
			$instance->register_routes();
		}

	}

	/**
	 * Attempt to authenticate the REST API request
	 *
	 * @since 1.0
	 *
	 * @param mixed $status Result of any other authentication attempts
	 *
	 * @return WP_Error|null|bool
	 */
	public function authenticate( $status ) {

		// Make sure there wasn't a different authentication method used before this
		if ( ! is_null( $status ) ) {
			return $status;
		}

		// Make sure this is a REST API request
		if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
			return $status;
		}

		$jwt = $this->get_access_token();

		// If no auth header included, bail to allow a different auth method
		if ( is_null( $jwt ) ) {
			return null;
		}

		$token = new Token();
		if ( is_wp_error( $token->validate_token( $jwt ) ) ) {
			// Return the WP_Error for why the token wansn't validated
			return $token;
		}
		$decoded_token = $token->decode_token( $jwt );

		// Token is valid, so let's set the current user
		wp_set_current_user( $decoded_token->data->user->id );

		return true;
	}

	/**
	 * Get the token from the Maestro-Authorization header
	 *
	 * @since 1.0
	 *
	 * @return null|string The token from the header or null
	 */
	public function get_access_token() {
		$token = null;
		if ( ! empty( $_SERVER['HTTP_MAESTRO_AUTHORIZATION'] ) ) {
			$token = wp_unslash( $_SERVER['HTTP_MAESTRO_AUTHORIZATION'] );
		}

		// Use getallheaders in case the HTTP_MAESTRO_AUTHORIZATION header is stripped by a server configuration
		if ( function_exists( 'getallheaders' ) ) {
			$headers = getallheaders();

			// Check for the authorization header case-insensitively
			foreach ( $headers as $key => $value ) {
				if ( strtolower( $key ) === 'maestro-authorization' ) {
					$token = $value;
				}
			}
		}

		return $token;
	}

}
