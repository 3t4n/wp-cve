<?php
/*
Plugin Name:    ShippingEasy for WP eCommerce
Plugin URI:     http://www.shippingeasy.com/
Description:    ShippingEasy is a powerful online shipping platform that integrates with your WP e-Commerce store, saving you time, money and hassle. <br/>To get started: 1) Click the ‘Activate’ link to the left of this description, 2) Register for a <a href="https://www.shippingeasy.com#register?channel=wordpress" target="_blank">free ShippingEasy account</a> and access your <a href="https://www.shippingeasy.com/my#api-area" target="_blank">API key</a>, 3) Save your API key within your <a href="options-general.php?page=wpsc-settings&tab=shipping&_wpnonce=1f03a3f602&shipping_module=shippingeasy#gateway_options" target="_blank">ShippingEasy configuration</a>, customise your settings and start shipping!
Version:        1.03
Author:         ShippingEasy <easysupport@shippingeasy.com>
Author URI:     http://www.shippingeasy.com/
 */

define('SE_PLUGIN_BASE_PATH', dirname(__FILE__));
define('SE_PLUGIN_BASE_URL', plugins_url(null, __FILE__));

require_once SE_PLUGIN_BASE_PATH.'/includes/ecommerce-webservicelib/SeApi.php';
require_once SE_PLUGIN_BASE_PATH.'/includes/SeWpUtils.php';
require_once SE_PLUGIN_BASE_PATH.'/includes/SeWpEcModule.php';
require_once SE_PLUGIN_BASE_PATH.'/includes/SeWpOptions.php';

// register activation (and similar) hooks
register_activation_hook( __FILE__, 'shippingeasy_activation');
register_deactivation_hook( __FILE__, 'shippingeasy_deactivation');

function shippingeasy_activation() {
  SeWpUtils::setDefaults();
}

function shippingeasy_deactivation() {
}

// Set the textdomain for this plugin so we can support localizations.
add_action('init', 'shippingeasy_textdomain');
function shippingeasy_textdomain() {
	load_plugin_textdomain('shippingeasy', null, SE_PLUGIN_BASE_PATH . '/languages/');
}

// Register scripts and styles for admin area
add_action('admin_init', 'shippingeasy_admin_init');
function shippingeasy_admin_init() {
  wp_register_script('shippingeasy_admin_js', SE_PLUGIN_BASE_URL.'/js/admin.js');
}

// Enqueue scripts and styles for admin area
add_action('admin_head', 'shippingeasy_admin_css_and_js');
function shippingeasy_admin_css_and_js() {
  wp_enqueue_script('shippingeasy_admin_js');
  echo '<link rel="stylesheet" type="text/css" href="' .SE_PLUGIN_BASE_URL.'/css/admin.css">';
}

add_action('parse_request', 'shippingeasy_parse_request');
function shippingeasy_parse_request() {
  // api entry point
  if (isset($_REQUEST['shippingeasyApi']) && isset($_REQUEST['resource']))
  {
    SeWpUtils::executeResource();
  }
}