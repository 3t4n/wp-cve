<?php
/**
 * Uninstall
 *
 * @package Exif Caption
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;
$option_names = array();
$wp_options = $wpdb->get_results(
	"
	SELECT option_name
	FROM {$wpdb->prefix}options
	WHERE option_name LIKE '%%exifcaption_settings%%'
	"
);
foreach ( $wp_options as $wp_option ) {
	$option_names[] = $wp_option->option_name;
}

/* For Single site */
if ( ! is_multisite() ) {
	$blogusers = get_users( array( 'fields' => array( 'ID' ) ) );
	foreach ( $blogusers as $user ) {
		delete_user_option( $user->ID, 'exifcaption', false );
		delete_user_option( $user->ID, 'excp_per_page', false );
		delete_user_option( $user->ID, 'exifcaption_filter_user', false );
		delete_user_option( $user->ID, 'exifcaption_filter_monthly', false );
		delete_user_option( $user->ID, 'exifcaption_search_text', false );
		delete_user_option( $user->ID, 'exifcaption_current_logs', false );
	}
	foreach ( $option_names as $option_name ) {
		delete_option( $option_name );
	}
} else {
	/* For Multisite */
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->prefix}blogs" );
	$original_blog_id = get_current_blog_id();
	foreach ( $blog_ids as $blogid ) {
		switch_to_blog( $blogid );
		$blogusers = get_users(
			array(
				'blog_id' => $blogid,
				'fields' => array( 'ID' ),
			)
		);
		foreach ( $blogusers as $user ) {
			delete_user_option( $user->ID, 'exifcaption', false );
			delete_user_option( $user->ID, 'excp_per_page', false );
			delete_user_option( $user->ID, 'exifcaption_filter_user', false );
			delete_user_option( $user->ID, 'exifcaption_filter_monthly', false );
			delete_user_option( $user->ID, 'exifcaption_search_text', false );
			delete_user_option( $user->ID, 'exifcaption_current_logs', false );
		}
		foreach ( $option_names as $option_name ) {
			delete_option( $option_name );
		}
	}
	switch_to_blog( $original_blog_id );

	/* For site options. */
	foreach ( $option_names as $option_name ) {
		delete_site_option( $option_name );
	}
}
