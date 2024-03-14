<?php
/**
 * This class handles the verification of captcha.
 *
 * @package uwc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'UWC_Captcha_Form_Action_Handler', false ) ) {
	/**
	 * UWC_Captcha_Form_Action_Handler Class.
	 */
	class UWC_Captcha_Form_Action_Handler {
		/**
		 * This is the message var used to store the failed captcha message.
		 *
		 * @var string $message
		 */
		public $message;
		/**
		 * This variable checks if we need to bypass the learndash filter or not.
		 *
		 * @var bool $bypass_learndash_registrayion_filter
		 */
		public $bypass_learndash_registrayion_filter = false;
		/**
		 * Check if user has login error.
		 *
		 * @var bool $check_login_error
		 */
		public $check_login_error = false;
		/**
		 * Hook.
		 */
		public function __construct() {
			add_filter( 'wp_authenticate_user', array( $this, 'uwc_authenticate' ), 10, 2 );
			add_filter( 'registration_errors', array( $this, 'uwc_registration_errors' ), 10, 2 );
			add_filter( 'lostpassword_post', array( $this, 'uwc_registration_errors' ), 10, 2 );
			add_filter( 'woocommerce_registration_errors', array( $this, 'uwc_registration_errors' ), 10, 2 );
			add_filter( 'learndash_alert_message', array( $this, 'uwc_learndash_alert_message' ), 10, 3 );
			add_filter( 'learndash_safe_redirect_location', array( $this, 'uwc_learndash_safe_redirect_location' ), 10, 3 );
			add_filter( 'learndash-registration-errors', array( $this, 'uwc_learndash_registration_errors' ), 10, 3 );
			add_action( 'woocommerce_after_checkout_validation', array( $this, 'uwc_woocommerce_checkout_process' ), 10, 2 );
			add_filter( 'preprocess_comment', array( $this, 'uwc_preprocess_comment' ) );
		}
		/**
		 * Add captcha error to user learndash registration.
		 *
		 * @since 1.1.1
		 * @param  array    $fields An array of posted data.
		 * @param  WP_Error $errors Validation errors.
		 */
		public function uwc_woocommerce_checkout_process( $fields, $errors ) {
			$get_result = $this->get_response_from_selected_captcha_method();
			if ( ! $get_result['response'] ) {
				$message = $get_result['errors'];
				$errors->add( 'validation', $message );
			}
		}
		/**
		 * Add captcha error to user learndash registration.
		 *
		 * @since 1.0.0
		 * @param array $registration_errors An Associative array of Registration error and description.
		 * @return array $registration_errors An Associative array of Registration error and description.
		 */
		public function uwc_learndash_registration_errors( $registration_errors ) {
			$errors_conditions = $this->uwc_get_captcha_error_code_messages();
			foreach ( $errors_conditions as $param => $message ) {
				if ( isset( $_GET[ $param ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$registration_errors[ $param ] = $message;
				}
			}
			return $registration_errors;
		}
		/**
		 * Filters the redirect location URL.
		 *
		 * @since 1.1.7
		 * @param string $location The URL to redirect the user to.
		 * @param int    $status   The HTTP Status to set. Default 302.
		 * @param string $context  Unique string provided by the caller to help filter conditions.
		 * @return string The URL to redirect the user to.
		 */
		public function uwc_learndash_safe_redirect_location( $location, $status, $context ) {
			if ( false === $this->check_login_error || false === strpos( $location, 'login=failed' ) ) {
				return remove_query_arg( 'uwc_recaptcha', $location );
			}
			$get_result = $this->get_response_from_selected_captcha_method();
			if ( ! $get_result['response'] ) {
				$this->message = $get_result['errors'];
				$location      = add_query_arg( 'uwc_recaptcha', $get_result['error_code'], $location );
			} else {
				$location = remove_query_arg( 'uwc_recaptcha', $location );
			}
			return $location;
		}
		/**
		 * Filters LearnDash custom alert message text.
		 *
		 * @since 1.0.0
		 * @param string $message Alert message text.
		 * @param string $type    Alert message type.
		 * @param string $icon   List of alert icon CSS classes.
		 * @return string $message Alert message.
		 */
		public function uwc_learndash_alert_message( $message, $type, $icon ) {
			if ( isset( $_GET['uwc_recaptcha'] ) && isset( $_GET['login'] ) && 'failed' === $_GET['login'] ) {// @codingStandardsIgnoreLine.
				$message = $this->uwc_get_message( wp_unslash( $_GET['uwc_recaptcha'] ) );// @codingStandardsIgnoreLine.
			}
			return $message;
		}
		/**
		 * Add captcha error to user registration.
		 *
		 * @since 1.0.0
		 * @param WP_Error $errors               A WP_Error object containing any errors encountered.
		 * @param string   $sanitized_user_login User's username after it has been sanitized.
		 * @return int|WP_Error Either user's ID or error on failure.
		 */
		public function uwc_registration_errors( $errors, $sanitized_user_login ) {
			$get_result = $this->get_response_from_selected_captcha_method();
			if ( ! $get_result['response'] && false === $this->bypass_learndash_registrayion_filter ) {
				$this->message = $get_result['errors'];
				$errors->add( $get_result['error_code'], $this->message );
			}
			$this->bypass_learndash_registrayion_filter = true;
			return $errors;
		}
		/**
		 * Add captcha error to user login.
		 *
		 * @since 1.0.0
		 * @param WP_User|WP_Error $user     WP_User or WP_Error object if a previous.
		 * @param string           $password Password to check against the user.
		 * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
		 */
		public function uwc_authenticate( $user, $password ) {
			$get_result = $this->get_response_from_selected_captcha_method();
			if ( ! $get_result['response'] ) {
				$this->message           = $get_result['errors'];
				$this->check_login_error = true;
				return new WP_Error( 'captcha_error', $this->message );
			}
			return $user;
		}
		/**
		 * Display captcha error after comment form submission.
		 *
		 * @since 1.1.6
		 * @param array $commentdata Comment data.
		 */
		public function uwc_preprocess_comment( $commentdata ) {
			$get_result = $this->get_response_from_selected_captcha_method();
			if ( ! $get_result['response'] ) {
				$error_message = sprintf(
					'<strong>%1$s</strong>:&nbsp;%2$s&nbsp;%3$s',
					__( 'Error', 'ultimate-wp-captcha' ),
					$get_result['errors'],
					__( 'Click the Back button on your Web browser and try again.', 'ultimate-wp-captcha' )
				);
				wp_die( $error_message ); // @codingStandardsIgnoreLine.
			}
			return $commentdata;
		}
		/**
		 * Get the which captcha method is being used.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_response_from_selected_captcha_method() {
			$uwc_setting_data = uwc_get_option();
			$get_result       = array( 'response' => true );
			if ( ! isset( $_POST["g-recaptcha-response"] ) ) { // @codingStandardsIgnoreLine.
				return $get_result;
			}
			if ( isset( $uwc_setting_data['captcha_method'] ) && 'google' === $uwc_setting_data['captcha_method'] ) {
				if ( empty( $uwc_setting_data['google_site_key'] ) ) {
					return $get_result;
				}
				$get_result = $this->uwc_google_recaptcha_verify_response();
			} else {
				if ( empty( $uwc_setting_data['hcaptcha_site_key'] ) ) {
					return $get_result;
				}
				$get_result = $this->uwc_hcaptcha_verify_response();
			}
			return $get_result;
		}

		/**
		 * Verify hCaptcha response.
		 *
		 * @since 1.0.0
		 * @return bool false|true
		 */
		public function uwc_hcaptcha_verify_response() {
			$sanitized_response = ! empty( $_POST["g-recaptcha-response"] ) ? uwc_clean( wp_unslash( $_POST["g-recaptcha-response"] ) ) : '';// @codingStandardsIgnoreLine.
			if ( ! isset( $sanitized_response ) || empty( $sanitized_response ) ) {
				$result = array(
					'response'   => false,
					'errors'     => $this->uwc_get_message( 'recaptcha-empty' ),
					'error_code' => 'recaptcha-empty',
				);
				return $result;
			}
			$uwc_setting_data = uwc_get_option();
			$result           = wp_remote_get(
				'https://hcaptcha.com/siteverify?secret=' .
				esc_html( $uwc_setting_data['hcaptcha_site_secret'] ) . '&response=' . $sanitized_response
			);
			return $this->get_capthca_message_after_validtaion( $result );
		}

		/**
		 * Verify google recaptcha response.
		 *
		 * @since 1.0.0
		 * @return bool false|true
		 */
		public function uwc_google_recaptcha_verify_response() {
			$sanitized_response = ! empty( $_POST["g-recaptcha-response"] ) ? uwc_clean( wp_unslash( $_POST["g-recaptcha-response"] ) ) : '';// @codingStandardsIgnoreLine.
			if ( ! isset( $sanitized_response ) || empty( $sanitized_response ) ) {
				$result = array(
					'response'   => false,
					'errors'     => $this->uwc_get_message( 'recaptcha-empty' ),
					'error_code' => 'recaptcha-empty',
				);
				return $result;
			}
			$remoteip         = $this->uwc_get_the_user_ip();
			$uwc_setting_data = uwc_get_option();
			$payload          = array(
				'secret'   => $uwc_setting_data['google_site_secret'],
				'response' => $sanitized_response,
				'remoteip' => $remoteip,
			);
			$result           = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array( 'body' => $payload ) );
			return $this->get_capthca_message_after_validtaion( $result );
		}
		/**
		 * Return captcha submission message whether it was success or not.
		 *
		 * @since 1.0.0
		 * @param string $result result obtain from the remote server after captcha submission.
		 * @return array captcha message and response code.
		 */
		public function get_capthca_message_after_validtaion( $result ) {
			if ( is_wp_error( $result ) ) {
				$error_msg  = $result->get_error_message();
				$get_result = array(
					'response' => false,
					'errors'   => $error_msg,
				);
				return $get_result;
			}
			$get_body   = wp_remote_retrieve_body( $result );
			$g_response = json_decode( $get_body, true );
			if ( isset( $g_response['success'] ) && true === $g_response['success'] ) {
				$get_result = array(
					'response' => true,
					'errors'   => '',
				);
			} else {
				$get_result = array(
					'response' => false,
					'reason'   => 'VERIFICATION_FAILED',
				);
			}

			if ( ! $get_result['response'] ) {
				$get_error_code           = isset( $g_response['error-codes'][0] ) ? $g_response['error-codes'][0] : 'incorrect';
				$get_result['errors']     = $this->uwc_get_message( $get_error_code );
				$get_result['error_code'] = $get_error_code;
			}
			$get_result = apply_filters( 'uwc_captha_response_message', $get_result );
			return $get_result;
		}
		/**
		 * Returns the captcha error message with error code.
		 *
		 * @since 1.1.7
		 * @return array Returned error message.
		 */
		public function uwc_get_captcha_error_code_messages() {
			$messages = array(
				'recaptcha-empty'                  => __( 'Please complete the captcha.', 'ultimate-wp-captcha' ),
				'missing-input-secret'             => __( 'The secret parameter is missing.', 'ultimate-wp-captcha' ),
				'invalid-input-secret'             => sprintf(
					'<strong>%s</strong> <a target="_blank" href="https://www.google.com/recaptcha/admin#list">%s</a> %s.',
					__( 'Secret Key is invalid.', 'ultimate-wp-captcha' ),
					__( 'Check your domain configurations', 'ultimate-wp-captcha' ),
					__( 'and enter it again', 'ultimate-wp-captcha' )
				),
				'missing-input-response'           => __( 'The response parameter is missing.', 'ultimate-wp-captcha' ),
				'invalid-input-response'           => __( 'The response parameter is invalid or malformed.', 'ultimate-wp-captcha' ),
				'bad-request'                      => __( 'The request is invalid or malformed.', 'ultimate-wp-captcha' ),
				'timeout-or-duplicate'             => __( 'The response is no longer valid: either is too old or has been used previously.', 'ultimate-wp-captcha' ),
				'incorrect'                        => __( 'You have entered an incorrect reCAPTCHA value.', 'ultimate-wp-captcha' ),
				'invalid-or-already-seen-response' => __( 'The response parameter has already been checked, or has another issue.', 'ultimate-wp-captcha' ),
				'not-using-dummy-passcode'         => __( 'You have used a testing sitekey but have not used its matching secret.', 'ultimate-wp-captcha' ),
				'sitekey-secret-mismatch'          => __( 'The sitekey is not registered with the provided secret.', 'ultimate-wp-captcha' ),
			);
			return $messages;
		}
		/**
		 * Retrieve the captcha error message as per the error code.
		 *
		 * @since 1.0.0
		 * @param  string $message_code used to retrieve the corresponding message.
		 * @return string $message      Returned error message.
		 */
		public function uwc_get_message( $message_code = 'incorrect' ) {
			$messages = $this->uwc_get_captcha_error_code_messages();
			if ( isset( $messages[ $message_code ] ) ) {
				$message = $messages[ $message_code ];
			} else {
				$message = $messages['incorrect'];
			}
			return $message;
		}
		/**
		 * Get visitor IP address.
		 *
		 * @since 1.0.0
		 * @return string|null
		 */
		public function uwc_get_the_user_ip() {
			if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
				return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
			} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
				// Make sure we always only send through the first IP in the list which should always be the client IP.
				return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
			} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
				return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
			}
			return '';
		}
	}
	new UWC_Captcha_Form_Action_Handler();
}
