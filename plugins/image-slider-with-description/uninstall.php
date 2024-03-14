<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('ImgSlider_option_1');
delete_option('ImgSlider_option_2');
 
// for site options in Multisite
delete_site_option('ImgSlider_option_1');
delete_site_option('ImgSlider_option_2');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ImgSlider_plugin");