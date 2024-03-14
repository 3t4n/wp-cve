<?php
/**
 * @author William Sergio Minossi
 * @copyright 2023 11 25
 */
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$wptools_options = array(
    'wptools_server_performance',
    'wptools_checkversion',
    'wptools_last_notification_date',
    'wptools_last_notification_date2',
    'wptools_activated_notice',
    'wptools_was_activated',
    'wptools_activated_pointer',
    'wptools_dismiss',
    'wptools_dismiss_language',
    'wptools_plugin_error',
    'wptools_radio_email_weekly_error_notification',
    'wptools_disable_ziparchive'
);


// Apaga todas as opções no site atual
foreach ($wptools_options as $option_name => $option_value) {
    if (is_multisite()) {
        // Apaga a opção no site atual em uma instalação multisite
        delete_site_option($option_name);
    } else {
        // Apaga a opção no site único
        delete_option($option_name);
    }
}

// Drop a custom db table
global $wpdb;
$current_table = $wpdb->prefix . 'wptools_errors';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . 'wptools_page_load_times';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
wp_clear_scheduled_hook('wptools_weekly_cron_job_loadtime');
wp_clear_scheduled_hook('wptools_weekly_cron_job');

?>