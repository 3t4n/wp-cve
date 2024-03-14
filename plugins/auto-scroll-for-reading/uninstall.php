<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://wpglob.com/
 * @since      1.0.0
 *
 * @package    Auto_Scroll_For_Reading
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if( get_option('wpb_auto_scroll_upgrade_plugin','false') === 'false' ){
    global $wpdb;

    $settings_table = $wpdb->prefix . 'wpgautoscroll_settings';
    $wpdb->query("DROP TABLE IF EXISTS `".$settings_table."`");

    delete_option( "wpg_auto_scroll_db_version");
    delete_option( "wpb_auto_scroll_upgrade_plugin");
}
