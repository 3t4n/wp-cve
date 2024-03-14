<?php
/**
 * Sibs Plugin Installation process
 *
 * This file is used for creating tables while installing the plugins.
 * Copyright (c) SIBS
 *
 * @package Sibs
 * @located at  /
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * Activation process
 */
function sibs_activation_process() {
	create_sibs_table();
	create_sibs_page();
}

/**
 * Uninstallation process
 */
function sibs_uninstallation_process() {
	delete_sibs_table();
	delete_sibs_page();
}

/**
 * Creates Sibs tables while activating the plugins
 * Calls from the hook "register_activation_hook"
 */
function create_sibs_table() {
	global $wpdb;
	$wpdb->hide_errors();
	$charset_collate = $wpdb->get_charset_collate();
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	if ( ! get_option( 'sibs_db_version' ) || get_option( 'sibs_db_version' ) !== SIBS_VERSION ) {
		$transaction_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sibs_transaction (
			`id` int(20) unsigned NOT NULL AUTO_INCREMENT,
			`order_no` bigint(20) unsigned NOT NULL,
			`payment_type` varchar(50) NOT NULL,
			`reference_id` varchar(50) NOT NULL,
			`payment_brand` varchar(100) NOT NULL,
			`transaction_id` varchar(100),
			`payment_id` varchar(30),
			`payment_status` varchar(30),
			`amount` decimal(17,2) NOT NULL,
			`refunded_amount` decimal(17,2) DEFAULT '0',
			`currency` char(3) NOT NULL,
			`customer_id` int(11) unsigned DEFAULT NULL,
			`date` datetime NOT NULL,
			`additional_information` LONGTEXT NULL,
			`active` tinyint(1) unsigned NOT NULL DEFAULT '1',
			PRIMARY KEY (`id`)
		 ) $charset_collate;";
		dbDelta( $transaction_sql );

		$recurring_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sibs_payment_information (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`cust_id` INT(11) NOT NULL,
			`payment_group` VARCHAR(6),
			`brand` VARCHAR(100),
			`holder` VARCHAR(100) NULL default NULL,
			`email` VARCHAR(100) NULL default NULL,
			`last4digits` VARCHAR(4),
			`expiry_month` VARCHAR(2),
			`expiry_year` VARCHAR(4),
			`server_mode` VARCHAR(4) NOT NULL,
			`channel_id` VARCHAR(32) NOT NULL,
			`reg_id` VARCHAR(32),
			`payment_default` boolean NOT NULL default '0',
			PRIMARY KEY (`id`)
		 ) $charset_collate;";
		dbDelta( $recurring_sql );

		if ( ! get_option( 'sibs_version' ) ) {
			add_option( 'sibs_version', SIBS_VERSION );
		} elseif ( get_option( 'sibs_version' ) !== SIBS_VERSION ) {
			update_option( 'sibs_version', SIBS_VERSION );
		}
	}// End if().
}

/**
 * Deletes Sibs settings values from wp_options tables
 * Calls from the hook "register_deactivation_hook"
 */
function delete_sibs_table() {
	global $wpdb;
	$wpdb->query( "delete from $wpdb->options where option_name like '%sibs%'" ); // db call ok; no-cache ok.
}

/**
 * Creates Sibs my payment information pages
 * Calls from the hook "register_activation_hook"
 */
function create_sibs_page() {
	global $wpdb;

	$the_page_title = 'My Payment Information';
	$the_page_name  = 'my-payment-information';

	// add the menu entry.
	delete_option( 'my_plugin_page_title' );
	add_option( 'my_plugin_page_title', $the_page_title, '', 'yes' );
	// add the slug.
	delete_option( 'my_plugin_page_name' );
	add_option( 'my_plugin_page_name', $the_page_name, '', 'yes' );
	// add the id.
	delete_option( 'my_plugin_page_id' );
	add_option( 'my_plugin_page_id', '0', '', 'yes' );

	$the_page = get_page_by_title( $the_page_title );

	if ( ! $the_page ) {
		// Create post object.
		$page_configs                   = array();
		$page_configs['post_title']     = $the_page_title;
		$page_configs['post_content']   = '[woocommerce_my_payment_information]';
		$page_configs['post_status']    = 'publish';
		$page_configs['post_type']      = 'page';
		$page_configs['comment_status'] = 'closed';
		$page_configs['ping_status']    = 'closed';
		$page_configs['post_category']  = array( 1 );

		// Insert configurations into the database.
		$the_page_id = wp_insert_post( $page_configs );
	} else {
		// the plugin may have been previously active and the page may just be trashed.
		$the_page_id = $the_page->ID;

		// make sure the page is not trashed.
		$the_page->post_status = 'publish';
		$the_page_id           = wp_update_post( $the_page );

		delete_option( 'my_plugin_page_id' );
		add_option( 'my_plugin_page_id', $the_page_id );
	}
}

/**
 * Deletes Sibs my payment information pages
 * Calls from the hook "register_deactivation_hook"
 */
function delete_sibs_page() {
	global $wpdb;

	$the_page_title = get_option( 'my_plugin_page_title' );
	$the_page_name  = get_option( 'my_plugin_page_name' );

	// the id of our page.
	$the_page_id = get_option( 'my_plugin_page_id' );
	if ( $the_page_id ) {
		wp_trash_post( $the_page_id ); // trash this page.
		wp_delete_post( $the_page_id ); // delete this page from trash.

	}

	delete_option( 'my_plugin_page_title' );
	delete_option( 'my_plugin_page_name' );
	delete_option( 'my_plugin_page_id' );
}
