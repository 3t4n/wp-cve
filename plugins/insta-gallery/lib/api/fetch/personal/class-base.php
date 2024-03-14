<?php
namespace QuadLayers\IGG\Api\Fetch\Personal;

use QuadLayers\IGG\Api\Fetch\Fetch as Fetch_Interface;

/**
 * Base
 */
abstract class Base implements Fetch_Interface {

	/**
	 * Instagram Graph API URL
	 *
	 * @var string
	 */
	protected $api_url = 'https://graph.instagram.com/me';

	/**
	 * Function to handle query response
	 *
	 * @param array $response Facebook response.
	 * @return array
	 */
	public function handle_response( $response = null ) {
		return $this->handle_error( $response ) ? $this->handle_error( $response ) : json_decode( $response['body'], true );
	}

	/**
	 * Function to handle error on query response
	 *
	 * @param array|object $response Facebook response.
	 * @return array
	 */
	public function handle_error( $response = null ) {
		// check if is wp error
		if ( is_wp_error( $response ) ) {
			return array(
				'code'    => $response->get_error_code(),
				'message' => $response->get_error_message(),
			);
		}
		if ( ! is_array( $response ) ) {
			return array(
				'code'    => 500,
				'message' => esc_html__( 'Unexpected error! Please reload the page.', 'insta-gallery' ),
			);
		}
		$is_error = ! isset( $response['response']['code'] ) || 200 !== $response['response']['code'];
		if ( $is_error ) {
			$message = isset( $response['response']['message'] ) ? $response['response']['message'] : esc_html__( 'Unknown error.', 'insta-gallery' );
			return array(
				'code'    => $response['response']['code'],
				'message' => $message,
			);
		}
		return false;
	}
}
