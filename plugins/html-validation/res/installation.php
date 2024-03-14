<?php
/**
 * INSTALLATION FUNCTIONS
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Redirect to settings page
 **/
function html_validation_activation_redirect( $plugin ) {
	if ( 'html-validation/html-validation.php' == $plugin ) {
		// Don't forget to exit() because wp_redirect doesn't exit automatically.
		wp_redirect( esc_url( get_site_url() ) . '/wp-admin/admin.php?page=html_validation/settings.php' );
		exit();
	}
}
add_action( 'activated_plugin', 'html_validation_activation_redirect' );

/**
 * Activate plugin
 **/
function html_validation_install( $network_wide = false ) {

	global $wpdb;

	if ( is_multisite() ) {
		// Get all blogs in the network and activate plugin on each one.
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );

			html_validation_create_tables();
			restore_current_blog();
		}
	} else {
		html_validation_create_tables();
	}
}

/**
 * Create database table
 **/
function html_validation_create_tables() {
	global $wpdb;

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = 'CREATE TABLE ' . $wpdb->prefix . 'html_validation_errors (
		errorid int(11) NOT NULL AUTO_INCREMENT, 
		linkid int(11) NOT NULL, 
		errorignre mediumint(9) NOT NULL, 
		error text NOT NULL, 
        errortype text NOT NULL, 
        linktype text NOT NULL,
		errorcode mediumtext NOT NULL, 
        purgemarker mediumtext NOT NULL, 
        adamarker mediumint(9) NOT NULL,
        autocorrect_marker mediumint(9) NOT NULL,
		PRIMARY KEY  (errorid)
	) ' . $charset_collate . ';';

	dbDelta( $sql );

		$sql2 = ' CREATE TABLE ' . $wpdb->prefix . 'html_validation_links (
		linkid int(11) NOT NULL AUTO_INCREMENT, 
		link text NOT NULL, 
         postid int(11) NOT NULL, 
        title text NOT NULL,
		type text NOT NULL,
        subtype text NOT NULL,
        scanflag mediumint(9) NOT NULL, 
        locateflag mediumint(9) NOT NULL, 
		linkignre mediumint(9) NOT NULL, 
		PRIMARY KEY  (linkid)
	) ' . $charset_collate . ';';

	dbDelta( $sql2 );

	// schedule daily scans.
	if ( ! wp_next_scheduled( 'html_validation_auto_scan_cron_hook' ) ) {
		wp_schedule_event( time(), 'daily', 'html_validation_auto_scan_cron_hook' );
	}
	// schedule initial scan.
	if ( ! wp_next_scheduled( 'html_validation_inital_scan_cron_hook' ) ) {
		wp_schedule_event( time(), 'htmlvalidation5minutes', 'html_validation_initial_scan_cron_hook' );
	}
}
/**
 * Deactivate hook
 **/
function html_validation_deactivate() {
			// clear crons on uninstall.
		wp_clear_scheduled_hook( 'html_validation_auto_scan_cron_hook' );
	wp_clear_scheduled_hook( 'html_validation_initial_scan_cron_hook' );
}

/**
 * UNINSTALL FUNCTIONS
 **/
function html_validation_uninstall() {
	global $wpdb;

	if ( is_multisite() ) {

		// Get all blogs in the network and activate plugin on each one.
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			html_validation_delete_tables();
			html_validation_remove_options();

			// clear crons.
			wp_clear_scheduled_hook( 'html_validation_auto_scan_cron_hook' );

			restore_current_blog();
		}
	} else {
		html_validation_delete_tables();
		html_validation_remove_options();

		// clear crons on uninstall.
		wp_clear_scheduled_hook( 'html_validation_auto_scan_cron_hook' );
	}
}
/**
 * Remove tables
 **/
function html_validation_delete_tables() {
	global $wpdb;

	$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'html_validation_errors' );

	$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'html_validation_links' );
}

/**
 * Remove options
 **/
function html_validation_remove_options() {
	foreach ( wp_load_alloptions() as $option => $value ) {
		if ( strpos( $option, 'html_validation_' ) === 0 && ! strstr( $option, '_pro_' ) ) {
			delete_option( $option );
		}
	}
}

/**
 * Deleting the table whenever a blog is deleted
 **/
function html_validation_delete_blog( $tables ) {
	global $wpdb;
	$tables[] = $wpdb->prefix . 'html_validation_errors';
	$tables[] = $wpdb->prefix . 'html_validation_links';

	return $tables;
}
add_filter( 'wpmu_drop_tables', 'html_validation_delete_blog' );

/**
 * Check version number for database updates
 */
function html_validation_version() {
	if ( ! is_admin() ) {
		return 0;
	}
	$current_version       = html_validation_plugin_get( 'Version' );
	$stored_option_version = get_option( 'html_validation_version' );
	if ( $current_version != $stored_option_version ) {
		html_validation_install();

		// run link inventory to set postid to termid for terms (support for ada compliance check).
		if ( '' != $stored_option_version && version_compare( $stored_option_version, '1.0.5', '<=' ) ) {
			html_validation_inventory_term_links();
		}

		update_option( 'html_validation_version', $current_version );
	}
}
add_action( 'admin_init', 'html_validation_version' );



/**
 * Inventory terms
 **/
function html_validation_inventory_term_links() {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'term_taxonomy inner join ' . $wpdb->prefix . 'terms ON  ' . $wpdb->prefix . 'term_taxonomy.term_id = ' . $wpdb->prefix . 'terms.term_id where %d', 1 ), ARRAY_A );
	$terms   = get_option( 'html_validation_terms', array( 'category' ) );
	if ( ! is_array( $terms ) ) {
		$terms = array();
	}
	foreach ( $results as $row ) {
		$link = get_term_link( (int) $row['term_id'], $row['taxonomy'] );

		if ( is_string( $link ) ) {
			$link = esc_url_raw( $link );
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set postid = %d where link = %s ', $row['term_id'], $link ) );  }
	}
}
