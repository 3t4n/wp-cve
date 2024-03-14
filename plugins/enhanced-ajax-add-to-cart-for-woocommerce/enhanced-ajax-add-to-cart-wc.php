<?php

/**
 * The plugin bootstrap file
 *
 * @link              www.theritesites.com
 * @since             1.0.0
 * @package           Enhanced_Ajax_Add_To_Cart_Wc
 *
 * @wordpress-plugin
 * Plugin Name:       Enhanced AJAX Add to Cart for WooCommerce
 * Plugin URI:        https://www.theritesites.com/plugins/enhanced-ajax-add-to-cart-wc
 * Description:       Creates a shortcode or block for you to be able to add an AJAX button with an associated quantity for you WooCommerce Product
 * Version:           2.3.0
 * Author:            TheRiteSites
 * Author URI:        https://www.theritesites.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       enhanced-ajax-add-to-cart-wc
 * Domain Path:       /languages
 * WC tested up to: 5.0
 * WC requires at least: 3.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'is_woocommerce_active' ) || ! function_exists( 'is_eaa2c_premium_active' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Current plugin version.
 */
defined( 'ENHANCED_AJAX_ADD_TO_CART' ) || define( 'ENHANCED_AJAX_ADD_TO_CART', '2.3.0' );
defined( 'EAA2C_PLUGIN_FILE' ) || define( 'EAA2C_PLUGIN_FILE', __FILE__ );

/**
 * The code that runs during plugin activation.
 * This action is documented in src/class-eaa2c-activator.php
 */
if ( ! function_exists( 'activate_enhanced_ajax_add_to_cart_wc' ) ) {
	function activate_enhanced_ajax_add_to_cart_wc() {
		require_once plugin_dir_path( __FILE__ ) . 'src/class-eaa2c-activator.php';
		Enhanced_Ajax_Add_To_Cart_Wc_Activator::activate();
	}
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in src/class-eaa2c-deactivator.php
 */
if ( ! function_exists( 'deactivate_enhanced_ajax_add_to_cart_wc' ) ) {
	function deactivate_enhanced_ajax_add_to_cart_wc() {
		require_once plugin_dir_path( __FILE__ ) . 'src/class-eaa2c-deactivator.php';
		Enhanced_Ajax_Add_To_Cart_Wc_Deactivator::deactivate();
	}
}

register_activation_hook( __FILE__, 'activate_enhanced_ajax_add_to_cart_wc' );
register_deactivation_hook( __FILE__, 'deactivate_enhanced_ajax_add_to_cart_wc' );

if ( ! class_exists( 'Enhanced_Ajax_Add_To_Cart_Wc' ) ) {
	include_once dirname( EAA2C_PLUGIN_FILE ) . '/src/class-enhanced-ajax-add-to-cart-wc.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
use \TRS\EAA2C;
if ( ! function_exists( 'run_enhanced_ajax_add_to_cart_wc' ) && true !== is_a2cp_active() ) {
	function run_enhanced_ajax_add_to_cart_wc() {

		$plugin = new Enhanced_Ajax_Add_To_Cart_Wc();
		$plugin->run();

	}
	run_enhanced_ajax_add_to_cart_wc();
}
