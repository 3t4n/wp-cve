<?php
/**
 * Fired when the plugin is uninstalled
 *
 * @package AutoClose
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;

if ( is_multisite() ) {

	// Get all blogs in the network and activate plugin on each one.
	$blogids = $wpdb->get_col( //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		"
		SELECT blog_id FROM $wpdb->blogs
		WHERE archived = '0' AND spam = '0' AND deleted = '0'
	"
	);

	foreach ( $blogids as $blogid ) {
		switch_to_blog( $blogid );
		acc_delete_data();
		restore_current_blog();
	}
} else {
	acc_delete_data();
}


/**
 * Delete Data.
 *
 * @since 2.0.0
 */
function acc_delete_data() {

	delete_option( 'acc_settings' );
	delete_option( 'ald_acc_settings' );

	if ( wp_next_scheduled( 'acc_cron_hook' ) ) {
		wp_clear_scheduled_hook( 'acc_cron_hook' );
	}

	if ( wp_next_scheduled( 'ald_acc_hook' ) ) {
		wp_clear_scheduled_hook( 'ald_acc_hook' );
	}

}


