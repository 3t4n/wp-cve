<?php
/**
 * Functions to activate/deactivate the plugin.
 *
 * @since 2.0.0
 *
 * @package AutoClose
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Fired for each blog when the plugin is activated.
 *
 * @since   1.0
 *
 * @param    boolean $network_wide    True if WPMU superadmin uses
 *                                    "Network Activate" action, false if
 *                                    WPMU is disabled or plugin is
 *                                    activated on an individual blog.
 */
function acc_install( $network_wide ) {
	global $wpdb;

	if ( is_multisite() && $network_wide ) {

		// Get all blogs in the network and activate plugin on each one.
		$blog_ids = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			"
        	SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0' AND deleted = '0'
		"
		);
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			acc_single_activate();
		}

		// Switch back to the current blog.
		restore_current_blog();

	} else {
		acc_single_activate();
	}
}
register_activation_hook( ACC_PLUGIN_FILE, 'acc_install' );


/**
 * Activation function for single blogs.
 *
 * @return void
 */
function acc_single_activate() {
	acc_get_settings();
}
