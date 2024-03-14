<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.kybernetik-services.com/
 * @since      1.0.0
 *
 * @package    WP_Sitemaps_Config
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// if not allowed to delete plugins show error
if ( ! current_user_can( 'delete_plugins' ) ) {
	$text = 'Sorry, you are not allowed to delete plugins for this site.';
	wp_die( esc_html__( $text ) );
}


// clean up the database considering multisite installation
if ( is_multisite() ) {

	// get registered site IDs
	$site_ids = array();
	if ( version_compare( get_bloginfo( 'version' ), '4.6', '>=' ) ) {
		$sites = get_sites();
		foreach ( $sites as $site ) {
			$site_ids[] = $site->id;
		}
	} else {
		$sites = wp_get_sites();
		foreach ( $sites as $site ) {
			$site_ids[] = $site[ 'blog_id' ];
		}
	}

	if ( empty ( $site_ids ) ) return;

	foreach ( $site_ids as $site_id ) {
		// switch to next blog
		switch_to_blog( $site_id );

		// remove settings
		delete_option( 'wp_sitemaps_config_options' );
		
		// delete all post meta data stored under '_wpxmlsitemap_settings'
		//$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE `meta_key` = '_wpxmlsitemap_settings'" );
		delete_metadata( 'post', 1, '_wpxmlsitemap_settings', '', true );
	}
	// restore the current blog, after calling switch_to_blog()
	restore_current_blog();
} else {
	// remove settings
	delete_option( 'wp_sitemaps_config_options' );
	// delete all post meta data stored under '_wpxmlsitemap_settings'
	//$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE `meta_key` = '_wpxmlsitemap_settings'" );
	delete_metadata( 'post', 1, '_wpxmlsitemap_settings', '', true );
}
