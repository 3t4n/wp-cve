<?php
/**
 * Shipping Nova Poshta for WooCommerce
 *
 * Plugin Name: Shipping Nova Poshta for WooCommerce
 * Plugin URI:  http://wp-unit.com/
 * Description: Select a branch on the checkout page, the creation of electronic invoices, calculating shipping costs, COD payment, and much more ...
 * Version: 1.5.9
 * Author: WP Unit
 * Author URI: http://wp-unit.com/
 * License: GPLv2 or later
 * Text Domain: shipping-nova-poshta-for-woocommerce
 *
 * @package Shipping Nova Poshta for WooCommerce
 * @author  WP Punk
 *
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 4.6
 * WC tested up to: 8.6
 */

use NovaPoshta\Main;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOVA_POSHTA_LITE', true );

if ( ! defined( 'NOVA_POSHTA_FILE' ) ) {
	define( 'NOVA_POSHTA_FILE', __FILE__ );
}
if ( ! defined( 'NOVA_POSHTA_PATH' ) ) {
	define( 'NOVA_POSHTA_PATH', plugin_dir_path( NOVA_POSHTA_FILE ) );
}
if ( ! defined( 'NOVA_POSHTA_URL' ) ) {
	define( 'NOVA_POSHTA_URL', plugin_dir_url( NOVA_POSHTA_FILE ) );
}

if ( defined( 'NOVA_POSHTA_PRO' ) ) {
	add_action(
		'admin_init',
		static function () {

			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
}

if ( ! function_exists( 'nova_poshta_load' ) ) {
	/**
	 * Load the provider class.
	 *
	 * @since 1.0.0
	 */
	function nova_poshta_load() {

		// Check PHP version.
		if ( version_compare( PHP_VERSION, '7.0', '<' ) ) {
			$message = esc_html__( 'The Shipping for Nova Poshta plugin has been deactivated. Your site is running an outdated version of PHP that is no longer supported and is not compatible with our plugin.', 'shipping-nova-poshta-for-woocommerce' );
		}

		// Check WooCommerce version.
		if ( ! function_exists( 'WC' ) || version_compare( WC()->version, '4.9', '<' ) ) {
			$message = esc_html__( 'The Shipping for Nova Poshta plugin has been deactivated. Our plugin extends the WooCommerce plugin, but this plugin doesn\'t active.', 'shipping-nova-poshta-for-woocommerce' );
		}

		if ( ! empty( $message ) ) {
			echo sprintf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html( $message )
			);

			unset( $_GET['activate'] ); // phpcs:ignore

			add_action(
				'admin_init',
				static function () {

					deactivate_plugins( plugin_basename( __FILE__ ) );
				}
			);

			return;
		}

		// Load the plugin.
		nova_poshta();
	}

	add_action( 'plugins_loaded', 'nova_poshta_load' );
}

if ( ! function_exists( 'nova_poshta' ) ) {
	/**
	 * This function is useful to quickly grabbing data used throughout the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Main
	 */
	function nova_poshta(): Main {

		require_once NOVA_POSHTA_PATH . 'functions.php';
		require_once NOVA_POSHTA_PATH . 'vendor/autoload.php';

		return Main::get_instance();
	}
}

if ( ! function_exists( 'nova_poshta_activate' ) ) {
	/**
	 * Function for activate plugin.
	 */
	function nova_poshta_activate() {

		$db = nova_poshta()->make( 'DB' );
		$db->create();

		$settings = nova_poshta()->make( 'Settings\Settings' );
		if ( $settings->api_key() ) {
			$api = nova_poshta()->make( 'Api\Api' );
			$api->cities();
		}

		do_action( 'nova_poshta_install' );
	}

	register_activation_hook( __FILE__, 'nova_poshta_activate' );
}

if ( ! function_exists( 'nova_poshta_deactivate' ) ) {
	/**
	 * Function for deactivate plugin.
	 */
	function nova_poshta_deactivate() {

		$object_cache = nova_poshta()->make( 'Cache\ObjectCache' );
		$object_cache->flush();

		$transient_cache = nova_poshta()->make( 'Cache\TransientCache' );
		$transient_cache->flush();

		do_action( 'nova_poshta_uninstall' );
	}

	register_deactivation_hook( __FILE__, 'nova_poshta_deactivate' );
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
