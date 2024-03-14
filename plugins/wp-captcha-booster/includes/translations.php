<?php
/**
 * This file is used for translation strings.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/includes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	foreach ( $user_role_permission as $permission ) {
		if ( current_user_can( $permission ) ) {
			$access_granted = true;
			break;
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {
		$cpb_support_forum = __( 'Ask For Help', 'wp-captcha-booster' );
		$cpb_full_features = __( 'Know about Full Features', 'wp-captcha-booster' );
		$cpb_or            = __( 'or', 'wp-captcha-booster' );
		$cpb_online_demos  = __( 'Check our Online Demos', 'wp-captcha-booster' );
		$cpb_general_tab   = __( 'Configuration', 'wp-captcha-booster' );
		$cpb_layout_tab    = __( 'Layout Design', 'wp-captcha-booster' );
		$cpb_signature_tab = __( 'Signature', 'wp-captcha-booster' );

		// footer.
		$message_premium_edition         = __( 'This feature is available only in Premium Editions! <br> Kindly Purchase to unlock it!', 'wp-captcha-booster' );
		$cpb_setting_saved               = __( 'Settings Saved!', 'wp-captcha-booster' );
		$cpb_success                     = __( 'Success!', 'wp-captcha-booster' );
		$cpb_ip_address_block            = __( 'Blocked Successfully', 'wp-captcha-booster' );
		$cpb_delete_data                 = __( 'Data Deleted!', 'wp-captcha-booster' );
		$cpb_number_of_digits            = __( 'The number should lie between 1 and 100!', 'wp-captcha-booster' );
		$cpb_number_of_captcha_character = __( 'The number should lie between 1 and 10!', 'wp-captcha-booster' );
		$cpb_country_unblock_message     = __( 'Unblocked Successfully!', 'wp-captcha-booster' );
		$cpb_error_message               = __( 'Error Message', 'wp-captcha-booster' );
		$cpb_arithmetic_action           = __( 'Choose at least one to continue!', 'wp-captcha-booster' );
		$cpb_ip_address                  = __( 'IP Address', 'wp-captcha-booster' );
		$cpb_location                    = __( 'Location', 'wp-captcha-booster' );
		$cpb_latitude                    = __( 'Latitude', 'wp-captcha-booster' );
		$cpb_longitude                   = __( 'Longitude', 'wp-captcha-booster' );
		$cpb_na                          = 'N/A';
		$cpb_choose_action               = __( 'Choose an Action!', 'wp-captcha-booster' );
		$cpb_confirm_delete              = __( 'Are you sure?', 'wp-captcha-booster' );
		$cpb_valid_ip_range              = __( 'Invalid IP Range!', 'wp-captcha-booster' );
		$cpb_valid_ip_address            = __( 'Invalid IP Address!', 'wp-captcha-booster' );
		$cpb_ip_address_already_blocked  = __( 'Already Blocked!', 'wp-captcha-booster' );
		$cpb_notification                = __( 'Notification!', 'wp-captcha-booster' );
		$cpb_block_own_ip_address        = __( 'You can\'t block your own IP Address!', 'wp-captcha-booster' );
		$cpb_block_own_ip_range          = __( 'You can\'t block this IP Range as your IP Address lies between it', 'wp-captcha-booster' );

		// captcha setup.
		$cpb_captcha_booster_type_tooltip                  = __( 'Choose between Logical Captcha or Text Captcha', 'wp-captcha-booster' );
		$cpb_captcha_booster_text_captcha                  = __( 'Text Captcha', 'wp-captcha-booster' );
		$cpb_captcha_booster_logical_captcha               = __( 'Logical Captcha', 'wp-captcha-booster' );
		$cpb_captcha_booster_character_title               = __( 'Number of Characters', 'wp-captcha-booster' );
		$cpb_captcha_booster_character_tooltip             = __( 'Number of Characters for creating Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_string_type_title             = __( 'Character Type', 'wp-captcha-booster' );
		$cpb_captcha_booster_string_type_tooltip           = __( 'Different Types available for creating Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_alphabets_digits              = __( 'Alphabets And Digits', 'wp-captcha-booster' );
		$cpb_captcha_booster_only_alphabets                = __( 'Only Alphabets', 'wp-captcha-booster' );
		$cpb_captcha_booster_only_digits                   = __( 'Only Digits', 'wp-captcha-booster' );
		$cpb_captcha_booster_text_case_title               = __( 'Text Case', 'wp-captcha-booster' );
		$cpb_captcha_booster_text_case_tooltip             = __( 'Text Case available for Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_upper_case                    = __( 'Upper Case', 'wp-captcha-booster' );
		$cpb_captcha_booster_lower_case                    = __( 'Lower Case', 'wp-captcha-booster' );
		$cpb_captcha_booster_random_case                   = __( 'Mixed', 'wp-captcha-booster' );
		$cpb_captcha_booster_case_sensitive_title          = __( 'Case Sensitive', 'wp-captcha-booster' );
		$cpb_captcha_booster_case_sensitive_tooltip        = __( 'Do you want Captcha to be Case Sensitive?', 'wp-captcha-booster' );
		$cpb_captcha_booster_width_title                   = __( 'Width (px)', 'wp-captcha-booster' );
		$cpb_captcha_booster_width_tooltip                 = __( 'The Width of Captcha Image in Pixels', 'wp-captcha-booster' );
		$cpb_captcha_booster_height_title                  = __( 'Height (px)', 'wp-captcha-booster' );
		$cpb_captcha_booster_height_tooltip                = __( 'The Height of Captcha Image in Pixels', 'wp-captcha-booster' );
		$cpb_captcha_booster_background_title              = __( 'Background Pattern', 'wp-captcha-booster' );
		$cpb_captcha_booster_background_tooltip            = __( 'Available Background Patterns for Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_background_pattern1           = 'Pattern 1';
		$cpb_captcha_booster_background_pattern2           = 'Pattern 2';
		$cpb_captcha_booster_background_pattern3           = 'Pattern 3';
		$cpb_captcha_booster_background_pattern4           = 'Pattern 4';
		$cpb_captcha_booster_background_pattern5           = 'Pattern 5';
		$cpb_captcha_booster_background_pattern6           = 'Pattern 6';
		$cpb_captcha_booster_background_pattern7           = 'Pattern 7';
		$cpb_captcha_booster_background_pattern8           = 'Pattern 8';
		$cpb_captcha_booster_background_pattern9           = 'Pattern 9';
		$cpb_captcha_booster_background_pattern10          = 'Pattern 10';
		$cpb_captcha_booster_background_pattern11          = 'Pattern 11';
		$cpb_captcha_booster_background_pattern12          = 'Pattern 12';
		$cpb_captcha_booster_background_pattern13          = 'Pattern 13';
		$cpb_captcha_booster_background_pattern14          = 'Pattern 14';
		$cpb_captcha_booster_background_pattern15          = 'Pattern 15';
		$cpb_captcha_booster_background_pattern16          = 'Pattern 16';
		$cpb_captcha_booster_background_pattern17          = 'Pattern 17';
		$cpb_captcha_booster_background_pattern18          = 'Pattern 18';
		$cpb_captcha_booster_text_style_title              = __( 'Text Style', 'wp-captcha-booster' );
		$cpb_captcha_booster_text_style_tooltip            = __( 'Font Style used in Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_text_font_title               = __( 'Font Family', 'wp-captcha-booster' );
		$cpb_captcha_booster_text_font_tooltip             = __( 'Type of Font Families available', 'wp-captcha-booster' );
		$cpb_captcha_booster_border_style_title            = __( 'Border Style', 'wp-captcha-booster' );
		$cpb_captcha_booster_border_style_tooltip          = __( 'Border Style used in Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_border_solid                  = 'Solid';
		$cpb_captcha_booster_border_dotted                 = 'Dotted';
		$cpb_captcha_booster_border_dashed                 = 'Dashed';
		$cpb_captcha_booster_lines_title                   = __( 'Number of Lines', 'wp-captcha-booster' );
		$cpb_captcha_booster_lines_tooltip                 = __( 'Total Number of Lines used in Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_lines_color_title             = __( 'Line Color', 'wp-captcha-booster' );
		$cpb_captcha_booster_lines_color_tooltip           = __( 'The Color of Lines used in Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_noise_level_title             = __( 'Noise Level', 'wp-captcha-booster' );
		$cpb_captcha_booster_noise_level_tooltip           = __( '0 - None, 100 - Maximum', 'wp-captcha-booster' );
		$cpb_captcha_booster_noise_level_placeholder       = __( 'Please provide Noise Level', 'wp-captcha-booster' );
		$cpb_captcha_booster_noise_color_title             = __( 'Noise Color', 'wp-captcha-booster' );
		$cpb_captcha_booster_noise_color_tooltip           = __( 'The Color of Noise used in Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_text_transparency_title       = __( 'Text Transparency %', 'wp-captcha-booster' );
		$cpb_captcha_booster_text_transparency_placeholder = __( 'Please provide Text Transparency', 'wp-captcha-booster' );
		$cpb_captcha_booster_signature_text_title          = __( 'Text', 'wp-captcha-booster' );
		$cpb_captcha_booster_signature_text_tooltip        = __( 'Signature Text for your Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_shadow_color_title            = __( 'Shadow Color', 'wp-captcha-booster' );
		$cpb_captcha_booster_shadow_color_tooltip          = __( 'The Color of Shadow used in Captcha Image', 'wp-captcha-booster' );
		$cpb_captcha_booster_mathematical_title            = __( 'Mathematical Operations', 'wp-captcha-booster' );
		$cpb_captcha_booster_mathematical_tooltip          = __( 'Different Mathematical Operations available', 'wp-captcha-booster' );
		$cpb_captcha_booster_arithmetic                    = __( 'Arithmetic', 'wp-captcha-booster' );
		$cpb_captcha_booster_relational                    = __( 'Relational', 'wp-captcha-booster' );
		$cpb_captcha_booster_arithmetic_title              = __( 'Arithmetic Options', 'wp-captcha-booster' );
		$cpb_captcha_booster_addition                      = __( 'Addition', 'wp-captcha-booster' );
		$cpb_captcha_booster_subtraction                   = __( 'Subtraction', 'wp-captcha-booster' );
		$cpb_captcha_booster_multiplication                = __( 'Multiplication', 'wp-captcha-booster' );
		$cpb_captcha_booster_division                      = __( 'Division', 'wp-captcha-booster' );
		$cpb_captcha_booster_arithmetic_tooltip            = __( 'What operations would you like to use for creating Captcha Image?', 'wp-captcha-booster' );
		$cpb_captcha_booster_relational_title              = __( 'Relational Options', 'wp-captcha-booster' );
		$cpb_captcha_booster_largest_number                = __( 'Largest Number', 'wp-captcha-booster' );
		$cpb_captcha_booster_smallest_number               = __( 'Smallest Number', 'wp-captcha-booster' );
		$cpb_captcha_booster_arrange_title                 = __( 'Arrange Order Options', 'wp-captcha-booster' );
		$cpb_captcha_booster_ascending_order               = __( 'Ascending Order', 'wp-captcha-booster' );
		$cpb_captcha_booster_descending_order              = __( 'Descending Order', 'wp-captcha-booster' );

		// other settings.
		$cpb_other_settings_automatic_plugin_updates_tooltip = __( 'Do you want Automatic Plugin Updates?', 'wp-captcha-booster' );
		$cpb_other_settings_automatic_plugin_updates         = __( 'Automatic Plugin Updates', 'wp-captcha-booster' );
		$cpb_other_settings_live_traffic_monitoring_label    = __( 'Live Traffic Monitoring', 'wp-captcha-booster' );
		$cpb_other_settings_live_traffic_monitoring_tooltip  = __( 'Do you want to Monitor Live Traffic?', 'wp-captcha-booster' );
		$cpb_other_settings_visitor_logs_monitoring_label    = __( 'Visitor Logs Monitoring', 'wp-captcha-booster' );
		$cpb_other_settings_visitor_logs_monitoring_tooltip  = __( 'Do you want to Monitor Visitor Logs?', 'wp-captcha-booster' );
		$cpb_other_settings_remove_tables                    = __( 'Remove Database at Uninstall', 'wp-captcha-booster' );
		$cpb_other_settings_remove_tables_tootltip           = __( 'Do you want to remove Database at Uninstall of the Plugin?', 'wp-captcha-booster' );
		$cpb_other_settings_ip_address_fetching_method       = __( 'How does Captcha Booster get IPs', 'wp-captcha-booster' );
		$cpb_other_settings_ip_address_tooltips              = __( 'Options available for retrieving IP Address', 'wp-captcha-booster' );
		$cpb_other_settings_ip_address_fetching_option1      = __( 'Let Captcha Booster use the most secure method to get visitor IP address. Prevents spoofing and works with most sites', 'wp-captcha-booster' );
		$cpb_other_settings_ip_address_fetching_option2      = __( 'Use PHP\'s built in REMOTE_ADDR and don\'t use anything else. Very secure if this is compatible with your site', 'wp-captcha-booster' );
		$cpb_other_settings_ip_address_fetching_option3      = __( 'Use the X-Forwarded-For HTTP header. Only use if you have a front-end proxy or spoofing may result', 'wp-captcha-booster' );
		$cpb_other_settings_ip_address_fetching_option4      = __( 'Use the X-Real-IP HTTP header. Only use if you have a front-end proxy or spoofing may result', 'wp-captcha-booster' );
		$cpb_other_settings_ip_address_fetching_option5      = __( 'Use the Cloudflare \'CF-Connecting-IP\' HTTP header to get a visitor IP. Only use if you\'re using Cloudflare', 'wp-captcha-booster' );

		// alert setup.
		$cpb_alert_setup_email_fails_login_title          = __( 'Email when a User Fails Login', 'wp-captcha-booster' );
		$cpb_alert_setup_email_success_login_title        = __( 'Email when a User Success Login', 'wp-captcha-booster' );
		$cpb_alert_setup_email_ip_address_blocked_title   = __( 'Email when an IP Address is Blocked', 'wp-captcha-booster' );
		$cpb_alert_setup_email_ip_address_unblocked_title = __( 'Email when an IP Address is Un-Blocked', 'wp-captcha-booster' );
		$cpb_alert_setup_email_ip_range_blocked_title     = __( 'Email when an IP Range is Blocked', 'wp-captcha-booster' );
		$cpb_alert_setup_email_ip_range_unblocked_title   = __( 'Email when an IP Range is Un-Blocked', 'wp-captcha-booster' );

		// email template.
		$cpb_email_templates_tooltip                        = __( 'Available Email Templates', 'wp-captcha-booster' );
		$cpb_email_templates_send_to_title                  = __( 'Send To', 'wp-captcha-booster' );
		$cpb_email_templates_send_to_email_tooltip          = __( 'A valid Email Address account to which you would like to send Emails', 'wp-captcha-booster' );
		$cpb_email_templates_send_email_address_placeholder = __( 'Please provide Email Address', 'wp-captcha-booster' );
		$cpb_email_templates_cc_title                       = 'CC';
		$cpb_email_templates_cc_email_tooltip               = __( 'A valid Email Address account used in the "CC" field. Use "," to separate multiple email addresses', 'wp-captcha-booster' );
		$cpb_email_templates_cc_email_address_placeholder   = __( 'Please provide CC Email', 'wp-captcha-booster' );
		$cpb_email_templates_bcc_title                      = 'BCC';
		$cpb_email_templates_bcc_email_tooltip              = __( 'A valid Email Address account used in the "BCC" field. Use "," to separate multiple email addresses', 'wp-captcha-booster' );
		$cpb_email_templates_bcc_email_address_placeholder  = __( 'Please provide BCC Email', 'wp-captcha-booster' );
		$cpb_email_templates_subject_tooltip                = __( 'Subject Line of your Email', 'wp-captcha-booster' );
		$cpb_email_templates_cpb_message_title              = __( 'Message', 'wp-captcha-booster' );
		$cpb_email_templates_message_content_tooptip        = __( 'The content of your Email', 'wp-captcha-booster' );
		$cpb_email_templates_successful_login               = __( 'Email Template For User Successful Login', 'wp-captcha-booster' );
		$cpb_email_templates_failure_login                  = __( 'Email Template For User Failure Login', 'wp-captcha-booster' );
		$cpb_email_templates_ip_address_blocked             = __( 'Email Template For IP Address Blocked', 'wp-captcha-booster' );
		$cpb_email_templates_ip_address_unblocked           = __( 'Email Template For IP Address Un-Blocked', 'wp-captcha-booster' );
		$cpb_email_templates_ip_range_blocked               = __( 'Email Template For IP Range Blocked', 'wp-captcha-booster' );
		$cpb_email_templates_ip_range_unblocked             = __( 'Email Template For IP Range Un-Blocked', 'wp-captcha-booster' );

		// roles and capabilities.
		$cpb_show_roles_and_capabilities_menu                        = __( 'Show Captcha Booster Menu', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_menu_tooltip                     = __( 'Choose who would be able to see Captcha Booster Menu?', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_administrator                    = __( 'Administrator', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_author                           = __( 'Author', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_editor                           = __( 'Editor', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_contributor                      = __( 'Contributor', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_subscriber                       = __( 'Subscriber', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_others                           = __( 'Others', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_topbar_menu                      = __( 'Show Captcha Booster Top Bar Menu', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_topbar_menu_tooltip              = __( 'Do you want to show Captcha Booster menu in Top Bar?', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_administrator_role               = __( 'An Administrator Role can do the following', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_administrator_role_tooltip       = __( 'Choose pages for users having Administrator Access', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_author_role                      = __( 'An Author Role can do the following', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_author_role_tooltip              = __( 'Choose pages for users having Author Access', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_editor_role                      = __( 'An Editor Role can do the following', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_editor_role_tooltip              = __( 'Choose pages for users having Editor Access', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_contributor_role                 = __( 'A Contributor Role can do the following', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_contributor_role_tooltip         = __( 'Choose pages for users having Contributor Access', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_subscriber_role                  = __( 'A Subscriber Role can do the following', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_subscriber_role_tooltip          = __( 'Choose pages for users having Subscriber Access', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_other_role                       = __( 'Other Roles can do the following', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_other_role_tooltip               = __( 'Choose pages for users having Others Role Access', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_other_roles_capabilities         = __( 'Please tick appropriate capabilities for security purposes', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_other_roles_capabilities_tooltip = __( 'Only users with these capabilities can access Captcha Booster', 'wp-captcha-booster' );

		// live traffic and login logs.
		$cpb_retriving_start_date_tooltip    = __( 'Start Date for Retrieving Data', 'wp-captcha-booster' );
		$cpb_retriving_end_date_tooltip      = __( 'End Date for Retrieving Data', 'wp-captcha-booster' );
		$cpb_live_traffic_monitoring_message = __( 'Logs Monitoring is currently Switched Off. Kindly go to General Settings > Other Settings in order to enable it', 'wp-captcha-booster' );
		$cpb_recent_login_on_world_map       = __( 'World Map', 'wp-captcha-booster' );
		$cpb_details                         = __( 'Details', 'wp-captcha-booster' );
		$cpb_status                          = __( 'Status', 'wp-captcha-booster' );

		// error messages.
		$cpb_error_message_login_failure_title        = __( 'Maximum Login Attempts', 'wp-captcha-booster' );
		$cpb_error_message_login_failure_tooltip      = __( 'Error Message to be displayed when a User exceeds Maximum Number of Login Attempts', 'wp-captcha-booster' );
		$cpb_error_message_login_failure_placeholder  = __( 'Please provide Error Message', 'wp-captcha-booster' );
		$cpb_error_message_invalid_captcha_title      = __( 'Invalid Captcha', 'wp-captcha-booster' );
		$cpb_error_message_invalid_captcha_tooltip    = __( 'Error Message to be displayed when a User inserts Invalid Captcha', 'wp-captcha-booster' );
		$cpb_error_message_blocked_ip_address_title   = __( 'Blocked IP Address', 'wp-captcha-booster' );
		$cpb_error_message_blocked_ip_address_tooltip = __( 'Error Message to be displayed when an IP Address is Blocked', 'wp-captcha-booster' );
		$cpb_error_message_empty_captcha_title        = __( 'Empty Captcha', 'wp-captcha-booster' );
		$cpb_error_message_empty_captcha_tooltip      = __( 'Error Message to be displayed when Captcha is Empty', 'wp-captcha-booster' );
		$cpb_error_message_blocked_ip_range_title     = __( 'Blocked IP Range', 'wp-captcha-booster' );
		$cpb_error_message_blocked_ip_range_tooltip   = __( 'Error Message to be displayed when an IP Range is Blocked', 'wp-captcha-booster' );
		$cpb_error_messages_blocked_country_label     = __( 'Blocked Country', 'wp-captcha-booster' );
		$cpb_error_messages_blocked_country_tooltip   = __( 'Error Message to be displayed when a Country is Blocked', 'wp-captcha-booster' );
		// Display settings.
		$cpb_display_settings_enable_captcha_for                = __( 'Enable Captcha for', 'wp-captcha-booster' );
		$cpb_display_settings_enable_captcha_tooltip            = __( 'Available Forms on which you can display Captcha', 'wp-captcha-booster' );
		$cpb_display_settings_login_form                        = __( 'Login Form', 'wp-captcha-booster' );
		$cpb_display_settings_registration_form                 = __( 'Registration Form', 'wp-captcha-booster' );
		$cpb_display_settings_reset_password_form               = __( 'Reset Password Form', 'wp-captcha-booster' );
		$cpb_display_settings_comment_form                      = __( 'Comment Form', 'wp-captcha-booster' );
		$cpb_display_settings_admin_comment_form                = __( 'Admin Comment Form', 'wp-captcha-booster' );
		$cpb_display_settings_hide_captcha_register_user        = __( 'Hide Captcha For Registered user', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_woocommerce_login         = __( 'WooCommerce Login Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_woocommerce_register      = __( 'WooCommerce Registration Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_woocommerce_lost_password = __( 'WooCommerce Reset Password Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_woocommerce_checkout      = __( 'WooCommerce Checkout Form', 'wp-captcha-booster' );
		$cpb_display_settings_contact_form7                     = __( 'Captcha For Contact Form 7', 'wp-captcha-booster' );
		$cpb_display_settings_buddypress                        = __( 'BuddyPress Registration Form', 'wp-captcha-booster' );
		$cpb_display_settings_buddypress_login                  = __( 'BuddyPress Login Form', 'wp-captcha-booster' );
		$cpb_display_settings_buddypress_lost_password          = __( 'BuddyPress Reset Password Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_bbpress_login             = __( 'bbPress Login Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_bbpress_register          = __( 'bbPress Registration Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_bbpress_lost_password     = __( 'bbPress Reset Password Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_bbpress_new_topic         = __( 'bbPress New Topic Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_bbpress_reply_topic       = __( 'bbPress Reply To Topic Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_wpforo_login              = __( 'wpForo Login Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_wpforo_register           = __( 'wpForo Registration Form', 'wp-captcha-booster' );
		$cpb_display_settings_captcha_jetpack_form              = __( 'Jetpack Contact Form', 'wp-captcha-booster' );

		// Blocking Options.
		$cpb_blocking_options_title                      = __( 'Auto IP Block', 'wp-captcha-booster' );
		$cpb_blocking_options_tooltip                    = __( 'Choose whether to block IP Address automatically when User exceeds Maximum Number of Login Attempts', 'wp-captcha-booster' );
		$cpb_blocking_options_login_attempts_title       = __( 'Maximum Login Attempts in a Day', 'wp-captcha-booster' );
		$cpb_blocking_options_login_attempts_tooltip     = __( 'Maximum Number of Login Attempts to be allowed in a Day', 'wp-captcha-booster' );
		$cpb_blocking_options_login_attempts_placeholder = __( 'Please provide Maximum Login Attempts', 'wp-captcha-booster' );

		// Manage IP Addresses.
		$cpb_manage_ip_addresses_tooltip    = __( 'Valid IP Address to be Blocked', 'wp-captcha-booster' );
		$cpb_manage_ip_addresses_view_block = __( 'View Blocked IP Addresses', 'wp-captcha-booster' );
		$cpb_tooltip_comment                = __( 'Reason for Blocking', 'wp-captcha-booster' );

		// Manage IP Ranges.
		$cpb_manage_ip_ranges_start_range_title       = __( 'Start IP Range', 'wp-captcha-booster' );
		$cpb_manage_ip_ranges_start_range_tooltip     = __( 'Valid IP Range to be Blocked', 'wp-captcha-booster' );
		$cpb_manage_ip_ranges_start_range_placeholder = __( 'Please provide Start IP Range', 'wp-captcha-booster' );
		$cpb_manage_ip_ranges_end_range_title         = __( 'End IP Range', 'wp-captcha-booster' );
		$cpb_manage_ip_ranges_end_range_placeholder   = __( 'Please provide End IP Range', 'wp-captcha-booster' );
		$cbp_manage_ip_ranges_block                   = __( 'Block IP Range', 'wp-captcha-booster' );
		$cpb_manage_ip_ranges_view_block              = __( 'View Blocked IP Ranges', 'wp-captcha-booster' );

		// Common Variables.
		$cpb_color_code                          = __( 'Color Code', 'wp-captcha-booster' );
		$cpb_block_for_title                     = __( 'Blocked for', 'wp-captcha-booster' );
		$cpb_block_for_tooltip                   = __( 'Maximum Time Duration', 'wp-captcha-booster' );
		$cpb_one_hour                            = '1 Hour';
		$cpb_twelve_hours                        = '12 Hours';
		$cpb_twenty_four_hours                   = '24 Hours';
		$cpb_forty_eight_hours                   = '48 Hours';
		$cpb_one_week                            = '1 Week';
		$cpb_one_month                           = '1 Month';
		$cpb_permanently                         = 'Permanently';
		$cpb_never                               = __( 'Never', 'wp-captcha-booster' );
		$cpb_button_clear                        = __( 'Clear', 'wp-captcha-booster' );
		$cpb_comments                            = __( 'Comments', 'wp-captcha-booster' );
		$cpb_placeholder_comment                 = __( 'Please provide Comments', 'wp-captcha-booster' );
		$cpb_start_date_heading                  = __( 'Start Date', 'wp-captcha-booster' );
		$cpb_end_date_heading                    = __( 'End Date', 'wp-captcha-booster' );
		$cpb_bulk_action                         = __( 'Bulk Action', 'wp-captcha-booster' );
		$cpb_apply                               = __( 'Apply', 'wp-captcha-booster' );
		$cpb_manage_ip_ranges                    = __( 'Manage IP Ranges', 'wp-captcha-booster' );
		$cpb_blocking_options                    = __( 'Blocking Options', 'wp-captcha-booster' );
		$cpb_manage_ip_addresses                 = __( 'Manage IP Addresses', 'wp-captcha-booster' );
		$cpb_submit                              = __( 'Submit', 'wp-captcha-booster' );
		$cpb_resources                           = __( 'Resources', 'wp-captcha-booster' );
		$cpb_http_user_agent                     = __( 'HTTP User Agent', 'wp-captcha-booster' );
		$cpb_error_message_common                = __( 'Error Messages', 'wp-captcha-booster' );
		$cpb_live_traffic_title                  = __( 'Live Traffic', 'wp-captcha-booster' );
		$cpb_visitor_logs_title                  = __( 'Visitor Logs', 'wp-captcha-booster' );
		$cpb_recent_login_log_title              = __( 'Login Logs', 'wp-captcha-booster' );
		$cpb_delete                              = __( 'Delete', 'wp-captcha-booster' );
		$cpb_block_ip_address                    = __( 'Block IP Address', 'wp-captcha-booster' );
		$cpb_start_date_placeholder              = __( 'Please provide Start Date', 'wp-captcha-booster' );
		$cpb_end_date_placeholder                = __( 'Please provide End Date', 'wp-captcha-booster' );
		$cpb_captcha_setup_menu                  = __( 'Captcha Setup', 'wp-captcha-booster' );
		$cpb_alert_setup_menu                    = __( 'Alert Setup', 'wp-captcha-booster' );
		$cpb_logs_menu                           = __( 'Logs', 'wp-captcha-booster' );
		$cpb_advance_security_menu               = __( 'Security Settings', 'wp-captcha-booster' );
		$cpb_country_blocks_menu                 = __( 'Country Blocks', 'wp-captcha-booster' );
		$cpb_email_templates_menu                = __( 'Email Templates', 'wp-captcha-booster' );
		$cpb_other_settings_menu                 = __( 'Other Settings', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_menu         = __( 'Roles & Capabilities', 'wp-captcha-booster' );
		$cpb_system_information_menu             = __( 'System Information', 'wp-captcha-booster' );
		$cpb_roles_and_capabilities_full_control = __( 'Full Control', 'wp-captcha-booster' );
		$cpb_captcha_booster_breadcrumb          = 'Captcha Booster';
		$cpb_captcha_booster_type_breadcrumb     = __( 'Captcha Type', 'wp-captcha-booster' );
		$cpb_display_settings_title              = __( 'Display Settings', 'wp-captcha-booster' );
		$cpb_ip_ranges                           = __( 'IP Ranges', 'wp-captcha-booster' );
		$cpb_enable                              = __( 'Enable', 'wp-captcha-booster' );
		$cpb_disable                             = __( 'Disable', 'wp-captcha-booster' );
		$cpb_save_changes                        = __( 'Save Changes', 'wp-captcha-booster' );
		$cpb_email_subject_title                 = __( 'Subject', 'wp-captcha-booster' );
		$cpb_placeholder_subject                 = __( 'Please provide Subject', 'wp-captcha-booster' );
		$cpb_block_time                          = __( 'Blocked Date & Time', 'wp-captcha-booster' );
		$cpb_release_time                        = __( 'Release Date & Time', 'wp-captcha-booster' );
		$cpb_user_name                           = __( 'User Name', 'wp-captcha-booster' );
		$cpb_date_time                           = __( 'Date & Time', 'wp-captcha-booster' );
		$cpb_action                              = __( 'Action', 'wp-captcha-booster' );
		$cpb_user_access_message                 = __( 'You don\'t have Sufficient Access to this Page. Kindly contact the Administrator for more Privileges', 'wp-captcha-booster' );
		$cpb_general_settings_menu               = __( 'General Settings', 'wp-captcha-booster' );
		$cpb_premium                             = __( 'Premium Edition', 'wp-captcha-booster' );

		// country Blocks.
		$cpb_country_blocks_available_countries_label   = __( 'Available Countries', 'wp-captcha-booster' );
		$cpb_country_blocks_available_countries_tooltip = __( 'List of Available Countries', 'wp-captcha-booster' );
		$cpb_country_blocks_add_button_label            = __( 'Add', 'wp-captcha-booster' );
		$cpb_country_blocks_remove_button_label         = __( 'Remove', 'wp-captcha-booster' );
		$cpb_country_blocks_blocked_countries_label     = __( 'Blocked Countries', 'wp-captcha-booster' );
		$cpb_country_blocks_blocked_countries_tooltip   = __( 'List of Blocked Countries', 'wp-captcha-booster' );
		$cpb_block                                      = __( 'Block', 'wp-captcha-booster' );
	}
}
