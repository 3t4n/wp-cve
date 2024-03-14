<?php
/**
 * Plugin Name: BIZAPPAY for WooCommerce
 * Plugin URI: https://bizappay.my
 * Description: Enable online payments using online banking for e-Commerce business.
 * Version: 1.0.9
 * Author: Fitweb Solutions
 * Author URI: https://fitweb.my
 * WC requires at least: 2.6.0
 * WC tested up to: 7.9.0
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

# Include Bizappay Class and register Payment Gateway with WooCommerce
add_action( 'plugins_loaded', 'bizappay_init', 0 );

function bizappay_init() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	include_once( 'src/bizappay.php' );

	add_filter( 'woocommerce_payment_gateways', 'add_bizappay_to_woocommerce' );
	function add_bizappay_to_woocommerce( $methods ) {
		$methods[] = 'Bizappay';

		return $methods;
	}
}

# Add custom action links
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bizappay_links' );

function bizappay_links( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=bizappay' ) . '">' . __( 'Settings', 'bizappay' ) . '</a>',
	);

	# Merge our new link with the default ones
	return array_merge( $plugin_links, $links );
}

add_action( 'init', 'bizappay_check_response', 15 );

function bizappay_check_response() {
	# If the parent WC_Payment_Gateway class doesn't exist it means WooCommerce is not installed on the site, so do nothing
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	include_once( 'src/bizappay.php' );

	$bizappay = new bizappay();
	$bizappay->check_bizappay_response();
}

add_action( 'woocommerce_api_callback', 'callback_bizappay' );

function callback_bizappay() {    
	include_once( 'src/bizappay.php' );
	$bizappayCall = new bizappay();
	$bizappayCall->callback_from_bizappay();
}

# checkout logo
add_filter('woocommerce_gateway_icon', function ($icon, $id) {
  if($id === 'Bizappay') {
	$icon = '<img style="max-height: 240px;max-width: 600px;float: none;" src="https://www.bizappay.com/asset/img/bp_checkout_logo.png" alt="bpwplogo" />';
	return $icon;
  } else {
    return $icon;
  }
}, 10, 2);
# end checkout logo

function bizappay_hash_error_msg( $content ) {
	return '<div class="woocommerce-error">The data that we received is invalid. Thank you.</div>' . $content;
}

function bizappay_payment_pending_msg( $content ) {
	return '<div class="woocommerce-error">The payment was pending. Please check your email for the receipt once payment process completed. Thank you.</div>' . $content;
}

function bizappay_success_msg( $content ) {
	return '<div class="woocommerce-info">The payment was successful. Thank you.</div>' . $content;
}