<?php
/**
 * @package     Splitit_WooCommerce_Plugin
 *
 * File - create-log-table.php
 * Function for create checkout data table
 */

require_once dirname( __DIR__ ) . '/classes/class-splitit-flexfields-payment-plugin-log.php';

function splitit_flexfields_payment_plugin_create_async_refund_log_table() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'splitit_async_refund_log';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = '';
	if ( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {

		SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'Create_async_refund_log_table: ' . $table_name );

		$sql = 'CREATE TABLE ' . $table_name . " (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) unsigned DEFAULT NULL,
			`order_id` bigint(20) unsigned DEFAULT NULL,
			`ipn` varchar(255) DEFAULT NULL,
			`refund_id` varchar(255) DEFAULT NULL,
			`refund_amount` varchar(255) DEFAULT NULL,
			`refund_reason` varchar(255) DEFAULT NULL,
			`action_type` varchar(255) DEFAULT NULL,
			`comment` varchar(255) DEFAULT NULL,
			`updated_at` datetime NOT NULL,
			PRIMARY KEY (`id`)
		) $charset_collate;";

		SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'sql: ' . $sql );
	}

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	if ( $wpdb->last_error ) {
		SplitIt_FlexFields_Payment_Plugin_Log::log_to_file( 'create_async_refund_log_table Error: ' . $wpdb->last_error );
	}
}
