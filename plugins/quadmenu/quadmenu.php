<?php

/**
 * Plugin Name:             QuadMenu
 * Plugin URI:              https://quadmenu.com
 * Description:             The best drag & drop WordPress Mega Menu plugin which allow you to create Tabs Menus & Carousel Menus.
 * Version:                 3.1.0
 * Text Domain:             quadmenu
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

define( 'QUADMENU_PLUGIN_NAME', 'QuadMenu' );
define( 'QUADMENU_PLUGIN_VERSION', '3.1.0' );
define( 'QUADMENU_PLUGIN_FILE', __FILE__ );
define( 'QUADMENU_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'QUADMENU_PLUGIN_URL', plugin_dir_url( QUADMENU_PLUGIN_FILE ) );
define( 'QUADMENU_PLUGIN_BASENAME', plugin_basename( QUADMENU_PLUGIN_FILE ) );
define( 'QUADMENU_PREFIX', 'quadmenu' );
define( 'QUADMENU_WORDPRESS_URL', 'https://wordpress.org/plugins/quadmenu/' );
define( 'QUADMENU_REVIEW_URL', 'https://wordpress.org/support/plugin/quadmenu/reviews/?filter=5#new-post' );
define( 'QUADMENU_DEMO_URL', 'https://quadmenu.com/?utm_source=quadmenu_admin' );
define( 'QUADMENU_PURCHASE_URL', QUADMENU_DEMO_URL );
define( 'QUADMENU_SUPPORT_URL', 'https://quadmenu.com/account/support/?utm_source=quadmenu_admin' );
define( 'QUADMENU_DOCUMENTATION_URL', 'https://quadmenu.com/documentation/?utm_source=quadmenu_admin' );
define( 'QUADMENU_GROUP_URL', 'https://www.facebook.com/groups/quadlayers' );
define( 'QUADMENU_DB_THEME', '_quadmenu_theme' );
define( 'QUADMENU_DB_ITEM', '_menu_item_quadmenu' );
define( 'QUADMENU_DEV', true );
define( 'QUADMENU_COMPILE', true );
// Pro compatibility
define( 'QUADMENU_DOMAIN', 'quadmenu' );
define( 'QUADMENU_PATH', QUADMENU_PLUGIN_DIR );
define( 'QUADMENU_DEMO', QUADMENU_DEMO_URL );

define( 'QUADMENU_PREMIUM_SELL_SLUG', 'quadmenu-pro' );
define( 'QUADMENU_PREMIUM_SELL_NAME', 'QuadMenu' );
define( 'QUADMENU_PREMIUM_SELL_URL', 'https://quadmenu.com/?utm_source=quadmenu_admin' );

define( 'QUADMENU_CROSS_INSTALL_SLUG', 'wp-whatsapp-chat' );
define( 'QUADMENU_CROSS_INSTALL_NAME', 'Whatsapp Chat' );
define( 'QUADMENU_CROSS_INSTALL_DESCRIPTION', esc_html__( 'WhatsApp Chat allows your customers to click a button and open a conversation from your website directly to your “WhatsApp” or “WhatsApp Business” phone number.', 'quadmenu' ) );
define( 'QUADMENU_CROSS_INSTALL_URL', 'https://quadlayers.com/portfolio/whatsapp-chat/?utm_source=quadmenu_admin' );

/**
 * Load composer autoload
 */
require_once __DIR__ . '/vendor/autoload.php';
/**
 * Load compatibility
 */
require_once __DIR__ . '/compatibility/old.php';
/**
 * Load vendor_packages packages
 */
require_once __DIR__ . '/vendor_packages/wp-i18n-map.php';
require_once __DIR__ . '/vendor_packages/wp-dashboard-widget-news.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-table-links.php';
require_once __DIR__ . '/vendor_packages/wp-notice-plugin-promote.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-suggestions.php';
/**
 * Load plugin classes
 */
require_once __DIR__ . '/lib/class-plugin.php';

register_activation_hook( __FILE__, array( 'QuadLayers\\QuadMenu\\Activation', 'activation' ) );
