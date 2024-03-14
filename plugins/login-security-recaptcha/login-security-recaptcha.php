<?php
/**
 * Plugin Name: Login Security Captcha
 * Plugin URI: https://scriptstown.com/wordpress-plugins/login-security-recaptcha/
 * Description: Secure WordPress login, registration, and comment form with Google reCAPTCHA or Cloudflare Turnstile. Prevent Brute-force attacks and more.
 * Version: 1.6.2
 * Author: ScriptsTown
 * Author URI: https://scriptstown.com/
 * Text Domain: login-security-recaptcha
 * Requires at least: 5.0
 * Requires PHP: 7.0
 */

defined( 'ABSPATH' ) || die();

define( 'STLSR_PLUGIN_VERSION', '1.6.2' );
define( 'STLSR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'STLSR_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'STLSR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

final class STLSR_Login_Security_Recaptcha {
	private static $instance = null;

	private function __construct() {
		$this->initialize_hooks();
		$this->setup_database();
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function initialize_hooks() {
		if ( is_admin() ) {
			require_once STLSR_PLUGIN_DIR_PATH . 'admin/admin.php';
		}
		require_once STLSR_PLUGIN_DIR_PATH . 'public/public.php';
	}

	private function setup_database() {
		require_once STLSR_PLUGIN_DIR_PATH . 'admin/inc/class-stlsr-database.php';
		register_activation_hook( __FILE__, array( 'STLSR_Database', 'activation' ) );
		register_deactivation_hook( __FILE__, array( 'STLSR_Database', 'deactivation' ) );
	}
}
STLSR_Login_Security_Recaptcha::get_instance();
