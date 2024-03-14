<?php
/**
 * Plugin Name: Boxtal Connect
 * Description: Negotiated rates for all types of shipping (home, relay, express, etc.). No subscription, no hidden fees.
 * Author: Boxtal
 * Author URI: https://www.boxtal.com
 * Text Domain: boxtal-connect
 * Domain Path: /Boxtal/BoxtalConnectWoocommerce/translation
 * Version: 1.2.22
 * WC requires at least: 2.6.14
 * WC tested up to: 6.2.1
 *
 * @package Boxtal\BoxtalConnectWoocommerce
 */

use Boxtal\BoxtalConnectWoocommerce\Plugin;

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

require_once trailingslashit( __DIR__ ) . 'Boxtal/BoxtalConnectWoocommerce/autoloader.php';

$plugin_instance = Plugin::initInstance( __FILE__ );

add_action( 'before_woocommerce_init', array( $plugin_instance, 'plugins_before_woocommerce_init_action' ) );

add_action( 'plugins_loaded', array( $plugin_instance, 'plugins_loaded_action' ) );

register_activation_hook( __FILE__, 'Boxtal\BoxtalConnectWoocommerce\Plugin::activation_hook' );

register_uninstall_hook( __FILE__, 'Boxtal\BoxtalConnectWoocommerce\Plugin::uninstall_hook' );

add_action( 'wpmu_new_blog', array( $plugin_instance, 'wpmu_new_blog_action' ), 10, 6 );

add_action( 'wpmu_drop_tables', array( $plugin_instance, 'wpmu_drop_tables_action' ) );

