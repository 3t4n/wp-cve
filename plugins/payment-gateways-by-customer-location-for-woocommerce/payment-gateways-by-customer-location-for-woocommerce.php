<?php
/*
Plugin Name: Payment Gateways by Customer Location for WooCommerce
Plugin URI: https://wpfactory.com/item/payment-gateways-by-customer-location-for-woocommerce/
Description: Set countries, states, cities or postcodes to include/exclude for WooCommerce payment gateways to show up.
Version: 1.5.4
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: payment-gateways-by-customer-location-for-woocommerce
Domain Path: /langs
WC tested up to: 8.1
*/

defined( 'ABSPATH' ) || exit;

if ( 'payment-gateways-by-customer-location-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	$plugin = 'payment-gateways-by-customer-location-for-woocommerce-pro/payment-gateways-by-customer-location-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

defined( 'ALG_WC_PGBCL_VERSION' ) || define( 'ALG_WC_PGBCL_VERSION', '1.5.4' );

defined( 'ALG_WC_PGBCL_FILE' ) || define( 'ALG_WC_PGBCL_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-pgbcl.php' );

if ( ! function_exists( 'alg_wc_gateways_by_location' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Payment_Gateways_by_Customer_Location to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_gateways_by_location() {
		return Alg_WC_Payment_Gateways_by_Customer_Location::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_gateways_by_location' );
