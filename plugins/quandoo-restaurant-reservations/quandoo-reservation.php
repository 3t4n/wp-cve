<?php
/**
 * Plugin Name: Quandoo Restaurant Reservations
 * Plugin URI: https://sites.quandoo.com/website-product/wordpress-plugin/
 * Description: The official Quandoo restaurant reservation plugin. Quickly integrate a button or calendar widget to start receiving reservations straight away.
 * Version: 1.1.1
 * Author: Quandoo
 * Tags: quandoo, restaurant, reservation, restaurant reservation, restaurant booking, booking system, reservation system, book, widget
 * Author URI: https://www.quandoo.com/
 * Requires at least: 4.0.0
 * Tested up to: 5.1
 *
 * Text Domain: quandoo-restaurant-reservations
 * Domain Path: /languages
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function Quandoo_Reservation() {
	return Quandoo_Reservation::instance();
} // End Quandoo_Reservation()

add_action( 'plugins_loaded', 'Quandoo_Reservation' );


final class Quandoo_Reservation {
	
	private static $_instance = null;
	public $token;
	public $version;
	public $plugin_url;
	public $plugin_path;
	public $admin;
	public $settings;
	// Admin - End

	// Post Types - Start
	public $post_types = array();

	public function __construct () {
		$this->token 			= 'quandoo-reservation';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.0';

		// Admin - Start
		require_once( 'classes/class-quandoo-reservation-settings.php' );
			$this->settings = Quandoo_Reservation_Settings::instance();

		if ( is_admin() ) {
			require_once( 'classes/class-quandoo-reservation-admin.php' );
			$this->admin = Quandoo_Reservation_Admin::instance();
		}
		// Admin - End

		// Post Types - Start
		require_once( 'classes/class-quandoo-reservation-post-type.php' );
	

		$this->post_types['quandoo-reservation'] = new Quandoo_Reservation_Post_Type( 'quandoo-reservation', __( 'Widgets', 'quandoo-reservation' ), __( 'Widgets', 'quandoo-reservation' ), array( 'menu_icon' => 'dashicons-calendar-alt' ) );
		// Post Types - End
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	} // End __construct()

	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'quandoo-reservation', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	} // End load_plugin_textdomain()


	public function install () {
		$this->_log_version_number();
	} // End install()

} // End Class
