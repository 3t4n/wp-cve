<?php

/**
 * Dojo for WooCommerce.
 *
 * Plugin Name:          Dojo for WooCommerce
 * Description:          Extends WooCommerce by taking payments via Dojo.
 * Version:              2.0.10
 * Author:               Dojo
 * Author URI:           http://dojo.tech/
 * License:              GNU General Public License v3.0
 * License URI:          http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:          woocommerce-dojo
 * Requires at least:    5.0
 * Tested up to:         6.3
 * WC requires at least: 7.0
 * WC tested up to:      8.4.0
 *
 * @package Dojo_For_WooCommerce
 * @author  Dojo
 * @link    http://dojo.tech/
 */

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
	exit();
}

load_plugin_textdomain(
	'woocommerce-dojo',
	false,
	basename(__DIR__) . DIRECTORY_SEPARATOR . 'languages'
);

define('WC_DOJO_VERSION', '2.0.10'); // WRCS: DEFINED_VERSION.

/**
 * Hooks Dojo on the plugins_loaded action if WooCommerce is active
 */
require_once ABSPATH . 'wp-admin/includes/plugin.php';

if (is_plugin_active('woocommerce/woocommerce.php')) {

	if (!function_exists('woocommerce_dojo_init')) {
		/**
		 * Dojo Init function
		 */
		function woocommerce_dojo_init()
		{
			if (!class_exists('WC_Payment_Gateway')) {
				return;
			}

			$dojo_checkout = plugin_dir_path(__FILE__) . 'includes/class-wc-dojo-checkout.php';

			if (file_exists($dojo_checkout)) {
				require_once $dojo_checkout;

				if (!function_exists('woocommerce_add_dojo_payment_gateway')) {
					/**
					 * Adds Dojo into the WooCommerce payment gateways
					 *
					 * @param array $methods WooCommerce payment gateways.
					 *
					 * @return array WooCommerce payment gateways
					 */
					function woocommerce_add_dojo_payment_gateway($methods)
					{
						$methods[] = 'WC_Dojo';
						return $methods;
					}
					add_filter('woocommerce_payment_gateways', 'woocommerce_add_dojo_payment_gateway');
				}

				if (!function_exists('woocommerce_add_dojo_allowed_redirect_hosts')) {
					/**
					 * Adds the host name of the Dojo Checkout to the allowed host names
					 *
					 * @param array $hosts Allowed host names.
					 *
					 * @return array Allowed host names
					 */
					function woocommerce_add_dojo_allowed_redirect_hosts($hosts)
					{
						$hosts[] = 'pay.dojo.tech';
						return $hosts;
					}
					add_filter('allowed_redirect_hosts', 'woocommerce_add_dojo_allowed_redirect_hosts');
				}
			}
		}
	}

	add_action('before_woocommerce_init', function () {
		// https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book
		if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
		}
	});
	add_action('plugins_loaded', 'woocommerce_dojo_init', 0);
}
