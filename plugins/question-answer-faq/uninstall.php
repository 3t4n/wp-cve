<?php
/**
 * Mideal Question answer Uninstall
 *
 * Uninstalling Question answer posts.
 *
 * @author  mideal
 * @package Question answer
 * @version 1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

/*
 * Only remove ALL product and page data if WC_REMOVE_ALL_DATA constant is set to true in user's
 * wp-config.php. This is to prevent data loss when deleting the plugin from the backend
 * and to ensure only the site owner can perform this action.
 */
if ( defined( 'MIDEAL_REMOVE_ALL_DATA' ) && true === MIDEAL_REMOVE_ALL_DATA ) {

	// Delete options.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'mideal\_faq\_%';" );

	// Delete posts.
	$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( 'mideal_faq');" );
	$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

	// Delete orphan relationships
	$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

	// Clear any cached data that has been removed
	wp_cache_flush();
}
