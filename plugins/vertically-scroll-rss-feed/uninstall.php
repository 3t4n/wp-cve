<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('gVerticalscroll_rssfeed_title');
delete_option('gVerticalscroll_rssfeed_font');
delete_option('gVerticalscroll_rssfeed_fontsize');
delete_option('gVerticalscroll_rssfeed_fontweight');
delete_option('gVerticalscroll_rssfeed_fontcolor');
delete_option('gVerticalscroll_rssfeed_width');
delete_option('gVerticalscroll_rssfeed_height');
delete_option('gVerticalscroll_rssfeed_slidedirection');
delete_option('gVerticalscroll_rssfeed_slidetimeout');
delete_option('gVerticalscroll_rssfeed_textalign');
delete_option('gVerticalscroll_rssfeed_textvalign');
delete_option('gVerticalscroll_rssfeed_noannouncement');
delete_option('gVerticalscroll_rssfeed_url');
 
// for site options in Multisite
delete_site_option('gVerticalscroll_rssfeed_title');
delete_site_option('gVerticalscroll_rssfeed_font');
delete_site_option('gVerticalscroll_rssfeed_fontsize');
delete_site_option('gVerticalscroll_rssfeed_fontweight');
delete_site_option('gVerticalscroll_rssfeed_fontcolor');
delete_site_option('gVerticalscroll_rssfeed_width');
delete_site_option('gVerticalscroll_rssfeed_height');
delete_site_option('gVerticalscroll_rssfeed_slidedirection');
delete_site_option('gVerticalscroll_rssfeed_slidetimeout');
delete_site_option('gVerticalscroll_rssfeed_textalign');
delete_site_option('gVerticalscroll_rssfeed_textvalign');
delete_site_option('gVerticalscroll_rssfeed_noannouncement');
delete_site_option('gVerticalscroll_rssfeed_url');