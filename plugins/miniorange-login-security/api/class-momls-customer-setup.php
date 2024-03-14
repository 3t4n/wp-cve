<?php
/** This file contains functions to setup customer with miniOrange.
 *
 * @package miniorange-login-security/api
 */

/**
 * This library is miniOrange Authentication Service.
 * Contains Request Calls to Customer service.
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once dirname( __FILE__ ) . '/class-momls-api.php';

if ( ! class_exists( 'Momls_Customer_Setup' ) ) {
	/**
	 *  Class contains functions to setup customer with miniOrange.
	 */
	class Momls_Customer_Setup {

		/**
		 * Email id of user.
		 *
		 * @var $email string.
		 */
		public $email;
		/**
		 * Phone number of user.
		 *
		 * @var int.
		 */
		public $phone;
		/**
		 * Customer key of user.
		 *
		 * @var string
		 */
		public $customer_key;
		/**
		 * Transaction id of the customer to send OTP via SMS or Email.
		 *
		 * @var string
		 */
		public $transaction_id;
		/**
		 * Function to check if customer exists or not.
		 *
		 * @return string
		 */
		public function momls_check_customer() {
			$url          = MO_HOST_NAME . '/moas/rest/customer/check-if-exists';
			$email        = get_site_option( 'mo2f_email' );
			$momls_api    = new Momls_Api();
			$fields       = array(
				'email' => $email,
			);
			$field_string = wp_json_encode( $fields );

			$response = $momls_api->momls_http_request( $url, $field_string );
			return $response;

		}
		/**
		 * Function to add the customer on miniOrange idp.
		 *
		 * @return string
		 */
		public function momls_create_customer() {
			global $momlsdb_queries;
			$url       = MO_HOST_NAME . '/moas/rest/customer/add';
			$momls_api = new Momls_Api();
			global $user;
			$user         = wp_get_current_user();
			$this->email  = get_site_option( 'mo2f_email' );
			$this->phone  = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_phone', $user->ID );
			$password     = get_site_option( 'mo2f_password' );
			$company      = get_option( 'mo2f_admin_company' ) !== '' ? get_option( 'mo2f_admin_company' ) : ( isset( $_SERVER['SERVER_NAME'] ) ? esc_url_raw( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : null );
			$fields       = array(
				'companyName'     => $company,
				'areaOfInterest'  => 'WordPress 2 Factor Authentication Plugin',
				'productInterest' => 'API_2FA',
				'email'           => $this->email,
				'phone'           => $this->phone,
				'password'        => $password,
			);
			$field_string = wp_json_encode( $fields );

			$content = $momls_api->momls_http_request( $url, $field_string );

			return $content;
		}
		/**
		 * Function to get customer key of user.
		 *
		 * @return string
		 */
		public function momls_get_customer_key() {
			$url = MO_HOST_NAME . '/moas/rest/customer/key';

			$email        = get_site_option( 'mo2f_email' );
			$password     = get_site_option( 'mo2f_password' );
			$momls_api    = new Momls_Api();
			$fields       = array(
				'email'    => $email,
				'password' => $password,
			);
			$field_string = wp_json_encode( $fields );

			$content = $momls_api->momls_http_request( $url, $field_string );

			return $content;
		}

		/**
		 * Function to validate the otp token.
		 *
		 * @param string $auth_type Authentication method of user.
		 * @param string $user_name Username of user.
		 * @param string $transaction_id Transaction id which is used to validate the sent otp token.
		 * @param string $otp_token OTP token received by user.
		 * @param string $c_key Customer key of user.
		 * @param string $customer_api_key Customer api key assigned by IDP to the user.
		 * @return string
		 */
		public function momls_validate_otp_token( $auth_type, $user_name, $transaction_id, $otp_token, $c_key, $customer_api_key ) {

			$url       = MO_HOST_NAME . '/moas/api/auth/validate';
			$momls_api = new Momls_Api();
			/* The customer Key provided to you */
			$customer_key = $c_key;

			/* The customer API Key provided to you */
			$api_key = $customer_api_key;

			/* Current time in milliseconds since midnight, January 1, 1970 UTC. */
			$current_time_in_millis = $momls_api->get_timestamp();

			/* Creating the Hash using SHA-512 algorithm */
			$string_to_hash = $customer_key . $current_time_in_millis . $api_key;
			$hash_value     = hash( 'sha512', $string_to_hash );

			$headers = $momls_api->get_http_header_array();
			$fields  = '';
			if ( 'SOFT TOKEN' === $auth_type || 'GOOGLE AUTHENTICATOR' === $auth_type ) {

				/*check for soft token*/
				$fields = array(
					'customerKey' => $customer_key,
					'username'    => $user_name,
					'token'       => $otp_token,
					'authType'    => $auth_type,
				);
			} elseif ( 'KBA' === $auth_type ) {
				if ( get_site_option( 'is_onprem' ) ) {

					$nonce = isset( $_POST['mo2f_validate_kba_details_nonce'] ) ? sanitize_key( wp_unslash( $_POST['mo2f_validate_kba_details_nonce'] ) ) : '';
					if ( wp_verify_nonce( $nonce, 'mo2f-validate-kba-details-nonce' ) ) {

						$session_id_encrypt = isset( $_POST['session_id'] ) ? ( isset( $_POST['session_id'] ) ? sanitize_text_field( wp_unslash( $_POST['session_id'] ) ) : null ) : null;
						if ( isset( $_POST['validate'] ) ) {
							$user_id = wp_get_current_user()->ID;
						} else {
							$user_id = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_current_user_id', $session_id_encrypt );
						}
						$redirect_to          = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : null;
						$kba_ans_1            = isset( $_POST['mo2f_answer_1'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_answer_1'] ) ) : null;
						$kba_ans_2            = isset( $_POST['mo2f_answer_2'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_answer_2'] ) ) : null;
						$questions_challenged = get_user_meta( $user_id, 'kba_questions_user' );
						$questions_challenged = $questions_challenged[0];
						$all_ques_ans         = ( get_user_meta( $user_id, 'mo2f_kba_challenge' ) );
						$all_ques_ans         = $all_ques_ans[0];
						$ans_1                = $all_ques_ans[ $questions_challenged[0] ];
						$ans_2                = $all_ques_ans[ $questions_challenged[1] ];

						$pass2fa        = new Momls_Miniorange_Password_2Factor_Login();
						$twofa_settings = new Momls_Miniorange_Authentication();
						$status         = '';
						if ( ! strcmp( md5( $kba_ans_1 ), $ans_1 ) && ! strcmp( md5( $kba_ans_2 ), $ans_2 ) ) {

							if ( isset( $_POST['validate'] ) ) {

								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'COMPLETED_TEST' ) );
								delete_user_meta( $user_id, 'mo2f_test_2FA' );
								$twofa_settings->momls_auth_show_success_message();
							} else {
								$pass2fa->momls_pass2login( $redirect_to, $session_id_encrypt );
							}
							$status = 'SUCCESS';
						} else {

							if ( isset( $_POST['validate'] ) ) {
								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_ANSWERS' ) );
								do_action( 'wpns_momls_show_message', get_site_option( 'mo2f_message' ), 'ERROR' );
							} else {
								$mo2fa_login_message = 'The answers you have provided are incorrect.';
								$mo2fa_login_status  = 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION';
								$question_answers    = get_user_meta( $user_id, 'mo2f_kba_challenge', true );
								$challenge_questions = array_keys( $question_answers );
								$random_keys         = array_rand( $challenge_questions, 2 );
								$challenge_ques1     = $challenge_questions[ $random_keys[0] ];
								$challenge_ques2     = $challenge_questions[ $random_keys[1] ];
								$questions           = array( $challenge_ques1, $challenge_ques2 );
								update_user_meta( $user_id, 'kba_questions_user', $questions );
								$mo2f_kbaquestions = $questions;
								$pass2fa->momls_pass2login_form_fields( $session_id_encrypt, $mo2fa_login_status, $mo2fa_login_message, $redirect_to, null );
							}
							$status = 'ERROR';
						}
						$field = array(
							'status' => $status,
						);
						return wp_json_encode( $field );
					}
				} else {
					$fields = array(
						'txId'    => $transaction_id,
						'answers' => array(
							array(
								'question' => $otp_token[0],
								'answer'   => $otp_token[1],
							),
							array(
								'question' => $otp_token[2],
								'answer'   => $otp_token[3],
							),
						),
					);
				}
			} else {
				// *check for otp over sms/email
				$fields = array(
					'txId'  => $transaction_id,
					'token' => $otp_token,
				);
			}
			$field_string = wp_json_encode( $fields );

			$content = $momls_api->momls_http_request( $url, $field_string, $headers );

			return $content;
		}

	}
}



