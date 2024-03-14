<?php

/** Global Variables */
$api_url = 'https://api.shipday.com/orders';
$debug_url = '';
$rest_key_install_url = 'https://api.shipday.com/woocommerce/install';
$shipday_debug_flag = false;

/** Functions */
function get_shipday_api_url(): string {
	global $api_url;
	return $api_url;
}

function get_shipday_debug_api_url(): string {
	global $debug_url;
	return $debug_url;
}

function get_shipday_rest_key_install_url() {
	global $rest_key_install_url;
	return $rest_key_install_url;
}

function shipday_handle_null($text) {
	return !isset($text) ? "" : $text;
}

function get_shipday_api_key() {
	$key = get_option('wc_settings_tab_shipday_api_key');
	return shipday_handle_null($key);
}

function get_shipday_sync_status() {
	$key = get_option('wc_settings_tab_shipday_sync');
	return shipday_handle_null($key) == 'yes';
}

function reset_shipday_sync_status() {
	update_option('wc_settings_tab_shipday_sync', 'no');
}

function get_shipday_order_manager() {
	$key = get_option('wc_settings_tab_shipday_order_manage');
	return shipday_handle_null($key);
}

function remove_emoji($string)
{
	// Match Enclosed Alphanumeric Supplement
	$regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
	$clear_string = preg_replace($regex_alphanumeric, '', $string);

	// Match Miscellaneous Symbols and Pictographs
	$regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
	$clear_string = preg_replace($regex_symbols, '', $clear_string);

	// Match Emoticons
	$regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
	$clear_string = preg_replace($regex_emoticons, '', $clear_string);

	// Match Transport And Map Symbols
	$regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
	$clear_string = preg_replace($regex_transport, '', $clear_string);

	// Match Supplemental Symbols and Pictographs
	$regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
	$clear_string = preg_replace($regex_supplemental, '', $clear_string);

	// Match Miscellaneous Symbols
	$regex_misc = '/[\x{2600}-\x{26FF}]/u';
	$clear_string = preg_replace($regex_misc, '', $clear_string);

	// Match Dingbats
	$regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
	$clear_string = preg_replace($regex_dingbats, '', $clear_string);

	return $clear_string;
}

?>
