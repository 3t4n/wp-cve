<?php

use blocks\WC_Gateway_Mypos_Blocks_Support;

/**
 * Plugin Name: Woocommerce myPOS Checkout
 * Plugin URI:
 * Description: myPOS Checkout.
 * Version: 1.3.30
 * Author: myPOS Europe LTD
 * Author URI: https://www.mypos.com
 * Developer: Intercard Finance
 * Developer URI:
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!defined('MYPOS_PLUGIN_FILE')) {
	define('MYPOS_PLUGIN_FILE', __FILE__);
}

// Makes sure the plugin is defined before trying to use it
if (!function_exists('is_plugin_active_for_network')) {
	require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

// Include the MyPOS class.
if (!class_exists('MyPOS', false)) {
	$GLOBALS['mypos'] = include dirname(MYPOS_PLUGIN_FILE) . '/includes/class-mypos.php';
}

class WC_Mypos_Payments
{

	public static function init()
	{
		add_action('plugins_loaded', array(__CLASS__, 'includes'), 0);
		add_filter('woocommerce_payment_gateways', array(__CLASS__, 'add_gateway'));
		add_action('woocommerce_blocks_loaded', array(__CLASS__, 'woocommerce_gateway_mypos_woocommerce_block_support')
		);
		add_action('init', 'mypos_checkout_for_woocommerce_mypos_checkout_for_woocommerce_block_init');
	}

	public static function add_gateway($gateways)
	{
		$options = get_option('woocommerce_mypos_virtual_settings', array());

		if (isset($options['hide_for_non_admin_users'])) {
			$hide_for_non_admin_users = $options['hide_for_non_admin_users'];
		} else {
			$hide_for_non_admin_users = 'no';
		}

		if (('yes' === $hide_for_non_admin_users && current_user_can('manage_options'))) {
			$gateways[] = 'WC_Gateway_Mypos';
		}
		$gateways[] = WC_Gateway_Mypos::class;
		return $gateways;
	}

	public static function includes()
	{
		if (class_exists('WC_Payment_Gateway')) {
			require_once 'includes/class-wc-gateway-mypos.php';
		}
	}

	public static function plugin_url()
	{
		return untrailingslashit(plugins_url('/', __FILE__));
	}

	public static function plugin_abspath()
	{
		return trailingslashit(plugin_dir_path(__FILE__));
	}

	public static function woocommerce_gateway_mypos_woocommerce_block_support()
	{
		if (class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
			require_once 'includes/blocks/class-wc-mypos-payments-blocks.php';
			add_action(
				'woocommerce_blocks_payment_method_type_registration',
				function (Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $paymentMethodRegistry) {
					$paymentMethodRegistry->register(new WC_Gateway_Mypos_Blocks_Support);
				}
			);

			add_filter('__experimental_woocommerce_blocks_add_data_attributes_to_block', function ($allowed_blocks) {
				$allowed_blocks[] = 'woocommerce/mypos-virtual-for-woocommerce';
				return $allowed_blocks;
			}, 10, 1);
		}
	}
}

function mypos_checkout_for_woocommerce_mypos_checkout_for_woocommerce_block_init()
{
	register_block_type(__DIR__ . '/build');
}

//Returns the main instance of MyPOS.
WC_Mypos_Payments::init();


