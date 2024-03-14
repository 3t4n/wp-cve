<?php
/**
 * Plugin Name: WCBoost - Wishlist
 * Description: Our WooCommerce Wishlist plugin enables customers to create personalized collections of products that they like but aren't ready to purchase immediately. Enhance the shopping experience by saving products for further consideration, making decisions easier than ever.
 * Plugin URI: https://wcboost.com/plugin/woocommerce-wishlist/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author: WCBoost
 * Version: 1.0.10
 * Author URI: https://wcboost.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 *
 * Text Domain: wcboost-wishlist
 * Domain Path: /languages/
 *
 * Requires PHP: 7.0
 * Requires at least: 4.5
 * Tested up to: 6.4
 * WC requires at least: 3.0.0
 * WC tested up to: 8.5
 *
 * @package WCBoost
 * @category Wishlist
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/plugin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/install.php';

// Declare compatibility with WooCommerce features.
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * Load and init plugin's instance
 */
function wcboost_wishlist() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	return \WCBoost\Wishlist\Plugin::instance();
}

add_action( 'woocommerce_loaded', 'wcboost_wishlist' );

/**
 * Install plugin on activation
 */
function wcboost_wishlist_activate() {
	// Install the plugin.
	if ( class_exists( 'WooCommerce' ) ) {
		\WCBoost\Wishlist\Install::install();
	}
}

register_activation_hook( __FILE__,  'wcboost_wishlist_activate' );
