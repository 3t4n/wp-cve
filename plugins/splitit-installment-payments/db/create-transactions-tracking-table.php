<?php
/**
 * @package     Splitit_WooCommerce_Plugin
 *
 * File - create-log-table.php
 * Function for create transaction tracking table
 */

function splitit_flexfields_payment_plugin_create_transactions_tracking_table() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'splitit_transactions_log';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = '';
	if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
		$sql = "CREATE TABLE $table_name (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NULL DEFAULT NULL,
			order_id bigint(20) unsigned NULL DEFAULT NULL,
			installment_plan_number varchar(100) DEFAULT NULL NULL,
			number_of_installments varchar(100) DEFAULT NULL NULL,
			processing varchar(50) DEFAULT NULL NULL,
			plan_create_succeed tinyint(4) NOT NULL DEFAULT 0,
			date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			FOREIGN KEY (user_id) REFERENCES " . $wpdb->prefix . 'users(ID) ON DELETE CASCADE,
			FOREIGN KEY (order_id) REFERENCES ' . $wpdb->prefix . "posts(ID) ON DELETE CASCADE,
			PRIMARY KEY  (id)
		) $charset_collate;";
	}

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
