<?php

/**
 * Plugin Name:       Woo Payment Discounts
 * Plugin URI:        http://www.wpcodelibrary.com
 * Description:       Setup discounts for specific payment methods is selected on checkout.
 * Version:           1.3.0
 * Author:            WPCodelibrary
 * Author URI:        http://www.wpcodelibrary.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-payment-discounts
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! defined( 'WPD_PLUGIN_URL' ) ) {
	define( 'WPD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}


if ( ! class_exists( 'Woo_Payment_Discounts' ) ) :

	/**
	 * Woo Discounts Per Payment Method Class.
	 */
	class Woo_Payment_Discounts {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		const VERSION = '1.3.0';

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin.
		 */
		private function __construct() {
			// Load plugin text domain.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
				$this->load_admin_files();
			}

			$this->load_dependancy();
		}

		/**
		 * Return an instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		protected function load_dependancy() {
			include_once( 'includes/class-woo-payment-add-discounts.php' );
		}

		protected function load_admin_files() {
			include_once( 'admin/class-woo-payment-discounts-admin.php' );
		}

		public static function activate() {
			add_option( 'woo_payment_discounts', array() );
			set_transient( '_wpd_screen_activation_redirect', true, 30 );
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'woo-payment-discounts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

	}

	/**
	 * Install plugin default options.
	 */
	register_activation_hook( __FILE__, array( 'Woo_Payment_Discounts', 'activate' ) );

	/**
	 * Initialize the plugin actions.
	 */
	add_action( 'plugins_loaded', array( 'Woo_Payment_Discounts', 'get_instance' ) );

endif;