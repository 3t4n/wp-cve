<?php
/** This file contains functions to make curl call to miniOrange cloud service to authenticate customers.
 *
 * @package miniorange-login-security/helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Momls_Curl' ) ) {
	/**
	 * This library is miniOrange Authentication Service.
	 * Contains Request Calls to Customer service.
	 **/
	class Momls_Curl {

		/**
		 * This function is invoke to create the customer after registration
		 *
		 * @param string $email .
		 * @param string $company .
		 * @param string $password .
		 * @param string $phone .
		 * @param string $first_name .
		 * @param string $last_name .
		 * @return string
		 */
		public static function momls_create_customer( $email, $company, $password, $phone = '', $first_name = '', $last_name = '' ) {
			$url      = Momls_Wpns_Constants::HOST_NAME . '/moas/rest/customer/add';
			$fields   = array(
				'companyName'    => $company,
				'areaOfInterest' => 'WordPress 2 Factor Authentication Plugin',
				'firstname'      => $first_name,
				'lastname'       => $last_name,
				'email'          => $email,
				'phone'          => $phone,
				'password'       => $password,
			);
			$json     = wp_json_encode( $fields );
			$response = self::momls_call_api( $url, $json );
			return $response;
		}
		/**
		 * It will help to get customer key
		 *
		 * @param string $email It will get the customer key.
		 * @param string $password It will get the password.
		 * @return string
		 */
		public static function momls_get_customer_key( $email, $password ) {
			$url      = Momls_Wpns_Constants::HOST_NAME . '/moas/rest/customer/key';
			$fields   = array(
				'email'    => $email,
				'password' => $password,
			);
			$json     = wp_json_encode( $fields );
			$response = self::momls_call_api( $url, $json );
			return $response;
		}
		/**
		 * It will help to submit the contact form .
		 *
		 * @param  string $q_email It is carrying the email address .
		 * @param  string $q_phone .
		 * @param  string $query .
		 * @return string
		 */
		public function momls_submit_contact_us( $q_email, $q_phone, $query ) {
			$current_user = wp_get_current_user();
			$url          = Momls_Wpns_Constants::HOST_NAME . '/moas/rest/customer/contact-us';

			$is_nc_with_1_user = get_site_option( 'mo2f_is_NC' ) && get_site_option( 'mo2f_is_NNC' );
			$is_ec_with_1_user = ! get_site_option( 'mo2f_is_NC' );

			$customer_feature = '';

			if ( $is_ec_with_1_user ) {
				$customer_feature = 'V1';
			} elseif ( $is_nc_with_1_user ) {
				$customer_feature = 'V3';
			}
			global $momls_wpns_utility;
			$query        = '[WordPress Multi Factor Authentication Plugin: - V ' . MO2F_VERSION . '-]: ' . $query;
			$fields       = array(
				'firstName' => $current_user->user_firstname,
				'lastName'  => $current_user->user_lastname,
				'company'   => isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '',
				'email'     => $q_email,
				'ccEmail'   => '2fasupport@xecurify.com',
				'phone'     => $q_phone,
				'query'     => $query,
			);
			$field_string = wp_json_encode( $fields );
			$response     = self::momls_call_api( $url, $field_string );
			return true;
		}
		/**
		 * It will check the customer.
		 *
		 * @param string $email .
		 * @return string
		 */
		public function momls_check_customer( $email ) {
			$url      = Momls_Wpns_Constants::HOST_NAME . '/moas/rest/customer/check-if-exists';
			$fields   = array( 'email' => $email );
			$json     = wp_json_encode( $fields );
			$response = self::momls_call_api( $url, $json );
			return $response;
		}
		/**
		 * Call in forgot password
		 *
		 * @return string
		 */
		public function momls_wpns_forgot_password() {
			$url          = Momls_Wpns_Constants::HOST_NAME . '/moas/rest/customer/password-reset';
			$email        = get_site_option( 'mo2f_email' );
			$customer_key = get_site_option( 'mo2f_customerKey' );
			$api_key      = get_site_option( 'Momls_Api_key' );
			$fields       = array( 'email' => $email );
			$json         = wp_json_encode( $fields );
			$auth_header  = $this->momls_create_auth_header( $customer_key, $api_key );
			$response     = self::momls_call_api( $url, $json, $auth_header );
			return $response;
		}
		// added for feedback.
		/**
		 * Send the email alert
		 *
		 * @param string $email .
		 * @param string $phone .
		 * @param string $message .
		 * @param string $feedback_option .
		 * @return string
		 */
		public function momls_send_email_alert( $email, $phone, $message, $feedback_option ) {

			global $momls_wpns_utility;
			global $user;
			$url          = Momls_Wpns_Constants::HOST_NAME . '/moas/api/notify/send';
			$customer_key = Momls_Wpns_Constants::DEFAULT_CUSTOMER_KEY;
			$api_key      = Momls_Wpns_Constants::DEFAULT_API_KEY;
			$from_email   = 'no-reply@xecurify.com';
			if ( 'momls_wpns_skip_feedback' === $feedback_option ) {
				$subject = 'Deactivate [Feedback Skipped]:WordPress Multi Factor Authentication Plugin -' . sanitize_text_field( $email );
			} elseif ( 'momls_wpns_feedback' === $feedback_option ) {
				$subject = 'Feedback: WordPress Multi Factor Authentication Plugin - ' . sanitize_text_field( $email );
			}
				$user          = wp_get_current_user();
			$is_nc_with_1_user = get_site_option( 'mo2f_is_NC' ) && get_site_option( 'mo2f_is_NNC' );
			$is_ec_with_1_user = ! get_site_option( 'mo2f_is_NC' );
			$customer_feature  = '';
			if ( $is_ec_with_1_user ) {
				$customer_feature = 'V1';
			} elseif ( $is_nc_with_1_user ) {
				$customer_feature = 'V3';
			}

			$query        = '[WordPress Multi Factor Authentication Plugin: ' . sanitize_text_field( $customer_feature ) . ' - V ' . MO2F_VERSION . ']: ' . wp_kses_post( $message );
			$company      = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
			$content      = '<div >Hello, <br><br>First Name :' . $user->user_firstname . '<br><br>Last  Name :' . $user->user_lastname . '   <br><br>Company :<a href="' . $company . '" target="_blank" >' . sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) . '</a><br><br>Phone Number :' . $phone . '<br><br>Email :<a href="mailto:' . esc_html( $email ) . '" target="_blank">' . esc_html( $email ) . '</a><br><br>Query :' . wp_kses_post( $query ) . '</div>';
			$fields       = array(
				'customerKey' => $customer_key,
				'sendEmail'   => true,
				'email'       => array(
					'customerKey' => $customer_key,
					'fromEmail'   => $from_email,
					'fromName'    => 'Xecurify',
					'toEmail'     => '2fasupport@xecurify.com',
					'toName'      => '2fasupport@xecurify.com',
					'subject'     => $subject,
					'content'     => $content,
				),
			);
			$field_string = wp_json_encode( $fields );
			$auth_header  = $this->momls_create_auth_header( $customer_key, $api_key );
			$response     = self::momls_call_api( $url, $field_string, $auth_header );
			return $response;

		}
		/**
		 * It will help to creating header
		 *
		 * @param string $customer_key .
		 * @param string $api_key .
		 * @return string .
		 */
		private static function momls_create_auth_header( $customer_key, $api_key ) {
			$current_timestamp_in_millis = round( microtime( true ) * 1000 );
			$current_timestamp_in_millis = number_format( $current_timestamp_in_millis, 0, '', '' );

			$string_to_hash = $customer_key . $current_timestamp_in_millis . $api_key;
			$auth_header    = hash( 'sha512', $string_to_hash );

			$header = array(
				'Content-Type'  => 'application/json',
				'Customer-Key'  => $customer_key,
				'Timestamp'     => $current_timestamp_in_millis,
				'Authorization' => $auth_header,
			);
			return $header;
		}
		/**
		 * The api function will be called for curl
		 *
		 * @param string $url .
		 * @param string $json_string .
		 * @param array  $http_header_array .
		 * @return string
		 */
		private static function momls_call_api( $url, $json_string, $http_header_array = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF-8',
			'Authorization' => 'Basic',
		) ) {

			$args = array(
				'method'      => 'POST',
				'body'        => $json_string,
				'timeout'     => '5',
				'redirection' => '5',
				'sslverify'   => true,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $http_header_array,
			);

			$momls_api = new Momls_Api();
			$response  = $momls_api->momls_wp_remote_post( $url, $args );
			return $response;
		}
	}
}
