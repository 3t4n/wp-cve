<?php
/**
 * This File is used for plugin header.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if accessed directly.
}
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	if ( isset( $user_role_permission ) && count( $user_role_permission ) > 0 ) {
		foreach ( $user_role_permission as $permission ) {
			if ( current_user_can( $permission ) ) {
				$access_granted = true;
				break;
			}
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {
		// Premium Edition.
		$cpo_message_premium_edition = __( 'This feature is available only in Premium Editions! <br> Kindly Purchase to unlock it!', 'wp-clean-up-optimizer' );
		$cpo_upgrade                 = __( 'Premium Edition', 'wp-clean-up-optimizer' );
		$cpo_upgrade_know_about      = __( 'Know about', 'wp-clean-up-optimizer' );
		$cpo_full_features           = __( 'Full Features', 'wp-clean-up-optimizer' );
		$cpo_chek_our                = __( 'or check our', 'wp-clean-up-optimizer' );
		$cpo_online_demos            = __( 'Online Demos', 'wp-clean-up-optimizer' );

		// Footer.
		$cpo_settings_saved                     = __( 'Settings Saved!', 'wp-clean-up-optimizer' );
		$cpo_advance_security_manage_ip_address = __( 'Blocked Successfully!', 'wp-clean-up-optimizer' );
		$cpo_delete_data                        = __( 'Data Deleted!', 'wp-clean-up-optimizer' );
		$cpo_empty_manual_clean_up_data         = __( 'Cleaned Successfully!', 'wp-clean-up-optimizer' );
		$cpo_optimize_tables                    = __( 'Optimized Successfully!', 'wp-clean-up-optimizer' );
		$cpo_repair_table                       = __( 'Repaired Successfully!', 'wp-clean-up-optimizer' );
		$cpo_country_unblocks                   = __( 'Unblocked Successfully!', 'wp-clean-up-optimizer' );
		$cpo_add_db_schedule                    = __( 'Scheduled Successfully!', 'wp-clean-up-optimizer' );
		$cpo_choose_delete                      = __( 'Please Choose to Proceed!', 'wp-clean-up-optimizer' );
		$cpo_perform_action                     = __( 'Are you sure?', 'wp-clean-up-optimizer' );
		$cpo_location                           = __( 'Location', 'wp-clean-up-optimizer' );
		$cpo_latitude                           = __( 'Latitude', 'wp-clean-up-optimizer' );
		$cpo_longitude                          = __( 'Longitude', 'wp-clean-up-optimizer' );
		$cpo_http_user_agent                    = __( 'HTTP User Agent', 'wp-clean-up-optimizer' );
		$cpo_not_available                      = __( 'N/A', 'wp-clean-up-optimizer' );
		$cpo_valid_ip_address_message           = __( 'Please provide valid IP Address', 'wp-clean-up-optimizer' );
		$cpo_valid_ip_address_title             = __( 'Error Message', 'wp-clean-up-optimizer' );
		$cpo_duplicate_ip_address_title         = __( 'Notification!', 'wp-clean-up-optimizer' );
		$cpo_valid_ip_range_message             = __( 'Please provide valid IP Range', 'wp-clean-up-optimizer' );
		$cpo_duplicate_ip_range                 = __( 'Already Blocked!', 'wp-clean-up-optimizer' );
		$cpo_success                            = __( 'Success!', 'wp-clean-up-optimizer' );
		$cpo_block_own_ip_address               = __( 'You can\'t block your own IP Address!', 'wp-clean-up-optimizer' );
		$cpo_block_own_ip_range                 = __( 'You can\'t block this IP Range as your IP Address lies between it!', 'wp-clean-up-optimizer' );

		// database view records.
		$cpo_database_manual_clean_up_view_records_label = __( 'Database - View Records', 'wp-clean-up-optimizer' );
		$cpo_rows                                        = __( 'Rows', 'wp-clean-up-optimizer' );
		$cpo_table_size                                  = __( 'Table Size', 'wp-clean-up-optimizer' );
		$cpo_database_view_record_back_button_label      = __( '<< Back to Manual Clean Up', 'wp-clean-up-optimizer' );

		$cpo_database_view_record_back_button_label = __( '<< Back to Database Optimizer', 'wp-clean-up-optimizer' );
		$cpo_add_new_wordpress_optimizer_schedule   = __( 'WordPress - Add New Schedule', 'wp-clean-up-optimizer' );
		$cpo_update_wordpress_optimizer_schedule    = __( 'WordPress - Update Schedule', 'wp-clean-up-optimizer' );
		$cpo_add_new_database_schedule              = __( 'Database - Add New Schedule', 'wp-clean-up-optimizer' );
		$cpo_update_database_schedule               = __( 'Database - Update Schedule', 'wp-clean-up-optimizer' );

		// recent Login Logs.
		$cpo_recent_logins_on_world_map_label = __( 'Logs On World Map', 'wp-clean-up-optimizer' );
		$cpo_recent_logins_start_date_tooltip = __( 'Start Date for Retrieving Data', 'wp-clean-up-optimizer' );
		$cpo_recent_logins_end_date_tooltip   = __( 'End Date for Retrieving Data', 'wp-clean-up-optimizer' );

		// live traffic.
		$cpo_live_traffic_resources = __( 'Resources', 'wp-clean-up-optimizer' );

		// Visitor Logs.
		$cpo_visitor_logs_monitoring_message = __( 'Monitoring is Turned Off. Kindly go to Other Settings in order to enable it', 'wp-clean-up-optimizer' );

		// alert setup.
		$cpo_alert_setup_email_user_fail_login_label      = __( 'Email when a user Fails Login', 'wp-clean-up-optimizer' );
		$cpo_alert_setup_email_user_success_login_label   = __( 'Email when a user Success Login', 'wp-clean-up-optimizer' );
		$cpo_alert_setup_email_ip_address_blocked_label   = __( 'Email when an IP Address is Blocked', 'wp-clean-up-optimizer' );
		$cpo_alert_setup_email_ip_address_unblocked_label = __( 'Email when an IP Address is Unblocked', 'wp-clean-up-optimizer' );
		$cpo_alert_setup_email_ip_range_blocked_label     = __( 'Email when an IP Range is Blocked', 'wp-clean-up-optimizer' );
		$cpo_alert_setup_email_ip_range_unblocked_label   = __( 'Email when an IP Range is Unblocked', 'wp-clean-up-optimizer' );

		// error messasges.
		$cpo_error_messages_max_login_attempts_label         = __( 'Maximum Login Attempts', 'wp-clean-up-optimizer' );
		$cpo_error_messages_max_login_attempts_label_tooltip = __( 'Error Message to be displayed when a User exceeds Maximum Number of Login Attempts', 'wp-clean-up-optimizer' );
		$cpo_error_messages_label_placeholder                = __( 'Please provide Error Message', 'wp-clean-up-optimizer' );

		$cpo_error_messages_blocked_country_label   = __( 'Blocked Country', 'wp-clean-up-optimizer' );
		$cpo_error_messages_blocked_country_tooltip = __( 'Error Message to be displayed when a Country is Blocked', 'wp-clean-up-optimizer' );
		$cpo_error_messages_max_ip_address_label    = __( 'Blocked IP Address', 'wp-clean-up-optimizer' );
		$cpo_error_messages_max_ip_address_tooltip  = __( 'Error Message to be displayed when an IP Address is Blocked', 'wp-clean-up-optimizer' );
		$cpo_error_messages_max_ip_range_label      = __( 'Blocked IP Range', 'wp-clean-up-optimizer' );
		$cpo_error_messages_max_ip_range_tooltip    = __( 'Error Message to be displayed when an IP Range is Blocked', 'wp-clean-up-optimizer' );

		// other Settings.
		$cpo_other_settings_trackbacks_label                   = __( 'Trackbacks', 'wp-clean-up-optimizer' );
		$cpo_other_settings_trackbacks_tooltip                 = __( 'Do you want to enable trackbacks to your site?', 'wp-clean-up-optimizer' );
		$cpo_other_settings_comments_tooltip                   = __( 'Do you want to allow people to comment on your posts or pages?', 'wp-clean-up-optimizer' );
		$cpo_other_settings_live_traffic_monitoring_label      = __( 'Live Traffic Monitoring', 'wp-clean-up-optimizer' );
		$cpo_other_settings_live_traffic_monitoring_tooltip    = __( 'Do you want to Monitor Live Traffic?', 'wp-clean-up-optimizer' );
		$cpo_other_settings_visitor_logs_monitoring_label      = __( 'Visitor Logs Monitoring', 'wp-clean-up-optimizer' );
		$cpo_other_settings_visitor_logs_monitoring_tooltip    = __( 'Do you want to Monitor Visitor Logs?', 'wp-clean-up-optimizer' );
		$cpo_other_settings_remove_tables_at_uninstall         = __( 'Remove Database at Uninstall', 'wp-clean-up-optimizer' );
		$cpo_other_settings_remove_tables_at_uninstall_tooltip = __( 'Do you want to remove Database at Uninstall of the Plugin?', 'wp-clean-up-optimizer' );
		$cpo_other_settings_error_reporting                    = __( 'Error Reporting', 'wp-clean-up-optimizer' );
		$cpo_other_settings_error_reporting_tooltip            = __( 'Choose Enable to Report your Errors in Error Logs Menu', 'wp-clean-up-optimizer' );
		$cpo_other_settings_ip_address_fetching_method         = __( 'How does Clean Up Optimizer get IPs', 'wp-clean-up-optimizer' );
		$cpo_other_settings_ip_address_tooltips                = __( 'Options available for retrieving IP Address', 'wp-clean-up-optimizer' );
		$cpo_other_settings_ip_address_fetching_option1        = __( 'Let Clean Up Optimizer use the most secure method to get visitor IP address. Prevents spoofing and works with most sites.', 'wp-clean-up-optimizer' );
		$cpo_other_settings_ip_address_fetching_option2        = __( 'Use PHP\'s built in REMOTE_ADDR and don\'t use anything else. Very secure if this is compatible with your site.', 'wp-clean-up-optimizer' );
		$cpo_other_settings_ip_address_fetching_option3        = __( 'Use the X-Forwarded-For HTTP header. Only use if you have a front-end proxy or spoofing may result.', 'wp-clean-up-optimizer' );
		$cpo_other_settings_ip_address_fetching_option4        = __( 'Use the X-Real-IP HTTP header. Only use if you have a front-end proxy or spoofing may result.', 'wp-clean-up-optimizer' );
		$cpo_other_settings_ip_address_fetching_option5        = __( "Use the Cloudflare 'CF-Connecting-IP' HTTP header to get a visitor IP. Only use if you're using Cloudflare.", 'wp-clean-up-optimizer' );

		// blocking options.
		$cpo_blocking_options_auto_ip_block_label                = __( 'Auto IP Block', 'wp-clean-up-optimizer' );
		$cpo_blocking_options_auto_ip_block_tootltip             = __( 'Choose whether to block IP Address automatically when User exceeds Maximum Number of Login Attempts', 'wp-clean-up-optimizer' );
		$cpo_blocking_options_max_login_attempts_day_label       = __( 'Maximum Login Attempts In a Day', 'wp-clean-up-optimizer' );
		$cpo_blocking_options_max_login_attempts_day_tooltip     = __( 'Maximum Number of Login Attempts to be allowed in a Day', 'wp-clean-up-optimizer' );
		$cpo_blocking_options_max_login_attempts_day_placeholder = __( 'Please provide Maximum Login Attempts in a Day', 'wp-clean-up-optimizer' );
		$cpo_blocking_options_blocked_for_tooltip                = __( 'Maximum Time Duration', 'wp-clean-up-optimizer' );

		// manage ip Addresses.
		$cpo_manage_ip_addresses_tooltip                     = __( 'Valid IP Address to be Blocked', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_addresses_comments_tooltip            = __( 'Reason for Blocking', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_addresses_comments_placeholder        = __( 'Please provide Comments', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_addresses_view_block_ip_address_label = __( 'View Blocked IP Addresses', 'wp-clean-up-optimizer' );

		// manage ip Ranges.
		$cpo_manage_ip_ranges_start_ip_range_label                = __( 'Start IP Range', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_ranges_start_ip_range_tooltip              = __( 'Valid IP Range to be Blocked', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_ranges_start_ip_range_placeholder          = __( 'Please provide Start IP Range', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_ranges_end_ip_range_label                  = __( 'End IP Range', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_ranges_end_ip_range_placeholder            = __( 'Please provide End IP Range', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_ranges_address_block_ip_range_button_label = __( 'Block IP Range', 'wp-clean-up-optimizer' );
		$cpo_manage_ip_ranges_view_block_ip_range_label           = __( 'View Blocked IP Ranges', 'wp-clean-up-optimizer' );
		$cpo_end_date             = __( 'End Date', 'wp-clean-up-optimizer' );
		$cpo_end_date_placeholder = __( 'Please choose End Date', 'wp-clean-up-optimizer' );

		// country Blocks.
		$cpo_country_blocks_available_countries_label   = __( 'Available Countries', 'wp-clean-up-optimizer' );
		$cpo_country_blocks_available_countries_tooltip = __( 'List of Available Countries', 'wp-clean-up-optimizer' );
		$cpo_country_blocks_add_button_label            = __( 'Add >>', 'wp-clean-up-optimizer' );
		$cpo_country_blocks_remove_button_label         = __( '<< Remove', 'wp-clean-up-optimizer' );
		$cpo_country_blocks_blocked_countries_label     = __( 'Blocked Countries', 'wp-clean-up-optimizer' );
		$cpo_country_blocks_blocked_countries_tooltip   = __( 'List Blocked Countries', 'wp-clean-up-optimizer' );

		// Email Templates.
		$cpo_email_templates_choose_email_template_label   = __( 'Choose Email Templates', 'wp-clean-up-optimizer' );
		$cpo_email_templates_send_to_label                 = __( 'Send To', 'wp-clean-up-optimizer' );
		$cpo_email_templates_cc_label                      = __( 'CC', 'wp-clean-up-optimizer' );
		$cpo_email_templates_bcc_label                     = __( 'BCC', 'wp-clean-up-optimizer' );
		$cpo_email_templates_message_label                 = __( 'Message', 'wp-clean-up-optimizer' );
		$cpo_email_templates_choose_template_tooltip       = __( 'Available Email Templates', 'wp-clean-up-optimizer' );
		$cpo_email_templates_send_emails_address_tooltip   = __( 'A valid Email Address account to which you would like to send Emails', 'wp-clean-up-optimizer' );
		$cpo_email_templates_cc_email_address_tooltip      = __( 'A valid Email Address account used in the "CC" field. Use "," to separate multiple email addresses', 'wp-clean-up-optimizer' );
		$cpo_email_templates_bcc_email_address_tooltip     = __( 'A valid Email Address account used in the "BCC" field. Use "," to separate multiple email addresses', 'wp-clean-up-optimizer' );
		$cpo_email_templates_subject_email_tooltip         = __( 'Subject Line of your Email', 'wp-clean-up-optimizer' );
		$cpo_email_templates_content_email_tooltip         = __( 'The content of your Email', 'wp-clean-up-optimizer' );
		$cpo_email_templates_successful_login_dropdown     = __( 'Email Template For User Successful Login', 'wp-clean-up-optimizer' );
		$cpo_email_templates_failure_login_dropdown        = __( 'Email Template For User Failure Login', 'wp-clean-up-optimizer' );
		$cpo_email_templates_ip_address_blocked_dropdown   = __( 'Email Template For IP Address Blocked', 'wp-clean-up-optimizer' );
		$cpo_email_templates_ip_address_unblocked_dropdown = __( 'Email Template For IP Address Unblocked', 'wp-clean-up-optimizer' );
		$cpo_email_templates_ip_range_blocked_dropdown     = __( 'Email Template For IP Range Blocked', 'wp-clean-up-optimizer' );
		$cpo_email_templates_ip_range_unblocked_dropdown   = __( 'Email Template For IP Range Unblocked', 'wp-clean-up-optimizer' );
		$cpo_email_templates_email_address_placeholder     = __( 'Please provide valid Email Address', 'wp-clean-up-optimizer' );
		$cpo_email_templates_cc_email_placeholder          = __( 'Please provide CC Email', 'wp-clean-up-optimizer' );
		$cpo_email_templates_bcc_email_placeholder         = __( 'Please provide BCC Email', 'wp-clean-up-optimizer' );
		$cpo_email_templates_subject_placeholder           = __( 'Please provide Subject', 'wp-clean-up-optimizer' );

		// Roles And Capabilities.
		$cpo_roles_and_capabilities_clean_up_optimizer_menu_label          = __( 'Show Clean Up Optimizer Menu', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_clean_up_top_bar_menu_label            = __( 'Show Clean Up Optimizer Top Bar Menu', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_administrator_role_label               = __( 'An Administrator Role can do the following', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_author_role_label                      = __( 'An Author Role can do the following', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_editor_role_label                      = __( 'An Editor Role can do the following', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_contributor_role_label                 = __( 'A Contributor Role can do the following', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_subscriber_role_label                  = __( 'A Subscriber Role can do the following', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_other_role_label                       = __( 'Other Roles can do the following', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_administrator_label                    = __( 'Administrator', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_author_label                           = __( 'Author', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_editor_label                           = __( 'Editor', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_contributor_label                      = __( 'Contributor', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_subscriber_label                       = __( 'Subscriber', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_other_label                            = __( 'Others', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_choose_specific_role                   = __( 'Choose among the following roles who would be able to see the Clean Up Optimizer Menu?', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_clean_up_top_bar_tooltip               = __( 'Do you want to show Clean Up Optimizer menu in Top Bar?', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_choose_page_admin_access_tooltip       = __( 'Choose what pages would be visible to the users having Administrator Access', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_choose_page_author_access_tooltip      = __( 'Choose what pages would be visible to the users having Author Access', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_choose_page_editor_access_tooltip      = __( 'Choose what pages would be visible to the users having Editor Access', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_choose_page_contributor_access_tooltip = __( 'Choose what pages would be visible to the users having Contributor Access', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_choose_page_subscriber_access_tooltip  = __( 'Choose what pages would be visible to the users having Subscriber Access', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_choose_page_other_access_tooltip       = __( 'Choose what pages would be visible to the users having Others Role Access', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_full_control_label                     = __( 'Full Control', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_other_roles_capabilities               = __( 'Please tick the appropriate capabilities for security purposes', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_other_roles_capabilities_tooltip       = __( 'Only users with these capabilities can access Clean up Optimizer', 'wp-clean-up-optimizer' );

		// Common Variables.
		$cpo_type_of_data                = __( 'Type Of Data', 'wp-clean-up-optimizer' );
		$cpo_count                       = __( 'Count', 'wp-clean-up-optimizer' );
		$cpo_auto_drafts                 = __( 'Auto Drafts', 'wp-clean-up-optimizer' );
		$cpo_empty                       = __( 'Empty', 'wp-clean-up-optimizer' );
		$cpo_dashboard_transient_feed    = __( 'Dashboard Transient Feed', 'wp-clean-up-optimizer' );
		$cpo_unapproved_comments         = __( 'Unapproved Comments', 'wp-clean-up-optimizer' );
		$cpo_orphan_comment_meta         = __( 'Orphan Comments Meta', 'wp-clean-up-optimizer' );
		$cpo_orphan_post_meta            = __( 'Orphan Posts Meta', 'wp-clean-up-optimizer' );
		$cpo_orphan_relationships        = __( 'Orphan Relationships', 'wp-clean-up-optimizer' );
		$cpo_revisions                   = __( 'Revisions', 'wp-clean-up-optimizer' );
		$cpo_remove_pingbacks            = __( 'Pingbacks', 'wp-clean-up-optimizer' );
		$cpo_remove_transient_options    = __( 'Transient Options', 'wp-clean-up-optimizer' );
		$cpo_remove_trackbacks           = __( 'Trackbacks', 'wp-clean-up-optimizer' );
		$cpo_spam_comments               = __( 'Spam Comments', 'wp-clean-up-optimizer' );
		$cpo_trash_comments              = __( 'Trash Comments', 'wp-clean-up-optimizer' );
		$cpo_drafts                      = __( 'Drafts', 'wp-clean-up-optimizer' );
		$cpo_deleted_posts               = __( 'Deleted Posts', 'wp-clean-up-optimizer' );
		$cpo_duplicated_post_meta        = __( 'Duplicated Post Meta', 'wp-clean-up-optimizer' );
		$cpo_oembed_caches_post_meta     = __( 'oEmbed Caches in Post Meta', 'wp-clean-up-optimizer' );
		$cpo_duplicated_comment_meta     = __( 'Duplicated Comment Meta', 'wp-clean-up-optimizer' );
		$cpo_orphan_user_meta            = __( 'Orphan User Meta', 'wp-clean-up-optimizer' );
		$cpo_duplicated_user_meta        = __( 'Duplicated User Meta', 'wp-clean-up-optimizer' );
		$cpo_orphaned_term_relationships = __( 'Orphaned Term Relationships', 'wp-clean-up-optimizer' );
		$cpo_unused_terms                = __( 'Unused Terms', 'wp-clean-up-optimizer' );

		$cpo_type                      = __( 'Type', 'wp-clean-up-optimizer' );
		$cpo_scheduled_start_date_time = __( 'Start Date & Time', 'wp-clean-up-optimizer' );

		$cpo_data_update_scheduled_clean_up               = __( 'Update Schedule', 'wp-clean-up-optimizer' );
		$cpo_data_action_label_scheduled_clean_up_tooltip = __( 'Do you want to Empty selected types of data?', 'wp-clean-up-optimizer' );
		$cpo_add_new_scheduled_duration_label_tooltip     = __( 'Set Time Duration for Scheduler', 'wp-clean-up-optimizer' );

		$cpo_table_name_heading = __( 'Table Name', 'wp-clean-up-optimizer' );

		$cpo_clean_up_clear_button_label = __( 'Clear', 'wp-clean-up-optimizer' );
		$cpo_clean_up_blocked_for_label  = __( 'Blocked For', 'wp-clean-up-optimizer' );
		$cpo_one_hour                    = __( '1 Hour', 'wp-clean-up-optimizer' );
		$cpo_twelve_hours                = __( '12 Hours', 'wp-clean-up-optimizer' );
		$cpo_twenty_four_hours           = __( '24 Hours', 'wp-clean-up-optimizer' );
		$cpo_forty_eight_hours           = __( '48 Hours', 'wp-clean-up-optimizer' );
		$cpo_one_week                    = __( '1 Week', 'wp-clean-up-optimizer' );
		$cpo_one_month                   = __( '1 Month', 'wp-clean-up-optimizer' );
		$cpo_one_permanently             = __( 'Permanently', 'wp-clean-up-optimizer' );
		$cpo_never                       = __( 'Never', 'wp-clean-up-optimizer' );
		$cpo_edit_tooltip                = __( 'Edit', 'wp-clean-up-optimizer' );
		$cpo_for                         = __( 'for', 'wp-clean-up-optimizer' );

		$cpo_apply                           = __( 'Apply', 'wp-clean-up-optimizer' );
		$cpo_delete                          = __( 'Delete', 'wp-clean-up-optimizer' );
		$cpo_block_ip_address                = __( 'Block IP Address', 'wp-clean-up-optimizer' );
		$cpo_ip_address                      = __( 'IP Address', 'wp-clean-up-optimizer' );
		$cpo_duration                        = __( 'Duration', 'wp-clean-up-optimizer' );
		$cpo_table_heading_blocked_date_time = __( 'Blocked Date & Time', 'wp-clean-up-optimizer' );
		$cpo_table_heading_release_date_time = __( 'Release Date & Time', 'wp-clean-up-optimizer' );
		$cpo_comments                        = __( 'Comments', 'wp-clean-up-optimizer' );

		$cpo_hourly               = __( 'Hourly', 'wp-clean-up-optimizer' );
		$cpo_daily                = __( 'Daily', 'wp-clean-up-optimizer' );
		$cpo_start_on             = __( 'Start On', 'wp-clean-up-optimizer' );
		$cpo_start_on_placeholder = __( 'Please choose Start On', 'wp-clean-up-optimizer' );
		$cpo_start_on_tooltip     = __( 'Start Date for Scheduler to run', 'wp-clean-up-optimizer' );
		$cpo_start_time           = __( 'Start Time', 'wp-clean-up-optimizer' );
		$cpo_start_time_tooltip   = __( 'Start Time for Scheduler to run', 'wp-clean-up-optimizer' );
		$cpo_repeat_every         = __( 'Repeat Every', 'wp-clean-up-optimizer' );
		$cpo_repeat_every_tooltip = __( 'After how much time the Scheduler will repeat', 'wp-clean-up-optimizer' );
		$cpo_hrs                  = __( 'hrs', 'wp-clean-up-optimizer' );
		$cpo_mins                 = __( 'mins', 'wp-clean-up-optimizer' );

		$cpo_table_heading_ip_range  = __( 'IP Ranges', 'wp-clean-up-optimizer' );
		$cpo_start_date              = __( 'Start Date', 'wp-clean-up-optimizer' );
		$cpo_start_date_placeholder  = __( 'Please choose Start Date', 'wp-clean-up-optimizer' );
		$cpo_submit                  = __( 'Submit', 'wp-clean-up-optimizer' );
		$cpo_table_heading_user_name = __( 'User Name', 'wp-clean-up-optimizer' );
		$cpo_table_heading_date_time = __( 'Date & Time', 'wp-clean-up-optimizer' );
		$cpo_table_heading_status    = __( 'Status', 'wp-clean-up-optimizer' );
		$cpo_table_heading_details   = __( 'Details', 'wp-clean-up-optimizer' );
		$cpo_name_hook_label         = __( 'Name of the Hook', 'wp-clean-up-optimizer' );
		$cpo_interval_hook_label     = __( 'Interval Hook', 'wp-clean-up-optimizer' );
		$cpo_args_label              = __( 'Args', 'wp-clean-up-optimizer' );
		$cpo_next_execution_label    = __( 'Next Execution', 'wp-clean-up-optimizer' );
		$cpo_subject_label           = __( 'Subject', 'wp-clean-up-optimizer' );
		$cpo_bulk_action_dropdown    = __( 'Bulk Action', 'wp-clean-up-optimizer' );
		$cpo_action                  = __( 'Action', 'wp-clean-up-optimizer' );
		$cpo_optimize_dropdown       = __( 'Optimize', 'wp-clean-up-optimizer' );
		$cpo_repair_dropdown         = __( 'Repair', 'wp-clean-up-optimizer' );

		$cpo_roles_capabilities_label         = __( 'Roles & Capabilities', 'wp-clean-up-optimizer' );
		$cpo_dashboard                        = __( 'Dashboard', 'wp-clean-up-optimizer' );
		$cpo_wp_optimizer                     = __( 'WP Optimizer', 'wp-clean-up-optimizer' );
		$cpo_schedule_wp_optimizer            = __( 'WP Scheduler Optimizer', 'wp-clean-up-optimizer' );
		$cpo_database_optimizer               = __( 'DB Optimizer', 'wp-clean-up-optimizer' );
		$cpo_schedule_database_optimizer      = __( 'Scheduler DB Optimizer', 'wp-clean-up-optimizer' );
		$cpo_add_new_scheduled_clean_up_label = __( 'Add New Schedule', 'wp-clean-up-optimizer' );
		$cpo_view_records_label               = __( 'View Records', 'wp-clean-up-optimizer' );
		$cpo_logs_label                       = __( 'Logs', 'wp-clean-up-optimizer' );
		$cpo_logs_recent_login_logs           = __( 'Login Logs', 'wp-clean-up-optimizer' );
		$cpo_logs_live_traffic                = __( 'Live Traffic', 'wp-clean-up-optimizer' );
		$cpo_logs_visitor_logs                = __( 'Visitor Logs', 'wp-clean-up-optimizer' );
		$cpo_general_settings_label           = __( 'General Settings', 'wp-clean-up-optimizer' );
		$cpo_notifications_setup              = __( 'Notifications Setup', 'wp-clean-up-optimizer' );
		$cpo_message_settings                 = __( 'Message Settings', 'wp-clean-up-optimizer' );
		$cpo_general_other_settings           = __( 'Other Settings', 'wp-clean-up-optimizer' );
		$cpo_security_settings                = __( 'Security Settings', 'wp-clean-up-optimizer' );
		$cpo_blockage_settings                = __( 'Blockage Settings', 'wp-clean-up-optimizer' );
		$cpo_block_unblock_ip_addresses       = __( 'Block / Unblock IP Addresses', 'wp-clean-up-optimizer' );
		$cpo_block_unblock_ip_ranges          = __( 'Block / Unblock IP Ranges', 'wp-clean-up-optimizer' );
		$cpo_block_unblock_countries          = __( 'Block / Unblock Countries', 'wp-clean-up-optimizer' );
		$cpo_email_templates_label            = __( 'Email Templates', 'wp-clean-up-optimizer' );
		$cpo_roles_and_capabilities_label     = __( 'Roles & Capabilities', 'wp-clean-up-optimizer' );
		$cpo_cron_jobs_label                  = __( 'Cron Jobs', 'wp-clean-up-optimizer' );
		$cpo_cron_custom_jobs_label           = __( 'Custom Jobs', 'wp-clean-up-optimizer' );
		$cpo_cron_core_jobs_label             = __( 'Core Jobs', 'wp-clean-up-optimizer' );
		$cpo_feature_request_label            = __( 'Ask For Help', 'wp-clean-up-optimizer' );
		$cpo_system_information_label         = __( 'System Information', 'wp-clean-up-optimizer' );
		$cpo_enable                           = __( 'Enable', 'wp-clean-up-optimizer' );
		$cpo_disable                          = __( 'Disable', 'wp-clean-up-optimizer' );
		$cpo_clean_up_optimizer               = 'Clean Up Optimizer';
		$cpo_save_changes                     = __( 'Save Changes', 'wp-clean-up-optimizer' );
		$cpo_roles_capabilities_message       = __( 'You do not have Sufficient Access to this Page. Kindly contact the Administrator for more Privileges', 'wp-clean-up-optimizer' );
		$cpo_block                            = __( 'Block', 'wp-clean-up-optimizer' );
	}
}
