<?php
/**
 * Request for Nova Poshta API.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api\Http;

/**
 * Wrapper class for making HTTP requests.
 *
 * @since 1.0.0
 */
class Request {

	/**
	 * User-agent value.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const USER_AGENT = 'nova-poshta-api-v2';

	/**
	 * Base URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $base_url = '';

	/**
	 * Parameters.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $parameters = [];

	/**
	 * Request constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $base_url Base URL.
	 * @param array  $params   Parameters.
	 */
	public function __construct( string $base_url, array $params = [] ) {

		$this->base_url   = $base_url;
		$this->parameters = array_merge_recursive( $this->default_parameters(), $params );
	}

	/**
	 * Send a request based on method (main interface).
	 *
	 * @since 1.0.0
	 *
	 * @param string $request_method Request method POST, GET, PUT and other.
	 * @param string $model          Model name.
	 * @param string $method         Method name.
	 * @param array  $properties     List of properties.
	 *
	 * @return Response|WP_Error
	 */
	public function request( string $request_method, string $model, string $method, array $properties = [] ) {

		// Merge options.
		$options = array_merge_recursive(
			$this->parameters,
			[
				'body' => [
					'modelName'        => $model,
					'calledMethod'     => $method,
					'methodProperties' => (object) $properties,
				],
			]
		);

		// Set a request method.
		$options['method'] = $request_method;

		// Prepare body - API expect a JSON format.
		$options['body'] = wp_json_encode( $options['body'] );

		// Pass a query data to body.
		if ( ! empty( $options['query'] ) && 'GET' === $method ) {
			$options['body'] = $options['query'];
		}

		/**
		 * Filter a request options before it's sent.
		 *
		 * @since 1.0.0
		 *
		 * @param array   $options  Request options.
		 * @param string  $method   Request method.
		 * @param string  $uri      Request URI.
		 * @param Request $instance Instance of Request class.
		 */
		$options = apply_filters( 'nova_poshta_api_http_request_option', $options, $method, $this );

		// Retrieve the raw response from a safe HTTP request.
		$response = wp_safe_remote_request( $this->base_url, $options );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response = new Response( $response );

		if ( is_wp_error( $response->get_body() ) ) {
			return $response->get_body();
		}

		return $response;
	}

	/**
	 * Retrieve default parameters for request.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function default_parameters(): array {

		/**
		 * Request parameters.
		 *
		 * @since 1.0.0
		 *
		 * @param array   $defaults Request arguments.
		 * @param Request $instance Instance of Request class.
		 */
		return (array) apply_filters(
			'nova_poshta_api_http_request',
			[
				'method'      => 'GET',
				'timeout'     => 5,
				'data_format' => 'body',
				'headers'     => [
					'Content-Type' => 'application/json',
				],
			],
			$this
		);
	}
}
