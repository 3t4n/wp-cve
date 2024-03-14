<?php
/**
 * Uninstall file, which would delete all user metadata and configuration settings
 *
 * @since 1.0
 */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

global $wpdb;

$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '%cs_ld_addon%';");
$wpdb->query("DELETE FROM $wpdb->options WHERE option_name='csld_version';");