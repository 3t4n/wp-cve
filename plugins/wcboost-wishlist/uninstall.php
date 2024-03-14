<?php
/**
 * Uninstall plugin
 */

namespace WCBoost\Wishlist;

// If uninstall not called from WordPress exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Uninstall the plugin.
 *
 * @return void
 */
function uninstall() {
	global $wpdb;

	// Define local private attribute.
	$wpdb->wcboost_wishlists       = $wpdb->prefix . 'wcboost_wishlists';
	$wpdb->wcboost_wishlists_items = $wpdb->prefix . 'wcboost_wishlists_items';

	// Delete option from options table.
	delete_option( 'wcboost_wishlist_version' );
	delete_option( 'wcboost_wishlist_db_version' );

	// Remove any additional options and custom table.
	$sql = "DROP TABLE IF EXISTS `{$wpdb->wcboost_wishlists}`";
	$wpdb->query( $sql );
	$sql = "DROP TABLE IF EXISTS `{$wpdb->wcboost_wishlists_items}`";
	$wpdb->query( $sql );
}

// Check if is multi-site.
if ( ! is_multisite() ) {
	uninstall();
} else {
	global $wpdb;
	$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
	$original_blog_id = get_current_blog_id();

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		uninstall();
	}

	switch_to_blog( $original_blog_id );
}
