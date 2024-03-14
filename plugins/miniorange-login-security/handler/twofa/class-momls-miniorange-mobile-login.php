<?Php
/** This file contains functions regarding mobile login or passwordless login.
 *
 * @package miniorange-login-security/handler/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * This library is miniOrange Authentication Service.
 * Contains Request Calls to Customer service.
 */
require dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'momls-common-login.php';
DEFINE( 'DS', DIRECTORY_SEPARATOR );
if ( ( ! class_exists( 'Momls_Miniorange_Mobile_Login' ) ) ) {
	/**
	 * Mobile Login class
	 */
	class Momls_Miniorange_Mobile_Login {
		/**
		 * Default login
		 *
		 * @param object $user - User Object.
		 * @param string $user_name - Username of the user.
		 * @param string $password - Password of the user.
		 * @return return current user.
		 */
		public function momls_default_login( $user, $user_name, $password ) {

			global $momlsdb_queries;
			$currentuser = wp_authenticate_username_password( $user, $user_name, $password );
			if ( is_wp_error( $currentuser ) ) {
				return $currentuser;
			} else {
				if ( get_site_option( 'is_onprem' ) && ( ! get_site_option( 'mo2f_login_policy' ) || get_site_option( 'mo2f_enable_login_with_2nd_factor' ) ) ) {
					$mo2f_configured_2_f_a_method = get_user_meta( $currentuser->ID, 'currentMethod', true );
					$session_id                   = isset( $_POST['miniorange_user_session'] ) ? sanitize_text_field( wp_unslash( $_POST['miniorange_user_session'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification.Missing -- Getting called after authentication hook.
					$redirect_to                  = isset( $_REQUEST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Getting called after authentication hook.
					$handle_second_factor         = new Momls_Miniorange_Password_2Factor_Login();
					if ( is_null( $session_id ) ) {
						$session_id = $handle_second_factor->momls_create_session();
					}

					$key   = get_site_option( 'mo2f_customer_token' );
					$error = $handle_second_factor->momls_initiate_2nd_factor( $currentuser, $redirect_to, $session_id );

				}
				$this->momls_login_start_session();
				$pass2fa_login_session        = new Momls_Miniorange_Password_2Factor_Login();
				$session_id                   = $pass2fa_login_session->momls_create_session();
				$mo2f_configured_2_f_a_method = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $currentuser->ID );
				$redirect_to                  = isset( $_REQUEST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- It is on default WordPress login form.
				if ( $mo2f_configured_2_f_a_method ) {
					$mo2f_user_email               = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $currentuser->ID );
					$mo2f_user_registration_status = $momlsdb_queries->momls_get_user_detail( 'mo_2factor_user_registration_status', $currentuser->ID );
					if ( $mo2f_user_email && 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo2f_user_registration_status ) { // checking if user has configured any 2nd factor method.
						Momls_Utility::momls_set_user_values( $session_id, 'mo2f_login_message', '<strong>ERROR</strong>: Login with password is disabled for you. Please Login using your phone.' );
						$this->momls_auth_show_error_message();
						$this->momls_redirectto_wp_login();
						$error = new WP_Error();
						return $error;
					} else { // if user has not configured any 2nd factor method then logged him in without asking 2nd factor.
						$this->momls_verify_and_authenticate_userlogin( $currentuser, $redirect_to, $session_id );
					}
				} else { // plugin is not activated for non-admin then logged him in.
					$this->momls_verify_and_authenticate_userlogin( $currentuser, $redirect_to, $session_id );
				}
			}
		}
		/**
		 * This is function is to create the session.
		 */
		private function momls_login_start_session() {
			if ( ! session_id() || empty( session_id() ) || ! isset( $_SESSION ) ) {
				session_start();
			}
		}
		/**
		 * This function displays the error messages.
		 *
		 * @param string $value carry a value.
		 * @return void
		 */
		public function momls_auth_show_error_message( $value = null ) {
			remove_filter( 'login_message', array( $this, 'momls_auth_success_message' ) );
			add_filter( 'login_message', array( $this, 'momls_auth_error_message' ) );
		}
		/**
		 * This function is useful to redirect to default WordPress login form incase 2FA is unset.
		 *
		 * @return void
		 */
		public function momls_redirectto_wp_login() {
			global $momlsdb_queries;
			$pass2fa_login_session = new Momls_Miniorange_Password_2Factor_Login();
			$session_id            = $pass2fa_login_session->momls_create_session();
			remove_action( 'login_enqueue_scripts', array( $this, 'momls_2_factor_hide_login' ) );
			if ( get_site_option( 'mo2f_enable_login_with_2nd_factor' ) ) {
				Momls_Utility::momls_set_user_values( $session_id, 'mo_2factor_login_status', 'MO_2_FACTOR_LOGIN_WHEN_PHONELOGIN_ENABLED' );
			} else {
				Momls_Utility::momls_set_user_values( $session_id, 'mo_2factor_login_status', 'MO_2_FACTOR_SHOW_USERPASS_LOGIN_FORM' );
			}
		}
		/**
		 * This function is useful to authenticate on login.
		 *
		 * @param object $user - User Object.
		 * @param string $redirect_to - URL to which user should be redirected.
		 * @param string $session_id - Encrypted session id.
		 * @return void
		 */
		private function momls_verify_and_authenticate_userlogin( $user, $redirect_to = null, $session_id = null ) {
			$user_id = $user->ID;
			wp_set_current_user( $user_id, $user->user_login );
			$this->momls_remove_current_activity( $session_id );
			wp_set_auth_cookie( $user_id, true );
			do_action( 'wp_login', $user->user_login, $user );
			momls_redirect_user_to( $user, $redirect_to );
			exit;
		}
		/**
		 * This function is useful for removing the current activity stoed in sessionand cookie.
		 *
		 * @param string $session_id - Encrypted session id.
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
			Momls_Utility::momls_unset_temp_user_details_in_table( null, $session_id, 'destroy' );
		}
		/**
		 * This function enqueues custom login script.
		 *
		 * @return void
		 */
		public function momls_custom_login_enqueue_scripts() {
			wp_enqueue_script( 'jquery' );
			$bootstrappath = plugins_url( 'includes/css/bootstrap.min.css', dirname( dirname( __FILE__ ) ) );
			$bootstrappath = str_replace( '/handler/includes/css', '/includes/css', $bootstrappath );
			wp_enqueue_style( 'bootstrap_script', $bootstrappath, array(), MO2F_VERSION );
			wp_enqueue_script( 'bootstrap_script', plugins_url( 'includes/js/bootstrap.min.js', dirname( dirname( __FILE__ ) ) ), array(), MO2F_VERSION, false );
		}
		/**
		 * This function is useful for hide login form.
		 *
		 * @return void
		 */
		public function momls_2_factor_hide_login() {
			$bootstrappath = plugins_url( 'includes/css/bootstrap.min.css', dirname( dirname( __FILE__ ) ) );
			$bootstrappath = str_replace( '/handler/includes/css', '/includes/css', $bootstrappath );
			$hidepath      = plugins_url( 'includes/css/hide-login-form.min.css', dirname( dirname( __FILE__ ) ) );
			$hidepath      = str_replace( '/handler/includes/css', '/includes/css', $hidepath );

			wp_register_style( 'hide-login', $hidepath, array(), MO2F_VERSION );
			wp_register_style( 'bootstrap', $bootstrappath, array(), MO2F_VERSION );
			wp_enqueue_style( 'hide-login' );
			wp_enqueue_style( 'bootstrap' );

		}
		/**
		 * This function displays the success messages.
		 *
		 * @return string
		 */
		public function momls_auth_success_message() {
			$message = isset( $_SESSION['mo2f_login_message'] ) ? sanitize_text_field( $_SESSION['mo2f_login_message'] ) : '';

			// if the php session folder has insufficient permissions, cookies to be used.
			$message = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_login_message' );

			if ( ! empty( $message ) ) {
				$message = 'Please login into your account using password.';
			}

			return "<div> <p class='message'>" . sanitize_text_field( $message ) . '</p></div>';
		}
		/**
		 * This function displaky error message
		 *
		 * @return string
		 */
		public function momls_auth_error_message() {
			$id      = 'login_error';
			$message = isset( $_SESSION['mo2f_login_message'] ) ? sanitize_text_field( $_SESSION['mo2f_login_message'] ) : '';
			// if the php session folder has insufficient permissions, cookies to be used.
			$message = Momls_Utility::momls_retrieve_user_temp_values( 'mo2f_login_message' );
			if ( ! empty( $message ) ) {
				$message = 'Invalid Username';
			}
			if ( get_site_option( 'momls_wpns_activate_recaptcha_for_login' ) ) {
				$message = 'Invalid Username or recaptcha';
			}
			return "<div id='" . sanitize_text_field( $id ) . "'> <p>" . sanitize_text_field( $message ) . '</p></div>';
		}
		/**
		 * This function is use to show the success message.
		 *
		 * @param string $message - Success message to be shown to the user.
		 * @return void
		 */
		public function momls_auth_show_success_message( $message = '' ) {
			remove_filter( 'login_message', array( $this, 'momls_auth_error_message' ) );
			add_filter( 'login_message', array( $this, 'momls_auth_success_message' ) );
		}
		// login form fields.
		/**
		 * This function have login footer
		 *
		 * @return void
		 */
		public function momls_login_footer_form() {

			?>
		<input type="hidden" name="miniorange_login_nonce"
		value="<?php echo esc_attr( wp_create_nonce( 'miniorange-2-factor-login-nonce' ) ); ?>"/>
		<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo esc_url( wp_login_url() ); ?>" hidden>
			<input type="hidden" name="miniorange_mobile_validation_failed_nonce"
			value="<?php echo esc_attr( wp_create_nonce( 'miniorange-2-factor-mobile-validation-failed-nonce' ) ); ?>"/>
		</form>
		<form name="f" id="mo2f_show_qrcode_loginform" method="post" action="" hidden>
			<input type="text" name="mo2fa_username" id="mo2fa_username" hidden/>
			<input type="text" name="g-recaptcha-response" id = 'g-recaptcha-response' hidden/>
			<input type="hidden" name="miniorange_login_nonce"
			value="<?php echo esc_attr( wp_create_nonce( 'miniorange-2-factor-login-nonce' ) ); ?>"/>	
		</form>
			<?php

		}
	}
}

?>
