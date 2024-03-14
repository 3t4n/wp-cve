<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('mt_title');
delete_option('mt_width');
delete_option('mt_height');
delete_option('mt_delay');
delete_option('mt_speed');
delete_option('mt_defaulttext');
 
// for site options in Multisite
delete_site_option('mt_title');
delete_site_option('mt_width');
delete_site_option('mt_height');
delete_site_option('mt_delay');
delete_site_option('mt_speed');
delete_site_option('mt_defaulttext');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mt_plugin");