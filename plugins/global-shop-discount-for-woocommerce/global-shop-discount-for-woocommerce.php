<?php
/*
Plugin Name: Global Shop Discount for WooCommerce
Plugin URI: https://wpfactory.com/item/global-shop-discount-for-woocommerce/
Description: Add global shop discount to all WooCommerce products. Beautifully.
Version: 1.9.6
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: global-shop-discount-for-woocommerce
Domain Path: /langs
WC tested up to: 8.5
*/

defined( 'ABSPATH' ) || exit;

if ( 'global-shop-discount-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.9.1
	 * @since   1.4.0
	 */
	$plugin = 'global-shop-discount-for-woocommerce-pro/global-shop-discount-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		defined( 'ALG_WC_GLOBAL_SHOP_DISCOUNT_FILE_FREE' ) || define( 'ALG_WC_GLOBAL_SHOP_DISCOUNT_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_GLOBAL_SHOP_DISCOUNT_VERSION' ) || define( 'ALG_WC_GLOBAL_SHOP_DISCOUNT_VERSION', '1.9.6' );

defined( 'ALG_WC_GLOBAL_SHOP_DISCOUNT_FILE' ) || define( 'ALG_WC_GLOBAL_SHOP_DISCOUNT_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-global-shop-discount.php' );

if ( ! function_exists( 'alg_wc_global_shop_discount' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Global_Shop_Discount to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_global_shop_discount() {
		return Alg_WC_Global_Shop_Discount::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_global_shop_discount' );
