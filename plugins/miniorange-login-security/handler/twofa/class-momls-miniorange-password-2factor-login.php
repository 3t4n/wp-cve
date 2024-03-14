<?php
/** It enables user to log in through mobile authentication as an additional layer of security over password.
 *
 * @package        miniorange-login-security/handler/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * This library is miniOrange Authentication Service.
 * Contains Request Calls to Customer service.
 */
require 'class-momls-miniorange-mobile-login.php';
/**
 *  This file will include frontend of the 2FA method prompts.
 */
require dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'prompt-twofa-methods.php';

if ( ! class_exists( 'Momls_Miniorange_Password_2Factor_Login' ) ) {
	/**
	 * Class will help to set two factor on login
	 */
	class Momls_Miniorange_Password_2Factor_Login {
		/**
		 *  It will store the KBA Question
		 *
		 * @var string .
		 */
		private $mo2f_kbaquestions;
		/**
		 * For user id variable
		 *
		 * @var string
		 */
		private $mo2f_userid;
		/**
		 * It will store the transaction id
		 *
		 * @var string .
		 */

		private $mo2f_transaction_id;

		/**
		 * Pass2 login redirect function
		 *
		 * @return string
		 */
		public function momls_pass2login_redirect() {

			global $momlsdb_queries;

			if ( isset( $_POST['miniorange_alternate_login_kba_nonce'] ) ) { /*check kba validation*/
				$nonce = sanitize_key( wp_unslash( $_POST['miniorange_alternate_login_kba_nonce'] ) );
				if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-alternate-login-kba-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', esc_html_e( '<strong>ERROR</strong>: Invalid Request.', 'miniorange-login-security' ) );
					return $error;
				} else {
					$this->momls_pass2login_start_session();
					$session_id_encrypt = isset( $_POST['session_id'] ) ? sanitize_text_field( wp_unslash( $_POST['session_id'] ) ) : null;
					$user_id            = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_current_user_id', $session_id_encrypt );
					$redirect_to        = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : null;
					$this->momls_pass2login_kba_verification( $user_id, $redirect_to, $session_id_encrypt );
				}
			} elseif ( isset( $_POST['miniorange_kba_nonce'] ) ) { /*check kba validation*/
				$nonce = sanitize_key( wp_unslash( $_POST['miniorange_kba_nonce'] ) );
				if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-kba-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', esc_html_e( '<strong>ERROR</strong>: Invalid Request.', 'miniorange-login-security' ) );
					return $error;
				} else {

					$this->momls_pass2login_start_session();
					$session_id_encrypt = isset( $_POST['session_id'] ) ? sanitize_text_field( wp_unslash( $_POST['session_id'] ) ) : null;
					$user_id            = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_current_user_id', $session_id_encrypt );
					$redirect_to        = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : null;
					if ( isset( $user_id ) ) {
						if ( Momls_Utility::momls_check_empty_or_null( isset( $_POST['mo2f_answer_1'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_answer_1'] ) ) : '' ) || Momls_Utility::momls_check_empty_or_null( isset( $_POST['mo2f_answer_2'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_answer_2'] ) ) : '' ) ) {
							$mo2fa_login_message = 'Please provide both the answers.';
							$mo2fa_login_status  = 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION';
							$this->momls_pass2login_form_fields( $session_id_encrypt, $mo2fa_login_status, $mo2fa_login_message, $redirect_to, null );
						}
						$otp_token     = array();
						$kba_questions = Momls_Utility::momls_retrieve_user_temp_values( 'mo_2_factor_kba_questions', $session_id_encrypt );
						$otp_token[0]  = $kba_questions[0];
						$otp_token[1]  = isset( $_POST['mo2f_answer_1'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_answer_1'] ) ) : '';
						$otp_token[2]  = $kba_questions[1];
						$otp_token[3]  = isset( $_POST['mo2f_answer_2'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_answer_2'] ) ) : '';
						// if the php session folder has insufficient permissions, cookies to be used.
						$mo2f_login_transaction_id = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_transactionId', $session_id_encrypt );
						$kba_validate              = new Momls_Customer_Setup();
						$kba_validate_response     = json_decode( $kba_validate->momls_validate_otp_token( 'KBA', null, $mo2f_login_transaction_id, $otp_token, get_site_option( 'mo2f_customerKey' ), get_site_option( 'Momls_Api_key' ) ), true );
						$email                     = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user_id );
						if ( strcasecmp( $kba_validate_response['status'], 'SUCCESS' ) === 0 ) {
								$this->momls_pass2login( $redirect_to, $session_id_encrypt );

						} else {

							$mo2fa_login_message = 'The answers you have provided are incorrect.';
							$mo2fa_login_status  = 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION';
							$this->momls_pass2login_form_fields( $session_id_encrypt, $mo2fa_login_status, $mo2fa_login_message, $redirect_to, null );
						}
					} else {
						$this->momls_remove_current_activity( $session_id_encrypt );
						return new WP_Error( 'invalid_username', esc_html_e( '<strong>ERROR</strong>: Please try again..', 'miniorange-login-security' ) );
					}
				}
			} elseif ( isset( $_POST['miniorange_mobile_validation_failed_nonce'] ) ) { /*Back to miniOrange Login Page if mobile validation failed and from back button of mobile challenge, soft token and default login*/
				$nonce = sanitize_key( wp_unslash( $_POST['miniorange_mobile_validation_failed_nonce'] ) );
				if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-mobile-validation-failed-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );
					return $error;
				} else {
					$this->momls_pass2login_start_session();
					$session_id_encrypt = isset( $_POST['session_id'] ) ? sanitize_text_field( wp_unslash( $_POST['session_id'] ) ) : null;
					$this->momls_remove_current_activity( $session_id_encrypt );
					$current_method = isset( $_POST['currentMethod'] ) ? sanitize_text_field( wp_unslash( $_POST['currentMethod'] ) ) : null;

				}
			} elseif ( isset( $_POST['miniorange_soft_token_nonce'] ) ) { /*Validate Soft Token,OTP over SMS,OTP over EMAIL,Phone verification */
				$nonce = sanitize_key( wp_unslash( $_POST['miniorange_soft_token_nonce'] ) );
				if ( ! wp_verify_nonce( $nonce, 'miniorange-2-factor-soft-token-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', esc_html_e( '<strong>ERROR</strong>: Invalid Request.', 'miniorange-login-security' ) );
					return $error;
				} else {
					$this->momls_pass2login_start_session();
					$session_id_encrypt = isset( $_POST['session_id'] ) ? sanitize_text_field( wp_unslash( $_POST['session_id'] ) ) : null;
					$mo2fa_login_status = isset( $_POST['request_origin_method'] ) ? sanitize_text_field( wp_unslash( $_POST['request_origin_method'] ) ) : null;
					$redirect_to        = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : null;
					$softtoken          = '';
					$user_id            = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_current_user_id', $session_id_encrypt );

					$attempts = get_site_option( 'mo2f_attempts_before_redirect', 3 );
					if ( Momls_Utility::momls_check_empty_or_null( isset( $_POST['mo2fa_softtoken'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2fa_softtoken'] ) ) : '' ) ) {
						if ( $attempts > 1 || 'disabled' === $attempts ) {
							update_site_option( 'mo2f_attempts_before_redirect', $attempts - 1 );
							$mo2fa_login_message = 'Please enter OTP to proceed.';
							$this->momls_pass2login_form_fields( $session_id_encrypt, $mo2fa_login_status, $mo2fa_login_message, $redirect_to, null );
						} else {
							$session_id_encrypt = isset( $_POST['session_id'] ) ? sanitize_text_field( wp_unslash( $_POST['session_id'] ) ) : null;
							$this->momls_remove_current_activity( $session_id_encrypt );
							return new WP_Error( 'limit_exceeded', '<strong>ERROR</strong>: Number of attempts exceeded.' );
						}
					} else {

						$softtoken = sanitize_text_field( wp_unslash( $_POST['mo2fa_softtoken'] ) );
						if ( ! Momls_Utility::momls_check_number_length( $softtoken ) ) {
							if ( $attempts > 1 || 'disabled' === $attempts ) {
								update_site_option( 'mo2f_attempts_before_redirect', $attempts - 1 );
								$mo2fa_login_message = 'Invalid OTP. Only digits within range 4-8 are allowed. Please try again.';
								$this->momls_pass2login_form_fields( $session_id_encrypt, $mo2fa_login_status, $mo2fa_login_message, $redirect_to, null );

							} else {
								$session_id_encrypt = isset( $_POST['session_id'] ) ? sanitize_text_field( wp_unslash( $_POST['session_id'] ) ) : null;
								$this->momls_remove_current_activity( $session_id_encrypt );
								update_site_option( 'mo2f_attempts_before_redirect', 3 );
								return new WP_Error( 'limit_exceeded', '<strong>ERROR</strong>: Number of attempts exceeded.' );
							}
						}
					}
					$user_email = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user_id );

					if ( isset( $user_id ) ) {
						$customer = new Momls_Customer_Setup();
						$content  = '';
						// if the php session folder has insufficient permissions, cookies to be used.
						$mo2f_login_transaction_id = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_transactionId', $session_id_encrypt );
						if ( isset( $mo2fa_login_status ) && 'MO_2_FACTOR_CHALLENGE_GOOGLE_AUTHENTICATION' === $mo2fa_login_status ) {
							if ( get_site_option( 'is_onprem' ) ) {
								include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-momls-google-auth-onpremise.php';
								$gauth_obj = new Momls_Google_Auth_Onpremise();
								$secret    = $gauth_obj->momls_gauth_get_secret( $user_id );
								$content   = $gauth_obj->momls_verify_code( $secret, $softtoken );
							} else {
								$content = json_decode( $customer->momls_validate_otp_token( 'GOOGLE AUTHENTICATOR', $user_email, null, $softtoken, get_site_option( 'mo2f_customerKey' ), get_site_option( 'Momls_Api_key' ) ), true );

							}
						} else {
							$this->momls_remove_current_activity( $session_id_encrypt );
							return new WP_Error( 'invalid_username', esc_html_e( '<strong>ERROR</strong>: Invalid Request. Please try again.', 'miniorange-login-security' ) );
						}

						if ( strcasecmp( $content['status'], 'SUCCESS' ) === 0 ) {
							update_site_option( 'mo2f_attempts_before_redirect', 3 );
								$this->momls_pass2login( $redirect_to, $session_id_encrypt );

						} else {
							if ( $attempts > 1 || 'disabled' === $attempts ) {
								update_site_option( 'mo2f_attempts_before_redirect', $attempts - 1 );
								$message = 'MO_2_FACTOR_CHALLENGE_SOFT_TOKEN' === $mo2fa_login_status ? 'You have entered an invalid OTP.<br>Please click on <b>Sync Time</b> in the miniOrange Authenticator app to sync your phone time with the miniOrange servers and try again.' : 'Invalid OTP. Please try again.';
								$this->momls_pass2login_form_fields( $session_id_encrypt, $mo2fa_login_status, $message, $redirect_to, null );
							} else {
								$session_id_encrypt = isset( $_POST['session_id'] ) ? sanitize_text_field( wp_unslash( $_POST['session_id'] ) ) : null;
								$this->momls_remove_current_activity( $session_id_encrypt );
								update_site_option( 'mo2f_attempts_before_redirect', 3 );
								return new WP_Error( 'limit_exceeded', '<strong>ERROR</strong>: Number of attempts exceeded.' );
							}
						}
					} else {
						$this->momls_remove_current_activity( $session_id_encrypt );
						return new WP_Error( 'invalid_username', esc_html_e( '<strong>ERROR</strong>: Please try again..', 'miniorange-login-security' ) );
					}
				}
			}
		}
		/**
		 * Removing the current activity
		 *
		 * @param string $session_id It will carry the session id .
		 * @return void
		 */
		public function momls_remove_current_activity( $session_id ) {
			global $momlsdb_queries;
			$session_variables = array(
				'mo2f_current_user_id',
				'mo2f_1stfactor_status',
				'mo_2factor_login_status',
				'mo2f-login-qrCode',
				'mo2f_transactionId',
				'mo2f_login_message',
				'mo_2_factor_kba_questions',
				'mo2f_show_qr_code',
				'mo2f_google_auth',
				'mo2f_authy_keys',
			);

			$cookie_variables = array(
				'mo2f_current_user_id',
				'mo2f_1stfactor_status',
				'mo_2factor_login_status',
				'mo2f-login-qrCode',
				'mo2f_transactionId',
				'mo2f_login_message',
				'kba_question1',
				'kba_question2',
				'mo2f_show_qr_code',
				'mo2f_google_auth',
				'mo2f_authy_keys',
			);

			$temp_table_variables = array(
				'session_id',
				'mo2f_current_user_id',
				'mo2f_login_message',
				'mo2f_1stfactor_status',
				'mo2f_transactionId',
				'mo_2_factor_kba_questions',
				'ts_created',
			);

			Momls_Utility::momls_unset_session_variables( $session_variables );
			Momls_Utility::momls_unset_cookie_variables( $cookie_variables );
			$key        = get_site_option( 'mo2f_encryption_key' );
			$session_id = Momls_Utility::momls_decrypt_data( $session_id, $key );
			$momlsdb_queries->save_user_login_details(
				$session_id,
				array(

					'mo2f_current_user_id'      => '',
					'mo2f_login_message'        => '',
					'mo2f_1stfactor_status'     => '',
					'mo2f_transactionId'        => '',
					'mo_2_factor_kba_questions' => '',
					'ts_created'                => '',
				)
			);

		}
		/**
		 * It will use to start the session
		 *
		 * @return void
		 */
		public function momls_pass2login_start_session() {
			if ( ! session_id() || empty( session_id() ) || ! isset( $_SESSION ) ) {
				$session_path = ini_get( 'session.save_path' );
				if ( is_writable( $session_path ) && is_readable( $session_path ) ) {
					session_start();
				}
			}
		}
		/**
		 * It will handle kba validation
		 *
		 * @param string $user_id It will carry the user id .
		 * @param string $redirect_to It will carry the redirect url .
		 * @param string $session_id It will carry the session id .
		 * @return void
		 */
		private function momls_pass2login_kba_verification( $user_id, $redirect_to, $session_id ) {
			global $momlsdb_queries,$loginuserid;
			$loginuserid = $user_id;
			$user_email  = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user_id );
			if ( is_null( $session_id ) ) {
				$session_id = $this->momls_create_session();
			}
			if ( get_site_option( 'is_onprem' ) ) {
				$question_answers    = get_user_meta( $user_id, 'mo2f_kba_challenge', true );
				$challenge_questions = array_keys( $question_answers );
				$random_keys         = array_rand( $challenge_questions, 2 );
				$challenge_ques1     = $challenge_questions[ $random_keys[0] ];
				$challenge_ques2     = $challenge_questions[ $random_keys[1] ];
				$questions           = array( $challenge_ques1, $challenge_ques2 );
				update_user_meta( $user_id, 'kba_questions_user', $questions );
				$mo2fa_login_message = 'Please answer the following questions:';
				$mo2fa_login_status  = 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION';
				$mo2f_kbaquestions   = $questions;
				Momls_Utility::momls_set_user_values( $session_id, 'mo_2_factor_kba_questions', $questions );
				$this->momls_pass2login_form_fields( $session_id, $mo2fa_login_status, $mo2fa_login_message, $redirect_to = null, $this->mo2f_kbaquestions );
			}
		}
		/**
		 * It will pass 2fa on login flow
		 *
		 * @param string $session_id_encrypt It will carry the session id .
		 * @param string $mo2fa_login_status It will carry the login status message .
		 * @param string $mo2fa_login_message It will carry the login message .
		 * @param string $redirect_to It will carry the redirect url .
		 * @param string $qr_code It will carry the qr code .
		 * @return void
		 */
		public function momls_pass2login_form_fields( $session_id_encrypt, $mo2fa_login_status = null, $mo2fa_login_message = null, $redirect_to = null, $qr_code = null ) {

			$login_status  = $mo2fa_login_status;
			$login_message = $mo2fa_login_message;
			if ( $this->momls_pass2login_check_otp_status( $login_status ) ) { // for soft-token,otp over email,sms,phone verification,google auth.

				$user_id = $this->mo2f_userid ? $this->mo2f_userid : Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_current_user_id', $session_id_encrypt );
				momls_get_otp_authentication_prompt( $login_status, $login_message, $redirect_to, $session_id_encrypt, $user_id );
				exit;
			} elseif ( $this->momls_pass2login_check_kba_status( $login_status ) ) { // for Kba.
				$kbaquestions = $this->mo2f_kbaquestions ? $this->mo2f_kbaquestions : Momls_Utility::momls_retrieve_user_temp_values( 'mo_2_factor_kba_questions', $session_id_encrypt );
				if ( get_site_option( 'is_onprem' ) ) {
					$user_id = $this->mo2f_userid ? $this->mo2f_userid : Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_current_user_id', $session_id_encrypt );
					$ques    = get_user_meta( $user_id, 'kba_questions_user' );
					momls_get_kba_authentication_prompt( $login_message, $redirect_to, $session_id_encrypt, $ques[0] );
				} else {
					momls_get_kba_authentication_prompt( $login_message, $redirect_to, $session_id_encrypt, $kbaquestions );
				}
				exit;
			} else { // show login screen.
				$this->momls_pass2login_show_wp_login_form();
				if ( get_site_option( 'is_onprem' ) ) {
					$this->momls_pass2login_show_wp_login_form();
				}
			}
		}
		/**
		 * Pass2login otp check status
		 *
		 * @param string  $login_status It will store the login status message .
		 * @param boolean $sso It will store the softtoken message .
		 * @return boolean
		 */
		private function momls_pass2login_check_otp_status( $login_status, $sso = false ) {
			if ( 'MO_2_FACTOR_CHALLENGE_GOOGLE_AUTHENTICATION' === $login_status ) {
				return true;
			}

			return false;
		}
		/**
		 * It will Check kba status
		 *
		 * @param string $login_status It will store the login status message .
		 * @return boolean
		 */
		private function momls_pass2login_check_kba_status( $login_status ) {
			if ( 'MO_2_FACTOR_CHALLENGE_KBA_AUTHENTICATION' === $login_status ) {
				return true;
			}

			return false;
		}
		/**
		 * Pass2login for showing login form
		 *
		 * @return mixed
		 */
		public function momls_pass2login_show_wp_login_form() {

				$session_id_encrypt = $this->momls_create_session();
			?>
		<p><input type="hidden" name="miniorange_login_nonce"
			value="<?php echo esc_attr( wp_create_nonce( 'miniorange-2-factor-login-nonce' ) ); ?>"/>

			<input type="hidden" id="sessid" name="miniorange_user_session"
			value="<?php echo esc_attr( $session_id_encrypt ); ?>"/>

		</p>

			<?php

		}

		/**
		 * Otp verification
		 *
		 * @param object $user It will carry the current user .
		 * @param string $mo2f_second_factor It will store the second factor method .
		 * @param string $redirect_to It will store the redirect url .
		 * @param string $session_id It will carry the session id .
		 * @return void
		 */
		private function momls_pass2login_otp_verification( $user, $mo2f_second_factor, $redirect_to, $session_id = null ) {
			global $momlsdb_queries;
			if ( is_null( $session_id ) ) {
				$session_id = $this->momls_create_session();
			}
			$mo2f_external_app_type = get_user_meta( $user->ID, 'mo2f_external_app_type', true );
			$mo2f_user_phone        = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_phone', $user->ID );
			if ( 'GOOGLE AUTHENTICATOR' === $mo2f_second_factor ) {
				$mo2fa_login_message = 'Please enter the one time passcode shown in the <b> Authenticator</b> app.';
				$mo2fa_login_status  = 'MO_2_FACTOR_CHALLENGE_GOOGLE_AUTHENTICATION';
				$this->momls_pass2login_form_fields( $session_id, $mo2fa_login_status, $mo2fa_login_message, $redirect_to, null );
			}
		}
		/**
		 * Pass2 login method
		 *
		 * @param string $redirect_to It will carry the redirect url.
		 * @param string $session_id_encrypted It will carry the session id.
		 * @return void
		 */
		public function momls_pass2login( $redirect_to = null, $session_id_encrypted = null ) {

			if ( empty( $this->mo2f_userid ) ) {
				$user_id               = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_current_user_id', $session_id_encrypted );
				$mo2f_1stfactor_status = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_1stfactor_status', $session_id_encrypted );
			} else {
				$user_id               = $this->mo2f_userid;
				$mo2f_1stfactor_status = 'VALIDATE_SUCCESS';
			}
			if ( $user_id && $mo2f_1stfactor_status && ( 'VALIDATE_SUCCESS' === $mo2f_1stfactor_status ) ) {
				$currentuser = get_user_by( 'id', $user_id );
				wp_set_current_user( $user_id, $currentuser->user_login );
				$mobile_login = new Momls_Miniorange_Mobile_Login();
				$mobile_login->momls_remove_current_activity( $session_id_encrypted );
				wp_set_auth_cookie( $user_id, true );
				do_action( 'wp_login', $currentuser->user_login, $currentuser );
				momls_redirect_user_to( $currentuser, $redirect_to );
				exit;
			} else {
				$this->momls_remove_current_activity( $session_id_encrypted );
			}
		}
		/**
		 * This function will invoke to create session for user
		 *
		 * @return string
		 */
		public function momls_create_session() {
			global $momlsdb_queries;
			$session_id = Momls_Utility::momls_random_str( 20 );
			$momlsdb_queries->momls_insert_user_login_session( $session_id );
			$key                = get_site_option( 'mo2f_encryption_key' );
			$session_id_encrypt = Momls_Utility::momls_encrypt_data( $session_id, $key );
			return $session_id_encrypt;
		}
		/**
		 * It will initiate 2nd factor
		 *
		 * @param object $currentuser It will carry the current user detail .
		 * @param string $redirect_to It will carry the redirect url .
		 * @param string $session_id_encrypt It will carry the session id .
		 * @return string
		 */
		public function momls_initiate_2nd_factor( $currentuser, $redirect_to = null, $session_id_encrypt = null ) {
			global $momlsdb_queries;

			$this->momls_pass2login_start_session();
			if ( is_null( $session_id_encrypt ) ) {
				$session_id_encrypt = $this->momls_create_session();
			}

			Momls_Utility::momls_set_user_values( $session_id_encrypt, 'mo2f_current_user_id', $currentuser->ID );
			Momls_Utility::momls_set_user_values( $session_id_encrypt, 'mo2f_1stfactor_status', 'VALIDATE_SUCCESS' );

			$this->mo2f_userid = $currentuser->ID;
			$is_customer_admin = get_site_option( 'mo2f_miniorange_admin' ) === $currentuser->ID ? true : false;

			if ( get_site_option( 'is_onprem' ) ) {
				$is_customer_admin = true;
			}

			if ( $is_customer_admin ) {
				$email                               = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $currentuser->ID );
				$mo_2factor_user_registration_status = $momlsdb_queries->momls_get_user_detail( 'mo_2factor_user_registration_status', $currentuser->ID );
				$kba_configuration_status            = $momlsdb_queries->momls_get_user_detail( 'mo2f_SecurityQuestions_config_status', $currentuser->ID );
				if ( get_site_option( 'mo2f_enable_brute_force' ) ) {
					$mo2f_allwed_login_attempts = get_site_option( 'mo2f_allwed_login_attempts' );
				} else {
					$mo2f_allwed_login_attempts = 'disabled';
				}
				update_user_meta( $currentuser->ID, 'mo2f_user_login_attempts', $mo2f_allwed_login_attempts );

				if ( get_site_option( 'is_onprem' ) ) {
					$mo_2factor_user_registration_status = 'MO_2_FACTOR_PLUGIN_SETTINGS';
					$email                               = get_user_meta( $currentuser->ID, 'email', true );
				}
				if ( ( $email && 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status ) || ( get_site_option( 'is_onprem' ) && 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status ) ) { // checking if user has configured any 2nd factor method.

						$mo2f_second_factor = '';
						$mo2f_second_factor = momls_get_user_2ndfactor( $currentuser );
					if ( get_site_option( 'is_onprem' ) ) {
						$user  = $currentuser;
						$roles = (array) $user->roles;
						$flag  = 0;
						foreach ( $roles as $role ) {
							if ( get_site_option( 'mo2fa_' . $role ) === '1' ) {
								$flag = 1;
							}
						}
						$mo2f_second_factor = get_user_meta( $currentuser->ID, 'currentMethod', true );

						if ( 'Security Questions' === $mo2f_second_factor ) {
							$mo2f_second_factor = 'KBA';
						} elseif ( 'Google Authenticator' === $mo2f_second_factor ) {
							$mo2f_second_factor = 'GOOGLE AUTHENTICATOR';
						} else {
							$mo2f_second_factor = 'NONE';
						}
						if ( 0 === $flag ) {
							$mo2f_second_factor = 'NONE';
						}
					}

					if ( Momls_Utility::momls_check_if_request_is_from_mobile_device( isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' ) && $kba_configuration_status ) {
						$this->momls_pass2login_kba_verification( $currentuser->ID, $redirect_to, $session_id_encrypt );
					} else {
						if ( 'GOOGLE AUTHENTICATOR' === $mo2f_second_factor ) {
							$this->momls_pass2login_otp_verification( $currentuser, $mo2f_second_factor, $redirect_to, $session_id_encrypt );
						} elseif ( 'KBA' === $mo2f_second_factor ) {
							$this->momls_pass2login_kba_verification( $currentuser->ID, $redirect_to, $session_id_encrypt );
						} elseif ( 'NONE' === $mo2f_second_factor ) {
							$this->momls_pass2login( $redirect_to, $session_id_encrypt );
						} else {
							$this->momls_remove_current_activity( $session_id_encrypt );
							$error = new WP_Error();
							$error->add( 'empty_username', esc_html_e( '<strong>ERROR</strong>: Two Factor method has not been configured.', 'miniorange-login-security' ) );
							return $error;
						}
					}
				} else {

					return $currentuser;
				}
			} else { // plugin is not activated for current role then logged him in without asking 2 factor.

				return $currentuser;
			}

		}
		/**
		 * It will call at the time of authentication .
		 *
		 * @param object $user It will carry the user detail.
		 * @param string $username It will carry the username .
		 * @param string $password It will carry the password .
		 * @param string $redirect_to It will carry the redirect url .
		 * @return string
		 */
		public function momls_check_username_password( $user, $username, $password, $redirect_to = null ) {

			if ( get_site_option( 'mo2f_login_policy' ) ) {
				if ( is_a( $user, 'WP_Error' ) && ! empty( $user ) ) {
					return $user;
				}
			}
			$currentuser = '';

			// if an app password is enabled, this is an XMLRPC / APP login ?
			if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {

				$currentuser = wp_authenticate_username_password( $user, $username, $password );
				if ( is_wp_error( $currentuser ) ) {
					return false;
				} else {
					return $currentuser;
				}
			} else {

					$currentuser = wp_authenticate_username_password( $user, $username, $password );
				if ( is_wp_error( $currentuser ) ) {
					$currentuser->add( 'invalid_username_password', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Username or password.', 'miniorange-login-security' ) );
					return $currentuser;
				} else {
					global $momlsdb_queries;
					$this->mo2f_userid            = $currentuser->ID;
					$mo2f_configured_2_f_a_method = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $currentuser->ID );
					if ( get_site_option( 'is_onprem' ) ) {
						$mo2f_configured_2_f_a_method = get_user_meta( $currentuser->ID, 'currentMethod', true );
					}
					if ( get_site_option( 'is_onprem' ) && 'Security Questions' === $mo2f_configured_2_f_a_method ) {
						$this->momls_initiate_2nd_factor( $currentuser, null, null );
					} else {
						$session_id  = isset( $_POST['miniorange_user_session'] ) ? sanitize_text_field( wp_unslash( $_POST['miniorange_user_session'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification.Missing -- Request is coming from Wordpres login form.
						$redirect_to = isset( $_POST['redirect_to'] ) ? sanitize_text_field( wp_unslash( $_POST['redirect_to'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification.Missing -- Request is coming from WooCommerce login form.

						if ( is_null( $session_id ) ) {
							$session_id = $this->momls_create_session();
						}

						$key   = get_site_option( 'mo2f_customer_token' );
						$error = $this->momls_initiate_2nd_factor( $currentuser, $redirect_to, $session_id );

						if ( is_wp_error( $error ) ) {
							return $error;
						}
						return $error;
					}
				}
			}

		}
		/**
		 * It will help to enqueue the default login
		 *
		 * @return void
		 */
		public function momls_enable_jquery_default_login() {
			wp_enqueue_script( 'jquery' );
		}

	}
}

?>
