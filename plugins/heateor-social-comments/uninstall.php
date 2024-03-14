<?php
//if uninstall not called from WordPress, exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
$heateor_sc_options = get_option( 'heateor_sc' );
if( isset( $heateor_sc_options['delete_options'] ) ) {
	$heateor_sc_options = array(
		'heateor_sc',
		'heateor_sc_feedback_submitted',
		'heateor_sc_version',
		'heateor_sc_plugin_notification_read'
	);
	global $wpdb;
	// For multisite
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		$heateor_sc_blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		$heateor_sc_original_blog_id = $wpdb->blogid;
		foreach ( $heateor_sc_blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			foreach ( $heateor_sc_options as $option ) {
				delete_site_option( $option );
			}
			$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_heateor_sc%'" );
		}
		switch_to_blog( $heateor_sc_original_blog_id );
	} else {
		foreach ( $heateor_sc_options as $option ) {
			delete_option( $option );
		}
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_heateor_sc%'" );
	}
}