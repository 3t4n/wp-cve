<?php
/**
 * CoinMotion Uninstall
 *
 * Uninstalling Coinmotion Plugin deletes tables, and options.
 *
 * @version 2.3.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;

$wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "%coinmotion\_%"' );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'coinmotion');
