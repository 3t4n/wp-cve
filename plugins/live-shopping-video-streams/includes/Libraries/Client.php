<?php 
/**
 * Client provides methods for interacting with the API.
 *
 * @category   Channelize
 * @package    Client
 * @copyright  Copyright (c) 2021 {@link https://channelize.io/ Channelize, Inc.}
 */
 
 
 
namespace Includes\Libraries;

use Includes\Libraries\Exceptions\InternalServerException;


class Client {

	/**
	 * Subdomain for all requests.
	 */


	/**
	 * Default API key for all requests, may be overridden with the Client constructor
	 */
	public static $privateKey;

	/**
	 * Default User Id to be sent in header
	 */
	public static $userId;

	const DEFAULT_ENCODING = 'UTF-8';

	/**
	 * Base API URL
	 */
	public static $apiUrl = 'https://api.channelize.io/v2';

	/**
	 * The path to your CA certs. Use only if needed (if you can't fix libcurl/php).
	 */
	public static $CACertPath = false;

	/**
	 * API Key instance, may differ from the static key
	 */
	private $_privateKey;

	const GET                             = 'GET';
	const POST                            = 'POST';
	const PUT                             = 'PUT';
	const DELETE                          = 'DELETE';
	const HEAD                            = 'HEAD';
	const PATH_USERS                      = '/users';
	const PATH_CUSTOMER                   = '/users';
	const PATH_CONVERSATIONS              = '/conversations';
	const PATH_MESSAGES                   = '/messages';
	const PATH_PUSH_NOTIFICATION_SETTINGS = '/push_notification_settings';

	/**
	 * Create a new Primemessenger Client
	 *
	 * @param string API key. Do not specify to use the default API key (which must be set at the static variable)
	 */
	function __construct( $privateKey = null ) {
		$this->_privateKey = $privateKey;
	}

	public function request( $method, $uri, $data = null, $formData = true, $userId = null ) {
		return $this->sendRequest( $method, $uri, $data, $formData, $userId );
	}

	public function baseUri() {
		return sprintf( static::$apiUrl );

	}

	/**
	 * Current API key
	 *
	 * @return string API key
	 */
	public function privateKey() {
		return ( empty( $this->privateKey ) ? static::$privateKey : $this->_privateKey );
	}

	/**
	 * Sends an HTTP request to the Primemessenger API
	 *
	 * @param string $method Specifies the HTTP method to be used for this request
	 * @param string $uri    Target URI for this request (relative to the API root)
	 * @param mixed  $data   x-www-form-urlencoded data (or array) to be sent in a POST request body
	 *
	 * @return $code, $response
	 */
	private function sendRequest( $method, $uri, $data = '', $formData = true, $userId = null ) {
		if ( ! $formData ) {
			$data        = json_encode( $data );
			$contentType = 'application/json';
		} else {
			$contentType = 'multipart/form-data';
		}

		if ( substr( $uri, 0, 4 ) != 'http' ) {
			$uri = $this->baseUri() . $uri;
		}
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $uri );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
		if ( self::$CACertPath ) {
			curl_setopt( $ch, CURLOPT_CAINFO, self::$CACertPath );
		}

		if ( empty( $userId ) ) {
			$userId = static::$userId;
		}

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, false );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 45 );
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: ' . $contentType,
				'Accept: application/json',
				'Authorization: Basic ' . base64_encode( $this->privateKey() ),
				'User-Id:' . $userId,
			)
		);
		curl_setopt( $ch, CURLOPT_USERPWD, $this->privateKey() );

		if ( 'POST' == $method ) {
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		} elseif ( 'PUT' == $method ) {
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		} elseif ( 'GET' != $method ) {
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
		}

		$response = curl_exec( $ch );

		if ( $response === false ) {
			$errorNumber = curl_errno( $ch );
			$message     = curl_error( $ch );
			curl_close( $ch );
			$this->raiseCurlError( $errorNumber, $message );
		}

		$statusCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );

		list($header, $body) = explode( "\r\n\r\n", $response, 2 );

		// Larger responses end up prefixed by "HTTP/1.1 100 Continue\r\n\r\n" which
		// needs to be discarded.
		if ( strpos( $header, ' 100 Continue' ) !== false ) {
			list($header, $body) = explode( "\r\n\r\n", $body, 2 );
		}
		$headers = $this->getHeaders( $header );

		return new Response( $statusCode, $headers, $body );
	}

	private function getHeaders( $headerText ) {
		$headers       = explode( "\r\n", $headerText );
		$returnHeaders = array();
		foreach ( $headers as &$header ) {
			preg_match( '/([^:]+): (.*)/', $header, $matches );
			if ( sizeof( $matches ) > 2 ) {
				$returnHeaders[ $matches[1] ] = $matches[2];
			}
		}
		return $returnHeaders;
	}

	private function raiseCurlError( $errorNumber, $message ) {
		switch ( $errorNumber ) {
			case CURLE_COULDNT_CONNECT:
			case CURLE_COULDNT_RESOLVE_HOST:
			case CURLE_OPERATION_TIMEOUTED:
				throw new InternalServerException( "Failed to connect to Primemessenger ($message)." );
			case CURLE_SSL_CACERT:
			case CURLE_SSL_PEER_CERTIFICATE:
				throw new InternalServerException( "Could not verify Primemessenger's SSL certificate." );
			default:
				throw new InternalServerException( 'An unexpected error occurred connecting with Primemessenger.' );
		}
	}
}

