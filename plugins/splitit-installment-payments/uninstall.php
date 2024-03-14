<?php
/**
 * File - uninstall.php
 * Remove DB tables and clear options
 *
 * @package     Splitit_WooCommerce_Plugin
 */

// @if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

$option_name = 'woocommerce_splitit_settings';

delete_option( $option_name );

// @for site options in Multisite
delete_site_option( $option_name );

// @drop a custom database table
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}splitit_log" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}splitit_order_data_with_ipn" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}splitit_transactions_log" );
