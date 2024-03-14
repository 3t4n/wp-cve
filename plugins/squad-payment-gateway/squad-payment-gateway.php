<?php

/**
 * Plugin Name: Squad Payment Gateway
 * Plugin URI: https://github.com/SquadInc/squad-wp-plugin
 * Author: Squad Developers
 * Author URI: http://squadco.com/
 * Description: Provides Seamless Payments with Multiple payment options.
 * Version: 1.0.8
 * Tested up to: 6.0.2
 * License: GPL2
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: squad-payment-gateway
 * 
 * Class WC_Gateway_Squad file.
 *
 * @package WooCommerce\Squad
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
define('WC_SQUAD_MAIN_FILE', __FILE__);
define('WC_SQUAD_VERSION', '1.0.6');

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return;

add_action('plugins_loaded', 'squad_payment_init', 11);
add_filter('woocommerce_currencies', 'sqaud_add_ngn_currencies');
add_filter('woocommerce_currency_symbol', 'sqaud_add_ngn_currencies_symbol', 10, 2);
add_filter('woocommerce_payment_gateways', 'add_to_woo_squad_payment_gateway', 99);

function squad_payment_init()
{
	if (class_exists('WC_Payment_Gateway')) {
		require_once plugin_dir_path(__FILE__) . '/includes/class-wc-payment-gateway-squad.php';
		// require_once plugin_dir_path( __FILE__ ) . '/includes/class-wc-gateway-squad-subscriptions.php';
	}
}

function add_to_woo_squad_payment_gateway($gateways)
{
	$gateways[] = 'WC_Gateway_Squad';
	return $gateways;
}

function sqaud_add_ngn_currencies($currencies)
{
	$currencies['NGN'] = __('Nigerian Naira', 'squad-payment-gateway');
	return $currencies;
}

function sqaud_add_ngn_currencies_symbol($currency_symbol, $currency)
{
	switch ($currency) {
		case 'NGN':
			$currency_symbol = 'NGN';
			break;
	}
	return $currency_symbol;
}
