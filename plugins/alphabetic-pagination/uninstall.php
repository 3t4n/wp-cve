<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

$option_name = '';

if($option_name!=''){
	delete_option( $option_name );	
	// For site options in multisite
	delete_site_option( $option_name );  
}

//drop a custom db table
global $wpdb;
$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'ap_%'" );

//note in multisite looping through blogs to delete options on each blog does not scale. You'll just have to leave them.