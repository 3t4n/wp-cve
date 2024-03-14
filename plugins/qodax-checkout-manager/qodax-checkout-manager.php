<?php
/**
 * Plugin Name: Qodax Checkout Manager
 * Plugin URI: https://kirillbdev.pro/qodax-checkout-manager?utm_source=plugin&utm_medium=referal
 * Description: Customize and manage checkout fields in your WooCommerce store with a simple and user-friendly interface.
 * Version: 1.2.1
 * Author: kirillbdev
 * License URI: license.txt
 * Requires PHP: 7.4
 * Tested up to: 6.4
 * WC tested up to: 8.3
*/

if ( ! defined('ABSPATH')) {
  exit;
}

define('QODAX_CHECKOUT_MANAGER_PLUGIN_NAME', plugin_basename(__FILE__));
define('QODAX_CHECKOUT_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('QODAX_CHECKOUT_MANAGER_PLUGIN_ENTRY', __FILE__);
define('QODAX_CHECKOUT_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__));

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/helpers.php';

add_action( 'before_woocommerce_init', function() {
    if (class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

Qodax\CheckoutManager\Kernel::instance();