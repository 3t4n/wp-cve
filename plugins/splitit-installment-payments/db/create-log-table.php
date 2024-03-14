<?php
/**
 * @package     Splitit_WooCommerce_Plugin
 *
 * File - create-log-table.php
 * Function for create log table
 */

function splitit_flexfields_payment_plugin_create_log_table() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'splitit_log';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = '';
	if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
		$sql = "CREATE TABLE $table_name (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NULL DEFAULT NULL,
			method varchar(191) DEFAULT NULL NULL,
			message TEXT DEFAULT NULL NULL,
			date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			FOREIGN KEY (user_id) REFERENCES " . $wpdb->prefix . "users(ID) ON DELETE CASCADE,
			PRIMARY KEY  (id)
		) $charset_collate;";
	}

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
