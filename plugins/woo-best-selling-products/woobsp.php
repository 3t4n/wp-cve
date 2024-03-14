<?php
/**
 * Plugin Name: Best Selling Products for WooCommerce
 * Description: A widget and shortcode displaying your best selling WooCommerce products, with thumbnail, title, price, star rating and link to the product.
 * Version: 1.3.1
 * Author: Blaze Concepts
 * Author URI: https://www.blazeconcepts.co.uk
 * License: GPL2
 * Text Domain: woo-best-selling-products
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 4.6.2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !function_exists('get_plugin_data') ){
	    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

if ( !function_exists( 'woobsp_version' ) ) {
	function woobsp_version() {
		$woobsp_plugin_data = get_plugin_data( __FILE__ );
		$woobsp_plugin_version = $woobsp_plugin_data['Version'];
		return $woobsp_plugin_version;
	}
}

if ( !function_exists( 'woobsp_widget_scripts' ) ) {
	function woobsp_widget_scripts() {
		wp_register_style( 'woobsp_widget_css', plugins_url( '/assets/woobsp-styles.css', __FILE__ ), false, woobsp_version(), 'all' );
		wp_enqueue_style( 'woobsp_widget_css' );
	}
	add_action( 'wp_enqueue_scripts', 'woobsp_widget_scripts' );
}

//widget
require_once('widget/woobsp-widget.php');

//shortcode
require_once('widget/woobsp-shortcode.php');