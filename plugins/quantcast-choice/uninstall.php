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
 * @link       https://www.quantcast.com
 * @since      1.0.0
 *
 * @package    QC_Choice
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clean out the plugin option values on uninstall
delete_option('qc_choice_vendor_list_version');
delete_option('qc_choice_language');
delete_option('qc_choice_auto_localize');
delete_option('qc_choice_display_ui');
delete_option('qc_choice_display_layout');
delete_option('qc_choice_min_days_between_ui_displays');
delete_option('qc_choice_non_consent_display_frequency');
delete_option('qc_choice_post_consent_page');
delete_option('qc_choice_publisher_name');
delete_option('qc_choice_publisher_logo');
delete_option('qc_choice_initial_screen_title_text');
delete_option('qc_choice_initial_screen_body_text');
delete_option('qc_choice_initial_screen_reject_button_text');
delete_option('qc_choice_initial_screen_no_option');
delete_option('qc_choice_initial_screen_accept_button_text');
delete_option('qc_choice_initial_screen_purpose_link_text');
delete_option('qc_choice_purpose_screen_header_title_text');
delete_option('qc_choice_purpose_screen_title_text');
delete_option('qc_choice_purpose_screen_body_text');
delete_option('qc_choice_purpose_screen_enable_all_button_text');
delete_option('qc_choice_purpose_screen_vendor_link_text');
delete_option('qc_choice_purpose_screen_cancel_button_text');
delete_option('qc_choice_purpose_screen_save_and_exit_button_text');
delete_option('qc_choice_vendor_screen_title_text');
delete_option('qc_choice_vendor_screen_body_text');
delete_option('qc_choice_vendor_screen_accept_all_button_text');
delete_option('qc_choice_vendor_screen_reject_all_button_text');
delete_option('qc_choice_vendor_screen_purposes_link_text');
delete_option('qc_choice_vendor_screen_cancel_button_text');
delete_option('qc_choice_vendor_screen_save_and_exit_button_text');
delete_option('qc_choice_vendors');
delete_option('qc_choice_initial_screen_custom_link_1_text');
delete_option('qc_choice_initial_screen_custom_link_1_url');
delete_option('qc_choice_initial_screen_custom_link_2_text');
delete_option('qc_choice_initial_screen_custom_link_2_url');
