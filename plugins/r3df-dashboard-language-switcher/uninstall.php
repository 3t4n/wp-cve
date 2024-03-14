<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$options = get_option( 'r3df_dashboard_language_switcher' );

if ( $options && is_multisite() ) {
	// Delete widget settings option from options table
	if ( $options['cleanup_on_deactivate'] ) {
		foreach ( wp_get_sites() as $site ) {
			switch_to_blog( $site['blog_id'] );
			delete_option( 'r3df_dashboard_language_switcher' );
			restore_current_blog();
		}
		// Delete the user settings
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->usermeta WHERE meta_key = %s", 'r3df_dashboard_language' ) );
	}
} elseif ( $options ) {
	// Delete widget settings option from options table
	if ( $options['cleanup_on_deactivate'] ) {
		delete_option( 'r3df_dashboard_language_switcher' );
		// Delete the user settings
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->usermeta WHERE meta_key = %s", 'r3df_dashboard_language' ) );
	}
}
