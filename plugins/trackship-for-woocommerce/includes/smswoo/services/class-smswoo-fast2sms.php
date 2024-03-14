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

if ( ! class_exists( 'SMSWOO_Fast2sms' ) ) {

	/**
	 * Fats2SMS
	 *
	 * @class   SMSWOO_Fast2sms
	 * @since   1.0
	 *
	 */
	class SMSWOO_Fast2sms extends SMSWOO_Sms_Gateway {

		private $_fast2sms_api_key;

		/**
		 * Constructor
		 *
		 * @since   1.0
		 * @return  void
		 */
		public function __construct() {

			$this->_fast2sms_api_key    = get_option( 'smswoo_fast2sms_key' );

			parent::__construct();

		}

		/**
		 * Send SMS
		 *
		 * @since   1.0
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

			$type = empty( apply_filters( 'smswoo_additional_charsets', get_option( 'smswoo_active_charsets', array() ) ) ) ? 'english' : 'unicode';

			$args = array(
				'authorization'	=> $this->_fast2sms_api_key,
				'sender_id'		=> $from,
				'message'		=> $message,
				'language'		=> $type,
				'route'			=> 'v3',
				'numbers'		=> $to_phone,
			);
			//print_r($args);

			$wp_remote_http_args = array(
				'method' => 'GET',
				'body'   => $args,
			);

			$endpoint = 'https://www.fast2sms.com/dev/bulkV2';

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

			if ( 1 != $result['return'] ) {
				
				/* translators: %s: search for a tag */
				throw new Exception( sprintf( __( 'An error has occurred: %s', 'smswoo' ), $result['message'] ) );

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
			
			throw new Exception( sprintf( __( 'An error has occurred: Fast2SMS is not supported for phone number validation on checkout, Please contact support', 'smswoo' ) ) );

		}

	}

}
