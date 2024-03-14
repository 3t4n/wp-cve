<?php
/**
 * Load default settings.
 *
 * @package Custom_Login_Dashboard
 */

namespace CustomLoginDashboard\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to handle ajax request of loading default settings.
 */
class Load_Default_Settings {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_cldashboard_load_default_settings', [ $this, 'load_default_settings' ] );

	}

	/**
	 * Load default settings.
	 */
	public function load_default_settings() {

		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! wp_verify_nonce( $nonce, 'cldashboard_nonce_load_default_settings' ) ) {
			wp_send_json_error( __( 'Invalid token', 'erident-custom-login-and-dashboard' ), 401 );
		}

		$default_settings = cldashboard_get_field_default_values();
		update_option( 'plugin_erident_settings', $default_settings );

		wp_send_json_success(
			[
				'message'  => __( 'Settings have been replaced', 'erident-custom-login-and-dashboard' ),
				'settings' => $default_settings,
			]
		);

	}

}
