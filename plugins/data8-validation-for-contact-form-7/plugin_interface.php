<?php
// create custom plugin settings menu
add_action('admin_menu', 'd8cf7_create_menu');
add_action('admin_init', 'register_d8cf7settings');
add_action('admin_notices', 'add_settings_errors');

function d8cf7_create_menu() {
	add_menu_page("Data8 Validation", "Data8 Validation", "administrator", "d8_value_setting-panel", "d8cf7_settings_page", null, 66);
}

function register_d8cf7settings() {
	register_setting('d8cf7-settings-group', 'd8cf7_password');
	register_setting('d8cf7-autosettings-group', 'd8cf7_ajax_key');
	register_setting('d8cf7-autosettings-group', 'd8cf7_client_api_key');
	register_setting('d8cf7-autosettings-group', 'd8cf7_email_validation_level');
	register_setting('d8cf7-autosettings-group', 'd8cf7_telephone_validation');
	register_setting('d8cf7-autosettings-group', 'd8cf7_telephone_default_country');
	register_setting('d8cf7-autosettings-group', 'd8cf7_telephone_required_country');
	register_setting('d8cf7-autosettings-group', 'd8cf7_telephone_allowed_prefixes');
	register_setting('d8cf7-autosettings-group', 'd8cf7_telephone_barred_prefixes');
	register_setting('d8cf7-autosettings-gorup', 'd8cf7_telephone_advanced_options');
	register_setting('d8cf7-autosettings-group', 'd8cf7_predictiveaddress');
	register_setting('d8cf7-autosettings-group', 'd8cf7_predictiveaddress_options');
	register_setting('d8cf7-autosettings-group', 'd8cf7_salaciousName');
	register_setting('d8cf7-autosettings-group', 'd8cf7_bank_validation');
	register_setting('d8cf7-autosettings-group', 'd8cf7_error');
}

function d8cf7_settings_page() {
	include('includes/d8cf7_header.php');
	include('includes/d8cf7_settings.php');
	include('includes/d8cf7_instructions.php');
	include('includes/d8cf7_footer.php');	
}

function d8cf7_settings_link($links) { 
  $settings_link = "<a href=\"admin.php?page=d8_value_setting-panel\">Settings</a>"; 
  array_unshift($links, $settings_link); 
  return $links; 
}

// display default admin notice - e.g. Saved Successfully!
function add_settings_errors() {
    settings_errors();
}

//get end user id if default country code is auto
function getEndUserIp() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {			
		$ip = $_SERVER['HTTP_CLIENT_IP'];			
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {			
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];			
	} else {			
		$ip = $_SERVER['REMOTE_ADDR'];			
	}

	return $ip;
}

function ajaxAsyncRequest($serviceUrl, $data) {
	// Use curl to make request to Data8 API.
	// Initialize curl object
	$curl = curl_init($serviceUrl);

	$data_string = json_encode($data);

	// Set curl options
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	// Execute curl and return response
	$response = curl_exec($curl);

	if (curl_error($curl)) {
		$error_msg = curl_error($curl);
	}

	// Close request
	curl_close($curl);

	if (isset($error_msg)) {
		return $error_msg;
	}
	else
	{
		return json_decode($response, true);
	}	
}

function d8cf7_password_changing() {
	
	update_option('d8cf7_ajax_key', $_POST['d8cf7_ajax_key']);
	update_option('d8cf7_client_api_key', $_POST['d8cf7_client_api_key']);
	update_option('d8cf7_error', "");

	// adjust stored validation variables based on check box values
	update_option('d8cf7_email_validation_level', $_POST['d8cf7_email_validation_level']);
	update_option('d8cf7_telephone_validation', $_POST['d8cf7_telephone_validation']);
	update_option('d8cf7_telephone_default_country', $_POST['d8cf7_telephone_default_country']);
	update_option('d8cf7_telephone_required_country', $_POST['d8cf7_telephone_required_country']);
	update_option('d8cf7_telephone_allowed_prefixes', $_POST['d8cf7_telephone_allowed_prefixes']);
	update_option('d8cf7_telephone_barred_prefixes', $_POST['d8cf7_telephone_barred_prefixes']);
	update_option('d8cf7_predictiveaddress', $_POST['d8cf7_predictiveaddress']);
	update_option('d8cf7_predictiveaddress_options', $_POST['d8cf7_predictiveaddress_options']);
	update_option('d8cf7_telephone_advanced_options', $_POST['d8cf7_telephone_advanced_options']);
	update_option('d8cf7_salaciousName', $_POST['d8cf7_salaciousName']);
	update_option('d8cf7_bank_validation', $_POST['d8cf7_bank_validation']);

	return "";
}

add_filter("pre_update_option_d8cf7_password", 'd8cf7_password_changing', 10, 2);

require_once plugin_dir_path(__FILE__) . '/includes/d8cf7-validation.php';
require_once plugin_dir_path(__FILE__) . '/includes/d8wc-validation.php';
require_once plugin_dir_path(__FILE__) . '/includes/d8gf-validation.php';
require_once plugin_dir_path(__FILE__) . '/includes/d8wpf-validation.php';
require_once plugin_dir_path(__FILE__) . '/includes/d8ep-validation.php';