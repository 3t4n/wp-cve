<?php
/**
 * Response from Nova Poshta API.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api\Http;

use WP_Error;

/**
 * Wrapper class for parse responses.
 *
 * @since 1.0.0
 */
class Response {

	/**
	 * Input data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $input = [];

	/**
	 * Output data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $output = [];

	/**
	 * Request constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input The response data.
	 */
	public function __construct( array $input ) {

		$this->input = $input;
	}

	/**
	 * Retrieve only the response code from the raw response.
	 *
	 * @since 1.0.0
	 *
	 * @return int|string The response code as an integer.
	 */
	public function get_response_code() {

		$code = wp_remote_retrieve_response_code( $this->input );

		return ! empty( $code ) ? $code : 0;
	}

	/**
	 * Retrieve only the response message from the raw response.
	 *
	 * @since 1.0.0
	 *
	 * @return string The response message.
	 */
	public function get_response_message(): string {

		$message = wp_remote_retrieve_response_message( $this->input );

		return ! empty( $message ) ? $message : '';
	}

	/**
	 * Retrieve only the body from the raw response.
	 *
	 * @since 1.0.0
	 *
	 * @return array|WP_Error The body of the response.
	 */
	public function get_body() {

		$body = json_decode( wp_remote_retrieve_body( $this->input ), true );

		if ( ! empty( $body['success'] ) && ! empty( $body['data'] ) ) {
			return $body['data'];
		}

		$error = new WP_Error();

		if ( empty( $body['errors'] ) ) {
			$error->add( 400, esc_html__( 'Something went wrong', 'shipping-nova-poshta-for-woocommerce' ) );

			return $error;
		}

		foreach ( $body['errors'] as $key => $message ) {
			$error->add( 400, $message );
		}

		return $error;
	}
}
