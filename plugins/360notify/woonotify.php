<?php
/*
Plugin Name: 360Notify
Version: 3.5.1
Plugin URI: https://360messenger.com
Description: A practical plugin that enables you to easily and automatically notify administrators, clients, and vendors via WhatsApp.
Author URI: https://360Messenger.com
Author: 360messenger
Contributors: 360messenger
WC requires at least: 6.0.0
WC tested up to: 8.5.2
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! defined( 'WooNotify_VERSION' ) ) {
	define( 'WooNotify_VERSION', '3.5.1' );
}

if ( ! defined( 'WooNotify_URL' ) ) {
	define( 'WooNotify_URL', plugins_url( '', __FILE__ ) );
}

if ( ! defined( 'WooNotify_INCLUDE_DIR' ) ) {
	define( 'WooNotify_INCLUDE_DIR', dirname( __FILE__ ) . '/includes' );
}

register_activation_hook( __FILE__, 'WooNotify_360Messenger_Register' );
register_deactivation_hook( __FILE__, 'WooNotify_360Messenger_Register' );
function WooNotify_360Messenger_Register() {
	delete_option( 'WooNotify_table_archive' );
	delete_option( 'WooNotify_table_contacts' );
	delete_option( 'WooNotify_hide_about_page' );
	delete_option( 'WooNotify_redirect_about_page' );
}

require_once 'includes/class-gateways.php';
require_once 'includes/class-settings-api.php';
require_once 'includes/class-settings.php';
require_once 'includes/class-helper.php';
require_once 'includes/class-bulk.php';
require_once 'includes/class-about.php';
require_once 'includes/class-ads.php';
require_once 'includes/string.php';
require_once 'includes/class-metabox.php';
require_once 'includes/class-subscription.php';
require_once 'includes/class-product-tab.php';
require_once 'includes/class-product-events.php';
require_once 'includes/class-orders.php';
require_once 'includes/class-archive.php';
require_once 'includes/class-contacts.php';
require_once 'includes/class-functions.php';

require_once 'includes/class-deprecateds.php';

add_action( 'admin_enqueue_scripts', 'load_woo_360Messenger_admin_style' );
function load_woo_360Messenger_admin_style() {
	wp_enqueue_style( 'woonotify_admin_style', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css' );
	wp_style_add_data( 'woonotify_admin_style', 'rtl', 'replace' );
}
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

