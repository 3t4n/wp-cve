<?php
namespace QuadLayers\IGG\Api\Fetch\Personal\User_Profile;

use QuadLayers\IGG\Api\Fetch\Personal\Base;

/**
 * Api_Fetch_Personal_User_Profile
 */
class Get extends Base {

	/**
	 * Function to get profile data from feed.
	 *
	 * @param string $access_token Account access_token.
	 * @return array $data
	 */
	public function get_data( $access_token = null ) {
		$response = $this->get_response( $access_token );
		$data     = $this->response_to_data( $response );
		return $data;
	}

	/**
	 * Function to parse response to usable data.
	 *
	 * @param array $response Raw response from instagram.
	 * @return array
	 */
	public function response_to_data( $response = null ) {

		/**
		 * If response is not valid, return response.
		 */
		if ( ! isset( $response['id'], $response['username'] ) ) {
			return $response;
		}

		$data = array(
			'id'        => $response['id'],
			'username'  => $response['username'],
			'link'      => 'https://www.instagram.com/' . $response['username'],
			'nickname'  => '',
			'website'   => '',
			'biography' => '',
			'avatar'    => '',
		);

		return $data;
	}

	/**
	 * Function to query instagram data.
	 *
	 * @param string $access_token Account access_token.
	 * @return array
	 */
	public function get_response( $access_token = null ) {
		$url = $this->get_url( $access_token );

		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 30,
			)
		);
		$response = $this->handle_response( $response );

		return $response;
	}

	/**
	 * Function to build query url.
	 *
	 * @param string $access_token Account access_token.
	 * @return string
	 */
	public function get_url( $access_token = null ) {
		$url = add_query_arg(
			array(
				'fields'       => 'id,media_count,username,account_type',
				'access_token' => $access_token,
			),
			$this->api_url
		);

		return $url;
	}
}
