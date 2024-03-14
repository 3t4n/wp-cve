<?php
/*
Plugin Name: 4Geeks Payments for WooCommerce
Plugin URI: https://4geeks.io/payments
Description: Accept online payments on Woocommerce through 4Geeks Payments.
Version: 2.1.1
Author: 4Geeks
Author URI: https://4geeks.io/
WC requires at least: 3.9
WC tested up to: 6.0
*/

add_action('plugins_loaded', 'cw_gpayments_init', 0);
function cw_gpayments_init()
{
	//if condition use to do nothin while WooCommerce is not installed
	if (!class_exists('WC_Payment_Gateway')) return;
	include_once('gpayments-connector-woocommerce.php');
	// class add it too WooCommerce
	add_filter('woocommerce_payment_gateways', 'cw_add_gpayments_gateway');
	function cw_add_gpayments_gateway($methods)
	{
		$methods[] = 'WC_GPayments_Connection';
		return $methods;
	}
}
// Add custom action links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cw_gpayments_gateway_action_links');
function cw_gpayments_gateway_action_links($links)
{
	$plugin_links = array(
		'<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout') . '">' . __('Settings', 'wc-4gpayments') . '</a>',
	);
	return array_merge($plugin_links, $links);
}
