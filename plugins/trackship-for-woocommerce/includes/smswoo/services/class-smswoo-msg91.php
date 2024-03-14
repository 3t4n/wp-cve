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

if ( ! class_exists( 'SMSWOO_Msg91' ) ) {

	/**
	 * Class SMSWOO_Msg91
	 * 
	 * @since 1.0
	 *
	 */
	class SMSWOO_Msg91 extends SMSWOO_Sms_Gateway {

		public $new_status;
		public $_msg91_authkey;

		/**
		 * Constructor
		 *
		 * @since 1.0
		 * @return void
		 */
		public function __construct() {

			$this->_msg91_authkey = get_option( 'smswoo_msg91_authkey' );

			parent::__construct();

		}

		/**
		 * Send SMS
		 *
		 * @since 1.0
		 *
		 * @param $to_phone string
		 * @param $message string
		 * @param $country_code string
		 *
		 * @return void
		 * @throws Exception for WP HTTP API error, no response, HTTP status code is not 201 or if HTTP status code not set
		 */
		public function send( $to_phone, $message, $country_code ) {

			$to_phone = str_replace( '+', '', $to_phone );

			if ( '' != $this->_from_asid ) {

				$from = $this->_from_asid;

			} else {

				$from = $this->_from_number;

			}

			if ( 'IN' == $country_code ) {
				$country_dial_code = '91';
			} else if ( 'US' == $country_code ) {
				$country_dial_code = '1';
			} else {
				$country_dial_code = '0';
			}
			
			if ( get_option('smswoo_msg91_dlt') ) {
				$template_id = get_option('smswoo_trackship_status_' . $this->new_status . '_sms_template_templete_id');
				$template_var = get_option('smswoo_trackship_status_' . $this->new_status . '_sms_template_template_var' );
				$sms_notification = TSWC_SMSWoo_SMS_Notification::get_instance();
				$template_var = $sms_notification->replace_message_variables($template_var);
				$template_var = $template_var ? explode( ',', $template_var) : [];

				$var = [];

				$replace_var = $sms_notification->replace_message_variables( $template_var, '' );
				// $replace_var = $replace_var ? explode( ',', $replace_var ) : [];
				// $template_var = $template_var ? explode( ',', $template_var ) : [];
				$var = array();
				$i = 1;
				foreach ($replace_var as $key => $val) {
					$var[ 'var' . $i] = $val;
					$i++;
				}
				
				// Assuming $to_phone is the recipient's phone number
				$recipient = array(
					'mobiles' => $to_phone,
				);

				// Combine $body, $var, and $recipient to form the final structure
				$body = array(
					'template_id' => $template_id,
					'short_url' => '1 (On) or 0 (Off)', // Set to '1' for On, '0' for Off
					'recipients' => array($recipient + $var),
				);

				$args = array(
					'body' => wp_json_encode( $body ),
					'headers' => array(
						'authkey' => $this->_msg91_authkey,
						'Content-Type' => 'application/json',
					),
				);
				$url = 'https://control.msg91.com/api/v5/flow/';
			} else {
				$body = array(
					'sender'	=> $from,
					'route'		=> '4',
					'country'	=> $country_dial_code,
					'sms' => array(
						array (
							'message' => $message,
							'to' => array (
								$to_phone,
							),
						),
					),
					'unicode'	=> 1
				);
				
				$args = array(
					'body' => wp_json_encode( $body ),
					'headers' => array(
						'authkey' => $this->_msg91_authkey,
						'Content-Type' => 'application/json',
					),
				);
				$url = 'https://api.msg91.com/api/v2/sendsms';
			}

			$response = wp_safe_remote_post( $url, $args);
			//echo '<pre>';print_r($response);echo '</pre>';exit;
			
			// WP HTTP API error like network timeout, etc
			if ( is_wp_error( $response ) ) {

				throw new Exception( $response->get_error_message() );

			}

			if ( isset( $response['response']['code'] ) ) {

				if ( 201 != $response['response']['code'] && 200 != $response['response']['code'] ) {

					$response = json_decode( $response['body'], true );

					throw new Exception( ( isset( $response['message'] ) ) ? $response['message'] : __( 'An error has occurred while sending the sms', 'trackship-for-woocommerce' ) );
				}

				$response = json_decode( $response['body'], true );

			} else {

				throw new Exception( __( 'No answer code', 'trackship-for-woocommerce' ) );

			}

			$this->_log[] = $response;

			return;

		}
		
		/**
		 * Send SMS
		 *
		 * @since 1.0
		 *
		 * @param $to_phone string
		 *
		 * @return void
		 * @throws Exception for WP HTTP API error, no response, HTTP status code is not 201 or if HTTP status code not set
		 */
		public function validate_number( $to_phone ) {

			throw new Exception( sprintf( __( 'An error has occurred: MSG91 is not supported for phone number validation on checkout, Please contact support', 'smswoo' ) ) );

		}

	}
}
