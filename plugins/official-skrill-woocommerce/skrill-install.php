<?php
/**
 * Skrill Plugin Installation process
 *
 * This file is used for creating tables while installing the plugins.
 * Copyright (c) Skrill
 *
 * @package Skrill
 * @located at  /
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Activate Skrill plugin
 */
function skrill_activate_plugin() {
	skrill_create_table();
	skrill_update_v1_0_09();
	skrill_update_configuration();
}

/**
 * Deactivate Skrill plugin
 */
function skrill_uninstall_plugin() {
	skrill_delete_options();
}

/**
 * Skrill update configuration
 */
function skrill_update_configuration() {
	$skrill_version = get_option( 'skrill_version' );
	if ( ! $skrill_version ) {
		add_option( 'skrill_version', SKRILL_PLUGIN_VERSION );
	} else {
		update_option( 'skrill_version', SKRILL_PLUGIN_VERSION );
	}

	$skrill_completed_status = get_option( 'skrill_completed_status' );
	if ( ! $skrill_completed_status ) {
		add_option( 'skrill_completed_status', 'processing' );
	}

	$payment_methods[] = 'Skrill_Flexible';
	$payment_methods[] = 'Skrill_WLT';
	$payment_methods[] = 'Skrill_PSC';
	$payment_methods[] = 'Skrill_ACC';
	$payment_methods[] = 'Skrill_VSA';
	$payment_methods[] = 'Skrill_MSC';
	$payment_methods[] = 'Skrill_MAE';
	$payment_methods[] = 'Skrill_OBT';
	$payment_methods[] = 'Skrill_GIR';
	$payment_methods[] = 'Skrill_SFT';
	$payment_methods[] = 'Skrill_IDL';
	$payment_methods[] = 'Skrill_PLI';
	$payment_methods[] = 'Skrill_PWY';
	$payment_methods[] = 'Skrill_EPY';
	$payment_methods[] = 'Skrill_ALI';
	$payment_methods[] = 'Skrill_NTL';
	$payment_methods[] = 'Skrill_ADB';
	$payment_methods[] = 'Skrill_AOB';
	$payment_methods[] = 'Skrill_ACI';
	$payment_methods[] = 'Skrill_PCH';
	$payment_methods[] = 'Skrill_SPX';
	$payment_methods[] = 'Skrill_BLK';
	$payment_methods[] = 'Skrill_PGF';
	$payment_methods[] = 'Skrill_EWLTID';
	$payment_methods[] = 'Skrill_EWLTPH';
	$payment_methods[] = 'Skrill_EWLTKR';
	$payment_methods[] = 'Skrill_MUB';
	$payment_methods[] = 'Skrill_MBW';

	foreach ( $payment_methods as $payment_method ) {
		$value['enabled'] = 'yes';
		if ( 'Skrill_ACC' === $payment_method ) {
			$value['show_separately'] = 'yes';
		}

		update_option( 'woocommerce_' . strtolower( $payment_method ) . '_settings', $value );

		$value = array();
	}
}

/**
 * Create Skrill transaction log table
 */
function skrill_create_table() {
	global $wpdb;
	$wpdb->hide_errors();
	$charset_collate = $wpdb->get_charset_collate();
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$transaction_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}skrill_transaction_log (
        `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
        `order_id` bigint(20) unsigned NOT NULL,
        `transaction_id` varchar(100),
        `mb_transaction_id` varchar(50) NOT NULL,
        `payment_method_id` varchar(30),
        `payment_type` varchar(16) NOT NULL,
        `payment_status` varchar(30),
        `amount` decimal(17,2) NOT NULL,
        `refunded_amount` decimal(17,2) DEFAULT '0',
        `currency` char(3) NOT NULL,
        `customer_id` int(11) unsigned DEFAULT NULL,
        `date` datetime NOT NULL,
        `additional_information` LONGTEXT NULL,
        `payment_response` LONGTEXT NULL,
        `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`)
    ) $charset_collate;";
	dbDelta( $transaction_sql );
}

/**
 * Delete Skrill options
 */
function skrill_delete_options() {
	global $wpdb;
	$wpdb->query( $wpdb->prepare( 'delete from ' . $wpdb->options . ' where option_name like %s', 'skrill%' ) ); // db call ok, no-cache ok.
}

/**
 * Skrill update to version 1.1.09
 */
function skrill_update_v1_0_09() {
	$skrill_version = get_option( 'skrill_version' );
	if ( isset( $skrill_version )
		&& version_compare( $skrill_version, '1.0.09', '<' )
		&& version_compare( SKRILL_PLUGIN_VERSION, '1.0.09', '>=' )
	) {
		change_skrill_order_status_to_default_wc_order_status();
	}
}

/**
 * Change skrill order status to default wc order status
 */
function change_skrill_order_status_to_default_wc_order_status() {
	skrill_change_order_status( 'wc-skrill-accepted', 'completed' );
	skrill_change_order_status( 'wc-skrill-fraud', 'on-hold' );
	skrill_change_order_status( 'wc-skrill-invalid', 'on-hold' );
}

/**
 * Skrill change order status
 *
 * @param array  $old_status - old order status.
 * @param string $new_status - new order status.
 */
function skrill_change_order_status( $old_status, $new_status ) {
	global $wpdb;

	$wpdb->hide_errors();

	$orders = $wpdb->get_results( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_status = %s', $old_status ), ARRAY_A ); // db call ok; no-cache ok.

	if ( ! empty( $orders ) ) {
		foreach ( $orders as $key => $value ) {
			$wc_order = new WC_Order( $value['ID'] );
			$wc_order->update_status( $new_status, 'order_note' );
		}
	}
}
