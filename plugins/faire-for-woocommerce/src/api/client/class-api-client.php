<?php

namespace Faire\Wc\Api\Client;

use Exception;
use Faire\Wc\Api\Interfaces\Api_Auth_Interface;
use Faire\Wc\Api\Interfaces\Api_Driver_Interface;
use Faire\Wc\Api\Request;
use Faire\Wc\Api\Response;
use Faire\Wc\Api\Utils;

/**
 * A generic REST API client implementation.
 *
 * This class does not expose any public methods. It is intended to be extended to create real client classes,
 * alleviating most of the work involved in using drivers, auth handlers, creating request and response objects as
 * well as ensuring proper request URL integrity.
 *
 * This class provides the following functionality:
 * - protected methods for sending GET, POST and DELETE requests
 * - a base URL property that is prepended to route before creating and sending requests
 * - implements usage of a REST API driver for sending requests
 * - implements optional usage of an auth handler to authorize requests before sending them via the driver
 *
 * Example usage:
 *  ```
 *  class My_Api_Client extends Api_Client {
 *
 *      public function do_foo_bar( ) {
 *          $response = $this->post( 'foo/bar, array('some' => 'data') );
 *
 *          if ($response->status === 200) {
 *              return $response->body;
 *          }
 *
 *          throw new Exception($response->body->error_msg);
 *      }
 *
 *  }
 *  ```
 *
 * @since [*next-version*]
 */
class Api_Client {

	/**
	 * The base URL for the REST API.
	 *
	 * @since [*next-version*]
	 *
	 * @var string
	 */
	protected string $base_url;

	/**
	 * The REST API driver to use.
	 *
	 * @since [*next-version*]
	 *
	 * @var Api_Driver_Interface
	 */
	protected Api_Driver_Interface $driver;

	/**
	 * The authorization driver to use.
	 *
	 * @since [*next-version*]
	 *
	 * @var Api_Auth_Interface|null
	 */
	protected ?Api_Auth_Interface $auth;

	/**
	 * Constructor.
	 *
	 * @param string                  $base_url The base URL for the REST API.
	 * @param Api_Driver_Interface    $driver   The REST API driver to use.
	 * @param Api_Auth_Interface|null $auth     Optional authorization driver to use.
	 *
	 * @since [*next-version*]
	 */
	public function __construct(
		string $base_url,
		Api_Driver_Interface $driver,
		Api_Auth_Interface $auth = null
	) {
		$this->base_url = $base_url;
		$this->driver   = $driver;
		$this->auth     = $auth;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since [*next-version*]
	 */
	protected function get(
		$route,
		array $params = array(),
		array $headers = array(),
		array $cookies = array()
	): Response {
		return $this->send_request( Request::TYPE_GET, $route, $params, '', $headers, $cookies );
	}

	/**
	 * Retrieves a page of results.
	 *
	 * @param string $route Route to get the results.
	 * @param array  $args  Arguments for the request.
	 *
	 * @return Response
	 */
	protected function get_page( string $route, array $args ): Response {
		$parameters    = array();
		$args['page']  = isset( $args['page'] ) ? $args['page'] : 1;
		$args['limit'] = isset( $args['limit'] ) ? $args['limit'] : 50;

		// Send the request and get the response.
		return $this->get( $route, $args );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since [*next-version*]
	 */
	protected function post(
		$route,
		$body = '',
		array $headers = array(),
		array $cookies = array()
	): Response {
		return $this->send_request( Request::TYPE_POST, $route, array(), $body, $headers, $cookies );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since [*next-version*]
	 */
	protected function put(
		$route,
		$body = '',
		array $headers = array(),
		array $cookies = array()
	): Response {
		return $this->send_request( Request::TYPE_PUT, $route, array(), $body, $headers, $cookies );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since [*next-version*]
	 */
	protected function patch(
		$route,
		$body = '',
		array $headers = array(),
		array $cookies = array()
	): Response {
		return $this->send_request( Request::TYPE_PATCH, $route, array(), $body, $headers, $cookies );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since [*next-version*]
	 */
	protected function delete(
		$route,
		$body = '',
		array $headers = array(),
		array $cookies = array()
	): Response {
		return $this->send_request( Request::TYPE_DELETE, $route, array(), $body, $headers, $cookies );
	}

	/**
	 * Get Brand profile
	 *
	 * @return object The brand information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function get_brand_profile(): object {

		// The brands profile endpoint.
		$route = 'brands/profile';

		// Send the request and get the response.
		$response = $this->get( $route, array() );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Test API connection
	 *
	 * @return bool boolean The result of the test connection attempt with the remote API.
	 *
	 * @throws Exception
	 */
	public function test_connection(): bool {

		if ( get_transient( 'faire_api_connection_test' ) ) {
			return true;
		}

		// Test connection by getting the brands profile endpoint per documentation recommendation.
		$route = 'brands/profile';

		// Send the request and get the response.
		$response = $this->get( $route, array() );

		// Return the response body on success.
		if ( 200 === $response->status || 201 === $response->status ) {
			set_transient( 'faire_api_connection_test', true, 24 * 60 * 60 );

			return true;
		}

		// Otherwise, throw an exception using the response's error messages.
		$this->throw_error( $response );
		delete_transient( 'faire_api_connection_test' );

		return false;
	}

	/**
	 * Sends a request using the internal driver.
	 *
	 * @param int    $type    The request type, either {@link TYPE_GET} or {@link TYPE_POST}.
	 * @param string $route   The request route, relative to the REST APIs base URL.
	 * @param array  $params  The GET params for this request.
	 * @param mixed  $body    The body of the request.
	 * @param array  $headers The request headers to send.
	 * @param array  $cookies The request cookies to send.
	 *
	 * @return Response The response.
	 * @since [*next-version*]
	 */
	protected function send_request(
		int $type,
		string $route,
		array $params = array(),
		$body = null,
		array $headers = array(),
		array $cookies = array()
	): Response {
		// Generate the full URL and the request object.
		$full_url = Utils::merge_url_and_route( $this->base_url, $route );
		$request  = new Request( $type, $full_url, $params, $body, $headers, $cookies );

		// If we have an authorization driver, authorize the request.
		if ( null !== $this->auth ) {
			$request = $this->auth->authorize( $request );
		}

		// Send the request using the driver and obtain the response.
		return $this->driver->send( $request );
	}

	/**
	 * Returns the body of a response as a JSON object.
	 *
	 * @param Response $response The response from a request.
	 * @param array    $success_statuses The response statuses considered success.
	 *
	 * @return object The response body as a JSON object.
	 * @throws Exception Exception.
	 */
	protected function get_response_body( Response $response, array $success_statuses ): object {

		// Throw an exception using the response's error messages.
		if ( ! in_array( $response->status, $success_statuses, true ) ) {
			$this->throw_error( $response );
		}

		// Return the response body on success.
		return is_object( $response->body ) ? $response->body : json_decode( $response->body );
	}

	/**
	 * Faire API Error http status responses:
	 *
	 * 400  Bad Request -- Your request is invalid.
	 * 401  Unauthorized -- Your API key is wrong.
	 * 404  Not Found -- The requested resource could not be found.
	 * 405  Method Not Allowed -- You tried to access an entity with an invalid method.
	 * 418  I'm a teapot.
	 * 429  Too Many Requests.
	 * 500  Internal Server Error -- We had a problem with our server. Try again later.
	 * 503  Service Unavailable -- We're temporarily offline for maintenance. Please try again later
	 *
	 * @param Object|null $response Response.
	 *
	 * @throws Exception Exception.
	 */
	protected function throw_error( $response = null ) {

		$error_message = __( 'Unknown API Response', 'faire-for-woocommerce' );
		$status_code   = 0;

		$response_obj = ( is_object( $response->body ) ) ? $response->body : json_decode( $response->body );
		if ( is_object( $response_obj ) ) {
			$error_message = ( isset( $response_obj->message ) ) ? (
				! is_scalar( $response_obj->message ) ? var_export( $response_obj->message, true ) : $response_obj->message
			) : $error_message;
			$status_code   = ( isset( $response_obj->status_code ) ) ? (int) $response_obj->status_code : $status_code;
		}

		throw new Exception(
			// translators: %s error.
			sprintf( __( 'Faire API error: %s', 'faire-for-woocommerce' ), $error_message ),
			$status_code
		);
	}

}
