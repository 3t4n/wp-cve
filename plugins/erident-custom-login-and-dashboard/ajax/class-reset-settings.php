<?php
/**
 * Reset settings.
 *
 * @package Custom_Login_Dashboard
 */

namespace CustomLoginDashboard\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to handle ajax request of settings reset.
 */
class Reset_Settings {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_cldashboard_reset_settings', [ $this, 'reset' ] );

	}

	/**
	 * Reset settings.
	 */
	public function reset() {

		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$role  = isset( $_POST['type'] ) ? $_POST['type'] : 'all';

		if ( ! wp_verify_nonce( $nonce, 'cldashboard_nonce_reset_settings' ) ) {
			wp_send_json_error( __( 'Invalid token', 'erident-custom-login-and-dashboard' ), 401 );
		}

		if ( 'all' === $role ) {
			delete_option( 'plugin_erident_settings' );
		}

		wp_send_json_success( __( 'Settings have been reset', 'erident-custom-login-and-dashboard' ) );

	}

}
