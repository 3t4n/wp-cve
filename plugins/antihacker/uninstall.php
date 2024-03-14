<?php
/**
 * @author William Sergio Minossi
 * @copyright 2016
 */
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
$antihacker_option_name[] = 'my_radio_xml_rpc';
$antihacker_option_name[] = 'antihacker_rest_api';
$antihacker_option_name[] = 'antihacker_automatic_plugins';
$antihacker_option_name[] = 'antihacker_automatic_themes';
$antihacker_option_name[] = 'antihacker_replace_login_error_msg';
$antihacker_option_name[] = 'antihacker_disallow_file_edit';
$antihacker_option_name[] = 'antihacker_debug_is_true';
$antihacker_option_name[] = 'antihacker_firewall';
$antihacker_option_name[] = 'my_whitelist';
$antihacker_option_name[] = 'my_email_to';
$antihacker_option_name[] = 'antihacker_hide_wp';
$antihacker_option_name[] = 'antihacker_block_enumeration';
$antihacker_option_name[] = 'antihacker_block_all_feeds';
$antihacker_option_name[] = 'antihacker_new_user_subscriber';
$antihacker_option_name[] = 'antihacker_checkversion';
$antihacker_option_name[] = 'antihacker_block_falsegoogle';
$antihacker_option_name[] = 'antihacker_block_search_plugins';
$antihacker_option_name[] = 'antihacker_block_search_themes';
$antihacker_option_name[] = 'antihacker_version';
$antihacker_option_name[] = 'antihacker_block_tor';
$antihacker_option_name[] = 'antihacker_block_false_google';
$antihacker_option_name[] = 'antihacker_block_http_tools';
$antihacker_option_name[] = 'antihacker_blank_ua';
$antihacker_option_name[] = 'antihacker_radio_limit_visits';
$antihacker_option_name[] = 'antihacker_rate_limiting_day';
$antihacker_option_name[] = 'antihacker_rate_limiting';
$antihacker_option_name[] = 'antihacker_rate404_limiting';
$antihacker_option_name[] = 'antihacker_application_password';
$antihacker_option_name[] = 'antihacker_checkbox_all_fail';
$antihacker_option_name[] = 'antihacker_Blocked_Firewall';
$antihacker_option_name[] = 'antihacker_Blocked_else_email';
$antihacker_option_name[] = 'antihacker_my_radio_report_all_logins';
$antihacker_option_name[] = 'antihacker_googlesafe_checked';
$antihacker_option_name[] = 'antihacker_safebrowsing';
$antihacker_option_name[] = 'antihacker_http_tools';
$antihacker_option_name[] = 'antihacker_update_http_tools';
$antihacker_option_name[] = 'anti_hacker_last_feedback';
$antihacker_option_name[] = 'antihacker_optin';
$antihacker_option_name[] = 'antihacker_show_widget';
$antihacker_option_name[] ='antihacker_notif_scan';
$antihacker_option_name[] ='antihacker_notif_level';
$antihacker_option_name[] ='antihacker_notif_visit';
$antihacker_option_name[] ='antihacker_last_scan';
$antihacker_option_name[] ='antihacker_last_plugin_scan';
$antihacker_option_name[] ='antihacker_last_theme_scan';
$antihacker_option_name[] ='antihacker_last_theme_update';
$antihacker_option_name[] ='antihacker_disable_sitemap';

$wnum = count($antihacker_option_name);
for ($i = 0; $i < $wnum; $i++)
{
 delete_option( $antihacker_option_name[$i] );
 // For site options in Multisite
 delete_site_option( $antihacker_option_name[$i] );    
}
// Drop a custom db table
global $wpdb;
$current_table = $wpdb->prefix . 'ah_stats';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . 'ah_blockeds';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . 'ah_visitorslog';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . 'ah_fingerprint';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . "ah_tor";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . "ah_scan_files";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . "ah_scan";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . "ah_rules";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );

register_deactivation_hook(__FILE__, 'antihacker_deactivation');

wp_clear_scheduled_hook('antihacker_weekly_scan');
wp_clear_scheduled_hook('antihacker_cron_hook');
wp_clear_scheduled_hook('antihacker_cron_hook2');
?>