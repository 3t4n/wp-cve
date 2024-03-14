<?php
/**
 * Plugin Name: YITH Essential Kit for WooCommerce #1
 * Plugin URI: https://wordpress.org/plugins/yith-essential-kit-for-woocommerce-1/
 * Description: With <code><strong>YITH Essential Kit for WooCommerce #1</strong></code> you will be free to add new and powerful features to make your e-commerce site unique. Activate the plugin you want and start using your site to a new and improved level. <a href="https://yithemes.com/" target="_blank">Find new awesome plugins on <strong>YITH</strong></a>
 * Text Domain: yith-essential-kit-for-woocommerce-1
 * Domain Path: /languages/
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Version: 2.29.0
 * Requires at least: 6.2
 * Tested up to: 6.4
 * WC requires at least: 8.4
 * WC tested up to: 8.6
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Essential Kit for Woocommerce #1
 * @version 2.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! defined( 'YJP_DIR' ) ) {
	define( 'YJP_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YJP_URL' ) ) {
	define( 'YJP_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YJP_ASSETS_URL' ) ) {
	define( 'YJP_ASSETS_URL', YJP_URL . 'assets' );
}

if ( ! defined( 'YJP_TEMPLATE_PATH' ) ) {
	define( 'YJP_TEMPLATE_PATH', YJP_DIR . 'templates' );
}

if ( ! defined( 'YJP_VERSION' ) ) {
	define( 'YJP_VERSION', '2.29.0' );
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

/* Plugin Framework Version Check */
! function_exists( 'yit_maybe_plugin_fw_loader' ) && require_once 'plugin-fw/init.php';
yit_maybe_plugin_fw_loader( dirname( __FILE__ ) );


load_plugin_textdomain( 'yith-essential-kit-for-woocommerce-1', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

if ( ! class_exists( 'Plugin_Upgrader' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
}
require_once YJP_DIR . 'class-yith-jetpack.php';
require_once YJP_DIR . 'class-yith-essential-kit-upgrader-skin.php';

global $yith_jetpack_1;
$yith_jetpack_1 = new YITH_JetPack( __FILE__, 'YITH Essential Kit for WooCommerce #1', 1 );

register_activation_hook( __FILE__, 'yith_essential_kit_welcome_screen_activate' );
/**
 * VERSION 2.0 Migration
 */
function yith_essential_kit_welcome_screen_activate() {
	get_site_option( 'yith_essential_kit_main_version', '1.0' );
}

require_once 'migration.php';
