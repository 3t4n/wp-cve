<?php

/**
 * @package   Payamito
 * @link      https://payamito.com/
 * Plugin Name:       Payamito SMS Woocommerce
 * Plugin URI:        https://payamito.com/lib
 * Description:       Easily sms any activity that needs to be notified in WooCommerce with this plugin. Designed with❤
 * in Payamito team
 * Version: 1.3.5
 * Author: Payamito
 * Author URI: https://payamito.com/
 * Text Domain: payamito-woocommerce
 * Domain Path:/languages
 * Requires PHP: 7.4.0
 * Requires at least: 5.0.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 7.0.0
 * WC tested up to: 8.5.2
 */

if (!defined('ABSPATH')) {
	die('direct access abort ');
}
if (!defined('PAYAMITO_WC_PLUGIN_FILE')) {
	define('PAYAMITO_WC_PLUGIN_FILE', __FILE__);
}

require_once __DIR__ . '/Define-constants.php';
require_once __DIR__ . '/includes/functions.php';

if (!class_exists('Payamito_Woocommerce')) {
	include_once PAYAMITO_WC_DIR . '/includes/payamito-woocommerce.php';
}

register_activation_hook(__FILE__, 'payamito_wc_activate');
register_deactivation_hook(__FILE__, 'payamito_wc_deactivate');
add_action('plugins_loaded', 'payamito_wc_set_locale');
add_action('before_woocommerce_init',  'payamito_declare_woocommerce_feature_compatibility');

function payamito_declare_woocommerce_feature_compatibility()
{
	if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
	}
}

if (!function_exists("payamito_wc_set_locale")) {
	function payamito_wc_set_locale()
	{
		$dirname = str_replace('//', '/', wp_normalize_path(dirname(__FILE__)));
		$mo      = $dirname . '/languages/' . 'payamito-woocommerce-' . get_locale() . '.mo';
		load_textdomain('payamito-woocommerce', $mo);
	}
}

function payamito_wc_activate()
{
	do_action("payamito_wc_activate");

	require_once PAYAMITO_WC_DIR . '/includes/class-install.php';

	Payamito\Woocommerce\Install::install(PAYAMITO_WC_COR_VER, PAYAMITO_WC_PLUGIN_FILE, PAYAMITO_WC_COR_DIR);
}

function payamito_wc_deactivate()
{
	do_action("payamito_wc_deactivate");
	wp_clear_scheduled_hook('payamito_wc_abandoned');
}

/**
 * @return object|Payamito_Woocommerce|null
 */
function payamito_wc()
{
	return Payamito_Woocommerce::get_instance();
}

payamito_wc();
