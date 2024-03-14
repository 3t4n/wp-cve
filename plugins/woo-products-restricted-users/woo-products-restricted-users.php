<?php
/*
Plugin Name: Products Restricted Users for WooCommerce
Plugin URI: https://codection.com/woocommerce-products-restricted-users
Description: This plugin allows to choose in each product a list of users that will only be the only which could see and buy them
Version: 0.5.3
Author: Codection
Author URI: https://codection.com/
WC requires at least: 3.0.0
WC tested up to: 8.2.2
*/

use \Automattic\WooCommerce\Utilities\FeaturesUtil;

add_action( 'plugins_loaded', function(){
	require 'class-wpru-metabox.php';
	require 'class-wpru-filters.php';
	require 'class-wpru-restricted-product.php';

	new WPRU_Metabox();

	$wpru_filters = new WPRU_Filters();
	$wpru_filters->hooks();

	// addons
	foreach ( glob( plugin_dir_path( __FILE__ ) . 'addons/*.php' ) as $file ) {
		include_once $file;
	}
} );

add_action( 'before_woocommerce_init', function() {
    if ( !class_exists( FeaturesUtil::class ) )
        return;
	
    FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
} );