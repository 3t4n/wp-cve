<?php
/**
 * Install Function
 *
 * @package     Church Tithe WP
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Install
 *
 * Runs on plugin install
 *
 * @since 1.0
 * @global $wpdb
 * @param  bool $network_wide If the plugin is being network-activated.
 * @return void
 */
function church_tithe_wp_install( $network_wide = false ) {
	global $wpdb;

	if ( is_multisite() && $network_wide ) {

		foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) { // phpcs:ignore

			switch_to_blog( $blog_id );
			church_tithe_wp_run_install();
			restore_current_blog();

		}
	} else {

		church_tithe_wp_run_install();

	}

	// Get the date when the threshold was last reset.
	$last_reset_timestamp = get_option( 'ctwp_threshold_reset' );

	if ( empty( $last_reset_timestamp ) ) {
		update_option( 'ctwp_threshold_reset', time() );
	}

	update_option( 'church_tithe_wp_just_activated', true );

}
register_activation_hook( CHURCH_TITHE_WP_PLUGIN_FILE, 'church_tithe_wp_install' );

/**
 * Run the Church_Tithe_WP Install process
 *
 * @since  1.0.0
 * @return void
 */
function church_tithe_wp_run_install() {

	// Create the databases.
	church_tithe_wp()->transactions_db->create_table();
	church_tithe_wp()->arrangements_db->create_table();

	// Create the Apple Pay verification file in the site root.
	church_tithe_wp_create_apple_verification_file();

}

/**
 * When a new Blog is created in multisite, see if Church Tithe WP is network activated, and run the installer
 *
 * @since  1.0.0
 * @param  int    $blog_id The Blog ID created.
 * @param  int    $user_id The User ID set as the admin.
 * @param  string $domain  The URL.
 * @param  string $path    Site Path.
 * @param  int    $site_id The Site ID.
 * @param  array  $meta    Blog Meta.
 * @return void
 */
function church_tithe_wp_new_blog_created( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

	if ( is_plugin_active_for_network( plugin_basename( CHURCH_TITHE_WP_PLUGIN_FILE ) ) ) {

		switch_to_blog( $blog_id );
		church_tithe_wp_install();
		restore_current_blog();

	}

}
add_action( 'wpmu_new_blog', 'church_tithe_wp_new_blog_created', 10, 6 );

/**
 * Drop our custom tables when a mu site is deleted
 *
 * @since  1.0.0
 * @param  array $tables  The tables to drop.
 * @param  int   $blog_id The Blog ID being deleted.
 * @return array          The tables to drop
 */
function church_tithe_wp_wpmu_drop_tables( $tables, $blog_id ) {

	switch_to_blog( $blog_id );
	$transactions_db = new Church_Tithe_WP_Transactions_DB();
	if ( $transactions_db->installed() ) {
		$tables[] = $transactions_db->table_name;
	}
	$transactions_db = new Church_Tithe_WP_Arrangements_DB();
	if ( $arrangements_db->installed() ) {
		$tables[] = $arrangements_db->table_name;
	}
	restore_current_blog();

	return $tables;

}
add_filter( 'wpmu_drop_tables', 'church_tithe_wp_wpmu_drop_tables', 10, 2 );

/**
 * Flush the rewrite rules after activation
 *
 * @since  1.0.0
 * @return void
 */
function church_tithe_wp_handle_after_activation_actions() {

	$church_tithe_wp_just_activated = get_option( 'church_tithe_wp_just_activated' );

	if ( ! $church_tithe_wp_just_activated ) {
		return;
	}

	// Delete the just activated flag.
	delete_option( 'church_tithe_wp_just_activated' );

	// Flush the rewrite rules.
	flush_rewrite_rules();

}
add_action( 'shutdown', 'church_tithe_wp_handle_after_activation_actions' );
