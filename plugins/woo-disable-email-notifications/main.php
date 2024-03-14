<?php
/*
 * Plugin Name: Disable Email Notifications for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/woo-disable-email-notifications/
 * Description: With this plugin, you will choose which email notifications you want to disable on Woocommerce.
 * Version: 1.0.2
 * Author: Bright Plugins
 * Requires PHP: 7.2.0
 * Requires at least: 4.0
 * Tested up to: 6.4.1
 * WC tested up to: 8.3.1
 * WC requires at least: 3.9
 * Author URI: http://brightplugins.com/
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( !file_exists( __DIR__ . "/vendor/autoload.php" ) ) {return;}
require __DIR__ . '/vendor/autoload.php';

/**
 * Define the required plugin constants
 */
define( 'BRIGHT_WDEN_ASSETS', plugins_url( '', __FILE__ ) . '/assets' );
define( 'BRIGHT_WDEN_FULL_NAME', plugin_basename( __FILE__ ) );


/**
 * Adds an action hook to load the plugin when plugins are loaded.
 * Initializes the Bootstrap class of the Woo_Disable_Email_Notification plugin.
 */
add_action( 'plugins_loaded', 'bright_wden_load_plugin' );

function bright_wden_load_plugin() {
	new \Woo_Disable_Email_Notification\Bootstrap();
}
