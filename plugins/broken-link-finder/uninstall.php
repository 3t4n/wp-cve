<?php
/**
 * This file has code to delete database enteries of plugin when plugin gets uninstalled.
 *
 * @package broken-link-finder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}moblc_link_details_table" );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange -- no caching is required here and for unquoted names %1s is required.
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}moblc_scan_status_table" );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange -- no caching is required here and for unquoted names %1s is required.
$wpdb->query( $wpdb->prepare( 'DELETE FROM %1soptions WHERE `option_name` LIKE "moblc_%"', array( $wpdb->prefix ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.LikeWildcardsInQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
