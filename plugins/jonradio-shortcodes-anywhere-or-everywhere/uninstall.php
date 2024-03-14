<?php
//	Ensure call comes from WordPress, not a hacker or anyone else trying direct access.
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

	
/*  Remove any tables, options, and such created by this Plugin.

	Check for is_multisite() function even existing as could be very old Version of WordPress.
*/
if ( function_exists('is_multisite') && is_multisite() ) {
	/*	delete_site_option( 'jr_saoe_network_settings' );
		would go here.
		
		Support old versions of WordPress before wp_get_sites() existed.
	*/
	global $wpdb, $site_id;
	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = $site_id" );
	foreach ($blogs as $blog_obj) {
		delete_blog_option( $blog_obj->blog_id, 'jr_saoe_settings' );
	}
} else {
	delete_option( 'jr_saoe_settings' );
}
?>