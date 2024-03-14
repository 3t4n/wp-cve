<?php
/**
 * Google API client auth code exception.
 *
 * @package GoogleAnalytics
 */

/**
 * Google API client auth code exception.
 */
class Ga_Lib_Google_Api_Client_AuthCode_Exception extends Ga_Lib_Google_Api_Client_Exception {

	/**
	 * Get error response data.
	 *
	 * @param string $response Response.
	 *
	 * @return array[]
	 */
	protected function get_error_response_data( $response ) {
		$data = json_decode( $response, true );

		$error = $data['error'];

		if ( true === is_array( $error ) && true === isset( $error['message'] ) ) {
			$error = $error['message'];
		}

		if ( false === empty( $data['error'] ) ) {
			return array(
				'error' => array(
					'message' => $error,
					'code'    => 500,
				),
			);
		} else {
			return array(
				'error' => array(
					'message' => 'Google API - uknown error.',
					'code'    => 500,
				),
			);
		}
	}
}
