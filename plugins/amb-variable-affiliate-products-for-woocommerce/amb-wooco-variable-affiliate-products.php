<?php

/**
 * @wordpress-plugin
 * Plugin Name:       AMB Variable Affiliate Products for WooCommerce
 * Plugin URI:        http://wpguild.com/
 * Description:       Make variable products behave like External/Affiliate products. Make the buy button redirect to your affiliate, and customize the buy button text!
 * Version:           1.0.2
 * Author:  		  WP Guild
 * Author URI:        http://wpguild.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WC requires at least: 3.5.0
 * WC tested up to: 8.0
 */

defined( 'ABSPATH' ) or die( 'No.' );

// ****************************
// ******* CONSTANTS **********
// ****************************

// Root directory for the plugin.
define( 'AMB_WCVAP_ROOT_DIR', str_replace( '\\', '/', dirname( __FILE__ ) ) );

// Path to the main plugin file.
define( 'AMB_WCVAP_PLUGIN_PATH', AMB_WCVAP_ROOT_DIR . '/' . basename( __FILE__ ) );

// ****************************
// ****** END CONSTANTS *******
// ****************************

if ( ! class_exists( 'AMB_WPVap_Plugin' ) ) {
	class AMB_WPVap_Plugin {
		public function __construct() {
			$this::includes();
			$this->add_hooks();
		}

		public function add_hooks() {
			$prod = new AMB_WC_Product_Variable_Affiliate();

			// Parent custom fields
			add_action( 'woocommerce_product_options_inventory_product_data', array( $prod, 'add_custom_fields' ) );
			add_action( 'woocommerce_update_product', array( $prod, 'after_save_processes' ), 10, 1 );

			// Variation custom fields
			add_action( 'woocommerce_product_after_variable_attributes', array( $prod, 'add_variation_custom_fields' ), 10, 3 );
			add_action( 'woocommerce_save_product_variation', array( $prod, 'after_save_variation_processes' ), 10, 2 );

			// Admin notice and scripts
			add_action( 'admin_notices', array( $prod, 'admin_notices' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'main_js' ) );

			// For the custom cart text, redirects, and add to cart message.
			add_filter( 'woocommerce_product_single_add_to_cart_text', array( $prod, 'cart_text' ), 20, 1 );
            add_filter( 'woocommerce_add_to_cart_redirect', array( $prod, 'redirect' ) );
			add_filter( 'wc_add_to_cart_message_html', array( $prod, 'remove_add_to_cart_message' ), 10, 2 );
			
			// WooCommerce Settings
			add_filter( 'woocommerce_product_settings', array( $prod, 'importing_mode_option' ), 10, 1 );
		}

		public static function includes() {
			include_once( plugin_dir_path( __FILE__ ) . 'includes/amb-wcvap-functions.php' );
		}

		public function main_js() {
			$current_screen = get_current_screen();

			// Check that we're on the product page
			if ( ( $current_screen->id == "product" ) ) {

				wp_enqueue_script( 'amb-wpvap-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), '1.0.0', true );
				wp_register_style( 'amb-wpvap-stylesheet', plugins_url( 'css/amb-wpvap-css.css', __FILE__ ), false, '1.0.0' );
-        		wp_enqueue_style( 'amb-wpvap-stylesheet' );

			}
		}
	}
}

$AMB_wpVAP = new AMB_WPVap_Plugin();