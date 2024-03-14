<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('Popupwfb_group');
delete_option('Popupwfb_session');
 
// for site options in Multisite
delete_site_option('Popupwfb_group');
delete_site_option('Popupwfb_session');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}popupwith_fancybox");