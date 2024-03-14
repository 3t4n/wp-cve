<?php
/*
 * Plugin Name: Woo Out Of Stock Products
 * Plugin URI: http://andrewgunn.org/
 * Description: Dynamically display a custom uploaded or default "Out of Stock" image overlay for products in your WooCommerce store that are out of stock.
 * Version: 2.3
 * Author: Andrew Gunn
 * Author URI: http://andrewgunn.org
 * Text Domain: woo-out-of-stock-products
 * License: GPL2
 */
defined('ABSPATH') or die('Plugin file cannot be accessed directly.');

/**
 * Including files in other directories
 */
include_once ('admin/admin.php');
//include_once('inc/shortcode.php');
include_once ('inc/script-styles.php');

/**
 * Register and enqueue jQuery files to run on frontend, enqueue on admin_init
 */
add_action('init', 'register_outofstock_scripts');

function register_outofstock_scripts() {
	//wp_register_script( 'outofstock_js', plugins_url('inc/outofstock.js', __FILE__), array('jquery'));
	wp_register_style('outofstock_css', plugins_url('inc/outofstock.css', __FILE__));
	//wp_register_script( 'outofstock_js' );
	wp_enqueue_style('outofstock_css');
}

/**
 * Adding Settings link to plugin page
 */
add_filter('plugin_action_links', 'outofstock_settings_link', 10, 5);

function outofstock_settings_link($actions, $plugin_file) {
	static $plugin;

	if (!isset($plugin)) {
		$plugin = plugin_basename(__FILE__);
	}

	if ($plugin == $plugin_file) {

		$settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=products&section=outofstock">'.__('Settings', 'General').'</a>',
			'reports'                   => '<a href="edit.php?post_type=product&page=outofstock-stats">'.__('Reports', 'General').'</a>');

		$actions = array_merge($settings, $actions);
	}

	return $actions;
}