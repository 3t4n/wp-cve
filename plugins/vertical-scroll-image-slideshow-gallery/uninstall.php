<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('VSslideshow_title');
delete_option('VSslideshow_width');
delete_option('VSslideshow_height');
delete_option('VSslideshow_time');
delete_option('VSslideshow_dir');
delete_option('VSslideshow_imglink');
 
// for site options in Multisite
delete_site_option('VSslideshow_title');
delete_site_option('VSslideshow_width');
delete_site_option('VSslideshow_height');
delete_site_option('VSslideshow_time');
delete_site_option('VSslideshow_dir');
delete_site_option('VSslideshow_imglink');