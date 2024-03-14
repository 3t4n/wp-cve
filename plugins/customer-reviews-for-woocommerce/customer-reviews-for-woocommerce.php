<?php
/*
Plugin Name: Customer Reviews for WooCommerce
Plugin Title: Customer Reviews for WooCommerce Plugin
Plugin URI: https://wordpress.org/plugins/customer-reviews-for-woocommerce/
Description: Looking to boost your WooCommerce sales? Using the WooCommerce customer reviews widget, you can! Collect more reviews and build brand loyalty with this free tool.
Tags: woocommerce, woocoomerce reviews, review plugin, review reminder, customer reviews, reviews
Author: Trustindex.io <support@trustindex.io>
Author URI: https://www.trustindex.io/
Contributors: trustindex
License: GPLv2 or later
Version: 3.2.1
Text Domain: customer-reviews-for-woocommerce
Domain Path: /languages/
Donate link: https://www.trustindex.io/prices/
*/
/*
Copyright 2019 Trustindex Kft (email: support@trustindex.io)
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once plugin_dir_path( __FILE__ ) . 'trustindex-plugin.class.php';
require_once plugin_dir_path( __FILE__ ) . 'trustindex-woocommerce-plugin.class.php';
$trustindex_woocommerce = new TrustindexWoocommercePlugin('woocommerce', __FILE__, "3.2.1", "Customer Reviews for WooCommerce", "Trustindex");
add_action('plugins_loaded', array($trustindex_woocommerce, 'loadI18N'));
add_action('admin_menu', array($trustindex_woocommerce, 'add_setting_menu_wc'), 10);
add_filter('plugin_action_links', array($trustindex_woocommerce, 'add_plugin_action_links'), 10, 2);
add_filter('plugin_row_meta', array($trustindex_woocommerce, 'add_plugin_meta_links'), 10, 2 );
add_action('init', array($trustindex_woocommerce, 'output_buffer') );
add_action('admin_enqueue_scripts', array($trustindex_woocommerce, 'trustindex_add_scripts') );
?>