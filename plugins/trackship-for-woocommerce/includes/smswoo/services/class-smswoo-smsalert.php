<?php
/**
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SMSWOO_SMSAlert' ) ) {

	/**
	 * SMS Alert
	 *
	 * @class   SMSWOO_SMSAlert
	 * @since   
	 *
	 */
	class SMSWOO_SMSAlert extends SMSWOO_Sms_Gateway {

		private $_smsalert_api_key;
		public $new_status;

		/**
		 * Constructor
		 *
		 * @since  
		 * @return  void
		 */
		public function __construct() {

			$this->_smsalert_api_key    = get_option( 'smswoo_smsalert_key' );

			parent::__construct();

		}

		/**
		 * Send SMS
		 *
		 * @since   
		 *
		 * @param   $to_phone     string
		 * @param   $message      string
		 * @param   $country_code string
		 *
		 * @return  void
		 * @throws  Exception for WP HTTP API error, no response, HTTP status code is not 201 or if HTTP status code not set
		 */
		public function send( $to_phone, $message, $country_code ) {
			
			$to_phone = str_replace( '+', '', $to_phone );
			
			//added in version 1.2.2
			$to_phone = ( '91' !== substr( $to_phone, 0, 2 ) ? '+' . $to_phone : substr( $to_phone, 2 ) );

			if ( '' != $this->_from_asid ) {

				$from = $this->_from_asid;

			} else {

				$from = $this->_from_number;

			}

			$args = array(
				'apikey'		=> $this->_smsalert_api_key,
				'sender'		=> $from,
				'mobileno'		=> $to_phone,
				'text'			=> $message,		
			);
			//print_r($args);exit;

			$wp_remote_http_args = array(
				'method' => 'POST',
				'body'   => http_build_query($args),
			);

			$endpoint = 'https://www.smsalert.co.in/api/push.json';

			// perform HTTP request with endpoint / args
			$response = wp_safe_remote_request( esc_url_raw( $endpoint ), $wp_remote_http_args );
			//print_r($response);

			// WP HTTP API error like network timeout, etc
			if ( is_wp_error( $response ) ) {

				throw new Exception( $response->get_error_message() );

			}

			$this->_log[] = $response;

			// Check for proper response / body
			if ( ! isset( $response['response'] ) || ! isset( $response['body'] ) ) {

				throw new Exception( __( 'No answer', 'smswoo' ) );

			}

			$result = json_decode( $response['body'], true );
			//print_r($result);
			
			if ( 'success' != $result['status'] ) {
				
				$description = isset( $result['description']['desc'] ) ? $result['description']['desc'] : $result['description'];
				/* translators: %s: search for a tag */
				throw new Exception( sprintf( __( 'An error has occurred: %s', 'smswoo' ), $description ) );

			}

			return;

		}
		
		/**
		 * Send SMS
		 *
		 * @since   1.0
		 *
		 * @param   $to_phone     string
		 *
		 * @return  void
		 * @throws  Exception for WP HTTP API error, no response, HTTP status code is not 201 or if HTTP status code not set
		 */
		public function validate_number( $to_phone ) {
			
			throw new Exception( sprintf( __( 'An error has occurred: SMS Alert is not supported for phone number validation on checkout, Please contact support', 'smswoo' ) ) );

		}

	}
}
