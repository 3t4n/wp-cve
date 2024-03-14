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

if ( ! class_exists( 'SMSWOO_Nexmo' ) ) {

	class SMSWOO_Nexmo extends SMSWOO_Sms_Gateway {

		private $_nexmo_api_key;

		private $_nexmo_api_secret;
		
		public $new_status;

		/**
		 * Constructor
		 *
		 * @since   1.0
		 * @return  void
		 */
		public function __construct() {

			$this->_nexmo_api_key    = get_option( 'smswoo_nexmo_key' );
			$this->_nexmo_api_secret = get_option( 'smswoo_nexmo_secret' );

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

			if ( '' != $this->_from_asid ) {

				$from = $this->_from_asid;

			} else {

				$from = $this->_from_number;

			}

			$type = empty( apply_filters( 'smswoo_additional_charsets', get_option( 'smswoo_active_charsets', array() ) ) ) ? 'text' : 'unicode';

			$args = http_build_query(
				array(
					'from'		=> $from,
					'to'		=> $to_phone,
					'type'		=> $type,
					'text'		=> $message,
					'api_key'	=> $this->_nexmo_api_key,
					'api_secret'=> $this->_nexmo_api_secret,
				)
			);

			$wp_remote_http_args = array(
				'method' => 'POST',
				'body'   => $args,
				'header' => 'Content-type: application/x-www-form-urlencoded\r\n' .
							'Content-Length: ' . strlen( $args ) . '\r\n'
			);

			$endpoint = 'https://rest.nexmo.com/sms/json';

			// perform HTTP request with endpoint / args
			$response = wp_safe_remote_request( esc_url_raw( $endpoint ), $wp_remote_http_args );

			// WP HTTP API error like network timeout, etc
			if ( is_wp_error( $response ) ) {

				throw new Exception( $response->get_error_message() );

			}

			$this->_log[] = $response;

			// Check for proper response / body
			if ( ! isset( $response['response'] ) || ! isset( $response['body'] ) ) {

				throw new Exception( __( 'No answer', 'trackship-for-woocommerce' ) );

			}

			$result = json_decode( $response['body'], true );

			if ( 0 != $result['messages'][0]['status'] ) {
				/* translators: %s: search for a tag */
				throw new Exception( sprintf( __( 'An error has occurred: %s', 'trackship-for-woocommerce' ), $result['messages'][0]['error-text'] ) );

			}

			return;

		}
		
		/**
		 * Send SMS
		 *
		 * @since 1.0
		 *
		 * @param $to_phone string
		 *
		 * @return  void
		 * @throws  Exception for WP HTTP API error, no response, HTTP status code is not 201 or if HTTP status code not set
		 */
		public function validate_number( $to_phone ) {

			$args = http_build_query(
				array(
					'api_key'	=> $this->_nexmo_api_key,
					'api_secret'=> $this->_nexmo_api_secret,
					'number'	=> $to_phone,
				)
			);

			$wp_remote_http_args = array(
				'method' => 'POST',
				'body'   => $args,
				'header' => 'Content-type: application/x-www-form-urlencoded\r\n' .
							'Content-Length: ' . strlen( $args ) . '\r\n'
			);

			$endpoint = 'https://api.nexmo.com/ni/basic/json';

			// perform HTTP request with endpoint / args
			$response = wp_safe_remote_request( esc_url_raw( $endpoint ), $wp_remote_http_args );

			// WP HTTP API error like network timeout, etc
			if ( is_wp_error( $response ) ) {

				throw new Exception( $response->get_error_message() );

			}

			$this->_log[] = $response;

			// Check for proper response / body
			if ( ! isset( $response['response'] ) || ! isset( $response['body'] ) ) {

				throw new Exception( __( 'No answer', 'trackship-for-woocommerce' ) );

			}

			$result = json_decode( $response['body'], true );

			if ( 0 != $result['status'] ) {
				/* translators: %s: search for a tag */
				throw new Exception( sprintf( __( 'An error has occurred: %s', 'trackship-for-woocommerce' ), $result['status_message'] ) );

			}

			return;

		}

	}

}
