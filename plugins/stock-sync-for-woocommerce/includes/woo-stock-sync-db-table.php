<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wss_db_version;
$wss_db_version = '1.1';

register_activation_hook( WOO_STOCK_SYNC_FILE, function() {
	wporg_wss_install_db_table();
} );

/**
 * Create / update log table
 */
function wporg_wss_install_db_table() {
	global $wpdb;
	global $wss_db_version;

	$table_name = $wpdb->prefix . 'wss_log';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		product_id bigint(20) unsigned,
		type varchar(255) default '',
		message text,
		data longtext,
		has_error smallint(1) default 0,
		created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	update_option( 'wss_db_version', $wss_db_version );
}

/**
 * Update log table - add "has_error" column
 */
add_action( 'plugins_loaded', function() {
	global $wss_db_version;

	if ( get_option( 'wss_db_version' ) != $wss_db_version ) {
		wporg_wss_install_db_table();
	}
} );
