<?php

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

if (!class_exists( 'Wugrat_Pro') ) {
    $option_name = 'wugrat_group_order';

    delete_option($option_name);

    // for site options in Multisite
    delete_site_option($option_name);

    // drop a custom database table
    global $wpdb;

    $result = $wpdb->query("ALTER TABLE {$wpdb->prefix}term_taxonomy DROP COLUMN children");
    $result = $wpdb->query("DELETE FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'wugrat_group'");
}
