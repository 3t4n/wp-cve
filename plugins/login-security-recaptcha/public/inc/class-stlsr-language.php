<?php
defined( 'ABSPATH' ) || die();

class STLSR_Language {
	public static function load_translation() {
		load_plugin_textdomain( 'login-security-recaptcha', false, basename( STLSR_PLUGIN_DIR_PATH ) . '/languages' );
	}
}
