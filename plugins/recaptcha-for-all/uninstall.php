<?php
/**
 * @author William Sergio Minossi
 * @copyright 2021
 */
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

//grep -r "add_option(" . > work.txt
//grep -r "update_option(" . > work1.txt

$wptools_option_name[] = 'recaptcha_for_all_background_color';
$wptools_option_name[] = 'recaptcha_for_all_foreground_color';
$wptools_option_name[] = 'recaptcha_for_all_btn_background_color';
$wptools_option_name[] = 'recaptcha_for_all_btn_foreground_color';
$wptools_option_name[] = 'recaptcha_for_all_background';
$wptools_option_name[] = 'recaptcha_for_all_button';
$wptools_option_name[] = 'recaptcha_for_all_message';
$wptools_option_name[] = 'recaptcha_for_all_settings_china';
$wptools_option_name[] = 'bill_show_warnings';
$wptools_option_name[] = 'recaptcha_for_all_was_activated';
$wptools_option_name[] = 'recaptcha_for_all_ip_whitelist';
$wptools_option_name[] = 'recaptcha_for_all_string_whitelist';
$wptools_option_name[] = 'recaptcha_dismiss_language';
$wptools_option_name[] = 'recaptcha_for_all_dismiss';
$wptools_option_name[] = 'recaptcha_for_all_image_background';
$wptools_option_name[] = 'recaptcha_for_all_sitekey';
$wptools_option_name[] = 'recaptcha_for_all_secretkey';
$wptools_option_name[] = 'recaptcha_for_all_pages';
$wptools_option_name[] = 'recaptcha_for_all_slugs';
$wptools_option_name[] = 'recaptcha_for_all_settings';
$wptools_option_name[] = 'recaptcha_for_all_settings_provider';
$wptools_option_name[] = 'recaptcha_for_all_recaptcha_score';
$wptools_option_name[] = 'sbb_javascript_error';
$wptools_option_name[] = 'recaptcha_for_all_box_position';
$wptools_option_name[] = 'recaptcha_for_all_box_width';
$wptools_option_name[] = 'recaptcha_for_all_image_option';
$wptools_option_name[] = 'recaptcha_for_all_last_plugin_version';


$wnum = count($wptools_option_name);
for ($i = 0; $i < $wnum; $i++)
{
 delete_option( $wptools_option_name[$i] );
 // For site options in Multisite
 delete_site_option( $wptools_option_name[$i] );    
}

$current_table = $wpdb->prefix . "recaptcha_for_all_stats";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
?>