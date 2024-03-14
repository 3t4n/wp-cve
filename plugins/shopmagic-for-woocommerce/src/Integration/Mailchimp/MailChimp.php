<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Integration\Mailchimp;

/**
 * Super-simple, minimum abstraction MailChimp API v3 wrapper
 * MailChimp API v3: http://developer.mailchimp.com
 * This wrapper: https://github.com/drewm/mailchimp-api
 *
 * @author Drew McLellan <drew.mclellan@gmail.com>
 * @version 2.2
 */
class MailChimp {

	/**
	 * @var string
	 */
	private const HEADERS = 'headers';
	/**
	 * @var string
	 */
	private const BODY = 'body';

	/*
	  SSL Verification
		Read before disabling:
		http://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/
	*/
	/**
	 * @var string
	 */
	private const STATUS = 'status';
	/**
	 * @var bool
	 */
	public $verify_ssl = true;
	private $api_key;
	/**
	 * @var mixed[]|string
	 */
	private $api_endpoint = 'https://<dc>.api.mailchimp.com/3.0';
	/**
	 * @var bool
	 */
	private $request_successful = false;
	/**
	 * @var string
	 */
	private $last_error = '';
	/**
	 * @var array<int|string, null>&mixed[]
	 */
	private $last_response = [];
	/**
	 * @var mixed[]
	 */
	private $last_request = [];

	/**
	 * Create a new instance
	 *
	 * @param string $api_key Your MailChimp API key
	 *
	 * @throws \Exception
	 */
	public function __construct( $api_key, $verify_ssl = false ) {
		$this->verify_ssl = $verify_ssl;

		$this->api_key = $api_key;

		if ( empty( $this->api_key ) ) {
			throw new \Exception( 'Missing MailChimp API key.' );
		}

		if ( is_string( $this->api_key ) && strpos( $this->api_key, '-' ) === false ) {
			throw new \Exception( 'Invalid MailChimp API key supplied.' );
		}

		[, $data_center]    = explode( '-', $this->api_key );
		$this->api_endpoint = str_replace( '<dc>', $data_center, $this->api_endpoint );

		$this->last_response = [
			self::HEADERS => null,
			self::BODY    => null,
		];
	}

	/**
	 *
	 * @return
	 */
	public function getApiKey() {
		return $this->api_key;
	}

	/**
	 * Was the last request successful?
	 *
	 * @return bool  True for success, false for failure
	 */
	public function success() {
		return $this->request_successful;
	}

	/**
	 * Get the last error returned by either the network transport, or by the API.
	 * If something didn't work, this should contain the string describing the problem.
	 *
	 * @return  array|false  describing the error
	 */
	public function getLastError() {
		return $this->last_error ?: false;
	}

	/**
	 * Get an array containing the HTTP headers and the body of the API response.
	 *
	 * @return array  Assoc array with keys 'headers' and 'body'
	 */
	public function getLastResponse() {
		return $this->last_response;
	}

	/**
	 * Get an array containing the HTTP headers and the body of the API request.
	 *
	 * @return array  Assoc array
	 */
	public function getLastRequest() {
		return $this->last_request;
	}

	/**
	 * Make an HTTP DELETE request - for deleting data
	 *
	 * @param string $method URL of the API request method
	 * @param array $args Assoc array of arguments (if any)
	 * @param int $timeout Timeout limit for request in seconds
	 *
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function delete( string $method, array $args = [], int $timeout = 10 ) {
		return $this->makeRequest( 'delete', $method, $args, $timeout );
	}

	/**
	 * Performs the underlying HTTP request. Not very exciting.
	 *
	 * @param string $http_verb The HTTP verb to use: get, post, put, patch, delete
	 * @param string $method The API method to be called
	 * @param array $args Assoc array of parameters to be passed
	 *
	 * @return array|false Assoc array of decoded result
	 * @throws Exception
	 */
	private function makeRequest( string $http_verb, string $method, array $args = [], int $timeout = 10 ) {
		if ( ! \function_exists( 'curl_init' ) || ! \function_exists( 'curl_setopt' ) ) {
			throw new \Exception( "cURL support is required, but can't be found." );
		}

		$url = $this->api_endpoint . '/' . $method;

		$this->last_error         = '';
		$this->request_successful = false;
		$response                 = [
			self::HEADERS => null,
			self::BODY    => null,
		];
		$this->last_response      = $response;

		$this->last_request = [
			'method'   => $http_verb,
			'path'     => $method,
			'url'      => $url,
			self::BODY => '',
			'timeout'  => $timeout,
		];

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			[
				'Accept: application/vnd.api+json',
				'Content-Type: application/vnd.api+json',
				'Authorization: apikey ' . $this->api_key,
			]
		);
		curl_setopt( $ch, CURLOPT_USERAGENT, 'DrewM/MailChimp-API/3.0 (github.com/drewm/mailchimp-api)' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl );
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
		curl_setopt( $ch, CURLOPT_ENCODING, '' );
		curl_setopt( $ch, CURLINFO_HEADER_OUT, true );

		switch ( $http_verb ) {
			case 'post':
				curl_setopt( $ch, CURLOPT_POST, true );
				$this->attachRequestPayload( $ch, $args );
				break;

			case 'get':
				$query = http_build_query( $args );
				curl_setopt( $ch, CURLOPT_URL, $url . '?' . $query );
				break;

			case 'delete':
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
				break;

			case 'patch':
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PATCH' );
				$this->attachRequestPayload( $ch, $args );
				break;

			case 'put':
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
				$this->attachRequestPayload( $ch, $args );
				break;
		}

		$response[ self::BODY ]    = curl_exec( $ch );
		$response[ self::HEADERS ] = curl_getinfo( $ch );

		if ( isset( $response[ self::HEADERS ]['request_header'] ) ) {
			$this->last_request[ self::HEADERS ] = $response[ self::HEADERS ]['request_header'];
		}

		if ( $response[ self::BODY ] === false ) {
			$this->last_error = curl_error( $ch );
		}

		curl_close( $ch );

		return $this->formatResponse( $response );
	}

	/**
	 * Encode the data and attach it to the request
	 *
	 * @param resource $ch cURL session handle, used by reference
	 * @param array $data Assoc array of data to attach
	 */
	private function attachRequestPayload( &$ch, array $data ): void {
		$encoded                          = json_encode( $data );
		$this->last_request[ self::BODY ] = $encoded;
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $encoded );
	}

	/**
	 * Decode the response and format any error messages for debugging
	 *
	 * @param array $response The response from the curl request
	 *
	 * @return array|false     The JSON decoded into an array
	 */
	private function formatResponse( array $response ) {
		$this->last_response = $response;

		if ( ! empty( $response[ self::BODY ] ) ) {

			$d = json_decode( $response[ self::BODY ], true );

			if ( isset( $d[ self::STATUS ] ) && $d[ self::STATUS ] != '200' && isset( $d['detail'] ) ) {
				$this->last_error = sprintf( '%d: %s', $d[ self::STATUS ], $d['detail'] );
			} else {
				$this->request_successful = true;
			}

			return $d;
		}

		return false;
	}

	/**
	 * Make an HTTP GET request - for retrieving data
	 *
	 * @param string $method URL of the API request method
	 * @param array $args Assoc array of arguments (usually your data)
	 * @param int $timeout Timeout limit for request in seconds
	 *
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function get( string $method, array $args = [], int $timeout = 10 ) {
		return $this->makeRequest( 'get', $method, $args, $timeout );
	}

	/**
	 * Make an HTTP PATCH request - for performing partial updates
	 *
	 * @param string $method URL of the API request method
	 * @param array $args Assoc array of arguments (usually your data)
	 * @param int $timeout Timeout limit for request in seconds
	 *
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function patch( string $method, array $args = [], int $timeout = 10 ) {
		return $this->makeRequest( 'patch', $method, $args, $timeout );
	}

	/**
	 * Make an HTTP POST request - for creating and updating items
	 *
	 * @param string $method URL of the API request method
	 * @param array $args Assoc array of arguments (usually your data)
	 * @param int $timeout Timeout limit for request in seconds
	 *
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function post( string $method, array $args = [], int $timeout = 10 ) {
		return $this->makeRequest( 'post', $method, $args, $timeout );
	}

	/**
	 * Make an HTTP PUT request - for creating new items
	 *
	 * @param string $method URL of the API request method
	 * @param array $args Assoc array of arguments (usually your data)
	 * @param int $timeout Timeout limit for request in seconds
	 *
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function put( string $method, array $args = [], int $timeout = 10 ) {
		return $this->makeRequest( 'put', $method, $args, $timeout );
	}
}
