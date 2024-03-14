<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.1
 *
 * @package    Ya_Turbo
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$plugin_tables = implode(',', array(
	$wpdb->prefix . YATURBO_DB_FEEDS
));

$wpdb->query("DROP TABLE IF EXISTS {$plugin_tables}");

delete_option('ya-turbo');