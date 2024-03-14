<?php

namespace QuadLayers\IGG\Api\Fetch\Business\Hashtag_Id;

use QuadLayers\IGG\Api\Fetch\Business\Base;

/**
 * Api_Fetch_Business_Hashtag_Id
 */
class Get extends Base {

	/**
	 * Function to get hashtag id
	 *
	 * @param int    $id Account id.
	 * @param string $access_token Account access_token.
	 * @param string $hashtag Feed hashtag to look for.
	 * @return array|int
	 */
	public function get_data( $id = null, $access_token = null, $hashtag = null ) {
		$response = $this->get_response( $id, $access_token, $hashtag );
		$data     = $this->response_to_data( $response );
		return $data;
	}

	/**
	 * Function to query instagram data.
	 *
	 * @param int    $id Account id.
	 * @param string $access_token Account access_token.
	 * @param string $hashtag Feed hashtag to look for.
	 * @return array|int
	 */
	public function get_response( $id = null, $access_token = null, $hashtag = null ) {
		$url      = $this->get_url( $id, $access_token, $hashtag );
		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 30,
			)
		);
		return $this->handle_response( $response );
	}

	/**
	 * Function to parse response to usable data.
	 *
	 * @param array $response Raw response from instagram.
	 * @return array|int
	 */
	public function response_to_data( $response = null ) {
		if ( isset( $response['data'] ) ) {
			return $response['data'][0]['id'];
		}

		return $response;
	}

	/**
	 * Function to build query url.
	 *
	 * @param int    $id Account id.
	 * @param string $access_token Account access_token.
	 * @param string $hashtag Feed hashtag to look for.
	 * @return string
	 */
	public function get_url( $id = null, $access_token = null, $hashtag = null ) {

		$url = add_query_arg(
			array(
				'user_id'      => $id,
				'q'            => urlencode( $hashtag ),
				'access_token' => $access_token,
			),
			"{$this->api_url}/ig_hashtag_search"
		);
		return $url;
	}

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
		$body     = isset( $response['body'] ) ? json_decode( $response['body'], true ) : null;
		$is_error = isset( $body['error'] ) ? true : false;
		$message  = isset( $body['error']['error_user_msg'] ) ? $body['error']['error_user_msg'] : esc_html__( 'Unknown error.', 'insta-gallery' );
		$code     = isset( $body['error']['code'] ) ? $body['error']['code'] : 404;
		if ( $is_error ) {
			return array(
				'code'    => $code,
				'message' => $message,
			);
		}
		return false;
	}

}
