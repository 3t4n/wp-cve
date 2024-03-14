<?php
/*
Plugin Name: Multiple Email Recipients - WC
Plugin URI: 
Description: "Multiple Email Recipient" helps to Send two additional email addresses that can be used as email recipients for WooCommerce new order and cancelled order emails. You can select which type of order email you want to have as two email recipients Bcc via the settings.
Author: Sunarc
Author URI: https://www.suncartstore.com/
Version: 1.0.6
*/

/*
* Global variables
*/

// Retrieve settings data from the options table
$sunarcwoome_options = get_option('woome_settings_sunarc');


/**
 * Enqueue admin styles
 */
function sunarcwoome_admin_assets() {
	if ( @$_GET['page']=='woome_options') {
    	wp_enqueue_style( 'woome-admin-css', plugins_url('woocommerce-multiple-email-recipients').'/assets/css/woomeadmin.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'sunarcwoome_admin_assets' );

/******************************
* Includes
******************************/

include('INC/functions.php'); // display functions
include('INC/admin-page.php'); // the plugin options page HTML and save functions

/******************************
* Settings link for plugin
******************************/
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'sunarcwoome_plugin_settings_link' );

function sunarcwoome_plugin_settings_link( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=woome_options') ) .'">Settings</a>';
   return $links;
}