<?php
/*
 * Plugin Name: ELEX Minimum Order Amount for WooCommerce
 * Plugin URI: https://elextensions.com/plugin/elex-minimum-order-amount-for-woocommerce-free/
 * Description: This plugin helps you to configure minimum and maximum order amount based on WordPress user roles.
 * Version: 1.3.1
 * Author: ELEXtensions
 * Author URI: https://elextensions.com
 * Text Domain: elex-wc-checkout-restriction
 * WC requires at least: 2.6
 * WC tested up to: 8.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'MINIMUM_ORDER_MAIN_PATH' ) ) {
	define( 'MINIMUM_ORDER_MAIN_PATH', plugin_dir_url( __FILE__ ) );
}

// review component
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once  ABSPATH . 'wp-admin/includes/plugin.php';
}
include_once __DIR__ . '/review_and_troubleshoot_notify/review-and-troubleshoot-notify-class.php';
$data                      = get_plugin_data( __FILE__ );
$data['name']              = $data['Name'];
$data['basename']          = plugin_basename( __FILE__ );
$data['documentation_url'] = 'https://elextensions.com/knowledge-base/set-minimum-order-amount-woocommerce-elex-minimum-order-amount-for-woocommerce-plugin/';
$data['rating_url']        = 'https://elextensions.com/plugin/elex-minimum-order-amount-for-woocommerce-free/#reviews';
$data['support_url']       = 'https://wordpress.org/support/plugin/elex-minimum-order-amount-for-woocommerce/';

new \Elex_Review_Components( $data );

// Check if woocommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'elex_wccr_plugin_action_links' );
function elex_wccr_plugin_action_links( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=elex-wccr' ) . '">' . esc_html__( 'Settings', 'elex-wc-checkout-restriction' ) . '</a>',
		'<a href="https://elextensions.com/support/" target="_blank">' . esc_html__( 'Support', 'elex-wc-checkout-restriction' ) . '</a>',
	);
	return array_merge( $plugin_links, $links );
}

add_action( 'init', 'elex_wccr_admin_menu' );
function elex_wccr_admin_menu() {
	require_once 'includes/elex-wccr-frontend-template.php';
	require_once 'includes/elex-wccr-restrict-logic.php';
}


add_action( 'admin_menu', 'elex_wccr_admin_menu_option' );

function elex_wccr_admin_menu_option() {
	add_submenu_page( 'woocommerce', esc_html__( 'Minimum Order Amount', 'elex-wc-checkout-restriction' ), esc_html__( 'Minimum Order Amount', 'elex-wc-checkout-restriction' ), 'manage_woocommerce', 'admin.php?page=wc-settings&tab=elex-wccr' );
	
}

// High performance order tables compatibility.
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

