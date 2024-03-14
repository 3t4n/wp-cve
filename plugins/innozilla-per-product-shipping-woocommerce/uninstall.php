<?php
/**
 * Per Product Shipping Uninstall
 */
if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
	exit();
}

global $wpdb;

// Tables
if(in_array('innozilla-per-product-shipping-woocommerce-pro/woocommerce-innozilla-shipping-per-product-pro.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
    
} else {
	$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "innozilla_per_product_shipping_rules_woo" );
}