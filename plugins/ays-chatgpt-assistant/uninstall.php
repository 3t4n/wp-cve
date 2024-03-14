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
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Ays_Chatgpt_Assistant
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
if( get_option('ays_chatgpt_assistant_upgrade_plugin', 'false') === 'false' ){
    global $wpdb;

    $data_table    = $wpdb->prefix . 'ayschatgpt_data';
    $setting_table = $wpdb->prefix . 'ayschatgpt_settings';
    $front_setting_table = $wpdb->prefix . 'ayschatgpt_front_settings';
    $general_settings_table = $wpdb->prefix . 'ayschatgpt_general_settings';
    $rates_table = $wpdb->prefix . 'ayschatgpt_rates';

    $wpdb->query("DROP TABLE IF EXISTS `".$data_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$setting_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$front_setting_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$general_settings_table."`");
    $wpdb->query("DROP TABLE IF EXISTS `".$rates_table."`");

    delete_option( "ays_chatgpt_assistant_db_version");
    delete_option( "ays_chatgpt_assistant_upgrade_plugin");
}