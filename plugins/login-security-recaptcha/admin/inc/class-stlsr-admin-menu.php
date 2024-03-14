<?php
defined( 'ABSPATH' ) || die();

class STLSR_Admin_Menu {
	public static function register_assets() {
		wp_register_style( 'stlsr-admin', STLSR_PLUGIN_URL . 'assets/css/stlsr-admin.css', array(), STLSR_PLUGIN_VERSION, 'all' );
	}

	public static function create_menu() {
		$settings = add_options_page( esc_html__( 'Login Security Captcha', 'login-security-recaptcha' ), esc_html__( 'Login Security', 'login-security-recaptcha' ), 'manage_options', 'stlsr_settings', array( 'STLSR_Admin_Menu', 'settings' ) );
		add_action( 'admin_print_styles-' . $settings, array( 'STLSR_Admin_Menu', 'settings_assets' ) );

		$settings_submenu = add_submenu_page( 'stlsr_settings', esc_html__( 'Login Security Captcha', 'login-security-recaptcha' ), esc_html__( 'Login Security Captcha', 'login-security-recaptcha' ), 'manage_options', 'stlsr_settings', array( 'STLSR_Admin_Menu', 'settings' ) );
		add_action( 'admin_print_styles-' . $settings_submenu, array( 'STLSR_Admin_Menu', 'settings_assets' ) );
	}

	public static function settings() {
		require_once STLSR_PLUGIN_DIR_PATH . 'admin/inc/setting/index.php';
	}

	public static function settings_assets() {
		wp_enqueue_style( 'stlsr-admin' );
		wp_style_add_data( 'stlsr-admin', 'rtl', 'replace' );
		wp_enqueue_script( 'stlsr-admin', STLSR_PLUGIN_URL . 'assets/js/stlsr-admin.js', array( 'jquery', 'jquery-form' ), STLSR_PLUGIN_VERSION, true );
		wp_add_inline_script(
			'stlsr-admin',
			sprintf( 'var stlsradminurl = %s;', wp_json_encode( admin_url() ) ),
			'before'
		);
	}
}
