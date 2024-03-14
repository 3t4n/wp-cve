<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('ctsop_height_display_length_s1');
delete_option('ctsop_height_display_length_s2');
delete_option('ctsop_height_display_length_s3');
delete_option('ctsop_speed');
delete_option('ctsop_waitseconds');
 
// for site options in Multisite
delete_site_option('ctsop_height_display_length_s1');
delete_site_option('ctsop_height_display_length_s2');
delete_site_option('ctsop_height_display_length_s3');
delete_site_option('ctsop_speed');
delete_site_option('ctsop_waitseconds');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ctsop_plugin");