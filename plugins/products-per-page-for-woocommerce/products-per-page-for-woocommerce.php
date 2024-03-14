<?php
/*
Plugin Name: Products per Page for WooCommerce
Plugin URI: https://wpfactory.com/item/products-per-page-woocommerce/
Description: Products per page selector for WooCommerce.
Version: 2.2.0
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: products-per-page-for-woocommerce
Domain Path: /langs
WC tested up to: 8.6
*/

defined( 'ABSPATH' ) || exit;

if ( 'products-per-page-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 2.2.0
	 * @since   1.6.0
	 */
	$plugin = 'products-per-page-for-woocommerce-pro/products-per-page-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		defined( 'ALG_WC_PRODUCTS_PER_PAGE_FILE_FREE' ) || define( 'ALG_WC_PRODUCTS_PER_PAGE_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_PRODUCTS_PER_PAGE_VERSION' ) || define( 'ALG_WC_PRODUCTS_PER_PAGE_VERSION', '2.2.0' );

defined( 'ALG_WC_PRODUCTS_PER_PAGE_FILE' ) || define( 'ALG_WC_PRODUCTS_PER_PAGE_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-products-per-page.php' );

if ( ! function_exists( 'alg_wc_products_per_page' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Products_Per_Page to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_products_per_page() {
		return Alg_WC_Products_Per_Page::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_products_per_page' );
