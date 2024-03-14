<?php
/**
 * File contains user's feedback related functions at the time of deactivation of plugin.
 *
 * @package miniorange-login-security/handler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Momls_Feedback_Handler' ) ) {
	/**
	 * Class FeedbackHandler
	 */
	class Momls_Feedback_Handler {
		/**
		 * FeedbackHandler class constructor
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'momls_wpns_feedback_actions' ) );
		}

		/**
		 * Checks for post option value in the switch case.
		 *
		 * @return mixed
		 */
		public function momls_wpns_feedback_actions() {
            if ( isset( $_POST['momls_wpns_feedback_nonce'] ) ) {
                $nonce = isset( $_POST['momls_wpns_feedback_nonce'] ) ? sanitize_key( wp_unslash( $_POST['momls_wpns_feedback_nonce'] ) ) : '';
                if ( ! wp_verify_nonce( $nonce, 'mo-wpns-feedback-nonce' ) ) {
                    $error = new WP_Error();
                    $error->add( 'empty_username_feedback', '<strong>' . esc_html_e( 'ERROR', 'miniorange-login-security' ) . '</strong>: ' . __( 'Invalid Request.', 'miniorange-login-security' ) );
                    return $error;
                }
                global $momls_wpns_utility, $mo2f_dir_name;
                if ( current_user_can( 'manage_options' ) && isset( $_POST['option'] ) ) {
                    switch ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) ) {
                        case 'momls_wpns_skip_feedback':
                        case 'momls_wpns_feedback':
                            $this->momls_wpns_handle_feedback( $_POST );
                            break;

                    }
                }
            }
        }

		/**
		 * Sends the users feedback to miniOrange 2fa support when users deactivate the plugin.
		 *
		 * @param array $postdata The information received in the post request.
		 * @return mixed
		 */
		private function momls_wpns_handle_feedback( $postdata ) {
			if ( MO2F_TEST_MODE ) {
				deactivate_plugins( dirname( dirname( __FILE__ ) ) . '\\miniorange_2_factor_settings.php' );
				return;
			}
			$user              = wp_get_current_user();
			$feedback_option   = isset( $postdata['option'] ) ? sanitize_text_field( wp_unslash( $postdata['option'] ) ) : '';
			$message           = 'Plugin Deactivated : ';
			$deactivate_plugin = isset( $postdata['momls_wpns_deactivate_plugin'] ) ? sanitize_text_field( wp_unslash( $postdata['momls_wpns_deactivate_plugin'] ) ) : '';
			$message          .= ' ' . $deactivate_plugin;
			if ( 'Conflicts with other plugins' === $deactivate_plugin ) {
				$plugin_selected = isset( $postdata['mo2f_plugin_selected'] ) ? sanitize_text_field( wp_unslash( $postdata['mo2f_plugin_selected'] ) ) : '';
				$plugin          = Momls_Utility::momls_get_plugin_name_by_identifier( $plugin_selected );
				$message        .= ', Plugin selected - ' . $plugin . '.';
			}

			$deactivate_reason_message = array_key_exists( 'wpns_query_feedback', $postdata ) ? htmlspecialchars( sanitize_text_field( wp_unslash( $postdata['wpns_query_feedback'] ) ) ) : false;
			$activation_date           = get_site_option( 'mo2f_activated_time' );
			$current_date              = time();
			$send_configuration        = isset( $postdata['mo2f_get_configuration'] ) ? sanitize_text_field( wp_unslash( $postdata['mo2f_get_configuration'] ) ) : 0;
			$diff                      = $activation_date - $current_date;
			if ( false === $activation_date ) {
				$days = 'NA';
			} else {
				$days = abs( round( $diff / 86400 ) );
			}
			update_site_option( 'No_of_days_active_work', $days, 'yes' );

			$message .= ' D:' . $days . ',';
			$message .= '2FA]';

			$message .= ', Feedback : ' . $deactivate_reason_message . '&nbsp;';

			if ( $send_configuration ) {
				$message .= Momls_Wpns_Utility::momls_send_configuration();
			}

			$email = isset( $postdata['query_mail'] ) ? sanitize_email( wp_unslash( $postdata['query_mail'] ) ) : '';
			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$email = get_site_option( 'mo2f_email' );
				if ( empty( $email ) ) {
					$email = $user->user_email;
				}
			}
			$phone            = get_site_option( 'momls_wpns_admin_phone' );
			$feedback_reasons = new Momls_Curl();
			global $momls_wpns_utility;
			if ( ! is_null( $feedback_reasons ) ) {
				if ( ! $momls_wpns_utility->momls_is_curl_installed() ) {
					deactivate_plugins( dirname( dirname( __FILE__ ) ) . '\\miniorange_2_factor_settings.php' );
					wp_safe_redirect( 'plugins.php' );
					exit();
				} else {

					$submited = json_decode( $feedback_reasons->momls_send_email_alert( $email, $phone, $message, $feedback_option ), true );

					if ( json_last_error() === JSON_ERROR_NONE ) {
						if ( is_array( $submited ) && array_key_exists( 'status', $submited ) && 'ERROR' === $submited['status'] ) {
							do_action( 'wpns_momls_show_message', $submited['message'], 'ERROR' );

						} else {

							if ( ! $submited ) {

								do_action( 'wpns_momls_show_message', 'Error while submitting the query.', 'ERROR' );
							}
						}
					}

					if ( 'momls_wpns_feedback' === $feedback_option || 'momls_wpns_skip_feedback' === $feedback_option ) {
						deactivate_plugins( dirname( dirname( __FILE__ ) ) . '\\miniorange_2_factor_settings.php' );
					}
					do_action( 'wpns_momls_show_message', 'Thank you for the feedback.', 'SUCCESS' );

				}
			}
		}



	}
	new Momls_Feedback_Handler();
}
