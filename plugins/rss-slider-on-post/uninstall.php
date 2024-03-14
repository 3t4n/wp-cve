<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('rssslider_height_display_length_s1');
delete_option('rss_s1');
delete_option('rssslider_height_display_length_s2');
delete_option('rss_s2');
delete_option('rssslider_height_display_length_s3');
delete_option('rss_s3');
delete_option('rssslider_height_display_length_s4');
delete_option('rss_s4');
 
// for site options in Multisite
delete_site_option('rssslider_height_display_length_s1');
delete_site_option('rss_s1');
delete_site_option('rssslider_height_display_length_s2');
delete_site_option('rss_s2');
delete_site_option('rssslider_height_display_length_s3');
delete_site_option('rss_s3');
delete_site_option('rssslider_height_display_length_s4');
delete_site_option('rss_s4');