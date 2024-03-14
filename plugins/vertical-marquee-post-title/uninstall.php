<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('vmpt_title');
delete_option('vmpt_setting');
delete_option('vmpt_setting1');
delete_option('vmpt_setting2');
delete_option('vmpt_setting3');
delete_option('vmpt_setting4');
 
// for site options in Multisite
delete_site_option('vmpt_title');
delete_site_option('vmpt_setting');
delete_site_option('vmpt_setting1');
delete_site_option('vmpt_setting2');
delete_site_option('vmpt_setting3');
delete_site_option('vmpt_setting4');