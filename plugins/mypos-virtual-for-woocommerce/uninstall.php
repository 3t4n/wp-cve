<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb, $wp_version;

/*
 * Only remove ALL data if MYPOS_REMOVE_ALL_DATA constant is set to true in user's
 * wp-config.php. This is to prevent data loss when deleting the plugin from the backend
 * and to ensure only the site owner can perform this action.
 */
if (defined( 'MYPOS_REMOVE_ALL_DATA' ) && true === MYPOS_REMOVE_ALL_DATA) {
    include_once dirname(__FILE__) . '/includes/class-mypos-install.php';
    // Tables.
    MyPOS_Install::drop_tables();

    // Delete options.
    $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'mypos\_%';" );
    $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'widget\_mypos\_%';" );

    // Clear any cached data that has been removed.
    wp_cache_flush();
}
