<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://themegrill.com
 * @since      1.0.0
 *
 * @package    Maintenance_Page
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	global $wpdb;

	$old_blog = $wpdb->blogid;
	//Get all blog ids
	$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	foreach ( $blogids as $blog_id ) {
		switch_to_blog( $blog_id );
		maintenance_page_delete_table();
	}
	switch_to_blog( $old_blog );
} else {
	maintenance_page_delete_table();
}

/**
 * Delete custom table and options created by plugin
 *
 * @since 1.0.7
 * @global WPDB $wpdb
 * @return void
 */
function maintenance_page_delete_table() {
	global $wpdb;

	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}maintenance_page" );

	/** Delete all the Plugin Options */
	delete_option( 'mp_basics' );
	delete_option( 'mp_social' );
	delete_option( 'mp_subscribe' );
}
