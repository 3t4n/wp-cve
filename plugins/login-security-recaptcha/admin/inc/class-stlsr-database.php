<?php
defined( 'ABSPATH' ) || die();

class STLSR_Database {
	/**
	 * Plugin activation.
	 *
	 * @return void
	 */
	public static function activation() {
		add_option( 'stlsr_redirect_to_settings', true );
	}

	/**
	 * Plugin deactivation.
	 *
	 * @return void
	 */
	public static function deactivation() {
		delete_option( 'stlsr_redirect_to_settings' );
	}
}
