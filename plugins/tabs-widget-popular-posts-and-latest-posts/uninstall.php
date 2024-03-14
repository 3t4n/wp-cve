<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('tplp_popular_title');
delete_option('tplp_popular_posts');
delete_option('tplp_latest_title');
delete_option('tplp_latest_posts');
 
// for site options in Multisite
delete_site_option('tplp_popular_title');
delete_site_option('tplp_popular_posts');
delete_site_option('tplp_latest_title');
delete_site_option('tplp_latest_posts');