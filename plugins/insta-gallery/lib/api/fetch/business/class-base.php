<?php
namespace QuadLayers\IGG\Api\Fetch\Business;

use QuadLayers\IGG\Api\Fetch\Fetch as Fetch_Interface;

/**
 * Base
 */
abstract class Base implements Fetch_Interface {

	/**
	 * Facebook Graph API URL
	 *
	 * @var string
	 */
	protected $api_url = 'https://graph.facebook.com';

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

		$body    = isset( $response['body'] ) ? json_decode( $response['body'], true ) : null;
		$is_json = $this->is_json( isset( $response['body'] ) ? $response['body'] : null );
		if ( ! $is_json ) {
			return array(
				'code'    => 500,
				'message' => esc_html__( 'Unexpected error! Please reload the page.', 'insta-gallery' ),
			);
		}
		$is_error = isset( $body['error'] ) ? true : false;
		$message  = isset( $body['error']['message'] ) ? $body['error']['message'] : esc_html__( 'Unknown error.', 'insta-gallery' );
		$code     = isset( $body['error']['code'] ) ? $body['error']['code'] : 404;
		if ( $is_error ) {
			return array(
				'code'    => $code,
				'message' => $message,
			);
		}
		return false;
	}

	/**
	 * Function to verify is string is JSON
	 *
	 * @param string $string String to verify.
	 * @return boolean
	 */
	private function is_json( $string ) {
		json_decode( $string );
		return json_last_error() === JSON_ERROR_NONE;
	}
}
