<?php
/*
Plugin Name: Reactive Mortgage Calculator
Plugin URI: https://srizon.com/product/reactive-mortgage-calculator
Description: Responsive and Reactive mortgage calculator that you can customize
Text Domain: reactive-mortgage-calculator
Domain Path: /languages
Version: 1.1
Author: Afzal
Author URI: https://srizon.com/contact
*/

function srizon_mortgage_album_load_textdomain() {
	load_plugin_textdomain( 'reactive-mortgage-calculator', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

//if(true){
//	ini_set("log_errors", 1);
//	ini_set("error_log", "/tmp/php-error.log");
//}

add_action( 'plugins_loaded', 'srizon_mortgage_album_load_textdomain' );

require_once 'lib/SrizonMortgageDB.php';
require_once 'api/index.php';
// backend files
if ( is_admin() ) {
	require_once 'admin/index.php';
} else {
	require_once 'site/index.php';
}

register_activation_hook( __FILE__, 'srizon_mortgage_activate' );
add_action( 'wpmu_new_blog', 'srizon_mortgage_on_create_blog', 10, 6 );

function srizon_mortgage_activate( $network_wide ) {
	global $wpdb;
	if ( is_multisite() && $network_wide ) {
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			SrizonMortgageDB::createDBTables();
			restore_current_blog();
		}
	} else {
		SrizonMortgageDB::createDBTables();
	}
}

function srizon_mortgage_on_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	if ( is_plugin_active_for_network( 'reactive-mortgage-calculator/reactive-mortgage-calculator.php' ) ) {
		switch_to_blog( $blog_id );
		SrizonMortgageDB::createDBTables();
		restore_current_blog();
	}
}

function srizon_mortgage_get_resource_url( $relativePath ) {
	return plugins_url( $relativePath, plugin_basename( __FILE__ ) );
}


