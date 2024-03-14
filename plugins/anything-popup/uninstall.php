<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('pop_id');
 
// for site options in Multisite
delete_site_option('pop_id');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}AnythingPopup");