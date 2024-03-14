<?php
/**
 * Plugin Name: Call Now Button Ultimate
 * Plugin URI: http://gethuman.com
 * Description: This plugin adds a helpful contact button to the bottom of your mobile site that allows your visitors to call you when you are open, and email you when you are unavailable by phone.
 * Version: 1.1
 * Author: GetHuman
 * License: GPL3
 */

define('GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE', 'call_now_ultimate_plugin_settings_page');
define('GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_VERSION', '1.1');
define('GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_OPTIONS', 'gh_cnbu_call_now_button_ultimate_options');
define('GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_OPTIONS_GROUP', 'gh_cnbu_call_now_button_ultimate_options_group');

define('GH_CNUB_SETTINGS_SECTION_ID', 'status_section');
define('GH_CNUB_CONTACT_INFORMATION_SECTION_ID', 'contact_information_section');
define('GH_CNUB_HOURS_OF_OPERATION_SECTION_ID', 'hours_of_operation_section');
define('GH_CNUB_APPEARANCE_SECTION_ID', 'appearance_section');

define('GH_CNUB_IS_ENABLED_OPTION_NAME', 'is_enabled');
define('GH_CNUB_EMAIL_ADDRESS_OPTION_NAME', 'email_address');
define('GH_CNUB_PHONE_NUMBER_OPTION_NAME', 'phone_number');

define('GH_CNUB_HOURS_OF_OPERATION_LOWER_BOUND_OPTION_NAME', 'hours_of_operation_lower_bound');
define('GH_CNUB_HOURS_OF_OPERATION_UPPER_BOUND_OPTION_NAME', 'hours_of_operation_upper_bound');
define('GH_CNUB_HOURS_OF_OPERATION_TIME_ZONE_OPTION_NAME', 'hours_of_operation_time_zone');

define('GH_CNUB_OPEN_BUTTON_LABEL_TEXT_OPTION_NAME', 'open_button_label_text');
define('GH_CNUB_CLOSED_BUTTON_LABEL_TEXT_OPTION_NAME', 'closed_button_label_text');
define('GH_CNUB_BUTTON_LABEL_TEXT_COLOR_OPTION_NAME', 'button_label_text_color');
define('GH_CNUB_BUTTON_BACKGROUND_COLOR_OPTION_NAME', 'button_background_color');

define('GH_CNUB_STATUS_SETTINGS_ERROR', 'status_settings_error');
define('GH_CNUB_HOURS_OF_OPERATION_SETTINGS_ERROR', 'hours_of_operation_settings_error');
define('GH_CNUB_BUTTON_APPEARANCE_SETTINGS_ERROR', 'button_appearance_settings_error');
define('GH_CNUB_CONTACT_INFORMATION_SETTINGS_ERROR', 'contact_information_settings_error');

gh_cnbu_setup_call_now_button_ultimate();
gh_cnbu_add_call_now_button_ultimate_to_site();

function gh_cnbu_setup_call_now_button_ultimate() {
	add_action('admin_menu', 'gh_cnbu_add_call_now_button_ultimate_to_options_menu');
	add_action('admin_enqueue_scripts', 'gh_cnbu_add_wordpress_color_picker');
	add_action('admin_init', 'gh_cnbu_initialize_call_now_button_ultimate_settings_page');
}

function gh_cnbu_add_call_now_button_ultimate_to_site() {
	add_action('wp_footer', 'gh_cnbu_add_call_now_button_ultimate_to_footer');
}

function gh_cnbu_add_call_now_button_ultimate_to_options_menu() {
	add_menu_page(
		$page_title = 'Call Now Ultimate',
		$menu_title = 'Call Now Button Ultimate',
		$capability = 'administrator',
		$menu_slug = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$function = 'gh_cnbu_render_call_now_button_ultimate_settings_page',
		'dashicons-phone'
	);
}

function gh_cnbu_add_call_now_button_ultimate_to_footer() {
	$is_enabled = gh_cnbu_get_call_now_button_option(GH_CNUB_IS_ENABLED_OPTION_NAME);
	if ($is_enabled !== 'true') {
		return;
	}

	$query_params = array();

	$phone_number = gh_cnbu_get_call_now_button_option(GH_CNUB_PHONE_NUMBER_OPTION_NAME);
	$email = gh_cnbu_get_call_now_button_option(GH_CNUB_EMAIL_ADDRESS_OPTION_NAME);
	$hours_of_operation_lower_bound = gh_cnbu_get_call_now_button_option(GH_CNUB_HOURS_OF_OPERATION_LOWER_BOUND_OPTION_NAME);
	$hours_of_operation_upper_bound = gh_cnbu_get_call_now_button_option(GH_CNUB_HOURS_OF_OPERATION_UPPER_BOUND_OPTION_NAME);
	$hours_of_operation_time_zone = gh_cnbu_get_call_now_button_option(GH_CNUB_HOURS_OF_OPERATION_TIME_ZONE_OPTION_NAME);
	$open_button_label_text = gh_cnbu_get_call_now_button_option(GH_CNUB_OPEN_BUTTON_LABEL_TEXT_OPTION_NAME);
	$closed_button_label_text = gh_cnbu_get_call_now_button_option(GH_CNUB_CLOSED_BUTTON_LABEL_TEXT_OPTION_NAME);
	$button_label_text_color = gh_cnbu_get_call_now_button_option(GH_CNUB_BUTTON_LABEL_TEXT_COLOR_OPTION_NAME);
	$button_background_color = gh_cnbu_get_call_now_button_option(GH_CNUB_BUTTON_BACKGROUND_COLOR_OPTION_NAME);

	if (is_null($phone_number) && is_null($email)) {
		echo '<script>console.log("Please provide a valid phone and email for Call Now Button Ultimate.");</script>';
		return;
	}

	if (!is_null($phone_number)) {
		array_push($query_params, 'phone=' . urlencode($phone_number));
	}

	if (!is_null($email)) {
		array_push($query_params, 'email=' . urlencode($email));
	}
	if (!is_null($hours_of_operation_lower_bound)) {
		array_push($query_params, 'hoursLowerBound=' . urlencode($hours_of_operation_lower_bound));
	}

	if (!is_null($hours_of_operation_upper_bound)) {
		array_push($query_params, 'hoursUpperBound=' . urlencode($hours_of_operation_upper_bound));
	}

	if (!is_null($hours_of_operation_time_zone)) {
		array_push($query_params, 'hoursTimezone=' . urlencode($hours_of_operation_time_zone));
	}

	if (!is_null($open_button_label_text)) {
		array_push($query_params, 'openButtonLabelText=' . urlencode($open_button_label_text));
	}

	if (!is_null($closed_button_label_text)) {
		array_push($query_params, 'closedButtonLabelText=' . urlencode($closed_button_label_text));
	}

	if (!is_null($button_label_text_color)) {
		array_push($query_params, 'buttonLabelTextColor=' . urlencode($button_label_text_color));
	}

	if (!is_null($button_background_color)) {
		array_push($query_params, 'buttonBgColor=' . urlencode($button_background_color));
	}

	array_push($query_params, 'version=' . urlencode(GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_VERSION));

	$script_url = implode('&', $query_params);

	echo '
	<script id="gh-cnbu-plugin">
!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://gethuman.com/call-now-button-ultimate-wp-plugin.js?' . $script_url . '&ref=" + encodeURIComponent(window.location.href);var e=document.getElementById("gh-cnbu-plugin");e.parentNode.insertBefore(t,e)}();
	</script>';
}

function gh_cnbu_add_wordpress_color_picker($hook) {
	wp_enqueue_style('wp-color-picker');
	$custom_js_url = plugins_url('gethuman-call-now-button-ultimate.js', __FILE__);

	wp_enqueue_script(
		'call-now-button-ultimate-script-handle',
		$custom_js_url,
		array('jquery', 'wp-color-picker'),
		GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_VERSION,
		true
	);
}

function gh_cnbu_initialize_call_now_button_ultimate_settings_page() {
	register_setting(
		$option_group = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_OPTIONS_GROUP,
		$option_name = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_OPTIONS,
		$args = array(
			'sanitize_callback' => 'gh_cnbu_sanitize_call_now_button_settings',
		)
	);

	gh_cnbu_add_status_settings();
	gh_cnbu_add_contact_information_settings();
	gh_cnbu_add_hours_of_operation_settings();
	gh_cnbu_add_button_appearance_settings();
}

function gh_cnbu_sanitize_call_now_button_settings($input) {
	$persisted_settings = gh_cnbu_get_call_now_button_option_object();

	$input[GH_CNUB_PHONE_NUMBER_OPTION_NAME] = gh_cnbu_validate_phone_number($persisted_settings[GH_CNUB_PHONE_NUMBER_OPTION_NAME], $input[GH_CNUB_PHONE_NUMBER_OPTION_NAME]);
	$input[GH_CNUB_EMAIL_ADDRESS_OPTION_NAME] = gh_cnbu_validate_email_address($persisted_settings[GH_CNUB_EMAIL_ADDRESS_OPTION_NAME], $input[GH_CNUB_EMAIL_ADDRESS_OPTION_NAME]);

	if ($input[GH_CNUB_PHONE_NUMBER_OPTION_NAME] == '' || $input[GH_CNUB_EMAIL_ADDRESS_OPTION_NAME] == '') {
		add_settings_error(
			$setting = GH_CNUB_STATUS_SETTINGS_ERROR,
			$code = 'status_settings',
			$message = 'You need to enter a phone number and email address before enabling Call Now Button Ultimate.',
			$type = 'error'
		);

		$input[GH_CNUB_IS_ENABLED_OPTION_NAME] = 'false';
	}

	$hours_of_operation_lower_bound = $input[GH_CNUB_HOURS_OF_OPERATION_LOWER_BOUND_OPTION_NAME];
	$hours_of_operation_upper_bound = $input[GH_CNUB_HOURS_OF_OPERATION_UPPER_BOUND_OPTION_NAME];
	$hours_of_operation_time_zone = $input[GH_CNUB_HOURS_OF_OPERATION_TIME_ZONE_OPTION_NAME];

	$lower_bound_time_with_timezone = strtotime($hours_of_operation_lower_bound . ' ' . $hours_of_operation_time_zone);
	$upper_bound_time_with_timezone = strtotime($hours_of_operation_upper_bound . ' ' . $hours_of_operation_time_zone);

	if ($upper_bound_time_with_timezone < $lower_bound_time_with_timezone) {
		add_settings_error(
			$setting = GH_CNUB_HOURS_OF_OPERATION_SETTINGS_ERROR,
			$code = 'hours-of-operation',
			$message = 'Please select a time range with a "From" time that comes before a "To" time.',
			$type = 'error'
		);

		$input[GH_CNUB_HOURS_OF_OPERATION_LOWER_BOUND_OPTION_NAME] = $persisted_settings[GH_CNUB_HOURS_OF_OPERATION_LOWER_BOUND_OPTION_NAME];
		$input[GH_CNUB_HOURS_OF_OPERATION_UPPER_BOUND_OPTION_NAME] = $persisted_settings[GH_CNUB_HOURS_OF_OPERATION_UPPER_BOUND_OPTION_NAME];
		$input[GH_CNUB_HOURS_OF_OPERATION_TIME_ZONE_OPTION_NAME] = $persisted_settings[GH_CNUB_HOURS_OF_OPERATION_TIME_ZONE_OPTION_NAME];
	}

	$input[GH_CNUB_OPEN_BUTTON_LABEL_TEXT_OPTION_NAME] = gh_cnbu_validate_button_label_text(
		$existing_label_text = $persisted_settings[GH_CNUB_OPEN_BUTTON_LABEL_TEXT_OPTION_NAME],
		$new_label_text = $input[GH_CNUB_OPEN_BUTTON_LABEL_TEXT_OPTION_NAME]
	);

	$input[GH_CNUB_CLOSED_BUTTON_LABEL_TEXT_OPTION_NAME] = gh_cnbu_validate_button_label_text(
		$existing_label_text = $persisted_settings[GH_CNUB_CLOSED_BUTTON_LABEL_TEXT_OPTION_NAME],
		$new_label_text = $input[GH_CNUB_CLOSED_BUTTON_LABEL_TEXT_OPTION_NAME]
	);

	$input[GH_CNUB_BUTTON_LABEL_TEXT_COLOR_OPTION_NAME] = gh_cnbu_validate_call_now_button_ultimate_color(
		$existing_color = $persisted_settings[GH_CNUB_BUTTON_LABEL_TEXT_COLOR_OPTION_NAME],
		$new_color = $input[GH_CNUB_BUTTON_LABEL_TEXT_COLOR_OPTION_NAME]
	);

	$input[GH_CNUB_BUTTON_BACKGROUND_COLOR_OPTION_NAME] = gh_cnbu_validate_call_now_button_ultimate_color(
		$existing_color = $persisted_settings[GH_CNUB_BUTTON_BACKGROUND_COLOR_OPTION_NAME],
		$new_color = $input[GH_CNUB_BUTTON_BACKGROUND_COLOR_OPTION_NAME]
	);

	return $input;
}

function gh_cnbu_add_status_settings() {
	add_settings_section(
		$id = GH_CNUB_SETTINGS_SECTION_ID,
		$title = 'Call Now Button Ultimate Settings',
		$callback = 'gh_cnbu_render_overview_text',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE
	);

	add_settings_field(
		$id = 'call_now_button_ultimate_enabled_setting',
		$title = 'Status',
		$callback = 'gh_cnbu_render_toggle_status_fields',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_SETTINGS_SECTION_ID
	);
}

function gh_cnbu_add_contact_information_settings() {
	add_settings_section(
		$id = GH_CNUB_CONTACT_INFORMATION_SECTION_ID,
		$title = 'Contact Information',
		$callback = 'gh_cnbu_render_contact_information_overview',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE
	);

	add_settings_field(
		$id = 'call_now_button_ultimate_phone_number_setting',
		$title = 'Phone Number',
		$callback = 'gh_cnbu_render_phone_number_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_CONTACT_INFORMATION_SECTION_ID
	);

	add_settings_field(
		$id = 'call_now_button_ultimate_email_address_setting',
		$title = 'Email Address',
		$callback = 'gh_cnbu_render_email_address_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_CONTACT_INFORMATION_SECTION_ID
	);
}

function gh_cnbu_add_hours_of_operation_settings() {
	add_settings_section(
		$id = GH_CNUB_HOURS_OF_OPERATION_SECTION_ID,
		$title = 'Hours of Operation',
		$callback = 'gh_cnbu_render_hours_of_operation_overview',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE
	);

	add_settings_field(
		$id = 'hours_of_operation_lower_bound_setting',
		$title = 'From:',
		$callback = 'gh_cnbu_render_hours_of_operation_lower_bound_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_HOURS_OF_OPERATION_SECTION_ID
	);

	add_settings_field(
		$id = 'hours_of_operation_upper_bound_setting',
		$title = 'To: ',
		$callback = 'gh_cnbu_render_hours_of_operation_upper_bound_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_HOURS_OF_OPERATION_SECTION_ID
	);

	add_settings_field(
		$id = 'hours_of_operation_time_zone_setting',
		$title = 'Timezone: ',
		$callback = 'gh_cnbu_render_hours_of_operation_time_zone_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_HOURS_OF_OPERATION_SECTION_ID
	);

}

function gh_cnbu_add_button_appearance_settings() {
	add_settings_section(
		$id = GH_CNUB_APPEARANCE_SECTION_ID,
		$title = 'Call Now Button Appearance',
		$callback = 'gh_cnbu_render_button_appearance_section',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE
	);

	add_settings_field(
		$id = 'open_button_label_text_setting',
		$title = '"Open" Button Label Text:',
		$callback = 'gh_cnbu_render_open_button_label_text_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_APPEARANCE_SECTION_ID
	);

	add_settings_field(
		$id = 'closed_button_label_text_setting',
		$title = '"Closed" Button Label Text:',
		$callback = 'gh_cnbu_render_closed_button_label_text_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_APPEARANCE_SECTION_ID
	);

	add_settings_field(
		$id = 'button_text_color_setting',
		$title = 'Button Label Text Color: ',
		$callback = 'gh_cnbu_render_button_label_text_color_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_APPEARANCE_SECTION_ID
	);

	add_settings_field(
		$id = 'button_background_color_setting',
		$title = 'Button Background Color: ',
		$callback = 'gh_cnbu_render_button_background_color_field',
		$page = GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE,
		$section = GH_CNUB_APPEARANCE_SECTION_ID
	);
}

function gh_cnbu_render_overview_text() {
	echo 'Manage your Call Now Button Ultimate status, appearance, contact information, and hours of operation.';
	settings_errors($setting = GH_CNUB_STATUS_SETTINGS_ERROR);
}

function gh_cnbu_render_contact_information_overview() {
	echo 'Enter a phone number that you would like visitors to reach you by during your normal hours of operation and an email address they can reach you at when you are not available to be reached by phone.';
	settings_errors($setting = GH_CNUB_CONTACT_INFORMATION_SETTINGS_ERROR);
}

function gh_cnbu_render_toggle_status_fields() {
	$is_enabled = gh_cnbu_get_call_now_button_option(GH_CNUB_IS_ENABLED_OPTION_NAME);
	?>
	<div>When enabled, your visitors will see an option to call or email you based on your hours of operations. When disabled, your visitors will not see an option to call or email you.</div><br>
	<div>
		<input type="radio" name="<?=gh_cnbu_build_option_name(GH_CNUB_IS_ENABLED_OPTION_NAME)?>" value="true" <?php if ($is_enabled === 'true') {echo 'checked';}?>> Enabled<br>
		<input type="radio" name="<?=gh_cnbu_build_option_name(GH_CNUB_IS_ENABLED_OPTION_NAME)?>" value="false" <?php if ($is_enabled === 'false') {echo 'checked';}?>> Disabled<br><br>
	</div>
<?php }

function gh_cnbu_render_phone_number_field() {
	$phone_number = gh_cnbu_get_call_now_button_option(GH_CNUB_PHONE_NUMBER_OPTION_NAME);
	echo '<input type="text" name="' . gh_cnbu_build_option_name(GH_CNUB_PHONE_NUMBER_OPTION_NAME) . '" value="' . $phone_number . '" placeholder="555-555-5555">';
}

function gh_cnbu_render_email_address_field() {
	$email_address = gh_cnbu_get_call_now_button_option(GH_CNUB_EMAIL_ADDRESS_OPTION_NAME);
	echo '<input type="email" name="' . gh_cnbu_build_option_name(GH_CNUB_EMAIL_ADDRESS_OPTION_NAME) . '" value="' . $email_address . '" placeholder="john@smith.com">';
}

function gh_cnbu_render_hours_of_operation_overview() {
	echo "<p>If one of your visitors is on your website during your hours of operation, we'll display an option to call you. If they visit during an hour you are not open, we'll show them an email address they can use to get in touch with you.</p>";
	settings_errors($setting = GH_CNUB_HOURS_OF_OPERATION_SETTINGS_ERROR);
}

function gh_cnbu_render_button_appearance_section() {
	echo "<p>You can customize your call now button by choosing the button label text and color as well as the button background color.</p>";
	settings_errors($setting = GH_CNUB_BUTTON_APPEARANCE_SETTINGS_ERROR);
}

function gh_cnbu_render_open_button_label_text_field() {
	$button_label_text = gh_cnbu_get_call_now_button_option(GH_CNUB_OPEN_BUTTON_LABEL_TEXT_OPTION_NAME);
	echo '<input type="text" name="' . gh_cnbu_build_option_name(GH_CNUB_OPEN_BUTTON_LABEL_TEXT_OPTION_NAME) . '" value="' . $button_label_text . '"> (this text will link to your phone number)';
}

function gh_cnbu_render_closed_button_label_text_field() {
	$button_label_text = gh_cnbu_get_call_now_button_option(GH_CNUB_CLOSED_BUTTON_LABEL_TEXT_OPTION_NAME);
	echo '<input type="text" name="' . gh_cnbu_build_option_name(GH_CNUB_CLOSED_BUTTON_LABEL_TEXT_OPTION_NAME) . '" value="' . $button_label_text . '"> (this text will link to your email address)';
}

function gh_cnbu_render_button_label_text_color_field() {
	$button_label_text_color = gh_cnbu_get_call_now_button_option(GH_CNUB_BUTTON_LABEL_TEXT_COLOR_OPTION_NAME);
	echo '<input type="text" name="' . gh_cnbu_build_option_name(GH_CNUB_BUTTON_LABEL_TEXT_COLOR_OPTION_NAME) . '" value="' . $button_label_text_color . '" class="chooseColor">';
}

function gh_cnbu_render_button_background_color_field() {
	$button_background_color = gh_cnbu_get_call_now_button_option(GH_CNUB_BUTTON_BACKGROUND_COLOR_OPTION_NAME);
	echo '<input type="text" name="' . gh_cnbu_build_option_name(GH_CNUB_BUTTON_BACKGROUND_COLOR_OPTION_NAME) . '" value="' . $button_background_color . '" class="chooseColor">';
}

function gh_cnbu_get_potential_hours_of_operation_times($selected = '09:00', $interval = '+30 minutes') {

	$output = '';

	$current = strtotime('00:00');
	$end = strtotime('23:59');

	while ($current <= $end) {
		$time = date('H:i', $current);
		$sel = ($time == $selected) ? ' selected' : '';

		$output .= "<option value=\"{$time}\"{$sel}>" . date('h:i A', $current) . '</option>';
		$current = strtotime($interval, $current);
	}

	return $output;
}

function gh_cnbu_render_hours_of_operation_lower_bound_field() {
	$lower_bound = gh_cnbu_get_call_now_button_option(GH_CNUB_HOURS_OF_OPERATION_LOWER_BOUND_OPTION_NAME);
	echo '<select name="' . gh_cnbu_build_option_name(GH_CNUB_HOURS_OF_OPERATION_LOWER_BOUND_OPTION_NAME) . '">' . gh_cnbu_get_potential_hours_of_operation_times($selected = $lower_bound) . '</select>';
}

function gh_cnbu_render_hours_of_operation_upper_bound_field() {
	$upper_bound = gh_cnbu_get_call_now_button_option(GH_CNUB_HOURS_OF_OPERATION_UPPER_BOUND_OPTION_NAME);
	echo '<select name="' . gh_cnbu_build_option_name(GH_CNUB_HOURS_OF_OPERATION_UPPER_BOUND_OPTION_NAME) . '">' . gh_cnbu_get_potential_hours_of_operation_times($selected = $upper_bound) . '</select>';
}

function gh_cnbu_render_hours_of_operation_time_zone_field() {
	$time_zone = gh_cnbu_get_call_now_button_option(GH_CNUB_HOURS_OF_OPERATION_TIME_ZONE_OPTION_NAME);
	$timezone_identifiers = DateTimeZone::listIdentifiers();

	echo '<select name="' . gh_cnbu_build_option_name(GH_CNUB_HOURS_OF_OPERATION_TIME_ZONE_OPTION_NAME) . '">';

	for ($index = 0; $index < count($timezone_identifiers); $index++) {
		$time_zone_value = $timezone_identifiers[$index];
		$is_selected = $time_zone == $time_zone_value;

		echo '<option value="' . $time_zone_value . '"';if ($is_selected) {echo 'selected';}
		echo '>' . $time_zone_value . '</option>';
	}

	echo '</select>';
}

function gh_cnbu_render_call_now_button_ultimate_settings_page() {
	echo '<div><form action="options.php" method="POST">';
	settings_fields(GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_OPTIONS_GROUP);
	do_settings_sections(GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_SETTINGS_PAGE);
	submit_button();
	echo '</form></div>';
}

function gh_cnbu_validate_button_label_text($existing_label_text, $new_label_text) {
	if ($new_label_text == '') {
		add_settings_error(
			$setting = GH_CNUB_BUTTON_APPEARANCE_SETTINGS_ERROR,
			$code = 'button-appearance',
			$message = 'Please enter a button label text.',
			$type = 'error'
		);
		return $existing_label_text;
	}

	return sanitize_text_field($new_label_text);
}

function gh_cnbu_validate_email_address($existing_email, $new_email) {
	if (!is_email($new_email)) {
		add_settings_error(
			$setting = GH_CNUB_CONTACT_INFORMATION_SETTINGS_ERROR,
			$code = 'contact-information',
			$message = 'Please enter a valid email address.',
			$type = 'error'
		);
		return $existing_email;
	}

	return sanitize_email($new_email);
}

function gh_cnbu_validate_phone_number($existing_phone, $new_phone) {
	if ($new_phone == '') {
		add_settings_error(
			$setting = GH_CNUB_CONTACT_INFORMATION_SETTINGS_ERROR,
			$code = 'contact-information',
			$message = 'Please enter a valid phone number.',
			$type = 'error'
		);
		return $existing_phone;
	}

	return sanitize_text_field($new_phone);
}

function gh_cnbu_validate_call_now_button_ultimate_color($existing_color, $new_color) {
	if (!preg_match('/^#[a-f0-9]{6}$/i', $new_color)) {
		add_settings_error(
			$setting = GH_CNUB_BUTTON_APPEARANCE_SETTINGS_ERROR,
			$code = 'color-settings',
			$message = 'Please enter valid text and background colors.',
			$type = 'error'
		);
		return $existing_color;
	}

	return sanitize_text_field($new_color);
}

function gh_cnbu_get_call_now_button_option($option_name) {
	$option_value = gh_cnbu_get_call_now_button_option_object();

	return $option_value[$option_name];
}

function gh_cnbu_get_call_now_button_option_object() {
	return get_option(GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_OPTIONS, array(
		GH_CNUB_IS_ENABLED_OPTION_NAME => 'false',
		GH_CNUB_PHONE_NUMBER_OPTION_NAME => '',
		GH_CNUB_EMAIL_ADDRESS_OPTION_NAME => '',
		GH_CNUB_HOURS_OF_OPERATION_LOWER_BOUND_OPTION_NAME => '09:00',
		GH_CNUB_HOURS_OF_OPERATION_UPPER_BOUND_OPTION_NAME => '17:00',
		GH_CNUB_HOURS_OF_OPERATION_TIME_ZONE_OPTION_NAME => 'America/New_York',
		GH_CNUB_OPEN_BUTTON_LABEL_TEXT_OPTION_NAME => 'Call Us',
		GH_CNUB_CLOSED_BUTTON_LABEL_TEXT_OPTION_NAME => 'Email Us',
		GH_CNUB_BUTTON_LABEL_TEXT_COLOR_OPTION_NAME => '#FFFFFF',
		GH_CNUB_BUTTON_BACKGROUND_COLOR_OPTION_NAME => '#36d278',
	));
}

function gh_cnbu_build_option_name($option_name) {
	return GH_CNBU_CALL_NOW_BUTTON_ULTIMATE_OPTIONS . '[' . $option_name . ']';
}

?>
