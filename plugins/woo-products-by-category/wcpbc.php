<?php
/*
Plugin Name: List Products By Category Widget For WooCommerce
Plugin URI: https://www.blazeconcepts.co.uk/
Description: Display a list of all the products in a WooCommerce product category with this handy widget.
Version: 1.3.0
Author: Blaze Concepts
Author URI: https://www.blazeconcepts.co.uk/
License: GPL2
WC requires at least: 3.0.0
WC tested up to: 4.6.2
Text Domain: woo-products-by-category
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !function_exists('get_plugin_data') ){
	    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

if ( !function_exists( 'wcpbc_version' ) ) {
	function wcpbc_version() {
		$wcpbc_plugin_data = get_plugin_data( __FILE__ );
		$wcpbc_plugin_version = $wcpbc_plugin_data['Version'];
		return $wcpbc_plugin_version;
	}
}

if ( !function_exists( 'wcpbc_widget_scripts' ) ) {
	function wcpbc_widget_scripts() {
		wp_register_style( 'wcpbc_widget_css', plugins_url( '/public/wcpbc-styles.css', __FILE__ ), false, wcpbc_version(), 'all' );
		wp_enqueue_style( 'wcpbc_widget_css' );
	}
	add_action( 'wp_enqueue_scripts', 'wcpbc_widget_scripts' );
}

//widget
require_once('widget/wcpbc-widget.php');