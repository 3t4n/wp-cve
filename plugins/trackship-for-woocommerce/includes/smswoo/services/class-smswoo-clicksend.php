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

if ( ! class_exists( 'SMSWOO_Clicksend' ) ) {

	class SMSWOO_Clicksend extends SMSWOO_Sms_Gateway {
		
		private $_clicksend_username;
		private $_clicksend_key;
		public $new_status;
		
		/**
		* Constructor
		*
		* @since   1.0
		* @return  void
		*/
		public function __construct() {

			$this->_clicksend_username    = get_option( 'smswoo_clicksend_username' );
			$this->_clicksend_key    = get_option( 'smswoo_clicksend_key' );

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
			
			$type = empty( apply_filters( 'smswoo_additional_charsets', get_option( 'smswoo_active_charsets', array() ) ) ) ? 'english' : 'unicode';	
			
			$body = array(
				'messages' => array(
					array(
						'to'	=> $to_phone,
						'from'	=> $from,
						'source'=> 'smswoo',
						'body'	=> $message,
					),
				),
			);
			
			$args = array(
				'body' => wp_json_encode( $body ),
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $this->_clicksend_username . ':' . $this->_clicksend_key ),
					'Content-Type' => 'application/json',
				),
			);
			
			$url = 'https://rest.clicksend.com/v3/sms/send';
			
			$response = wp_safe_remote_post( $url, $args);
			
			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message() );
			}

			$this->_log[] = $response;

			// Check for proper response / body
			if ( ! isset( $response['response'] ) || ! isset( $response['body'] ) ) {
				throw new Exception( __( 'No answer', 'trackship-for-woocommerce' ) );
			}
			
			$result = json_decode( $response['body'], true );
			if ( 'SUCCESS' != $result['response_code'] ) {
				/* translators: %s: search for a tag */
				throw new Exception( sprintf( __( 'An error has occurred: %s', 'trackship-for-woocommerce' ), $result['response_msg'] ) );

			}

			if ( 'SUCCESS' != $result[ 'data' ][ 'messages' ][0]['status'] ) {
				/* translators: %s: search for a tag */
				throw new Exception( sprintf( __( 'An error has occurred: %s', 'trackship-for-woocommerce' ), $result[ 'data' ][ 'messages' ][0]['status'] ) );
				
			}
			
			return;
		}
		
		/**
		 * Phone number validation
		 *
		 * @since   1.0
		 *
		 * @throws  Exception for WP HTTP API error, no response, HTTP status code is not 201 or if HTTP status code not set
		 */
		public function validate_number( $to_phone ) {
			
			throw new Exception( sprintf( __( 'An error has occurred: Clicksend is not supported for phone number validation on checkout, Please contact support', 'trackship-for-woocommerce' ) ) );

		}
	}
}
