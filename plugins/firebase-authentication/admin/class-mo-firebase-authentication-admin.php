<?php
/**
 * Firebase Authentication Admin Functions
 *
 * @package firebase-authentication
 */

/**
 * Files included
 */
require_once 'partials' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin-display.php';


/**
 * Firebase Authentication Admin account handler
 */
class  MO_Firebase_Authentication_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}


	/**
	 * Enqueue styles for admin dashboard
	 *
	 * @param mixed $hook default parameter for enqueue styles.
	 *
	 * @return [type]
	 *  @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		if ( 'toplevel_page_mo_firebase_authentication' !== $hook ) {
				return;
		}
		wp_enqueue_style( 'mo_firebase_auth_admin_bootstrap_style', plugins_url( 'css' . DIRECTORY_SEPARATOR . 'bootstrap.min.css', __FILE__ ), array(), $this->version, false );
		wp_enqueue_style( 'mo_firebase_auth_firebase_phone_style', plugins_url( 'css' . DIRECTORY_SEPARATOR . 'phone.min.css', __FILE__ ), array(), $this->version, false );
		wp_enqueue_style( 'mo_firebase_auth_settings_style', plugins_url( 'css' . DIRECTORY_SEPARATOR . 'style.min.css', __FILE__ ), array(), $this->version, false );
		wp_enqueue_style( 'mo_firebase_auth_fontawesome', plugins_url( 'css' . DIRECTORY_SEPARATOR . 'font-awesome.min.css', __FILE__ ), array(), $this->version, false );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param mixed $hook default parameter for enqueue scripts.
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_mo_firebase_authentication' !== $hook ) {
				return;
		}
		wp_enqueue_script( 'mo_firebase_auth_bootstrap_script', plugins_url( 'js' . DIRECTORY_SEPARATOR . 'bootstrap.min.js', __FILE__ ), array(), $this->version, false );
		wp_enqueue_script( 'mo_firebase_auth_custom_settings_script', plugins_url( 'js' . DIRECTORY_SEPARATOR . 'custom.min.js', __FILE__ ), array(), $this->version, false );
		wp_enqueue_script( 'mo_firebase_auth_firebase_phone_script', plugins_url( 'js' . DIRECTORY_SEPARATOR . 'phone.min.js', __FILE__ ), array(), $this->version, false );
	}

	/**
	 * Initial render for firebase admin dashboardF
	 *
	 * @return void
	 */
	public function mo_firebase_auth_page() {
		global $wpdb;
		update_option( 'mo_fb_host_name', 'https://login.xecurify.com' );
		mo_firebase_authentication_main_menu();
	}

}
