<?php
/**
 * This file is used for translation strings.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
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
		// recaptcha.
		$cpb_upgrade_need_help             = __( 'Know about ', 'captcha-bank' );
		$cpb_documentation                 = __( 'Full Features', 'captcha-bank' );
		$cpb_read_and_check                = __( ' or check our ', 'captcha-bank' );
		$cpb_demos_section                 = __( 'Online Demos', 'captcha-bank' );
		$cpb_upgrade                       = __( 'Premium Edition', 'captcha-bank' );
		$cpb_message_premium_edition       = __( 'This feature is available only in Premium Editions! <br> Kindly Purchase to unlock it!', 'captcha-bank' );
		$cpb_captcha_bank_google_recaptcha = __( 'Google reCaptcha', 'captcha-bank' );
		$cpb_next_step                     = __( 'Next Step', 'captcha-bank' );
		$cpb_previous_step                 = __( 'Previous Step', 'captcha-bank' );
		$cpb_captcha_setting               = __( 'Captcha Setting', 'captcha-bank' );
		$cpb_choose_form                   = __( 'Choose Form', 'captcha-bank' );
		$cpb_image                         = __( 'Image', 'captcha-bank' );
		$cpb_captcha_confirm               = __( 'Confirm', 'captcha-bank' );
		$cpb_captcha_type                  = __( 'Captcha Type', 'captcha-bank' );
		$cpb_audio                         = __( 'Audio', 'captcha-bank' );
		$cpb_light                         = __( 'Light', 'captcha-bank' );
		$cpb_dark                          = __( 'Dark', 'captcha-bank' );
		$cpb_normal                        = __( 'Normal', 'captcha-bank' );
		$cpb_compact                       = __( 'Compact', 'captcha-bank' );
		$cpb_bottom_right                  = __( 'Bottom Right', 'captcha-bank' );
		$cpb_bottom_left                   = __( 'Bottom Left', 'captcha-bank' );
		$cpb_inline                        = __( 'Inline', 'captcha-bank' );
		$cpb_captcha_key_type              = __( 'Captcha Key Type', 'captcha-bank' );
		$cpb_recaptcha_theme               = __( 'reCaptcha Theme', 'captcha-bank' );
		$cpb_recaptcha_size                = __( 'reCaptcha Size', 'captcha-bank' );
		$cpb_data_badge                    = __( 'Data Badge', 'captcha-bank' );
		$cpb_recaptcha_language            = __( 'reCaptcha Language', 'captcha-bank' );
		$cpb_whitelist_ip_address          = __( 'Whitelist IP Address / Range', 'captcha-bank' );
		$cpb_website_is_behind_a_proxy     = __( 'Website is behind a Proxy', 'captcha-bank' );
		$cpb_recaptcha_v2                  = __( 'reCaptcha V2', 'captcha-bank' );
		$cpb_recaptcha_v3                  = __( 'reCaptcha V3', 'captcha-bank' );
		$cpb_invisible_recaptcha           = __( 'Invisible reCaptcha', 'captcha-bank' );
		$cpb_live_preview_message          = __( 'You can see the captcha live preview at right or left corner of your screen', 'captcha-bank' );

		$cpb_end_ip_range_tooltip           = __( 'Leave this blank, if you want to perform only on single IP', 'captcha-bank' );
		$cpb_whitelist_ip_type_tooltip      = __( 'Select what type of IP you want to whitelist', 'captcha-bank' );
		$cpb_whitelistip_for_login          = __( 'Enter the IP you want to whitelist for login', 'captcha-bank' );
		$cpb_give_your_remarks_here         = __( 'Give your remarks here!', 'captcha-bank' );
		$cpb_ip_address_range               = __( 'Blacklist IP Address / Range', 'captcha-bank' );
		$cpb_whitelist_ip_address_added_msg = __( 'IP added to Whitelist!', 'captcha-bank' );

		$cpb_ip_type                       = __( 'IP Type', 'captcha-bank' );
		$cpb_single_ip                     = __( 'Single IP', 'captcha-bank' );
		$cpb_ip_range                      = __( 'IP Range', 'captcha-bank' );
		$cpb_multiple                      = __( 'Multiple', 'captcha-bank' );
		$cpb_manage_whitelist_ip_addresses = __( 'Manage Whitelist IP Addresses ', 'captcha-bank' );
		$cpb_clear                         = __( 'Clear', 'captcha-bank' );
		$cpb_start_ip                      = __( 'Start IP', 'captcha-bank' );
		$cpb_end_ip                        = __( 'End IP', 'captcha-bank' );
		$cpb_secret_key                    = __( 'Secret Key', 'captcha-bank' );
		$cpb_site_key                      = __( 'Site Key', 'captcha-bank' );
		$cpb_exclude_ips_tooltip           = __( 'You can exclude specific IP addresses (separated by comma) from displaying the recaptcha', 'captcha-bank' );

		$cpb_general_tab   = __( 'Configuration', 'captcha-bank' );
		$cpb_layout_tab    = __( 'Layout Design', 'captcha-bank' );
		$cpb_signature_tab = __( 'Signature', 'captcha-bank' );

		// footer.
		$cpb_block_own_ip_address         = __( 'You can\'t block your own IP Address!', 'captcha-bank' );
		$cpb_block_own_ip_whitelist       = __( 'You can\'t Whitelist your own IP Address!', 'captcha-bank' );
		$cpb_block_own_ip_range           = __( 'You can\'t block this IP Range as your IP Address lies between it', 'captcha-bank' );
		$cpb_block_own_ip_whitelist_range = __( 'You can\'t Whitelist this IP Range as your IP Address lies between it', 'captcha-bank' );

		$cpb_setting_saved              = __( 'Settings Saved!', 'captcha-bank' );
		$cpb_success                    = __( 'Success!', 'captcha-bank' );
		$cpb_feature_request            = __( 'Feature Request Email Sent', 'captcha-bank' );
		$cpb_ip_address_block           = __( 'Blocked Successfully', 'captcha-bank' );
		$cpb_delete_data                = __( 'Data Deleted!', 'captcha-bank' );
		$cpb_number_of_digits           = __( 'The number should lie between 1 and 100!', 'captcha-bank' );
		$cpb_country_block_message      = __( 'Selected Countries Blocked!', 'captcha-bank' );
		$cpb_country_unblock_message    = __( 'Selected Countries Unblocked!', 'captcha-bank' );
		$cpb_error_message              = __( 'Error Message', 'captcha-bank' );
		$cpb_arithmetic_action          = __( 'Choose at least one to continue!', 'captcha-bank' );
		$cpb_ip_address                 = __( 'IP Address', 'captcha-bank' );
		$cpb_location                   = __( 'Location', 'captcha-bank' );
		$cpb_latitude                   = __( 'Latitude', 'captcha-bank' );
		$cpb_longitude                  = __( 'Longitude', 'captcha-bank' );
		$cpb_na                         = 'N/A';
		$cpb_choose_action              = __( 'Choose an Action!', 'captcha-bank' );
		$cpb_confirm                    = __( 'Are you sure?', 'captcha-bank' );
		$cpb_valid_ip_range             = __( 'Invalid IP Range!', 'captcha-bank' );
		$cpb_valid_ip_address           = __( 'Invalid IP Address!', 'captcha-bank' );
		$cpb_ip_address_already_blocked = __( 'Already Blocked!', 'captcha-bank' );
		$cpb_notification               = __( 'Notification!', 'captcha-bank' );

		$cpb_number_of_captcha_character    = __( 'The number should lie between 1 and 10!', 'wp-captcha-booster' );
		$cpb_ip_address_already_whitelisted = __( 'Already Whitelisted!', 'captcha-bank' );
		// captcha setup.
		$cpb_captcha_bank_site_key_tooltip           = __( 'Site Key for creating Google reCaptcha', 'captcha-bank' );
		$cpb_captcha_bank_secret_key_tooltip         = __( 'Secret Key for creating Google reCaptcha', 'captcha-bank' );
		$cpb_captcha_bank_recaptcha_key_type_tooltip = __( 'What type of google reCaptcha do you want to show?', 'captcha-bank' );
		$cpb_captcha_bank_recaptcha_type_tooltip     = __( 'Do you want reCaptcha to be Image or Audio?', 'captcha-bank' );
		$cpb_captcha_bank_recaptcha_theme_tooltip    = __( 'What type of reCaptcha Theme do you want?', 'captcha-bank' );
		$cpb_captcha_bank_recaptcha_size_tooltip     = __( 'What type of reCaptcha Size do you want?', 'captcha-bank' );
		$cpb_captcha_bank_data_badge_tooltip         = __( 'Reposition your Invisible reCaptcha', 'captcha-bank' );
		$cpb_recaptcha_language_tooltip              = __( 'In which Language you want your reCaptcha', 'captcha-bank' );


		$cpb_captcha_wizard_label                       = __( 'Wizard', 'captcha-bank' );
		$cpb_captcha_bank_type_tooltip                  = __( 'What type of Captcha do you want to show?', 'captcha-bank' );
		$cpb_captcha_bank_text_captcha                  = __( 'Text Captcha', 'captcha-bank' );
		$cpb_captcha_bank_logical_captcha               = __( 'Logical Captcha', 'captcha-bank' );
		$cpb_captcha_bank_character_title               = __( 'Number of Characters', 'captcha-bank' );
		$cpb_captcha_bank_character_tooltip             = __( 'Number of Characters for creating Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_string_type_title             = __( 'Character Type', 'captcha-bank' );
		$cpb_captcha_bank_string_type_tooltip           = __( 'Different Types available for creating Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_alphabets_digits              = __( 'Alphabets And Digits', 'captcha-bank' );
		$cpb_captcha_bank_only_alphabets                = __( 'Only Alphabets', 'captcha-bank' );
		$cpb_captcha_bank_only_digits                   = __( 'Only Digits', 'captcha-bank' );
		$cpb_captcha_bank_text_case_title               = __( 'Text Case', 'captcha-bank' );
		$cpb_captcha_bank_text_case_tooltip             = __( 'Text Case available for Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_upper_case                    = __( 'Upper Case', 'captcha-bank' );
		$cpb_captcha_bank_lower_case                    = __( 'Lower Case', 'captcha-bank' );
		$cpb_captcha_bank_random_case                   = __( 'Mixed', 'captcha-bank' );
		$cpb_captcha_bank_case_sensitive_title          = __( 'Case Sensitive', 'captcha-bank' );
		$cpb_captcha_bank_case_sensitive_tooltip        = __( 'Do you want Captcha to be Case Sensitive?', 'captcha-bank' );
		$cpb_captcha_bank_width_title                   = __( 'Width (px)', 'captcha-bank' );
		$cpb_captcha_bank_width_tooltip                 = __( 'The Width of Captcha Image in Pixels', 'captcha-bank' );
		$cpb_captcha_bank_height_title                  = __( 'Height (px)', 'captcha-bank' );
		$cpb_captcha_bank_height_tooltip                = __( 'The Height of Captcha Image in Pixels', 'captcha-bank' );
		$cpb_captcha_bank_background_title              = __( 'Background Pattern', 'captcha-bank' );
		$cpb_captcha_bank_background_tooltip            = __( 'Available Background Patterns for Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_background_pattern1           = 'Pattern 1';
		$cpb_captcha_bank_background_pattern2           = 'Pattern 2';
		$cpb_captcha_bank_background_pattern3           = 'Pattern 3';
		$cpb_captcha_bank_background_pattern4           = 'Pattern 4';
		$cpb_captcha_bank_background_pattern5           = 'Pattern 5';
		$cpb_captcha_bank_background_pattern6           = 'Pattern 6';
		$cpb_captcha_bank_background_pattern7           = 'Pattern 7';
		$cpb_captcha_bank_background_pattern8           = 'Pattern 8';
		$cpb_captcha_bank_background_pattern9           = 'Pattern 9';
		$cpb_captcha_bank_background_pattern10          = 'Pattern 10';
		$cpb_captcha_bank_background_pattern11          = 'Pattern 11';
		$cpb_captcha_bank_background_pattern12          = 'Pattern 12';
		$cpb_captcha_bank_background_pattern13          = 'Pattern 13';
		$cpb_captcha_bank_background_pattern14          = 'Pattern 14';
		$cpb_captcha_bank_background_pattern15          = 'Pattern 15';
		$cpb_captcha_bank_background_pattern16          = 'Pattern 16';
		$cpb_captcha_bank_background_pattern17          = 'Pattern 17';
		$cpb_captcha_bank_background_pattern18          = 'Pattern 18';
		$cpb_captcha_bank_text_style_title              = __( 'Text Style', 'captcha-bank' );
		$cpb_captcha_bank_text_style_tooltip            = __( 'Font Style used in Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_text_font_tooltip             = __( 'Type of Font Families available', 'captcha-bank' );
		$cpb_captcha_bank_border_style_title            = __( 'Border Style', 'captcha-bank' );
		$cpb_captcha_bank_border_style_tooltip          = __( 'Border Style used in Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_border_solid                  = 'Solid';
		$cpb_captcha_bank_border_dotted                 = 'Dotted';
		$cpb_captcha_bank_border_dashed                 = 'Dashed';
		$cpb_captcha_bank_lines_title                   = __( 'Lines', 'captcha-bank' );
		$cpb_captcha_bank_lines_tooltip                 = __( 'Total Number of Lines used in Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_lines_color_title             = __( 'Lines Color', 'captcha-bank' );
		$cpb_captcha_bank_lines_color_tooltip           = __( 'The Color of Lines used in Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_noise_level_title             = __( 'Noise level', 'captcha-bank' );
		$cpb_captcha_bank_noise_level_tooltip           = __( '0 - None, 100 - Maximum', 'captcha-bank' );
		$cpb_captcha_bank_noise_level_placeholder       = __( 'Please provide Noise Level', 'captcha-bank' );
		$cpb_captcha_bank_noise_color_title             = __( 'Noise Color', 'captcha-bank' );
		$cpb_captcha_bank_noise_color_tooltip           = __( 'The Color of Noise used in Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_text_transparency_title       = __( 'Text Transparency %', 'captcha-bank' );
		$cpb_captcha_bank_text_transparency_placeholder = __( 'Please provide Text Transparency', 'captcha-bank' );
		$cpb_captcha_bank_signature_text_title          = __( 'Text', 'captcha-bank' );
		$cpb_captcha_bank_signature_text_tooltip        = __( 'Signature Text for your Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_signature_font_title          = __( 'Font Family', 'captcha-bank' );
		$cpb_captcha_bank_shadow_color_title            = __( 'Shadow Color', 'captcha-bank' );
		$cpb_captcha_bank_shadow_color_tooltip          = __( 'The Color of shadow used in Captcha Image', 'captcha-bank' );
		$cpb_captcha_bank_mathematical_title            = __( 'Mathematical Operations', 'captcha-bank' );
		$cpb_captcha_bank_mathematical_tooltip          = __( 'Different Mathematical Operations available', 'captcha-bank' );
		$cpb_captcha_bank_arithmetic                    = __( 'Arithmetic', 'captcha-bank' );
		$cpb_captcha_bank_relational                    = __( 'Relational', 'captcha-bank' );
		$cpb_captcha_bank_arithmetic_title              = __( 'Arithmetic Actions', 'captcha-bank' );
		$cpb_captcha_bank_addition                      = __( 'Addition', 'captcha-bank' );
		$cpb_captcha_bank_subtraction                   = __( 'Subtraction', 'captcha-bank' );
		$cpb_captcha_bank_multiplication                = __( 'Multiplication', 'captcha-bank' );
		$cpb_captcha_bank_division                      = __( 'Division', 'captcha-bank' );
		$cpb_captcha_bank_arithmetic_tooltip            = __( 'What operations would you like to use for creating Captcha Image?', 'captcha-bank' );
		$cpb_captcha_bank_relational_title              = __( 'Relational Actions', 'captcha-bank' );
		$cpb_captcha_bank_largest_number                = __( 'Largest Number', 'captcha-bank' );
		$cpb_captcha_bank_smallest_number               = __( 'Smallest Number', 'captcha-bank' );
		$cpb_captcha_bank_arrange_title                 = __( 'Arrange Order', 'captcha-bank' );
		$cpb_captcha_bank_ascending_order               = __( 'Ascending Order', 'captcha-bank' );
		$cpb_captcha_bank_descending_order              = __( 'Descending Order', 'captcha-bank' );

		// other settings.
		$cpb_other_settings_remove_tables               = __( 'Remove Database at Uninstall', 'captcha-bank' );
		$cpb_other_settings_remove_tables_tootltip      = __( 'Do you want to remove Database at Uninstall of the Plugin?', 'captcha-bank' );
		$cpb_other_settings_ip_address_fetching_method  = __( 'How does Captcha Bank get IPs', 'captcha-bank' );
		$cpb_other_settings_ip_address_tooltips         = __( 'Options available for retrieving IP Address', 'captcha-bank' );
		$cpb_other_settings_ip_address_fetching_option1 = __( 'Let Captcha Bank use the most secure method to get visitor IP address. Prevents spoofing and works with most sites', 'captcha-bank' );
		$cpb_other_settings_ip_address_fetching_option2 = __( 'Use PHP\'s built in REMOTE_ADDR and don\'t use anything else. Very secure if this is compatible with your site', 'captcha-bank' );
		$cpb_other_settings_ip_address_fetching_option3 = __( 'Use the X-Forwarded-For HTTP header. Only use if you have a front-end proxy or spoofing may result', 'captcha-bank' );
		$cpb_other_settings_ip_address_fetching_option4 = __( 'Use the X-Real-IP HTTP header. Only use if you have a front-end proxy or spoofing may result', 'captcha-bank' );
		$cpb_other_settings_ip_address_fetching_option5 = __( 'Use the Cloudflare \'CF-Connecting-IP\' HTTP header to get a visitor IP. Only use if you\'re using Cloudflare', 'captcha-bank' );

		// alert setup.
		$cpb_alert_setup_email_fails_login_title          = __( 'Email when a User Fails Login', 'captcha-bank' );
		$cpb_alert_setup_email_success_login_title        = __( 'Email when a User Success Login', 'captcha-bank' );
		$cpb_alert_setup_email_ip_address_blocked_title   = __( 'Email when an IP Address is Blocked', 'captcha-bank' );
		$cpb_alert_setup_email_ip_address_unblocked_title = __( 'Email when an IP Address is Unblocked', 'captcha-bank' );
		$cpb_alert_setup_email_ip_range_blocked_title     = __( 'Email when an IP Range is Blocked', 'captcha-bank' );
		$cpb_alert_setup_email_ip_range_unblocked_title   = __( 'Email when an IP Range is Unblocked', 'captcha-bank' );

		// email template.
		$cpb_email_templates_tooltip                        = __( 'Available Email Templates', 'captcha-bank' );
		$cpb_email_templates_choose_title                   = __( 'Choose Email Template', 'captcha-bank' );
		$cpb_email_templates_send_to_title                  = __( 'Send To', 'captcha-bank' );
		$cpb_email_templates_send_to_email_tooltip          = __( 'A valid Email Address account to which you would like to send Emails', 'captcha-bank' );
		$cpb_email_templates_send_email_address_placeholder = __( 'Please provide valid Email Address', 'captcha-bank' );
		$cpb_email_templates_cc_title                       = 'Cc';
		$cpb_email_templates_cc_email_tooltip               = __( 'A valid Email Address account used in the "CC" field. Use "," to separate multiple email addresses', 'captcha-bank' );
		$cpb_email_templates_cc_email_address_placeholder   = __( 'Please provide Cc Email', 'captcha-bank' );
		$cpb_email_templates_bcc_title                      = 'Bcc';
		$cpb_email_templates_bcc_email_tooltip              = __( 'A valid Email Address account used in the "BCC" field. Use "," to separate multiple email addresses', 'captcha-bank' );
		$cpb_email_templates_bcc_email_address_placeholder  = __( 'Please provide Bcc Email', 'captcha-bank' );
		$cpb_email_templates_subject_tooltip                = __( 'Subject Line of your Email', 'captcha-bank' );
		$cpb_email_templates_cpb_message_title              = __( 'Message', 'captcha-bank' );
		$cpb_email_templates_message_content_tooptip        = __( 'The content of your Email', 'captcha-bank' );
		$cpb_email_templates_successful_login               = __( 'Email Template For User Successful Login', 'captcha-bank' );
		$cpb_email_templates_failure_login                  = __( 'Email Template For User Failure Login', 'captcha-bank' );
		$cpb_email_templates_ip_address_blocked             = __( 'Email Template For IP Address Blocked', 'captcha-bank' );
		$cpb_email_templates_ip_address_unblocked           = __( 'Email Template For IP Address Unblocked', 'captcha-bank' );
		$cpb_email_templates_ip_range_blocked               = __( 'Email Template For IP Range Blocked', 'captcha-bank' );
		$cpb_email_templates_ip_range_unblocked             = __( 'Email Template For IP Range Unblocked', 'captcha-bank' );

		// roles and capabilities.
		$cpb_show_roles_and_capabilities_menu                        = __( 'Show Captcha Bank Menu', 'captcha-bank' );
		$cpb_roles_and_capabilities_menu_tooltip                     = __( 'Choose who would be able to see Captcha Bank Menu?', 'captcha-bank' );
		$cpb_roles_and_capabilities_administrator                    = __( 'Administrator', 'captcha-bank' );
		$cpb_roles_and_capabilities_author                           = __( 'Author', 'captcha-bank' );
		$cpb_roles_and_capabilities_editor                           = __( 'Editor', 'captcha-bank' );
		$cpb_roles_and_capabilities_contributor                      = __( 'Contributor', 'captcha-bank' );
		$cpb_roles_and_capabilities_subscriber                       = __( 'Subscriber', 'captcha-bank' );
		$cpb_roles_and_capabilities_others                           = __( 'Others', 'captcha-bank' );
		$cpb_roles_and_capabilities_topbar_menu                      = __( 'Show Captcha Bank Top Bar Menu', 'captcha-bank' );
		$cpb_roles_and_capabilities_topbar_menu_tooltip              = __( 'Do you want to show Captcha Bank menu in Top Bar?', 'captcha-bank' );
		$cpb_roles_and_capabilities_administrator_role               = __( 'An Administrator Role can do the following', 'captcha-bank' );
		$cpb_roles_and_capabilities_administrator_role_tooltip       = __( 'Choose pages for users having Administrator Access', 'captcha-bank' );
		$cpb_roles_and_capabilities_author_role                      = __( 'An Author Role can do the following', 'captcha-bank' );
		$cpb_roles_and_capabilities_author_role_tooltip              = __( 'Choose pages for users having Author Access', 'captcha-bank' );
		$cpb_roles_and_capabilities_editor_role                      = __( 'An Editor Role can do the following', 'captcha-bank' );
		$cpb_roles_and_capabilities_editor_role_tooltip              = __( 'Choose pages for users having Editor Access', 'captcha-bank' );
		$cpb_roles_and_capabilities_contributor_role                 = __( 'A Contributor Role can do the following', 'captcha-bank' );
		$cpb_roles_and_capabilities_contributor_role_tooltip         = __( 'Choose pages for users having Contributor Access', 'captcha-bank' );
		$cpb_roles_and_capabilities_subscriber_role                  = __( 'A Subscriber Role can do the following', 'captcha-bank' );
		$cpb_roles_and_capabilities_subscriber_role_tooltip          = __( 'Choose pages for users having Subscriber Access', 'captcha-bank' );
		$cpb_roles_and_capabilities_other_role                       = __( 'Other Roles can do the following', 'captcha-bank' );
		$cpb_roles_and_capabilities_other_role_tooltip               = __( 'Choose pages for users having Others Role Access', 'captcha-bank' );
		$cpb_roles_and_capabilities_other_roles_capabilities         = __( 'Please tick appropriate capabilities for security purposes', 'captcha-bank' );
		$cpb_roles_and_capabilities_other_roles_capabilities_tooltip = __( 'Only users with these capabilities can access Captcha Bank', 'captcha-bank' );

		// live traffic and login logs.
		$cpb_recent_login_logs_start_date_tooltip = __( 'Start Date for Retrieving Data', 'captcha-bank' );
		$cpb_recent_login_logs_end_date_tooltip   = __( 'End Date for Retrieving Data', 'captcha-bank' );
		$cpb_details                              = __( 'Details', 'captcha-bank' );
		$cpb_status                               = __( 'Status', 'captcha-bank' );

		// error messages.
		$cpb_error_message_login_failure_title        = __( 'Maximum Login Attempts', 'captcha-bank' );
		$cpb_error_message_login_failure_tooltip      = __( 'Error Message to be displayed when a User exceeds Maximum Number of Login Attempts', 'captcha-bank' );
		$cpb_error_message_login_failure_placeholder  = __( 'Please provide Error Message', 'captcha-bank' );
		$cpb_error_message_invalid_captcha_title      = __( 'Invalid Captcha', 'captcha-bank' );
		$cpb_error_message_invalid_captcha_tooltip    = __( 'Error Message to be displayed when a User inserts Invalid Captcha', 'captcha-bank' );
		$cpb_error_message_blocked_ip_address_title   = __( 'Blocked IP Address', 'captcha-bank' );
		$cpb_error_message_blocked_ip_address_tooltip = __( 'Error Message to be displayed when an IP Address is Blocked', 'captcha-bank' );
		$cpb_error_message_empty_captcha_title        = __( 'Empty Captcha', 'captcha-bank' );
		$cpb_error_message_empty_captcha_tooltip      = __( 'Error Message to be displayed when Captcha is empty', 'captcha-bank' );
		$cpb_error_message_blocked_ip_range_title     = __( 'Blocked IP Range', 'captcha-bank' );
		$cpb_error_message_blocked_ip_range_tooltip   = __( 'Error Message to be displayed when an IP Range is Blocked', 'captcha-bank' );
		$cpb_error_messages_blocked_country_label     = __( 'Blocked Country', 'captcha-bank' );
		$cpb_error_messages_blocked_country_tooltip   = __( 'Error Message to be displayed when a Country is Blocked', 'captcha-bank' );

		// Display settings.
		$cpb_display_settings_enable_captcha_for                = __( 'Enable Captcha For', 'captcha-bank' );
		$cpb_display_settings_enable_captcha_tooltip            = __( 'Available Forms on which you can display Captcha', 'captcha-bank' );
		$cpb_display_settings_login_form                        = __( 'Login Form', 'captcha-bank' );
		$cpb_display_settings_registration_form                 = __( 'Registration Form', 'captcha-bank' );
		$cpb_display_settings_reset_password_form               = __( 'Reset Password Form', 'captcha-bank' );
		$cpb_display_settings_comment_form                      = __( 'Comment Form', 'captcha-bank' );
		$cpb_display_settings_admin_comment_form                = __( 'Admin Comment Form', 'captcha-bank' );
		$cpb_display_settings_hide_captcha_register_user        = __( 'Hide Captcha For Registered User', 'captcha-bank' );
		$cpb_display_settings_captcha_woocommerce_login         = __( 'WooCommerce Login Form', 'captcha-bank' );
		$cpb_display_settings_captcha_woocommerce_register      = __( 'WooCommerce Registration Form', 'captcha-bank' );
		$cpb_display_settings_captcha_woocommerce_lost_password = __( 'WooCommerce Reset Password Form', 'captcha-bank' );
		$cpb_display_settings_captcha_woocommerce_checkout      = __( 'WooCommerce Checkout Form', 'captcha-bank' );
		$cpb_display_settings_contact_form7                     = __( 'Contact Form 7', 'captcha-bank' );
		$cpb_display_settings_buddypress                        = __( 'BuddyPress Registration Form', 'captcha-bank' );
		$cpb_display_settings_buddypress_login                  = __( 'BuddyPress Login Form', 'captcha-bank' );
		$cpb_display_settings_buddypress_lost_password          = __( 'BuddyPress Reset Password Form', 'captcha-bank' );
		$cpb_display_settings_captcha_shortcode                 = __( 'Please copy and paste this shortcode in your Contact Form 7 after Saving Changes', 'captcha-bank' );
		$cpb_display_settings_captcha_bbpress_login             = __( 'bbPress Login Form', 'captcha-bank' );
		$cpb_display_settings_captcha_bbpress_register          = __( 'bbPress Registration Form', 'captcha-bank' );
		$cpb_display_settings_captcha_bbpress_lost_password     = __( 'bbPress Reset Password Form', 'captcha-bank' );
		$cpb_display_settings_captcha_bbpress_new_topic         = __( 'bbPress New Topic Form', 'captcha-bank' );
		$cpb_display_settings_captcha_bbpress_reply_topic       = __( 'bbPress Reply To Topic Form', 'captcha-bank' );
		$cpb_display_settings_captcha_wpforo_login              = __( 'wpForo Login Form', 'captcha-bank' );
		$cpb_display_settings_captcha_wpforo_register           = __( 'wpForo Registration Form', 'captcha-bank' );
		$cpb_display_settings_captcha_jetpack_form              = __( 'Jetpack Contact Form', 'captcha-bank' );

		// feature Requests.
		$cpb_feature_requests_thank_you         = __( 'Thank You!', 'captcha-bank' );
		$cpb_feature_requests_fill_form         = __( 'Kindly fill in the below form, if you would like to suggest some features which are not in the Plugin', 'captcha-bank' );
		$cpb_feature_requests_any_suggestion    = __( 'If you also have any suggestion/complaint, you can use the same form below', 'captcha-bank' );
		$cpb_feature_requests_write_us_on       = __( 'You can also write us on', 'captcha-bank' );
		$cpb_feature_requests_name_title        = __( 'Your Name', 'captcha-bank' );
		$cpb_feature_requests_name_tooltip      = __( 'Name of the Feedback Provider', 'captcha-bank' );
		$cpb_feature_requests_name_placeholder  = __( 'Please provide your Name', 'captcha-bank' );
		$cpb_feature_requests_email_title       = __( 'Your Email', 'captcha-bank' );
		$cpb_feature_requests_email_tooltip     = __( 'Valid Email Address of the Feedback Provider', 'captcha-bank' );
		$cpb_feature_requests_email_placeholder = __( 'Please provide your Email', 'captcha-bank' );
		$cpb_feature_requests_tooltip           = __( 'Feedback to be Sent', 'captcha-bank' );
		$cpb_feature_requests_placeholder       = __( 'Please provide your Feature Request', 'captcha-bank' );
		$cpb_feature_requests_send_request      = __( 'Send Request', 'captcha-bank' );

		// Blocking Options.
		$cpb_blocking_options_title                      = __( 'Auto IP Block', 'captcha-bank' );
		$cpb_blocking_options_tooltip                    = __( 'Choose whether to block IP Address automatically when User exceeds Maximum Number of Login Attempts', 'captcha-bank' );
		$cpb_blocking_options_login_attempts_title       = __( 'Maximum Login Attempts in a Day', 'captcha-bank' );
		$cpb_blocking_options_login_attempts_tooltip     = __( 'Maximum Number of Login Attempts to be allowed in a Day', 'captcha-bank' );
		$cpb_blocking_options_login_attempts_placeholder = __( 'Please provide Maximum Login Attempts', 'captcha-bank' );

		// Manage IP Addresses.
		$cpb_manage_ip_addresses_tooltip    = __( 'Valid IP Address to be Blocked', 'captcha-bank' );
		$cpb_manage_ip_addresses_view_block = __( 'View Blocked IP Addresses', 'captcha-bank' );
		$cpb_tooltip_comment                = __( 'Reason for Blocking', 'captcha-bank' );

		// Manage IP Ranges.
		$cpb_manage_ip_ranges_start_range_title       = __( 'Start IP Range', 'captcha-bank' );
		$cpb_manage_ip_ranges_start_range_tooltip     = __( 'Valid IP Range to be Blocked', 'captcha-bank' );
		$cpb_manage_ip_ranges_start_range_placeholder = __( 'Please provide Start IP Range', 'captcha-bank' );
		$cpb_manage_ip_ranges_end_range_title         = __( 'End IP Range', 'captcha-bank' );
		$cpb_manage_ip_ranges_end_range_tooltip       = __( 'Valid IP Range to be Blocked', 'captcha-bank' );
		$cpb_manage_ip_ranges_end_range_placeholder   = __( 'Please provide End IP Range', 'captcha-bank' );
		$cbp_manage_ip_ranges_block                   = __( 'Block IP Range', 'captcha-bank' );
		$cpb_manage_ip_ranges_view_block              = __( 'View Blocked IP Ranges', 'captcha-bank' );

		// Common Variables.
		$cpb_block                               = __( 'Block', 'captcha-bank' );
		$cpb_captcha_bank_title                  = 'Captcha Bank';
		$cpb_color_code                          = __( 'Color Code', 'captcha-bank' );
		$cpb_block_for_title                     = __( 'Blocked For', 'captcha-bank' );
		$cpb_block_for_tooltip                   = __( 'Maximum Time Duration', 'captcha-bank' );
		$cpb_one_hour                            = '1 Hour';
		$cpb_twelve_hours                        = '12 Hours';
		$cpb_twenty_four_hours                   = '24 Hours';
		$cpb_forty_eight_hours                   = '48 Hours';
		$cpb_one_week                            = '1 Week';
		$cpb_one_month                           = '1 Month';
		$cpb_permanently                         = 'Permanently';
		$cpb_never                               = __( 'Never', 'captcha-bank' );
		$cpb_button_clear                        = __( 'Clear', 'captcha-bank' );
		$cpb_comments                            = __( 'Comments', 'captcha-bank' );
		$cpb_placeholder_comment                 = __( 'Please provide Comments', 'captcha-bank' );
		$cpb_start_date_heading                  = __( 'Start Date', 'captcha-bank' );
		$cpb_end_date_heading                    = __( 'End Date', 'captcha-bank' );
		$cpb_bulk_action                         = __( 'Bulk Action', 'captcha-bank' );
		$cpb_apply                               = __( 'Apply', 'captcha-bank' );
		$cpb_feature_requests                    = __( 'Ask for Help', 'captcha-bank' );
		$cpb_submit                              = __( 'Submit', 'captcha-bank' );
		$cpb_resources                           = __( 'Resources', 'captcha-bank' );
		$cpb_http_user_agent                     = __( 'HTTP User Agent', 'captcha-bank' );
		$cpb_message_settings_label              = __( 'Message Settings', 'captcha-bank' );
		$cpb_delete                              = __( 'Delete', 'captcha-bank' );
		$cpb_block_ip_address                    = __( 'Block IP Address', 'captcha-bank' );
		$cpb_start_date_placeholder              = __( 'Please choose Start Date', 'captcha-bank' );
		$cpb_end_date_placeholder                = __( 'Please choose End Date', 'captcha-bank' );
		$cpb_captcha_setup_menu                  = __( 'Captcha Setup', 'captcha-bank' );
		$cpb_notification_setup_label            = __( 'Notifications Setup', 'captcha-bank' );
		$cpb_blockage_settings_label             = __( 'Blockage Settings', 'captcha-bank' );
		$cpb_block_unblock_ip_address_label      = __( 'Blacklist IP Addresses', 'captcha-bank' );
		$cpb_block_unblock_ip_range_label        = __( 'Blacklist IP Ranges', 'captcha-bank' );
		$cpb_block_unblock_countries_label       = __( 'Block / Unblock Countries', 'captcha-bank' );
		$cpb_email_templates_menu                = __( 'Email Templates', 'captcha-bank' );
		$cpb_other_settings_menu                 = __( 'Other Settings', 'captcha-bank' );
		$cpb_roles_and_capabilities_menu         = __( 'Roles & Capabilities', 'captcha-bank' );
		$cpb_system_information_menu             = __( 'System Information', 'captcha-bank' );
		$cpb_roles_and_capabilities_full_control = __( 'Full Control', 'captcha-bank' );
		$cpb_display_settings_title              = __( 'Display Settings', 'captcha-bank' );
		$cpb_ip_ranges                           = __( 'IP Ranges', 'captcha-bank' );
		$cpb_enable                              = __( 'Enable', 'captcha-bank' );
		$cpb_disable                             = __( 'Disable', 'captcha-bank' );
		$cpb_save_changes                        = __( 'Save Changes', 'captcha-bank' );
		$cpb_email_subject_title                 = __( 'Subject', 'captcha-bank' );
		$cpb_placeholder_subject                 = __( 'Please provide Subject', 'captcha-bank' );
		$cpb_block_time                          = __( 'Blocked Date & Time', 'captcha-bank' );
		$cpb_release_time                        = __( 'Release Date & Time', 'captcha-bank' );
		$cpb_user_name                           = __( 'User Name', 'captcha-bank' );
		$cpb_date_time                           = __( 'Date & Time', 'captcha-bank' );
		$cpb_action                              = __( 'Action', 'captcha-bank' );
		$cpb_user_access_message                 = __( 'You don\'t have Sufficient Access to this Page. Kindly contact the Administrator for more Privileges', 'captcha-bank' );
		$cpb_general_settings_menu               = __( 'General Settings', 'captcha-bank' );

		// country Blocks.
		$cpb_country_blocks_available_countries_label   = __( 'Available Countries', 'captcha-bank' );
		$cpb_country_blocks_available_countries_tooltip = __( 'List of Available Countries', 'captcha-bank' );
		$cpb_country_blocks_add_button_label            = __( 'Add', 'captcha-bank' );
		$cpb_country_blocks_remove_button_label         = __( 'Remove', 'captcha-bank' );
		$cpb_country_blocks_blocked_countries_label     = __( 'Blocked Countries', 'captcha-bank' );
		$cpb_country_blocks_blocked_countries_tooltip   = __( 'List of Blocked Countries', 'captcha-bank' );
	}
}
