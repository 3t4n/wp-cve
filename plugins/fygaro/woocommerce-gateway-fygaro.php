<?php
/**
 * Plugin Name: Fygaro Payment Gateway
 * Plugin URI: https://fygaro.com/w/plugins/
 * Description: Take secure payments on your WooCommerce store with Fygaro. -Beta-.
 * Version: 0.0.8
 *
 * Author: Fygaro
 * Author URI: https://fygaro.com/
 *
 * Text Domain: woocommerce-gateway-fygaro
 * Domain Path: /i18n/languages/
 *
 * Requires at least: 4.2
 * Tested up to: 6.4
 *
 * Copyright: © 2023 Fygaro
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fygaro Payment gateway plugin class.
 *
 * @class WC_Fygaro_Payments
 */
class WC_Fygaro_Payments {

	/**
	 * Plugin bootstrapping.
	 */
	public static function init() {

		// Fygaro Payments gateway class.
		add_action( 'plugins_loaded', array( __CLASS__, 'includes' ), 0 );

		// Make the Fygaro Payments gateway available to WC.
		add_filter( 'woocommerce_payment_gateways', array( __CLASS__, 'add_gateway' ) );

		// Registers WooCommerce Blocks integration.
		add_action( 'woocommerce_blocks_loaded', array( __CLASS__, 'woocommerce_gateway_fygaro_woocommerce_block_support' ) );

	}

	/**
	 * Add the Fygaro Payment gateway to the list of available gateways.
	 *
	 * @param array
	 */
	public static function add_gateway( $gateways ) {
		$gateways[] = 'WC_Gateway_Fygaro';
		return $gateways;
	}

	/**
	 * Plugin includes.
	 */
	public static function includes() {

		// Make the WC_Gateway_Fygaro class available.
		if ( class_exists( 'WC_Payment_Gateway' ) ) {
			require_once 'includes/class-wc-gateway-fygaro.php';
		}
	}

	/**
	 * Plugin url.
	 *
	 * @return string
	 */
	public static function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Plugin url.
	 *
	 * @return string
	 */
	public static function plugin_abspath() {
		return trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Registers WooCommerce Blocks integration.
	 *
	 */
	public static function woocommerce_gateway_fygaro_woocommerce_block_support() {
		if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			require_once dirname(__FILE__) . '/includes/blocks/class-wc-fygaro-payments-blocks.php';
			add_action(
				'woocommerce_blocks_payment_method_type_registration',
				function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
					$payment_method_registry->register( new WC_Gateway_Fygaro_Blocks_Support );
				}
			);
		}
	}
}

WC_Fygaro_Payments::init();
