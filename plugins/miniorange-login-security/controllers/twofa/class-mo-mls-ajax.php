<?php
/**
 * This file contains the ajax request handler.
 *
 * @package miniorange-login-security/controllers/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Mo_Mls_Ajax' ) ) {
	/**
	 * Class Mo_Mls_Ajax
	 */
	class Mo_Mls_Ajax {
		/**
		 * Constructor of class.
		 */
		public function __construct() {

			add_action( 'admin_init', array( $this, 'momls_2f_two_factor' ) );
		}
		/**
		 * Function for handling ajax requests.
		 *
		 * @return void
		 */
		public function momls_2f_two_factor() {
			add_action( 'wp_ajax_momls_two_factor_ajax', array( $this, 'momls_two_factor_ajax' ) );
		}
		/**
		 * Call functions as per ajax requests.
		 *
		 * @return void
		 */
		public function momls_two_factor_ajax() {
			if ( ! check_ajax_referer( 'momls_two_factor_nonce', 'nonce', false ) ) {
				wp_send_json_error( 'class-mo2f-ajax' );
			}
			switch ( isset( $_POST['momls_2f_two_factor_ajax'] ) ? sanitize_text_field( wp_unslash( $_POST['momls_2f_two_factor_ajax'] ) ) : '' ) {
				case 'momls_unlimitted_user':
					$this->momls_unlimitted_user();
					break;
				case 'momls_shift_to_onprem':
					$this->momls_shift_to_onprem();
					break;
				case 'momls_dismiss_button':
					$this->momls_dismiss_button();
					break;
			}
		}
		/**
		 * Function to shift user to Onpremise.
		 *
		 * @return void
		 */
		private function momls_shift_to_onprem() {
			update_site_option( 'is_onprem', 1 );
			wp_send_json( 'true' );
		}
		/**
		 * Function to hide feedback configuration from user
		 *
		 * @return boolean
		 */
		private function momls_dismiss_button() {
			update_site_option( 'donot_show_feedback_message', true );
			return true;
		}
		/**
		 * Function to check if On-premise is active or not.
		 *
		 * @return void
		 */
		private function momls_unlimitted_user() {
			$nonce = isset( $_POST['nonce'] ) && sanitize_key( wp_unslash( $_POST['nonce'], ) );
			if ( ! check_ajax_referer( 'unlimittedUserNonce', $nonce, false ) ) {
				echo 'NonceDidNotMatch';
				exit;
			} else {
				if ( isset( $_POST['enableOnPremise'] ) && sanitize_text_field( wp_unslash( $_POST['enableOnPremise'] ) ) === 'on' ) {
					global $wp_roles;
					foreach ( $wp_roles->role_names as $id => $name ) {
						add_site_option( 'mo2fa_' . $id, 1 );
						if ( 'administrator' === $id ) {
							add_site_option( 'mo2fa_' . $id . '_login_url', admin_url() );
						} else {
							add_site_option( 'mo2fa_' . $id . '_login_url', home_url() );
						}
					}
					echo 'OnPremiseActive';
					exit;
				} else {
					echo 'OnPremiseDeactive';
					exit;
				}
			}
		}

	}

	new Mo_Mls_Ajax();
}

