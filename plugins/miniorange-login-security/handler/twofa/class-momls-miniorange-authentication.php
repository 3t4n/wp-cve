<?php
/**
 * This file contains Create, read, update and delete user operations on miniOrange idp.
 *
 * @package miniorange-login-security/handler/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class Momls_Miniorange_Password_2Factor_Login.
 */
require 'class-momls-miniorange-password-2factor-login.php';
/**
 * Including two-fa-setup-notification.php.
 */
require dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa-setup-notification.php';
/**
 * Including class-momls-wpns-utility.php.
 */
require dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'helper' . DIRECTORY_SEPARATOR . 'class-momls-wpns-utility.php';
if ( ! class_exists( 'Momls_Miniorange_Authentication' ) ) {
	/**
	 * Class Momls_Miniorange_Authentication.
	 */
	class Momls_Miniorange_Authentication {
		/**
		 * Default customer key
		 *
		 * @var string
		 */
		private $default_customer_key = '16555';

		/**
		 * Default api key
		 *
		 * @var string
		 */
		private $default_api_key = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'momls_auth_save_settings' ) );
			add_action( 'plugins_loaded', array( $this, 'momls_update_db_check' ) );
			if ( (int) ( get_site_option( 'mo2f_activate_plugin' ) ) === 1 ) {
				$mo2f_rba_attributes = new Momls_Miniorange_Rba_Attributes();
				$pass2fa_login       = new Momls_Miniorange_Password_2Factor_Login();
				$mo2f_2factor_setup  = new Momls_Two_Factor_Setup();
				add_action( 'init', array( $pass2fa_login, 'momls_pass2login_redirect' ) );
				// for shortcode addon.
				$mo2f_ns_config = new Momls_Wpns_Utility();
				add_filter( 'mo2f_shortcode_rba_gauth', array( $mo2f_rba_attributes, 'momls_validate_google_auth' ), 10, 3 );
				add_filter( 'mo2f_shortcode_kba', array( $mo2f_2factor_setup, 'momls_register_kba_details' ), 10, 7 );
				add_filter( 'mo2f_update_info', array( $mo2f_2factor_setup, 'momls_update_userinfo' ), 10, 5 );
				add_action(
					'mo2f_shortcode_form_fields',
					array(
						$pass2fa_login,
						'momls_pass2login_form_fields',
					),
					10,
					5
				);
				add_filter( 'mo2f_gauth_service', array( $mo2f_rba_attributes, 'momls_google_auth_service' ), 10, 1 );
				if ( get_site_option( 'mo2f_login_policy' ) ) { // password + 2nd factor enabled.

					if ( get_site_option( 'mo_2factor_admin_registration_status' ) === 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' || get_site_option( 'is_onprem' ) ) {

						remove_filter( 'authenticate', 'wp_authenticate_username_password', 20 );

						add_filter( 'authenticate', array( $pass2fa_login, 'momls_check_username_password' ), 99999, 4 );
						add_action( 'init', array( $pass2fa_login, 'momls_pass2login_redirect' ) );
						add_action(
							'login_form',
							array(
								$pass2fa_login,
								'momls_pass2login_show_wp_login_form',
							),
							10
						);

						add_action(
							'login_enqueue_scripts',
							array(
								$pass2fa_login,
								'momls_enable_jquery_default_login',
							)
						);

						add_action(
							'woocommerce_login_form_end',
							array(
								$pass2fa_login,
								'momls_pass2login_show_wp_login_form',
							)
						);
						add_action(
							'wp_enqueue_scripts',
							array(
								$pass2fa_login,
								'momls_enable_jquery_default_login',
							)
						);

						// Actions for other plugins to use miniOrange 2FA plugin.
						add_action(
							'miniorange_pre_authenticate_user_login',
							array(
								$pass2fa_login,
								'momls_check_username_password',
							),
							1,
							4
						);
						add_action(
							'miniorange_post_authenticate_user_login',
							array(
								$pass2fa_login,
								'momls_initiate_2nd_factor',
							),
							1,
							3
						);
					}
				} else { // login with phone enabled.
					if ( get_site_option( 'mo_2factor_admin_registration_status' ) === 'MO_2_FACTOR_CUSTOMER_REGISTERED_SUCCESS' || get_site_option( 'is_onprem' ) ) {

						$mobile_login = new Momls_Miniorange_Mobile_Login();
						add_action( 'login_footer', array( $mobile_login, 'momls_login_footer_form' ) );

						remove_filter( 'authenticate', 'wp_authenticate_username_password', 20 );
						add_filter( 'authenticate', array( $mobile_login, 'momls_default_login' ), 99999, 3 );
						add_action( 'login_enqueue_scripts', array( $mobile_login, 'momls_custom_login_enqueue_scripts' ) );
					}
				}
			}
		}

		/**
		 * Update database check.
		 */
		public function momls_update_db_check() {

			update_site_option( 'mo_2f_switch_all', 1 );
			$userid = wp_get_current_user()->ID;
			add_site_option( 'mo2f_onprem_admin', $userid );
			// Deciding on On-Premise solution.
			$is_nc  = get_site_option( 'mo2f_is_NC' );
			$is_nnc = get_site_option( 'mo2f_is_NNC' );
			// Old users.
			if ( get_site_option( 'mo2f_customerKey' ) && ! $is_nc ) {
				add_site_option( 'is_onprem', 0 );
			}

			// new users using cloud.
			if ( get_site_option( 'mo2f_customerKey' ) && $is_nc && $is_nnc ) {
				add_site_option( 'is_onprem', 0 );
			}

			if ( get_site_option( 'mo2f_app_secret' ) && $is_nc && $is_nnc ) {
				add_site_option( 'is_onprem', 0 );
			} else {
				add_site_option( 'is_onprem', 1 );

			}
			if ( get_site_option( 'mo2f_network_features', 'not_exits' ) === 'not_exits' ) {
				do_action( 'mo2f_network_create_db' );
				update_site_option( 'mo2f_network_features', 1 );
			}
			if ( get_site_option( 'mo2f_encryption_key', 'not_exits' ) === 'not_exits' ) {
				$get_encryption_key = Momls_Utility::momls_random_str( 16 );
				update_site_option( 'mo2f_encryption_key', $get_encryption_key );

			}
			global $momlsdb_queries;
			$user_id = get_site_option( 'mo2f_miniorange_admin' );

			$current_db_version = get_site_option( 'mo2f_dbversion' );

			if ( $current_db_version < 143 ) {
				update_site_option( 'mo2f_dbversion', 143 );
				$momlsdb_queries->momls_generate_tables();

			}
			if ( ! get_site_option( 'mo2f_existing_user_values_updated' ) ) {

				if ( get_site_option( 'mo2f_customerKey' ) && ! get_site_option( 'mo2f_is_NC' ) ) {
					update_site_option( 'mo2f_is_NC', 0 );
				}

				$momls_check_if_user_column_exists = false;

				if ( $user_id && ! get_site_option( 'mo2f_is_NC' ) ) {

					$does_table_exist = $momlsdb_queries->momls_check_if_table_exists();
					if ( $does_table_exist ) {
						$momls_check_if_user_column_exists = $momlsdb_queries->momls_check_if_user_column_exists( $user_id );
					}
					if ( ! $momls_check_if_user_column_exists ) {
						$momlsdb_queries->momls_generate_tables();

						$momlsdb_queries->momls_insert_user( $user_id, array( 'user_id' => $user_id ) );

						add_site_option( 'mo2f_phone', get_site_option( 'user_phone' ) );
						add_site_option( 'mo2f_enable_login_with_2nd_factor', get_site_option( 'mo2f_show_loginwith_phone' ) );
						add_site_option( 'mo2f_remember_device', get_site_option( 'mo2f_deviceid_enabled' ) );
						add_site_option( 'mo2f_transactionId', get_site_option( 'mo2f-login-transactionId' ) );
						add_site_option( 'mo2f_is_NC', 0 );
						$phone      = get_user_meta( $user_id, 'mo2f_user_phone', true );
						$user_phone = $phone ? $phone : get_user_meta( $user_id, 'mo2f_phone', true );

						$momlsdb_queries->update_user_details(
							$user_id,
							array(
								'mo2f_GoogleAuthenticator_config_status' => get_user_meta( $user_id, 'mo2f_google_authentication_status', true ),
								'mo2f_SecurityQuestions_config_status' => get_user_meta( $user_id, 'mo2f_kba_registration_status', true ),
								'mo2f_EmailVerification_config_status' => true,
								'mo2f_AuthyAuthenticator_config_status' => get_user_meta( $user_id, 'mo2f_authy_authentication_status', true ),
								'mo2f_user_email' => get_user_meta( $user_id, 'mo_2factor_map_id_with_email', true ),
								'mo2f_user_phone' => $user_phone,
								'user_registration_with_miniorange' => get_user_meta( $user_id, 'mo_2factor_user_registration_with_miniorange', true ),
								'mobile_registration_status' => get_user_meta( $user_id, 'mo2f_mobile_registration_status', true ),
								'mo2f_configured_2FA_method' => get_user_meta( $user_id, 'mo2f_selected_2factor_method', true ),
								'mo_2factor_user_registration_status' => get_user_meta( $user_id, 'mo_2factor_user_registration_status', true ),
							)
						);

						if ( get_user_meta( $user_id, 'mo2f_mobile_registration_status', true ) ) {
							$momlsdb_queries->update_user_details(
								$user_id,
								array(
									'mo2f_miniOrangeSoftToken_config_status'            => true,
									'mo2f_miniOrangeQRCodeAuthentication_config_status' => true,
									'mo2f_miniOrangePushNotification_config_status'     => true,
								)
							);
						}

						if ( get_user_meta( $user_id, 'mo2f_otp_registration_status', true ) ) {
							$momlsdb_queries->update_user_details(
								$user_id,
								array(
									'mo2f_OTPOverSMS_config_status' => true,
								)
							);
						}

						$mo2f_external_app_type = get_user_meta( $user_id, 'mo2f_external_app_type', true ) === 'AUTHY 2-FACTOR AUTHENTICATION' ?
						'Authy Authenticator' : 'Google Authenticator';

						update_user_meta( $user_id, 'mo2f_external_app_type', $mo2f_external_app_type );

						delete_site_option( 'mo2f_show_loginwith_phone' );
						delete_site_option( 'mo2f_remember_device' );
						delete_site_option( 'mo2f-login-transactionId' );
						delete_user_meta( $user_id, 'mo2f_google_authentication_status' );
						delete_user_meta( $user_id, 'mo2f_kba_registration_status' );
						delete_user_meta( $user_id, 'mo2f_email_verification_status' );
						delete_user_meta( $user_id, 'mo2f_authy_authentication_status' );
						delete_user_meta( $user_id, 'mo_2factor_map_id_with_email' );
						delete_user_meta( $user_id, 'mo_2factor_user_registration_with_miniorange' );
						delete_user_meta( $user_id, 'mo2f_mobile_registration_status' );
						delete_user_meta( $user_id, 'mo2f_otp_registration_status' );
						delete_user_meta( $user_id, 'mo2f_selected_2factor_method' );
						delete_user_meta( $user_id, 'mo2f_configure_test_option' );
						delete_user_meta( $user_id, 'mo_2factor_user_registration_status' );

						update_site_option( 'mo2f_existing_user_values_updated', 1 );

					}
				}
			}

			if ( $user_id && ! get_site_option( 'mo2f_login_option_updated' ) ) {

				$does_table_exist = $momlsdb_queries->momls_check_if_table_exists();
				if ( $does_table_exist ) {
					$momls_check_if_user_column_exists = $momlsdb_queries->momls_check_if_user_column_exists( $user_id );
					if ( $momls_check_if_user_column_exists ) {
						$selected_2_f_a_method = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $user_id );
						update_site_option( 'mo2f_login_option_updated', 1 );
					}
				}
			}
		}

		/**
		 * Save settings on miniOrange authetication.
		 */
		public function momls_auth_save_settings() {

			if ( array_key_exists( 'page', $_REQUEST ) && sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) === 'mo_2fa_two_fa' ) {
				if ( ! session_id() || session_id() === '' || ! isset( $_SESSION ) ) {
					session_start();
				}
			}

			global $user;
			global $momlsdb_queries;
			$default_customer_key = $this->default_customer_key;
			$default_api_key      = $this->default_api_key;

			$user    = wp_get_current_user();
			$user_id = $user->ID;

			if ( current_user_can( 'manage_options' ) ) {

				if ( strlen( get_site_option( 'mo2f_encryption_key' ) ) > 17 ) {
					$get_encryption_key = Momls_Utility::momls_random_str( 16 );
					update_site_option( 'mo2f_encryption_key', $get_encryption_key );
				}
			}
			if ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'momls_validate_google_authy_test' ) {

				$nonce = isset( $_POST['momls_validate_google_authy_test_nonce'] ) ? sanitize_key( wp_unslash( $_POST['momls_validate_google_authy_test_nonce'] ) ) : null;

				if ( ! wp_verify_nonce( $nonce, 'mo2f-validate-google-authy-test-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				} else {
					$otp_token  = '';
					$otp_token1 = isset( $_POST['otp_token'] ) ? sanitize_text_field( wp_unslash( $_POST['otp_token'] ) ) : '';
					if ( Momls_Utility::momls_check_empty_or_null( $otp_token1 ) ) {
						update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ENTER_VALUE' ) );
						$this->momls_auth_show_error_message();

						return;
					} else {
						$otp_token = sanitize_text_field( wp_unslash( $_POST['otp_token'] ) );
					}
					$email = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
					if ( get_site_option( 'is_onprem' ) ) {
						include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-momls-google-auth-onpremise.php';
						$gauth_obj = new Momls_Google_Auth_Onpremise();
						$secret    = $gauth_obj->momls_gauth_get_secret( $user->ID );
						$content   = $gauth_obj->momls_verify_code( $secret, $otp_token );
						if ( strcasecmp( $content['status'], 'SUCCESS' ) === 0 ) { // Google OTP validated.
							if ( current_user_can( 'manage_options' ) ) {
								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'COMPLETED_TEST' ) );
							} else {
								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'COMPLETED_TEST' ) );
							}

							delete_user_meta( $user->ID, 'mo2f_test_2FA' );
							$this->momls_auth_show_success_message();

						} else {  // OTP Validation failed.
							update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_OTP' ) );
							$this->momls_auth_show_error_message();

						}
					} else {
						$customer = new Momls_Customer_Setup();
						$content  = json_decode( $customer->momls_validate_otp_token( 'GOOGLE AUTHENTICATOR', $email, null, $otp_token, get_site_option( 'mo2f_customerKey' ), get_site_option( 'Momls_Api_key' ) ), true );
						if ( json_last_error() === JSON_ERROR_NONE ) {

							if ( strcasecmp( $content['status'], 'SUCCESS' ) === 0 ) { // Google OTP validated.

								if ( current_user_can( 'manage_options' ) ) {
									update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'COMPLETED_TEST' ) );
								} else {
									update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'COMPLETED_TEST' ) );
								}

								delete_user_meta( $user->ID, 'mo2f_test_2FA' );
								$this->momls_auth_show_success_message();

							} else {  // OTP Validation failed.
								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_OTP' ) );
								$this->momls_auth_show_error_message();

							}
						} else {
							update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_WHILE_VALIDATING_OTP' ) );
							$this->momls_auth_show_error_message();

						}
					}
				}
			} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo2f_google_appname' ) {
				$nonce = isset( $_POST['mo2f_google_appname_nonce'] ) ? sanitize_key( wp_unslash( $_POST['mo2f_google_appname_nonce'] ) ) : null;

				if ( ! wp_verify_nonce( $nonce, 'mo2f-google-appname-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				} else {

					update_site_option( 'mo2f_google_appname', ( ( isset( $_POST['mo2f_google_auth_appname'] ) && ! empty( $_POST['mo2f_google_auth_appname'] ) ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_google_auth_appname'] ) ) : 'miniOrangeAuth' ) );
				}
			} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'momls_configure_google_authenticator_validate' ) {
				$nonce = isset( $_POST['momls_configure_google_authenticator_validate_nonce'] ) ? sanitize_key( wp_unslash( $_POST['momls_configure_google_authenticator_validate_nonce'] ) ) : null;

				if ( ! wp_verify_nonce( $nonce, 'mo2f-configure-google-authenticator-validate-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				} else {
					$otp_token = isset( $_POST['google_token'] ) ? sanitize_text_field( wp_unslash( $_POST['google_token'] ) ) : null;
					$ga_secret = isset( $_POST['google_auth_secret'] ) ? sanitize_key( wp_unslash( $_POST['google_auth_secret'] ) ) : null;

					if ( Momls_Utility::momls_check_number_length( $otp_token ) ) {
						$email = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
						if ( get_site_option( 'is_onprem' ) ) {

							$twofactor_transactions = new Momls_Db();
							$user                   = wp_get_current_user();
							$email                  = (string) $user->user_email;
							include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-momls-google-auth-onpremise.php';
							$gauth_obj = new Momls_Google_Auth_Onpremise();
							$content   = $gauth_obj->momls_verify_code( $_SESSION['secret_ga'], $otp_token );

							if ( 'SUCCESS' === $content['status'] ) {

								delete_user_meta( $user->ID, 'mo2f_2FA_method_to_configure' );

								delete_user_meta( $user->ID, 'configure_2FA' );

								$momlsdb_queries->update_user_details(
									$user->ID,
									array(
										'mo2f_GoogleAuthenticator_config_status' => true,
										'mo2f_AuthyAuthenticator_config_status' => false,
										'mo2f_configured_2FA_method' => 'Google Authenticator',
										'user_registration_with_miniorange' => 'SUCCESS',
										'mo_2factor_user_registration_status' => 'MO_2_FACTOR_PLUGIN_SETTINGS',
									)
								);
								update_user_meta( $user->ID, 'mo2f_2FA_method_to_configure', 'Google Authenticator' );
								update_user_meta( $user->ID, 'mo2f_external_app_type', 'Google Authenticator' );
								update_user_meta( $user->ID, 'currentMethod', 'Google Authenticator' );
								momls_display_test_2fa_notification( $user );
								$gauth_obj->momls_gauth_set_secret( $user->ID, sanitize_text_field( $_SESSION['secret_ga'] ) );
								unset( $_SESSION['secret_ga'] );

							} else {
								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_IN_SENDING_OTP_CAUSES' ) . '<br>1. ' . Momls_Constants::momls_lang_translate( 'INVALID_OTP' ) . '<br>2. ' . Momls_Constants::momls_lang_translate( 'APP_TIME_SYNC' ) );
								$this->momls_auth_show_error_message();

							}
						} else {
							$google_auth     = new Momls_Miniorange_Rba_Attributes();
							$google_response = json_decode( $google_auth->momls_validate_google_auth( $email, $otp_token, $ga_secret ), true );
							if ( json_last_error() === JSON_ERROR_NONE ) {
								if ( 'SUCCESS' === $google_response['status'] ) {
									$enduser  = new Momls_Two_Factor_Setup();
									$response = json_decode( $enduser->momls_update_userinfo( $email, 'GOOGLE AUTHENTICATOR', null, null, null ), true );

									if ( json_last_error() === JSON_ERROR_NONE ) {

										if ( 'SUCCESS' === $response['status'] ) {

											delete_user_meta( $user->ID, 'mo2f_2FA_method_to_configure' );

											delete_user_meta( $user->ID, 'configure_2FA' );

											$momlsdb_queries->update_user_details(
												$user->ID,
												array(
													'mo2f_GoogleAuthenticator_config_status' => true,
													'mo2f_AuthyAuthenticator_config_status'  => false,
													'mo2f_configured_2FA_method'             => 'Google Authenticator',
													'user_registration_with_miniorange'      => 'SUCCESS',
													'mo_2factor_user_registration_status'    => 'MO_2_FACTOR_PLUGIN_SETTINGS',
												)
											);

											update_user_meta( $user->ID, 'mo2f_external_app_type', 'Google Authenticator' );
											momls_display_test_2fa_notification( $user );

										} else {
											update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_PROCESS' ) );
											$this->momls_auth_show_error_message();

										}
									} else {
										update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_PROCESS' ) );
										$this->momls_auth_show_error_message();

									}
								} else {
									update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_IN_SENDING_OTP_CAUSES' ) . '<br>1. ' . Momls_Constants::momls_lang_translate( 'INVALID_OTP' ) . '<br>2. ' . Momls_Constants::momls_lang_translate( 'APP_TIME_SYNC' ) );
									$this->momls_auth_show_error_message();

								}
							} else {
								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_WHILE_VALIDATING_USER' ) );
								$this->momls_auth_show_error_message();

							}
						}
					} else {
						update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ONLY_DIGITS_ALLOWED' ) );
						$this->momls_auth_show_error_message();

					}
				}
			} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'momls_configure_authy_authenticator' ) {
				$nonce = isset( $_POST['momls_configure_authy_authenticator_nonce'] ) ? sanitize_key( wp_unslash( $_POST['momls_configure_authy_authenticator_nonce'] ) ) : null;

				if ( ! wp_verify_nonce( $nonce, 'mo2f-configure-authy-authenticator-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				} else {
					$authy          = new Momls_Miniorange_Rba_Attributes();
					$user_email     = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
					$authy_response = json_decode( $authy->momls_google_auth_service( $user_email ), true );
					if ( json_last_error() === JSON_ERROR_NONE ) {
						if ( 'SUCCESS' === $authy_response['status'] ) {
							$mo2f_authy_keys                      = array();
							$mo2f_authy_keys['authy_qrCode']      = $authy_response['qrCodeData'];
							$mo2f_authy_keys['mo2f_authy_secret'] = $authy_response['secret'];
							$_SESSION['mo2f_authy_keys']          = $mo2f_authy_keys;
						} else {
							update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_USER_REGISTRATION' ) );
							$this->momls_auth_show_error_message();
						}
					} else {
						update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_USER_REGISTRATION' ) );
						$this->momls_auth_show_error_message();
					}
				}
			} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'momls_configure_authy_authenticator_validate' ) {
				$nonce = isset( $_POST['momls_configure_authy_authenticator_validate_nonce'] ) ? sanitize_key( wp_unslash( $_POST['momls_configure_authy_authenticator_validate_nonce'] ) ) : null;
				if ( ! wp_verify_nonce( $nonce, 'mo2f-configure-authy-authenticator-validate-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				} else {
					$otp_token    = isset( $_POST['mo2f_authy_token'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_authy_token'] ) ) : null;
					$authy_secret = isset( $_POST['mo2f_authy_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_authy_secret'] ) ) : null;
					if ( Momls_Utility::momls_check_number_length( $otp_token ) ) {
						$email          = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
						$authy_auth     = new Momls_Miniorange_Rba_Attributes();
						$authy_response = json_decode( $authy_auth->momls_validate_google_auth( $email, $otp_token, $authy_secret ), true );
						if ( json_last_error() === JSON_ERROR_NONE ) {
							if ( 'SUCCESS' === $authy_response['status'] ) {
								$enduser  = new Momls_Two_Factor_Setup();
								$response = json_decode( $enduser->momls_update_userinfo( $email, 'GOOGLE AUTHENTICATOR', null, null, null ), true );
								if ( json_last_error() === JSON_ERROR_NONE ) {

									if ( 'SUCCESS' === $response['status'] ) {
										$momlsdb_queries->update_user_details(
											$user->ID,
											array(
												'mo2f_GoogleAuthenticator_config_status' => false,
												'mo2f_AuthyAuthenticator_config_status'  => true,
												'mo2f_configured_2FA_method'             => 'Authy Authenticator',
												'user_registration_with_miniorange'      => 'SUCCESS',
												'mo_2factor_user_registration_status'    => 'MO_2_FACTOR_PLUGIN_SETTINGS',
											)
										);
										update_user_meta( $user->ID, 'mo2f_external_app_type', 'Authy Authenticator' );
										delete_user_meta( $user->ID, 'mo2f_2FA_method_to_configure' );
										delete_user_meta( $user->ID, 'configure_2FA' );

										momls_display_test_2fa_notification( $user );

									} else {
										update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_PROCESS' ) );
										$this->momls_auth_show_error_message();
									}
								} else {
									update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_PROCESS' ) );
									$this->momls_auth_show_error_message();
								}
							} else {
								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_IN_SENDING_OTP_CAUSES' ) . '<br>1. ' . Momls_Constants::momls_lang_translate( 'INVALID_OTP' ) . '<br>2. ' . Momls_Constants::momls_lang_translate( 'APP_TIME_SYNC' ) );
								$this->momls_auth_show_error_message();
							}
						} else {
							update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_WHILE_VALIDATING_USER' ) );
							$this->momls_auth_show_error_message();
						}
					} else {
						update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ONLY_DIGITS_ALLOWED' ) );
						$this->momls_auth_show_error_message();
					}
				}
			} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo2f_save_kba' ) {
				$nonce = isset( $_POST['mo2f_save_kba_nonce'] ) ? sanitize_key( wp_unslash( $_POST['mo2f_save_kba_nonce'] ) ) : null;
				if ( ! wp_verify_nonce( $nonce, 'mo2f-save-kba-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				}
				$twofactor_transactions = new Momls_Db();
				$kba_q1                 = isset( $_POST['mo2f_kbaquestion_1'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kbaquestion_1'] ) ) : null;
				$kba_a1                 = isset( $_POST['mo2f_kba_ans1'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kba_ans1'] ) ) : null;
				$kba_q2                 = isset( $_POST['mo2f_kbaquestion_2'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kbaquestion_2'] ) ) : null;
				$kba_a2                 = isset( $_POST['mo2f_kba_ans2'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kba_ans2'] ) ) : null;
				$kba_q3                 = isset( $_POST['mo2f_kbaquestion_3'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kbaquestion_3'] ) ) : null;
				$kba_a3                 = isset( $_POST['mo2f_kba_ans3'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kba_ans3'] ) ) : null;
				if ( Momls_Utility::momls_check_empty_or_null( $kba_q1 ) || Momls_Utility::momls_check_empty_or_null( $kba_a1 ) || Momls_Utility::momls_check_empty_or_null( $kba_q2 ) || Momls_Utility::momls_check_empty_or_null( $kba_a2 ) || Momls_Utility::momls_check_empty_or_null( $kba_q3 ) || Momls_Utility::momls_check_empty_or_null( $kba_a3 ) ) {
					update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_ENTRY' ) );
					$this->momls_auth_show_error_message();
					return;
				}

				$kba_q1 = isset( $_POST['mo2f_kbaquestion_1'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kbaquestion_1'] ) ) : null;
				$kba_a1 = isset( $_POST['mo2f_kba_ans1'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kba_ans1'] ) ) : null;
				$kba_q2 = isset( $_POST['mo2f_kbaquestion_2'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kbaquestion_2'] ) ) : null;
				$kba_a2 = isset( $_POST['mo2f_kba_ans2'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kba_ans2'] ) ) : null;
				$kba_q3 = isset( $_POST['mo2f_kbaquestion_3'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kbaquestion_3'] ) ) : null;
				$kba_a3 = isset( $_POST['mo2f_kba_ans3'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_kba_ans3'] ) ) : null;

				if ( strcasecmp( $kba_q1, $kba_q2 ) === 0 || strcasecmp( $kba_q2, $kba_q3 ) === 0 || strcasecmp( $kba_q3, $kba_q1 ) === 0 ) {
					update_site_option( 'mo2f_message', 'The questions you select must be unique.' );
					$this->momls_auth_show_error_message();
					return;
				}
				$kba_q1 = addcslashes( stripslashes( $kba_q1 ), '"\\' );
				$kba_q2 = addcslashes( stripslashes( $kba_q2 ), '"\\' );
				$kba_q3 = addcslashes( stripslashes( $kba_q3 ), '"\\' );
				if ( get_site_option( 'is_onprem' ) ) {

					$kba_a1 = md5( addcslashes( stripslashes( $kba_a1 ), '"\\' ) );
					$kba_a2 = md5( addcslashes( stripslashes( $kba_a2 ), '"\\' ) );
					$kba_a3 = md5( addcslashes( stripslashes( $kba_a3 ), '"\\' ) );

					$question_answer = array(
						$kba_q1 => $kba_a1,
						$kba_q2 => $kba_a2,
						$kba_q3 => $kba_a3,
					);
					update_user_meta( $user_id, 'mo2f_kba_challenge', $question_answer );
					delete_user_meta( $user_id, 'configure_2FA' );
					$momlsdb_queries->update_user_details(
						$user->ID,
						array(
							'mo2f_SecurityQuestions_config_status' => true,
							'mo2f_configured_2FA_method' => 'Security Questions',
							'mo_2factor_user_registration_status' => 'MO_2_FACTOR_PLUGIN_SETTINGS',
						)
					);
					update_user_meta( $user->ID, 'currentMethod', 'Security Questions' );
					momls_display_test_2fa_notification( $user );
				} else {
					$kba_a1 = addcslashes( stripslashes( $kba_a1 ), '"\\' );
					$kba_a2 = addcslashes( stripslashes( $kba_a2 ), '"\\' );
					$kba_a3 = addcslashes( stripslashes( $kba_a3 ), '"\\' );

					$email            = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
					$kba_registration = new Momls_Two_Factor_Setup();
					$kba_reg_reponse  = json_decode( $kba_registration->momls_register_kba_details( $email, $kba_q1, $kba_a1, $kba_q2, $kba_a2, $kba_q3, $kba_a3 ), true );
					if ( json_last_error() === JSON_ERROR_NONE ) {
						if ( 'SUCCESS' === $kba_reg_reponse['status'] ) {
							if ( isset( $_POST['mobile_kba_option'] ) && sanitize_text_field( wp_unslash( $_POST['mobile_kba_option'] ) ) === 'mo2f_request_for_kba_as_emailbackup' ) {
								Momls_Utility::momls_unset_session_variables( 'mo2f_mobile_support' );

								delete_user_meta( $user->ID, 'configure_2FA' );
								delete_user_meta( $user->ID, 'mo2f_2FA_method_to_configure' );

								$message = esc_html_e( 'Your KBA as alternate 2 factor is configured successfully.', 'miniorange-login-security' );
								update_site_option( 'mo2f_message', $message );
								$this->momls_auth_show_success_message();

							} else {
								$enduser  = new Momls_Two_Factor_Setup();
								$response = json_decode( $enduser->momls_update_userinfo( $email, 'KBA', null, null, null ), true );
								if ( json_last_error() === JSON_ERROR_NONE ) {
									if ( 'ERROR' === $response['status'] ) {
										update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( $response['message'] ) );
										$this->momls_auth_show_error_message();

									} elseif ( 'SUCCESS' === $response['status'] ) {
										delete_user_meta( $user->ID, 'configure_2FA' );

										$momlsdb_queries->update_user_details(
											$user->ID,
											array(
												'mo2f_SecurityQuestions_config_status' => true,
												'mo2f_configured_2FA_method'           => 'Security Questions',
												'mo_2factor_user_registration_status'  => 'MO_2_FACTOR_PLUGIN_SETTINGS',
											)
										);

										momls_display_test_2fa_notification( $user );

									} else {
										update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_PROCESS' ) );
										$this->momls_auth_show_error_message();

									}
								} else {
									update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_REQ' ) );
									$this->momls_auth_show_error_message();

								}
							}
						} else {
							update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_WHILE_SAVING_KBA' ) );
							$this->momls_auth_show_error_message();

							return;
						}
					} else {
						update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_WHILE_SAVING_KBA' ) );
						$this->momls_auth_show_error_message();

						return;
					}
				}
			} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo2f_validate_kba_details' ) {

				$nonce = isset( $_POST['mo2f_validate_kba_details_nonce'] ) ? sanitize_key( wp_unslash( $_POST['mo2f_validate_kba_details_nonce'] ) ) : null;

				if ( ! wp_verify_nonce( $nonce, 'mo2f-validate-kba-details-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				} else {
					$kba_ans_1 = '';
					$kba_ans_2 = '';
					if ( Momls_Utility::momls_check_empty_or_null( isset( $_POST['mo2f_answer_1'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_answer_1'] ) ) : null ) || Momls_Utility::momls_check_empty_or_null( isset( $_POST['mo2f_answer_2'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_answer_2'] ) ) : null ) ) {
						update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_ENTRY' ) );
						$this->momls_auth_show_error_message();

						return;
					} else {
						$kba_ans_1 = sanitize_text_field( wp_unslash( $_POST['mo2f_answer_1'] ) );
						$kba_ans_2 = sanitize_text_field( wp_unslash( $_POST['mo2f_answer_2'] ) );
					}
					// if the php session folder has insufficient permissions, temporary options to be used.
					$kba_questions = isset( $_SESSION['mo_2_factor_kba_questions'] ) && ! empty( $_SESSION['mo_2_factor_kba_questions'] ) ? sanitize_text_field( $_SESSION['mo_2_factor_kba_questions'] ) : get_site_option( 'kba_questions' );

					$kba_ans    = array();
					$kba_ans[0] = isset( $kba_questions[0] ) ? $kba_questions[0] : '';
					$kba_ans[1] = $kba_ans_1;
					$kba_ans[2] = isset( $kba_questions[1] ) ? $kba_questions[1] : '';
					$kba_ans[3] = $kba_ans_2;

					// if the php session folder has insufficient permissions, temporary options to be used.
					$mo2f_transaction_id = isset( $_SESSION['mo2f_transactionId'] ) && ! empty( $_SESSION['mo2f_transactionId'] ) ? sanitize_text_field( $_SESSION['mo2f_transactionId'] ) : get_site_option( 'mo2f_transactionId' );

					$kba_validate          = new Momls_Customer_Setup();
					$kba_validate_response = json_decode( $kba_validate->momls_validate_otp_token( 'KBA', null, $mo2f_transaction_id, $kba_ans, get_site_option( 'mo2f_customerKey' ), get_site_option( 'Momls_Api_key' ) ), true );

					if ( json_last_error() === JSON_ERROR_NONE ) {
						if ( strcasecmp( $kba_validate_response['status'], 'SUCCESS' ) === 0 ) {

							unset( $_SESSION['mo_2_factor_kba_questions'] );
							unset( $_SESSION['mo2f_transactionId'] );
							delete_site_option( 'mo2f_transactionId' );
							delete_site_option( 'kba_questions' );
							update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'COMPLETED_TEST' ) );
							delete_user_meta( $user->ID, 'mo2f_test_2FA' );
							$this->momls_auth_show_success_message();
						} else {  // KBA Validation failed.
							update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_ANSWERS' ) );
							$this->momls_auth_show_error_message();

						}
					}
				}
			} elseif ( ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo2f_save_free_plan_auth_methods' ) ) {// user clicks on Set 2-Factor method.

				$nonce = isset( $_POST['miniorange_save_form_auth_methods_nonce'] ) ? sanitize_key( wp_unslash( $_POST['miniorange_save_form_auth_methods_nonce'] ) ) : null;

				if ( ! wp_verify_nonce( $nonce, 'miniorange-save-form-auth-methods-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );
					return $error;
				} else {
					$configured_method = isset( $_POST['mo2f_configured_2FA_method_free_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_configured_2FA_method_free_plan'] ) ) : '';
					$selected_action   = isset( $_POST['mo2f_selected_action_free_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_selected_action_free_plan'] ) ) : '';
					$momlsdb_queries->momls_insert_user( $user->ID );

					if ( 'select2factor' === $selected_action && get_site_option( 'is_onprem' ) ) {
						if ( 'SecurityQuestions' === $configured_method ) {
							update_user_meta( $user->ID, 'currentMethod', 'Security Questions' );
						} elseif ( 'GoogleAuthenticator' === $configured_method ) {
							update_user_meta( $user->ID, 'currentMethod', 'Google Authenticator' );
						} else {
							update_user_meta( $user->ID, 'currentMethod', $configured_method );
						}
						momls_display_test_2fa_notification( $user );
					}
					$is_customer_registered = $momlsdb_queries->momls_get_user_detail( 'user_registration_with_miniorange', $user->ID ) === 'SUCCESS' ? true : false;
					$selected_2_f_a_method  = Momls_Utility::momls_decode_2_factor( isset( $_POST['mo2f_configured_2FA_method_free_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_configured_2FA_method_free_plan'] ) ) : ( isset( $_POST['mo2f_selected_action_standard_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_selected_action_standard_plan'] ) ) : '' ), 'wpdb' );
					update_user_meta( $user->ID, 'mo2f_2FA_method_to_configure', $selected_2_f_a_method );
					if ( get_site_option( 'is_onprem' ) ) {
						$is_customer_registered = 1;
					}
					if ( $is_customer_registered ) {
						$selected_2_f_a_method = Momls_Utility::momls_decode_2_factor( isset( $_POST['mo2f_configured_2FA_method_free_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_configured_2FA_method_free_plan'] ) ) : ( isset( $_POST['mo2f_selected_action_standard_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_selected_action_standard_plan'] ) ) : '' ), 'wpdb' );
						$selected_action       = isset( $_POST['mo2f_selected_action_free_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_selected_action_free_plan'] ) ) : ( isset( $_POST['mo2f_selected_action_standard_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_selected_action_standard_plan'] ) ) : '' );
						$user_phone            = '';
						if ( isset( $_SESSION['user_phone'] ) ) {
							$user_phone = 'false' !== $_SESSION['user_phone'] ? sanitize_text_field( $_SESSION['user_phone'] ) : $momlsdb_queries->momls_get_user_detail( 'mo2f_user_phone', $user->ID );
						}

						// set it as his 2-factor in the WP database and server.
						if ( 'select2factor' === $selected_action ) {

								// update in the WordPress DB.
								$momlsdb_queries->update_user_details( $user->ID, array( 'mo2f_configured_2FA_method' => $selected_2_f_a_method ) );

								// update the server.
							if ( get_site_option( 'is_onprem' ) === 0 ) {
								$this->momls_save_2_factor_method( $user, $selected_2_f_a_method );
							}
						} elseif ( 'configure2factor' === $selected_action ) {

							// show configuration form of respective Two Factor method.
							update_user_meta( $user->ID, 'configure_2FA', 1 );
							update_user_meta( $user->ID, 'mo2f_2FA_method_to_configure', $selected_2_f_a_method );
						}
					} else {
						$momlsdb_queries->momls_insert_user( $user->ID );
						$momlsdb_queries->update_user_details( $user->ID, array( 'mo_2factor_user_registration_status' => 'REGISTRATION_STARTED' ) );
						update_user_meta( $user->ID, 'register_account_popup', 1 );
						update_site_option( 'mo2f_message', '' );
					}
				}
			} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_2factor_test_authentication_method' ) {
				// network security feature.
				$nonce = isset( $_POST['mo_2factor_test_authentication_method_nonce'] ) ? sanitize_key( wp_unslash( $_POST['mo_2factor_test_authentication_method_nonce'] ) ) : null;

				if ( ! wp_verify_nonce( $nonce, 'mo-2factor-test-authentication-method-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				} else {
					update_user_meta( $user->ID, 'mo2f_test_2FA', 1 );

					$selected_2_f_a_method = isset( $_POST['mo2f_configured_2FA_method_test'] ) ? sanitize_text_field( wp_unslash( $_POST['mo2f_configured_2FA_method_test'] ) ) : '';
					$customer              = new Momls_Customer_Setup();
					$email                 = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
					$customer_key          = get_site_option( 'mo2f_customerKey' );
					$api_key               = get_site_option( 'Momls_Api_key' );

					if ( ! get_site_option( 'is_onprem' ) ) {
						$selected_2_f_a_method_server = Momls_Utility::momls_decode_2_factor( $selected_2_f_a_method, 'server' );
					}

					if ( 'Security Questions' === $selected_2_f_a_method ) {

						if ( get_site_option( 'is_onprem' ) ) {
							$question_answers    = get_user_meta( $user->ID, 'mo2f_kba_challenge' );
							$challenge_questions = array_keys( $question_answers[0] );
							$random_keys         = array_rand( $challenge_questions, 2 );
							$challenge_ques1     = $challenge_questions[ $random_keys[0] ];
							$challenge_ques2     = $challenge_questions[ $random_keys[1] ];
							$questions           = array( $challenge_ques1, $challenge_ques2 );
							update_user_meta( $user->ID, 'kba_questions_user', $questions );
						} else {
							if ( json_last_error() === JSON_ERROR_NONE ) { /* Generate KBA Questions*/
								if ( 'SUCCESS' === $response['status'] ) {
									$_SESSION['mo2f_transactionId'] = $response['txId'];
									update_site_option( 'mo2f_transactionId', $response['txId'] );
									$questions                             = array();
									$questions[0]                          = $response['questions'][0]['question'];
									$questions[1]                          = $response['questions'][1]['question'];
									$_SESSION['mo_2_factor_kba_questions'] = $questions;
									update_site_option( 'kba_questions', $questions );

									update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ANSWER_SECURITY_QUESTIONS' ) );
									$this->momls_auth_show_success_message();

								} elseif ( 'ERROR' === $response['status'] ) {
									update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_FETCHING_QUESTIONS' ) );
									$this->momls_auth_show_error_message();

								}
							} else {
								update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_FETCHING_QUESTIONS' ) );
								$this->momls_auth_show_error_message();

							}
						}
					}
				}

				update_user_meta( $user->ID, 'mo2f_2FA_method_to_test', $selected_2_f_a_method );
			} elseif ( isset( $_POST['option'] ) && sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo2f_go_back' ) {
				$nonce = isset( $_POST['mo2f_go_back_nonce'] ) ? sanitize_key( wp_unslash( $_POST['mo2f_go_back_nonce'] ) ) : null;

				if ( ! wp_verify_nonce( $nonce, 'mo2f-go-back-nonce' ) ) {
					$error = new WP_Error();
					$error->add( 'empty_username', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . esc_html_e( 'Invalid Request.', 'miniorange-login-security' ) );

					return $error;
				} else {
					$session_variables = array(
						'mo2f_qrCode',
						'mo2f_transactionId',
						'mo2f_show_qr_code',
						'user_phone',
						'mo2f_google_auth',
						'mo2f_mobile_support',
						'mo2f_authy_keys',
					);
					Momls_Utility::momls_unset_session_variables( $session_variables );
					delete_site_option( 'mo2f_transactionId' );
					delete_site_option( 'user_phone_temp' );

					delete_user_meta( $user->ID, 'mo2f_test_2FA' );
					delete_user_meta( $user->ID, 'configure_2FA' );
				}
			}

		}
		/**
		 * Delete user details on deactivation.
		 */
		public function momls_auth_deactivate() {
			global $momlsdb_queries;
			$mo2f_register_with_another_email = get_site_option( 'mo2f_register_with_another_email' );
			$is_ec                            = ! get_site_option( 'mo2f_is_NC' ) ? 1 : 0;
			$is_nnc                           = get_site_option( 'mo2f_is_NC' ) && get_site_option( 'mo2f_is_NNC' ) ? 1 : 0;

			if ( $mo2f_register_with_another_email || $is_ec || $is_nnc ) {
				update_site_option( 'mo2f_register_with_another_email', 0 );
				$users = get_users( array() );

			}
		}


		/**
		 * Show succes message on authentication.
		 *
		 * @return void
		 */
		public function momls_auth_show_success_message() {
			do_action( 'wpns_momls_show_message', get_site_option( 'mo2f_message' ), 'SUCCESS' );

		}
		/**
		 * Get google authenticators parameters.
		 *
		 * @param object $user User object.
		 * @return void
		 */
		public static function momls_get_ga_parameters( $user ) {
			global $momlsdb_queries;
			$email           = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
			$google_auth     = new Momls_Miniorange_Rba_Attributes();
			$gauth_name      = get_site_option( 'mo2f_google_appname' );
			$gauth_name      = $gauth_name ? $gauth_name : 'miniOrangeAuth';
			$google_response = json_decode( $google_auth->momls_google_auth_service( $email, $gauth_name ), true );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				if ( 'SUCCESS' === $google_response['status'] ) {
					$mo2f_google_auth              = array();
					$mo2f_google_auth['ga_qrCode'] = $google_response['qrCodeData'];
					$mo2f_google_auth['ga_secret'] = $google_response['secret'];
					$_SESSION['mo2f_google_auth']  = $mo2f_google_auth;
				} else {
					update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_USER_REGISTRATION' ) );
					do_action( 'momls_auth_show_error_message' );
				}
			} else {
				update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_USER_REGISTRATION' ) );
				do_action( 'momls_auth_show_error_message' );

			}
		}
		/**
		 * Show error messages.
		 *
		 * @return void
		 */
		public function momls_auth_show_error_message() {
			do_action( 'wpns_momls_show_message', get_site_option( 'mo2f_message' ), 'ERROR' );

		}
		/**
		 * Save 2-factor method of a user.
		 *
		 * @param object $user user object.
		 * @param string $mo2f_configured_2_f_a_method configured 2FA method of a user.
		 * @return void
		 */
		private function momls_save_2_factor_method( $user, $mo2f_configured_2_f_a_method ) {
			global $momlsdb_queries;
			$email          = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
			$enduser        = new Momls_Two_Factor_Setup();
			$phone          = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_phone', $user->ID );
			$current_method = Momls_Utility::momls_decode_2_factor( $mo2f_configured_2_f_a_method, 'server' );

			$response = json_decode( $enduser->momls_update_userinfo( $email, $current_method, $phone, null, null ), true );

			if ( json_last_error() === JSON_ERROR_NONE ) {
				if ( 'ERROR' === $response['status'] ) {
					update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( $response['message'] ) );
					$this->momls_auth_show_error_message();
				} elseif ( 'SUCCESS' === $response['status'] ) {
					$configured_2famethod = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $user->ID );

					if ( in_array( $configured_2famethod, array( 'Google Authenticator', 'Authy Authenticator' ), true ) ) {
						update_user_meta( $user->ID, 'mo2f_external_app_type', $configured_2famethod );
					}
					$momlsdb_queries->update_user_details(
						$user->ID,
						array(
							'mo_2factor_user_registration_status' => 'MO_2_FACTOR_PLUGIN_SETTINGS',
						)
					);
					delete_user_meta( $user->ID, 'configure_2FA' );
					update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( $configured_2famethod ) . ' ' . Momls_Constants::momls_lang_translate( 'SET_2FA' ) );

					$this->momls_auth_show_success_message();
				} else {
					update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'ERROR_DURING_PROCESS' ) );
					$this->momls_auth_show_error_message();
				}
			} else {
				update_site_option( 'mo2f_message', Momls_Constants::momls_lang_translate( 'INVALID_REQ' ) );
				$this->momls_auth_show_error_message();
			}
		}

	}
	/**
	 * Check if a customer is registered.
	 *
	 * @return boolean
	 */
	function momls_is_customer_registered() {
		$email        = get_site_option( 'mo2f_email' );
		$customer_key = get_site_option( 'mo2f_customerKey' );
		if ( ! $email || ! $customer_key || ! is_numeric( trim( $customer_key ) ) ) {
			return 0;
		} else {
			return 1;
		}
	}
	new Momls_Miniorange_Authentication();
}

