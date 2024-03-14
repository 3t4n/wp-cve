<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

new Math_Captcha_Update();

class Math_Captcha_Update {

	public function __construct() {
		// actions
		add_action( 'init', array( $this, 'check_update' ) );
	}

	/**
	 * Check update.
	 */
	public function check_update() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) )
			return;

		// gets current database version
		$current_db_version = get_option( 'math_captcha_version', '1.0.0' );

		// new version?
		if ( version_compare( $current_db_version, Math_Captcha()->defaults['version'], '<' ) ) {
			if ( version_compare( $current_db_version, '1.0.9', '<' ) ) {
				update_option( 'math_captcha_options', Math_Captcha()->options['general'] );
				delete_option( 'mc_options' );
			}

			// updates plugin version
			update_option( 'math_captcha_version', Math_Captcha()->defaults['version'] );
		}
	}

}