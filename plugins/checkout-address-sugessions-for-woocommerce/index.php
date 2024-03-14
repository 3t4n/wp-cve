<?php
/*
  Plugin Name: Checkout Address Sugessions for WooCommerce
  Description: This Plugin gives address sugession when customers types their address on billing or shipping address fields on woocommerce checkout page using the Google Maps Places API.
  Author: Raman
  Author URI: 
  Text Domain: checkout-address-sugessions-for-woocommerce
  Version: 1.2.9
  Requires at least: 3.0
  Tested up to: 5.2.2
 */

/* 
	Plugin works only when woocommerce is activated and Google Api Key inserted
*/

global $cas_plugin_url, $cas_plugin_dir;

$cas_plugin_dir = dirname(__FILE__) . "/";
$cas_plugin_url = plugins_url()."/" . basename($cas_plugin_dir) . "/";
include_once $cas_plugin_dir.'lib/class.cas.woocommerce.php';
?>