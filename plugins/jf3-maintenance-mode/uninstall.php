<?php 

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die;

$option_name = 'wpjf3_mr';

$wpjf3_mr_saved_options = get_option( $option_name );

if ( $wpjf3_mr_saved_options[ "uninstall" ] === true ) {
	
	delete_option( $option_name );
	delete_option( "wpjf3_maintenance_redirect_version" );
	// for site options in Multisite
	delete_site_option( $option_name );
	delete_site_option( "wpjf3_maintenance_redirect_version" );
	// drop database tables
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}{$option_name}_access_keys" );
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}{$option_name}_unrestricted_ips" );
	
}
