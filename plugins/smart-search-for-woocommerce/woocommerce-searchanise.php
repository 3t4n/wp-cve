<?php
/**
 * Plugin Name: Smart WooCommerce Search by Searchanise
 * Plugin URI: https://searchanise.io/
 * Description: Searchanise shows product previews, relevant categories, pages, and search suggestions as you type.
 * Version: 1.0.16
 * Author: Searchanise
 * Author URI: https://searchanise.io/
 * License: GPLv3
 * WC requires at least: 3.0.0
 * WC tested up to: 7.7.1
 *
 * @package Searchanise
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( file_exists( __DIR__ . 'c3.php' ) ) {
	require_once __DIR__ . '/c3.php';
}

// Makes sure the plugin is defined before trying to use it.
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';
}

// Init.
require_once __DIR__ . '/init.php';
