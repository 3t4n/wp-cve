<?php
/**
 * Google API client exception.
 *
 * @package GoogleAnalytics
 */

/**
 * Google API client exception.
 */
class Ga_Lib_Google_Api_Client_Exception extends Ga_Lib_Api_Client_Exception {
	/**
	 * Google error response.
	 *
	 * @var string|null
	 */
	private $google_error_response = null;

	/**
	 * Constructor.
	 *
	 * @param string $msg Exception message.
	 */
	public function __construct( $msg ) {
		$this->set_google_error_response( $msg );
		$data = $this->get_error_response_data( $msg );
		parent::__construct( $data['error']['message'], $data['error']['code'] );
	}

	/**
	 * Sets google JSON response.
	 * Response structure:
	 * {
	 * "error": {
	 * "code": 403,
	 * "message": "User does not have sufficient permissions for this profile.",
	 * "status": "PERMISSION_DENIED",
	 * "details": [
	 * {
	 * "@type": "type.googleapis.com/google.rpc.DebugInfo",
	 * "detail": "[ORIGINAL ERROR] generic::permission_denied: User does not have sufficient permissions for this profile.
	 *  [google.rpc.error_details_ext] { message: \"User does not have sufficient permissions for this profile.\" }"
	 * }
	 * ]
	 * }
	 * }
	 *
	 * @param string $response Response string.
	 */
	public function set_google_error_response( $response ) {
		$this->google_error_response = $response;
	}

	/**
	 * Get Google error response.
	 *
	 * @return string
	 */
	public function get_google_error_response() {
		return $this->google_error_response;
	}

	/**
	 * Decodes JSON response
	 *
	 * @param string $response Response string.
	 *
	 * @return array Decoded object array.
	 */
	protected function get_error_response_data( $response ) {
		$data = json_decode( $response, true );
		if ( is_array( $data['error'] ) && ! empty( $data['error'] ) && ! empty( $data['error']['message'] ) && ! empty( $data['error']['code'] ) ) {
			return $data;
		} elseif ( ! empty( $data['error'] ) ) {
			return array(
				'error' => array(
					'message' => $data['error'],
					'code'    => 500,
				),
			);
		} else {
			return array(
				'error' => array(
					'message' => __( 'Google Reporting API - unknown error.' ),
					'code'    => 500,
				),
			);
		}
	}
}
