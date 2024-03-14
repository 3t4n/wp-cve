<?php

/**
 * Plugin Name:             WooCommerce Checkout Manager
 * Plugin URI:              https://quadlayers.com/products/woocommerce-checkout-manager/
 * Description:             Manage and customize WooCommerce Checkout fields (Add, Edit, Delete or re-order fields).
 * Version:                 7.4.6
 * Author:                  QuadLayers
 * Author URI:              https://quadlayers.com
 * License:                 GPLv3
 * Text Domain:             woocommerce-checkout-manager
 * Domain Path:             /languages
 * Request at least:        4.7.0
 * Tested up to:            6.4
 * Requires PHP:            5.6
 * WC requires at least:    4.0
 * WC tested up to:         8.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Definition globals varibles
 */
define( 'WOOCCM_PLUGIN_NAME', 'WooCommerce Checkout Manager' );
define( 'WOOCCM_PLUGIN_VERSION', '7.4.6' );
define( 'WOOCCM_PLUGIN_FILE', __FILE__ );
define( 'WOOCCM_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'WOOCCM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WOOCCM_PREFIX', 'wooccm' );
define( 'WOOCCM_WORDPRESS_URL', 'https://wordpress.org/plugins/woocommerce-checkout-manager/' );
define( 'WOOCCM_DOCUMENTATION_URL', 'https://quadlayers.com/documentation/woocommerce-checkout-manager/?utm_source=wooccm_admin' );
define( 'WOOCCM_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=wooccm_admin' );
define( 'WOOCCM_PREMIUM_SELL_URL', 'https://quadlayers.com/products/woocommerce-checkout-manager/?utm_source=wooccm_admin' );


/**
 * Developer debug variable
 */
define( 'WOOCCM_DEVELOPER', false );

/**
 * Load composer autoload
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load vendor_packages packages
 */
require_once __DIR__ . '/vendor_packages/wp-i18n-map.php';
require_once __DIR__ . '/vendor_packages/wp-dashboard-widget-news.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-table-links.php';
require_once __DIR__ . '/vendor_packages/wp-notice-plugin-required.php';
require_once __DIR__ . '/vendor_packages/wp-notice-plugin-promote.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-suggestions.php';

/**
 * Load plugin classes
 */
require_once __DIR__ . '/lib/class-plugin.php';

/**
 * Plugin activation hook
 */
register_activation_hook(
	__FILE__,
	function() {
		do_action( 'wooccm_activation' );
	}
);

/**
 * Plugin activation hook
 */
register_deactivation_hook(
	__FILE__,
	function() {
		do_action( 'wooccm_deactivation' );
	}
);

/**
 * Declarate compatibility with WooCommerce Custom Order Tables
 */
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
