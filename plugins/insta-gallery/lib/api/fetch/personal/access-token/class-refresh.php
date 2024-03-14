<?php
namespace QuadLayers\IGG\Api\Fetch\Personal\Access_Token;

use QuadLayers\IGG\Api\Fetch\Personal\Base;

/**
 * Api_Fetch_Personal_Refresh_Access_Token
 */
class Refresh extends Base {

	/**
	 * Endpoint to query
	 *
	 * @var string
	 */
	protected $api_url = 'https://socialfeed.quadlayers.com/renew_instagram.php';

	/**
	 * Function to get account data from Instagram.
	 *
	 * @param string $access_token Account access_token.
	 * @param int    $renew_account Count of renew attemps.
	 * @return array
	 */
	public function get_data( $access_token = null, $renew_account = null ) {
		$response = $this->get_response( $access_token, $renew_account );
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
		 * Compatibility with the old version
		 */
		if ( isset( $response['token_type'] ) ) {
			$response['access_token_type'] = $response['token_type'];
			unset( $response['token_type'] );
		}
		return $response;
	}

	/**
	 * Function to query instagram data.
	 *
	 * @param string $access_token Account access_token.
	 * @param int    $renew_account Count of renew attemps.
	 * @return array
	 */
	public function get_response( $access_token = null, $renew_account = null ) {
		$url = $this->get_url( $access_token, $renew_account );

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
	 * @param int    $renew_account Count of renew attemps.
	 * @return string
	 */
	public function get_url( $access_token = null, $renew_account = null ) {
		$url = add_query_arg(
			array(
				'access_token' => $access_token,
				'renew_count'  => $renew_account,
			),
			$this->api_url
		);

		return $url;
	}
}
