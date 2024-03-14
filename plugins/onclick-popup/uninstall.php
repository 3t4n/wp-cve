<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('onclickpopup_title');
delete_option('onclickpopup_setting1');
delete_option('onclickpopup_setting1_left');
delete_option('onclickpopup_setting1_top');
delete_option('onclickpopup_setting2');
delete_option('onclickpopup_setting2_left');
delete_option('onclickpopup_setting2_top');
delete_option('onclickpopup_setting3');
delete_option('onclickpopup_setting3_left');
delete_option('onclickpopup_setting3_top');
delete_option('onclickpopup_setting4');
delete_option('onclickpopup_setting4_left');
delete_option('onclickpopup_setting4_top');
delete_option('onclickpopup_setting5');
delete_option('onclickpopup_setting5_left');
delete_option('onclickpopup_setting5_top');
 
// for site options in Multisite
delete_site_option('onclickpopup_title');
delete_site_option('onclickpopup_setting1');
delete_site_option('onclickpopup_setting1_left');
delete_site_option('onclickpopup_setting1_top');
delete_site_option('onclickpopup_setting2');
delete_site_option('onclickpopup_setting2_left');
delete_site_option('onclickpopup_setting2_top');
delete_site_option('onclickpopup_setting3');
delete_site_option('onclickpopup_setting3_left');
delete_site_option('onclickpopup_setting3_top');
delete_site_option('onclickpopup_setting4');
delete_site_option('onclickpopup_setting4_left');
delete_site_option('onclickpopup_setting4_top');
delete_site_option('onclickpopup_setting5');
delete_site_option('onclickpopup_setting5_left');
delete_site_option('onclickpopup_setting5_top');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}onclick_popup_plugin");