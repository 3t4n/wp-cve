<?php
/**
 * Native Rent API Core class
 *
 * @package nativerent
 */

namespace NativeRent;

use Exception;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Class APICore
 */
class APICore {

	/**
	 * Default API host
	 *
	 * @var string
	 */
	protected static $default_api_host = 'http://plain.nativerent.ru';

	/**
	 * Base API URI
	 *
	 * @var string
	 */
	protected static $default_api_uri = '/api/v1/integration/wp/';

	/**
	 * Get API host (from env or default)
	 *
	 * @return string
	 */
	protected static function get_api_host() {
		$env_host = getenv( 'NATIVERENT_API_HOST' );
		if ( ! empty( $env_host ) ) {
			return esc_url( wp_unslash( $env_host ) );
		}

		return self::$default_api_host;
	}

	/**
	 * Make HTTP-request to API server. Returns an answer from API server;
	 *
	 * @param  string $command  API method name.
	 * @param  array  $body     Request body.
	 * @param  array  $options  List of options.
	 *
	 * @return string|array|bool
	 */
	protected static function _request( $command, $body, $options = array() ) {
		$path    = ( isset( $options['specialAPIURL'] ) ) ? $options['specialAPIURL'] : self::$default_api_uri;
		$method  = ( isset( $options['requestMethod'] ) ) ? $options['requestMethod'] : 'POST';
		$args    = array();
		$headers = array(
			'Connection' => 'keep-alive',
		);

		$request_url        = self::get_api_host() . $path . $command;
		$args['timeout']    = ( isset( $options['timeout'] ) ) ? (int) $options['timeout'] : 1;
		$args['user-agent'] = self::get_user_agent();
		$args['sslverify']  = false;
		$args['blocking']   = (bool) ( isset( $options['blocking'] ) ? $options['blocking'] : true );
		$x_forwarded_for    = self::get_x_forwarded_for();
		if ( ! empty( $x_forwarded_for ) ) {
			$headers['X-Forwarded-For'] = $x_forwarded_for;
		}

		if ( in_array( $method, array( 'GET', 'POST' ), true ) ) {
			$headers['Content-type'] = 'application/json; charset=utf-8';
			$headers['Accept']       = 'application/json';
			$args['method']          = $method;
			if ( 'POST' === $method ) {
				$args['body'] = json_encode( self::_sign_request( $body ) );
			}
		} elseif ( 'HEAD' === $method ) {
			$args['method'] = 'HEAD';
		}

		$token = Options::get( 'token' );
		if (
			! empty( $token ) && ( ! isset( $options['disableToken'] ) || ! $options['disableToken'] )
		) {
			$headers['Authorization'] = 'Bearer ' . $token;
		}

		$args['headers'] = $headers;
		$response        = wp_remote_request( $request_url, $args );
		if ( 'reportError' !== $command && $response instanceof WP_Error ) {
			nativerent_report_error( $response );

			return false;
		}

		$response_status = wp_remote_retrieve_response_code( $response );
		if ( 'POST' === $method && in_array( $response_status, array( 200, 422 ) ) ) {
			return json_decode( $response['body'], true );
		}
		if ( 'HEAD' === $method && $response_status >= 200 && $response_status < 300 ) {
			return array(
				'status'  => $response_status,
				'headers' => $response['headers'],
				'body'    => $response['body'],
			);
		}

		// Set flag to notify about an invalid token.
		if ( 401 == $response_status ) {
			Options::set_invalid_token_flag();
		}

		return false;
	}

	/**
	 * Default user agent
	 *
	 * @return string
	 */
	protected static function get_user_agent() {
		return 'nativerentplugin/' . NATIVERENT_PLUGIN_VERSION;
	}

	/**
	 * Get X-Forwarded-For header value
	 *
	 * @return string
	 */
	protected static function get_x_forwarded_for() {
		$remote_addr = sanitize_text_field(
			wp_unslash( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' )
		);
		if ( filter_var( $remote_addr, FILTER_VALIDATE_IP ) === false ) {
			return '';
		}

		return htmlspecialchars( $remote_addr );
	}

	/**
	 * Get and parse HTTP-request from API server. Returns parsed request.
	 *
	 * @return array
	 */
	protected static function _get_request() {
		$request = json_decode( trim( file_get_contents( 'php://input' ) ), true );
		if ( self::_verify_request_signature( $request ) ) {
			return $request;
		}
		self::_access_denied();

		return array();
	}

	/**
	 * Echo API response and terminate current script.
	 *
	 * @param  array $arguments  List of arguments.
	 *
	 * @return void
	 */
	protected static function _response( $arguments ) {
		header( 'Content-Type: application/json' );
		echo json_encode( $arguments );
		exit();
	}

	/**
	 * Sign request
	 *
	 * @param  array $arguments  List of arguments.
	 *
	 * @return array|false
	 */
	protected static function _sign_request( $arguments ) {
		if ( ! is_array( $arguments ) ) {
			return false;
		}

		$signature              = md5( json_encode( $arguments ) . Options::get( 'secretKey' ) );
		$arguments['signature'] = $signature;

		return $arguments;
	}

	/**
	 * Verification signature
	 *
	 * @param  array $arguments  List of arguments.
	 *
	 * @return bool
	 */
	protected static function _verify_request_signature( $arguments ) {
		if ( ! is_array( $arguments ) || ! isset( $arguments['signature'] ) ) {
			return false;
		}
		$request_args = $arguments;
		$request_sign = $arguments['signature'];
		unset( $request_args['signature'] );
		$correct_sign = self::_sign_request( $request_args )['signature'];

		return ( strlen( $request_sign ) > 0 && $request_sign === $correct_sign );
	}

	/**
	 * Access denied
	 *
	 * @return void
	 */
	protected static function _access_denied() {
		header( 'HTTP/1.1 403 Forbidden' );
		exit();
	}

	/**
	 * Error reporting method.
	 *
	 * @param  Exception $error  Exception instance.
	 * @param  array     $extra  Additional data.
	 *
	 * @return void
	 */
	public static function report_error( $error, $extra = array() ) {
		global $wp_version;
		$env      = @getenv( 'WORDPRESS_ENV' );
		$hostname = @gethostname();
		$payload  = array(
			'siteID'  => Options::get( 'siteID' ),
			'tags'    => array(
				'release'     => defined( 'NATIVERENT_PLUGIN_VERSION' ) ? NATIVERENT_PLUGIN_VERSION : 'undefined',
				'environment' => ! empty( $env ) ? $env : 'production',
				'server_name' => is_string( $hostname ) ? $hostname : '',
			),
			'context' => array(
				'os'      => @php_uname(),
				'cms'     => 'Wordpress ' . ! empty( $wp_version ) ? $wp_version : '(undefined)',
				'runtime' => 'PHP ' . @phpversion(),
				'url'     => ( isset( $_SERVER['REQUEST_URI'] )
					? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) )
					: '' ),
			),
			'error'   => array(
				'name'    => get_class( $error ),
				'message' => $error->getMessage(),
				'file'    => $error->getFile(),
				'line'    => $error->getLine(),
				'trace'   => json_encode( $error->getTrace() ),
			),
			'extra'   => $extra,
		);

		self::_request(
			'reportError',
			$payload,
			array(
				'requestMethod' => 'POST',
				'timeout'       => 3,
				'blocking'      => false,
			)
		);
	}
}
