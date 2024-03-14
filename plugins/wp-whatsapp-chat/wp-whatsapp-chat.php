<?php

/**
 * Plugin Name:             Social Chat
 * Description:             Social Chat allows your visitors to contact you or your team through WhatsApp with a single click.
 * Plugin URI:              https://quadlayers.com/products/whatsapp-chat/
 * Version:                 7.3.4
 * Text Domain:             wp-whatsapp-chat
 * Author:                  QuadLayers
 * Author URI:              https://quadlayers.com
 * License:                 GPLv3
 * Domain Path:             /languages
 * Request at least:        4.7.0
 * Tested up to:            6.4
 * Requires PHP:            5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define( 'QLWAPP_PLUGIN_NAME', 'Social Chat' );
define( 'QLWAPP_PLUGIN_VERSION', '7.3.4' );
define( 'QLWAPP_PLUGIN_FILE', __FILE__ );
define( 'QLWAPP_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'QLWAPP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'QLWAPP_PREFIX', 'qlwapp' );
define( 'QLWAPP_DOMAIN', QLWAPP_PREFIX );
define( 'QLWAPP_WORDPRESS_URL', 'https://wordpress.org/plugins/wp-whatsapp-chat/' );
define( 'QLWAPP_DEMO_URL', 'https://quadlayers.com/demo/whatsapp-chat/?utm_source=qlwapp_admin' );
define( 'QLWAPP_LANDING_URL', 'https://quadlayers.com/landing/whatsapp-chat/?utm_source=qlwapp_admin' );
define( 'QLWAPP_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=qlwapp_admin' );
define( 'QLWAPP_DOCUMENTATION_URL', 'https://quadlayers.com/documentation/whatsapp-chat/?utm_source=qlwapp_admin' );
define( 'QLWAPP_GROUP_URL', 'https://www.facebook.com/groups/quadlayers' );
define( 'QLWAPP_PHONE_NUMBER', '59895761512' );
define( 'QLWAPP_PREMIUM_SELL_URL', 'https://quadlayers.com/products/whatsapp-chat/?utm_source=qlwapp_admin' );

/**
 * Load composer autoload
 */
require_once __DIR__ . '/vendor/autoload.php';
/**
 * Load plugin helpers
 */
require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/compatibility/old.php';
/**
 * Load vendor_packages packages
 */
require_once __DIR__ . '/vendor_packages/wp-i18n-map.php';
require_once __DIR__ . '/vendor_packages/wp-dashboard-widget-news.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-table-links.php';
require_once __DIR__ . '/vendor_packages/wp-notice-plugin-promote.php';
/**
 * Load plugin classes
 */
require_once __DIR__ . '/lib/class-plugin.php';

/**
 * Plugin activation hook
 */
register_activation_hook(
	__FILE__,
	function () {
		do_action( 'qlwapp_activation' );
	}
);

/**
 * Plugin activation hook
 */
register_deactivation_hook(
	__FILE__,
	function () {
		do_action( 'qlwapp_deactivation' );
	}
);
