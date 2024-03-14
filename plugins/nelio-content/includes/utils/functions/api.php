<?php
/**
 * This file contains several helper functions that deal with the AWS API.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils/functions
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

/**
 * Whether to use Nelio’s proxy instead of accessing AWS directly or not.
 *
 * @return boolean whether to use Nelio’s proxy instead of accessing AWS directly or not.
 *
 * @since 2.0.0
 */
function nc_does_api_use_proxy() {

	/**
	 * Whether the plugin should use Nelio’s proxy instead of accessing AWS directly.
	 *
	 * @param boolean $uses_proxy use Nelio’s proxy instead of accessing AWS directly. Default: `false`.
	 *
	 * @since 2.0.0
	 */
	return apply_filters( 'nelio_content_use_nelio_proxy', false );

}//end nc_does_api_use_proxy()

/**
 * Returns the API url for the specified method.
 *
 * @param string $method  The metho we want to use.
 * @param string $context Either 'wp' or 'browser', depending on the location
 *                        in which the resulting URL has to be used.
 *                        Only wp calls might use the proxy URL.
 *
 * @return string the API url for the specified method.
 *
 * @since 1.1.0
 */
function nc_get_api_url( $method, $context ) {

	if ( 'browser' === $context ) {
		return 'https://api.neliocontent.com/v2' . $method;
	}//end if

	if ( nc_does_api_use_proxy() ) {
		return 'https://neliosoftware.com/proxy/content-api/v2' . $method;
	} else {
		return 'https://api.neliocontent.com/v2' . $method;
	}//end if

}//end nc_get_api_url()

/**
 * A token for accessing the API.
 *
 * @since 1.0.0
 * @var   string
 */
$nc_api_auth_token = '';

/**
 * Returns a new token for accessing the API.
 *
 * @param string $mode Either 'regular' or 'skip-errors'. If the latter is used, the function
 *                     won't generate any HTML errors.
 *
 * @return string a new token for accessing the API.
 *
 * @since 1.0.0
 */
function nc_generate_api_auth_token( $mode = 'regular' ) {

	if ( ! nc_get_site_id() ) {
		return false;
	}//end if

	global $nc_api_auth_token;

	// If we already have a token, return it.
	if ( ! empty( $nc_api_auth_token ) ) {
		return $nc_api_auth_token;
	}//end if

	// If we don't, let's see if there's a transient.
	$transient_name    = 'nc_api_token_' . get_current_user_id();
	$nc_api_auth_token = get_transient( $transient_name );

	if ( ! empty( $nc_api_auth_token ) ) {
		return $nc_api_auth_token;
	}//end if

	// If we don't have a token, let's get a new one!
	$uid               = get_current_user_id();
	$role              = 'plugin-user';
	$secret            = get_option( 'nc_api_secret', false );
	$nc_api_auth_token = '';

	$data = array(
		'method'    => 'POST',
		'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
		'sslverify' => ! nc_does_api_use_proxy(),
		'headers'   => array(
			'accept'       => 'application/json',
			'content-type' => 'application/json',
		),
		'body'      => wp_json_encode(
			array(
				'id'   => "$uid",
				'role' => $role,
				'auth' => md5( "{$uid}{$role}{$secret}" ),
			)
		),
	);

	// Iterate to obtain the token, or else things will go wrong.
	for ( $i = 0; $i < 3; ++$i ) {

		$url      = nc_get_api_url( '/site/' . get_option( 'nc_site_id' ) . '/key', 'wp' );
		$response = wp_remote_request( $url, $data );

		if ( ! nc_is_response_valid( $response ) ) {
			sleep( 3 );
			continue;
		}//end if

		// Save the new token.
		$response = json_decode( $response['body'], true );
		if ( isset( $response['token'] ) ) {
			$nc_api_auth_token = $response['token'];
		}//end if

		if ( ! empty( $nc_api_auth_token ) ) {
			break;
		}//end if

		sleep( 3 );

	}//end for

	if ( ! empty( $nc_api_auth_token ) ) {
		set_transient( $transient_name, $nc_api_auth_token, 150 * MINUTE_IN_SECONDS );
	}//end if

	// Send error if we couldn't get an API key.
	if ( 'skip-errors' !== $mode ) {

		$error_message = _x( 'There was an error while accessing Nelio Content’s API.', 'error', 'nelio-content' );

		if ( empty( $nc_api_auth_token ) ) {

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				header( 'HTTP/1.1 500 Internal Server Error' );
				wp_send_json( $error_message );
			} else {
				return false;
			}//end if
		}//end if
	}//end if

	return $nc_api_auth_token;

}//end nc_generate_api_auth_token()


/**
 * Returns the reference whose ID is the given ID.
 *
 * @param string         $code    API error code.
 * @param string|boolean $default Optional. Default error message.
 *
 * @return string Error message associated to the given error code.
 *
 * @since  1.0.0
 * @access public
 */
function nc_get_error_message( $code, $default = false ) {

	switch ( $code ) {

		case 'LicenseNotFound':
			return _x( 'Invalid license code.', 'error', 'nelio-content' );

		default:
			return $default;

	}//end switch

}//end nc_get_error_message()

/**
 * This function checks whether the response of a `wp_remote_*` call is valid
 * or not. A response is valid if it's not a WP_Error and the response code is
 * 200.
 *
 * @param array $response the response of a `wp_remote_*` call.
 *
 * @return boolean Whether the response is valid (i.e. not a WP_Error and a 200
 *                 response code) or not.
 *
 * @since 1.0.0
 */
function nc_is_response_valid( $response ) {

	if ( is_wp_error( $response ) ) {
		return false;
	}//end if

	if ( isset( $response['body'] ) ) {
		$body = json_decode( $response['body'], true );
		$body = ! empty( $body ) ? $body : array();
		if ( isset( $body['errorType'] ) && isset( $body['errorMessage'] ) ) {
			return false;
		}//end if
	}//end if

	if ( ! isset( $response['response'] ) ) {
		return true;
	}//end if

	$response = $response['response'];
	if ( ! isset( $response['code'] ) ) {
		return true;
	}//end if

	if ( 200 === $response['code'] ) {
		return true;
	}//end if

	return false;

}//end nc_is_response_valid()

/**
 * This function checks if the given response is valid or not. If it isn't,
 * it'll return a WP_Error (forwarding the original error code or
 * generating a new `500 Internal Server Error`) and a message describing the
 * error.
 *
 * @param array $response the response of a `wp_remote_*` call.
 *
 * @since 2.0.0
 */
function nc_extract_error_from_response( $response ) {

	if ( nc_is_response_valid( $response ) ) {
		return false;
	}//end if

	// If we couldn't open the page, let's return an empty result object.
	if ( is_wp_error( $response ) ) {
		return $response;
	}//end if

	// Extract body and response.
	$body     = json_decode( $response['body'], true );
	$response = $response['response'];

	// If the error is not an Unauthorized request, let's forward it to the user.
	$summary = $response['code'] . ' ' . $response['message'];
	if ( false === preg_match( '/^HTTP\/1.1 [0-9][0-9][0-9]( [A-Z][a-z]+)+$/', 'HTTP/1.1 ' . $summary ) ) {
		$summary = '500 Internal Server Error';
	}//end if

	// Check if the API returned an error code and error message.
	$error_message = false;
	if ( ! empty( $body['errorType'] ) && ! empty( $body['errorMessage'] ) ) {
		$error_message = nc_get_error_message( $body['errorType'], $body['errorMessage'] );
	}//end if

	if ( empty( $error_message ) ) {
		$error_message = sprintf(
			/* translators: the placeholder is a string explaining the error returned by the API. */
			_x( 'There was an error while accessing Nelio Content’s API: %s.', 'error', 'nelio-content' ),
			$summary
		);
	}//end if

	// Send code.
	return new WP_Error(
		'server-error',
		$error_message
	);

}//end nc_extract_error_from_response()
