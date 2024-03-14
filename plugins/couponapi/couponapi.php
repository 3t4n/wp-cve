<?php

/**
 * Plugin Name: CouponAPI
 * Plugin URI: https://couponapi.org
 * Description: Automatically import Coupons & Deals from popular Affiliate Networks into your WordPress Coupon Website.
 * Version: 6.1.1
 * Author: CouponAPI.org
 * Author URI: https://couponapi.org
 **/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

require plugin_dir_path(__FILE__) . 'activate.php';
require plugin_dir_path(__FILE__) . 'deactivate.php';
require plugin_dir_path(__FILE__) . 'views.php';
require plugin_dir_path(__FILE__) . 'save-api-config.php';
require plugin_dir_path(__FILE__) . 'save-import-config.php';
require plugin_dir_path(__FILE__) . 'save-brandlogos-config.php';
require plugin_dir_path(__FILE__) . 'brandlogos-resync.php';
require plugin_dir_path(__FILE__) . 'delete-offers.php';
require plugin_dir_path(__FILE__) . 'pull-feed.php';

function couponapi_submit_delete_offers()
{
	if (wp_verify_nonce($_POST['delete_offers_nonce'], 'couponapi')) {
		$message = couponapi_delete_offers();
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>' . __("Access Denied. Nonce could not be verified.", "couponapi") . '</p></div>';
	}
	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi');
	exit();
}

function couponapi_submit_sync_offers()
{
	if (wp_verify_nonce($_POST['sync_offers_nonce'], 'couponapi')) {


		global $wpdb;
		$wp_prefix = $wpdb->prefix;
		$server_name =  get_site_url();
		if(wp_next_scheduled('couponapi_process_batch_event')) {
			wp_clear_scheduled_hook('couponapi_process_batch_event');
		}
		$wpdb->query("DELETE FROM `{$wp_prefix}couponapi_upload`");
		
		if (strpos($server_name, "localhost") !== false or strpos($server_name, "127.0.0.1") !== false) {
			couponapi_delete_offers();
			$wpdb->query("REPLACE INTO " . $wp_prefix . "couponapi_config (name,value) VALUES ('last_extract','100')");
			// wp_schedule_single_event( time() , 'couponapi_pull_incremental_feed_event');
			$message = couponapi_pull_incremental_feed();
		} else {


			$config = $wpdb->get_row("SELECT (SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'API_KEY') API_KEY");

			$response = json_decode(file_get_contents("https://couponapi.org/api/getFullFeed/?API_KEY=" . $config->API_KEY . "&format=json&callback=" . get_site_url() . "/wp-json/feedcallback/v1/posts"));
			if ($response->result) {
				$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'success','Full feed requested')");
				$message = '<div class="notice notice-primary is-dismissible"><p>' . __("Your Feed Will Be Deliverd To You Within Few Minutes.", 'couponapi') . '</p></div>';
			} else {
				$message =  $response->error;
				$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'error','$message')");
				$message =  '<div class="notice notice-error is-dismissible"><p>' . $message . '.</p></div>';
			}
		}
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>' . __("Access Denied. Nonce could not be verified.", "couponapi") . '</p></div>';
	}
	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi');
	exit();
}
 
function couponapi_submit_pull_incremental_feed()
{
	if (wp_verify_nonce($_POST['pull_incremental_feed_nonce'], 'couponapi')) {
		$message = couponapi_pull_incremental_feed();
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>' . __("Access Denied. Nonce could not be verified.", "couponapi") . '</p></div>';
	}
	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi');
	exit();
}

function couponapi_file_upload()
{
	if (wp_verify_nonce($_POST['file_upload_nonce'], 'couponapi')) {
		if (!function_exists('wp_handle_upload')) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}
		$delimiter = ',';
		$file_processed = false;
		$uploadedfile = $_FILES['feed'];
		$upload_overrides = array('test_form' => false, 'mimes' => array('csv' => 'text/csv'));
		$movefile = wp_handle_upload($uploadedfile, $upload_overrides);
		if (!$movefile or isset($movefile['error'])) {
			$message = '<div class="notice notice-error is-dismissible"><p>' . __("Error during File Upload :", "couponapi") . $movefile['error'] . '</p></div>';
		} else {
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			$sql = "INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Uploading File')";
			$wpdb->query($sql);
			$feedFile = $movefile['file'];
			$wpdb->query('SET autocommit = 0;');
			$result = couponapi_save_csv_to_db($feedFile);
			if (!$result['error']) {
				$wpdb->query('COMMIT;');
				$wpdb->query('SET autocommit = 1;');
				$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Offer Feed saved to local database. Starting upload process') ");
				wp_schedule_single_event(time(), 'couponapi_process_batch_event'); // process next batch
				$message = '<div class="notice notice-info is-dismissible"><p>' . __("Upload process is running in background. Refresh Logs to see current status.", "couponapi") . '</p></div>';
			} else {
				$wpdb->query('ROLLBACK');
				$wpdb->query('SET autocommit = 1;');
				$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES
													(" . microtime(true) . ",'debug','" . esc_sql($result['error_msg']) . "'),
													(" . microtime(true) . ",'error','Error uploading feed to local database')");
				$message = '<div class="notice notice-error is-dismissible"><p>' . __("Error uploading feed to local database.", "couponapi") . '</p></div>';
			}
		}
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>' . __("Access Denied. Nonce could not be verified.", "couponapi") . '</p></div>';
	}
	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi-file-upload');
	exit();
}

function couponapi_download_logs()
{
	$message = '';
	if (wp_verify_nonce($_GET['log_nonce'], 'couponapi')) {
		global $wpdb;
		$wp_prefix = $wpdb->prefix;

		$gmt_offset = get_option('gmt_offset');
		$offset_sign = ($gmt_offset < 0) ? '-' : '+';
		$positive_offset = ($gmt_offset < 0) ? $gmt_offset * -1 : $gmt_offset;
		$hours = floor($positive_offset);
		$minutes = round(($positive_offset - $hours) * 60);
		$tz = $offset_sign . $hours . ':' . $minutes;

		$logs = $wpdb->get_results("SELECT
																			CONCAT(CONVERT_TZ(logtime,@@session.time_zone,'" . $tz . "'),' ','$tz') logtime,
																			msg_type,
																			message
																		FROM  " . $wp_prefix . "couponapi_logs
																		ORDER BY microtime");

		$filename = "couponapi_" . date("YmdHis") . ".log";
		$seperator = "\t";

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=" . $filename);
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies
		header("Content-Transfer-Encoding: UTF-8");

		$fp = fopen("php://output", "w");

		foreach ($logs as $log) {
			fputcsv($fp, array($log->logtime, $log->msg_type, $log->message), $seperator);
		}
		fclose($fp);
	} else {
		$message = '<div class="notice notice-error is-dismissible"><p>' . __("Access Denied. Nonce could not be verified.", "couponapi") . '</p></div>';
	}
	setcookie('message', $message);
	wp_redirect('admin.php?page=couponapi-logs');
	exit();
}

function couponapi_save_custom_template(){
	if (wp_verify_nonce($_POST['custom_template_nonce'], 'couponapi')){
		set_theme_mod('custom_coupon_template', $_POST['custom_coupon_template']);
	}
	wp_redirect('admin.php?page=couponapi-custom-template');
	exit();
}

function couponapi_get_config()
{

	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$result = array(
		'theme'		=> get_template(),
		'charset'  	=> $wpdb->charset,
		'autopilot' => 'Off',
		'curl'  	=> in_array('curl', get_loaded_extensions()),
		'allow_url_fopen' => ini_get('allow_url_fopen'),
		'cashback-plugin' => in_array('clipmydeals-cashback/clipmydeals-cashback.php', get_option('active_plugins')),
		'import_images' => 'Off',
		'use_grey_image' => 'on',
		'use_logos' => 'on',
		'import_locations' => 'Off',
		'cashback' => 'Off',
		'is_theme_supported' => couponapi_is_theme_supported(get_template())
	);
	if ($result['theme'] == 'clipmydeals') $result['location_taxonomy'] = get_theme_mod('location_taxonomy', false);

	$config = $wpdb->get_results("SELECT * FROM " . $wp_prefix . "couponapi_config");
	foreach ($config as $row)
		$result[$row->name] = $row->value;

	return $result;
}

function couponapi_get_troubleshootings()
{

	$configs = couponapi_get_config();

	$troubleshooting = array();

	// API Key
	if ($configs['autopilot'] == 'On') {
		$usage = json_decode(file_get_contents('https://couponapi.org/api/getUsage/?API_KEY=' . $configs['API_KEY']), true);
		if (!$usage['result']) {
			$troubleshooting[__('API Key', 'couponapi')] = array(
				"status" => "no",
				"message" => __("Invalid API Key or Account has Expired. Please check your API Key from <a target='_blank' href='https://couponapi.org/account/dashboard.php'>CouponAPI Dashboard</a>.", 'couponapi'),
			);
		} else {
			$troubleshooting[__('API Key', 'couponapi')] = array(
				"status" => "yes",
				"message" => __("You have an active subscription with CouponAPI", 'couponapi'),
			);
		}
	}

	// // Theme
	if (couponapi_is_theme_supported($configs['theme'])) {
		$troubleshooting[__('Theme', 'couponapi')] = array(
			"status" => "yes",
			"message" => sprintf(__("CouponAPI works perfectly with %s Theme", "couponapi"), ucfirst($configs['theme']))
		);
	} else {
		$troubleshooting[__('Theme', 'couponapi')] = array(
			"status" => "warning",
			"message" => __("It seems you are using a generic WordPress blogging theme instead of a niche Coupon Theme. CouponAPI will still import offers, however they will be available as simple \"WordPress Posts\". If you feel this is a mistake, and your theme natively supports Coupons, then please create a <a href='https://couponapi.org/help/index.php?a=add' >Ticket</a>. Our technical team will assess the feasibility of integrating with your theme.", "couponapi"),
		);
	}

	
	// WP-Cron
	if (empty($configs['last_cron'])) {
		$troubleshooting[__('WP-Cron', 'couponapi')] = array(
			"status" => "no",
			"message" => __("WP-Cron is possibly disabled on your server.", 'couponapi'),
		);
	} elseif (time() - $configs['last_cron'] > 600) {
		$troubleshooting[__('WP-Cron', 'couponapi')] = array(
			"status" => "warning",
			"message" => __("WP-Cron has not run since ", 'couponapi') . date('jS F Y, g:i a', intval($configs['last_cron']) + get_option('gmt_offset') * 60 * 60),
		);
	} else {
		$troubleshooting[__('WP-Cron', 'couponapi')] = array(
			"status" => "yes",
			"message" => __("WP-Cron is working fine. Last successful run was on ", 'couponapi') . date('jS F Y, g:i a', intval($configs['last_cron']) + get_option('gmt_offset') * 60 * 60),
		);
	}

	// CURL
	if ($configs['curl']) {
		$troubleshooting[__('cURL', 'couponapi')] = array(
			"status" => "yes",
			"message" => __("PHP CURL module is working", 'couponapi'),
		);
	} else {
		$troubleshooting[__('cURL', 'couponapi')] = array(
			"status" => "no",
			"message" => __("PHP CURL directive is not working. It is required to call external APIs. Please contact your hosting provider and get it enabled.", 'couponapi'),
		);
	}

	// Images
	if ($configs['import_images'] == 'On' and !couponapi_is_image_supported($configs['theme'], $configs['use_logos'])) {
		$troubleshooting[__('Images', 'couponapi')] = array(
			"status" => "no",
			"message" => ucfirst($configs['theme']) . __(" Theme does not support images hosted on third-party servers. Please add 'Store Logos' to stores/merchants in your theme to display on your offers.", 'couponapi'),
		);
	} elseif ($configs['import_images'] == 'On' and $configs['theme'] == 'clipmydeals') {
		$troubleshooting[__('Images', 'couponapi')] = array(
			"status" => "yes",
			"message" => __("If the source Affiliate Network has not added any image to an offer, CouponAPI will not be able to pass images in such case.<br/>For this, you must add Store Logos in WordPress > Coupons > Stores > Edit, so that the logo displays on offers where image is not available.", 'couponapi'),
		);
	}

	// Locations
	if (couponapi_is_location_supported(get_template()) and $configs['import_locations'] == 'On') {
		if ($configs['theme'] == 'clipmydeals' and !$configs['location_taxonomy']) {
			$troubleshooting[__('Locations', 'couponapi')] = array(
				"status" => "no",
				"message" => __("You have enabled 'Import Locations' in Import Settings, but Location Taxonomy is not activated on your theme", 'couponapi'),
			);
		} else {
			$troubleshooting[__('Locations', 'couponapi')] = array(
				"status" => "yes",
				"message" => __("You have enabled 'Import Locations' in Import Settings", 'couponapi'),
			);
		}
	} elseif (couponapi_is_location_supported(get_template())) {
		if ($configs['theme'] == 'clipmydeals' and !$configs['location_taxonomy']) {
			$troubleshooting[__('Locations', 'couponapi')] = array(
				"status" => "warning",
				"message" => __("Your Theme supports Location Taxonomy, but it is disabled by your settings", 'couponapi'),
			);
		} else {
			$troubleshooting[__('Locations', 'couponapi')] = array(
				"status" => "warning",
				"message" => __("Your Theme supports Location, but You have disabled 'Import Locations' in Import Settings", 'couponapi'),
			);
		}
	} else {
		$troubleshooting[__('Locations', 'couponapi')] = array(
			"status" => "warning",
			"message" => __("Your Theme does not supports Locations", 'couponapi'),
		);
	}

	// DB Character Set
	if ($configs['charset'] == 'utf8mb4') {
		$troubleshooting[__('Database', 'couponapi')] = array(
			"status" => "yes",
			"message" => sprintf(__("Your Database Character Set (%s) supports Non-English characters", 'couponapi'), $configs['charset']),
		);
	} else {
		$troubleshooting[__('Database', 'couponapi')] = array(
			"status" => "warning",
			"message" => sprintf(__("Your Database Character Set (%s) does not support Non-English characters.", 'couponapi'), $configs['charset']),
		);
	}

	// Cashback setting
	if($configs['cashback'] == 'On' and $configs['theme'] == 'clipmydeals' and $configs['cashback-plugin']){
		$troubleshooting[__('Cashback', 'couponapi')] = array(
			"status" => "yes",
			"message" => __("ClipMyDeals Cashback Plugin is installed on your website.", 'couponapi'),
		);
	} else if ($configs['cashback'] == 'On' and $configs['theme'] != 'clipmydeals') {
		$troubleshooting[__('Cashback', 'couponapi')] = array(
			"status" => "no",
			"message" => $configs['theme'] . __(" Theme does not support Cashback.", 'couponapi'),
		);
	} elseif ($configs['cashback'] == 'On' and !$configs['cashback-plugin']) {
		$troubleshooting[__('Cashback', 'couponapi')] = array(
			"status" => "no",
			"message" => __("You have enabled 'Cashback Mode' in Import Settings, but ClipMyDeals Cashback Plugin is not installed/activated on your website.", 'couponapi'),
		);
	} elseif ($configs['cashback'] != 'On' and $configs['cashback-plugin']) {
		$troubleshooting[__('Cashback', 'couponapi')] = array(
			"status" => "warning",
			"message" => __("ClipMyDeals Cashback Plugin is installed on your website, but you have not enabled 'Cashback' Mode' in Import Settings.", 'couponapi'),
		);
	}	
	

	// Allow url fopen
	if ($configs['allow_url_fopen']) {
		$troubleshooting[__('Allow URL fopen', 'couponapi')] = array(
			"status" => "yes",
			"message" => __("PHP 'allow_url_fopen' is enabled.", 'couponapi'),
		);
	} else {
		$troubleshooting[__('Allow URL fopen', 'couponapi')] = array(
			"status" => "warning",
			"message" => __("PHP 'allow_url_fopen' is disabled on your server.", 'couponapi'),
		);
	}

	return $troubleshooting;
}

function couponapi_register_api()
{
	register_rest_route('couponapi/v1', 'checkStatus', array(
		'methods'  => 'GET',
		'callback' => 'couponapi_server_checks',
		'permission_callback' => '__return_true',
		'args' => array(
			'API_KEY' => array(
				'required' => true
			),
			'debug_log' => array(
				'required' => false
			),
		)
	));
}

function couponapi_server_checks($data)
{

	global $wpdb;
	$response = couponapi_get_config();
	if ($data['API_KEY'] != $response['API_KEY']) {
		$response = array("API_KEY" => "Incorrect API Key");
	} else {
		$log_duration = esc_sql($data['duration']);
		$log_debug = (isset($data['debug_log']) and $data['debug_log'] == 'yes') ? "TRUE" : "`msg_type` != 'debug'";
		$response['logs'] = $wpdb->get_results("SELECT `logtime`, `message`, `msg_type` FROM `{$wpdb->prefix}couponapi_logs` WHERE logtime > NOW() - INTERVAL $log_duration AND $log_debug ORDER BY `microtime`");
	}
	return new WP_REST_Response(
		$response,
		200,
		array('Cache-Control' => 'no-cache, no-store, must-revalidate', 'Pragma' => 'no-cache', 'Expires' => '0', 'Content-Transfer-Encoding' => 'UTF-8')
	);
}


function couponapi_is_theme_supported($theme_name)
{
	$supported = array('clipmydeals', 'couponis', 'couponer', 'mts_coupon', 'clipper', 'couponxl', 'couponxxl', 'rehub', 'rehub-theme', 'wpcoupon', 'wp-coupon', 'wp-coupon-pro', 'CP', 'cp', 'CPq', 'couponhut','coupon-mart');
	return (in_array($theme_name, $supported) or substr($theme_name, 0, 2) === "CP");
}

function couponapi_is_location_supported($theme_name)
{
	$supported = array('clipmydeals', 'couponxl', 'couponxxl');
	return in_array($theme_name, $supported);
}

function couponapi_is_image_supported($theme_name, $use_logos = 'off')
{
	return $theme_name == 'clipmydeals' or (in_array($theme_name, array('couponxl', 'couponxxl', 'rehub', 'rehub-theme', 'couponhut', 'wpcoupon', 'wp-coupon', 'wp-coupon-pro')) and $use_logos == 'on');
}

function couponapi_str_like($haystack, $needle)
{
	if (strpos(strtolower($haystack), strtolower($needle)) !== false) {
		return true;
	} else {
		return false;
	}
}

function couponapi_admin_menu()
{
	//add_menu_page("Coupon API", "Coupon API", 7, "couponapi", "couponapi_display_main", "dashicons-rss",9);
	add_menu_page("Coupon API", 	"Coupon API", 	'manage_options', "couponapi", 	"couponapi_display_settings", "dashicons-rss", 9);
	add_submenu_page("couponapi", "Settings",	__("Settings", "couponapi"), 	'manage_options', "couponapi", "couponapi_display_settings");
	add_submenu_page("couponapi", "Coupon API BrandLogos Settings", "BrandLogos.org" , 'manage_options', "couponapi-brandlogos-settings", "couponapi_display_brandlogos_settings");
	add_submenu_page("couponapi", "Coupon API CSV Upload",	__("CSV Upload", "couponapi"), 	'manage_options', "couponapi-file-upload", "couponapi_display_file_upload");
	add_submenu_page("couponapi", "Coupon API Logs", 		__("Logs", "couponapi"), 		'manage_options', "couponapi-logs", 		"couponapi_display_logs");
	add_submenu_page("couponapi", "Coupon API Troubleshoot", __("Troubleshoot", "couponapi"), 'manage_options', "couponapi-troubleshoot", "couponapi_display_troubleshoot");
	if(!couponapi_is_theme_supported(couponapi_get_config()['theme'])){
		add_submenu_page("couponapi", "Coupon API Custom Template",__("Coupon Custom Template","couponapi"),'manage_options',"couponapi-custom-template","couponapi_custom_template");
	}
}

function couponapi_check_wpcron()
{
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	$wpdb->query("REPLACE INTO " . $wp_prefix . "couponapi_config (name,value) VALUES ('last_cron'," . microtime(true) . ")");
}



function couponapi_custom_wpcron_schedules($schedules)
{
	$schedules['every_five_minutes'] = array(
		'interval'  => 60 * 5,
		'display'   => __('Every 5 Minutes', 'couponapi')
	);
	return $schedules;
}

load_plugin_textdomain('couponapi', false, 'couponapi/languages/');


function pull_full_feed()
{
	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	$file_location =  "https://couponapi.org/api/download_feed.php";
	$file = $_GET['filename'];

	$sql = "SELECT * FROM " . $wp_prefix . "couponapi_config WHERE name = 'API_KEY'";
	$result = $wpdb->get_results($sql);
	$api_key = $result[0]->value;
	if ($api_key != $_GET['api_key']) {
		http_response_code(400);
		return http_response_code();
	} else {

		set_time_limit(0);
		
		//delete all offers
		couponapi_delete_offers();


		$sql = "INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Pulling Feed using Coupon API')";
		$wpdb->query($sql);

		$sql = "INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'debug','$file')";
		$wpdb->query($sql);

		$wpdb->query('SET autocommit = 0;');

		$result = couponapi_save_json_to_db($file_location . "?file=$file&api_key=$api_key");

		if ($result['totalCounter'] == 0) {
			// If the account is temporarily inactive, we do not get any offers in the file.
			// Not updating the last_extract time in such situations, prevents loss of data after re-activation.
			$wpdb->query('SET autocommit = 1;');
			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'success','No updates found in this extract')");
			return 'OK';
		} elseif (!$result['error']) {
			$wpdb->query("REPLACE INTO " . $wp_prefix . "couponapi_config (name,value) VALUES ('last_extract','" . time() . "') ");
			$wpdb->query('COMMIT;');
			$wpdb->query('SET autocommit = 1;');
			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Starting upload process. This may take several minutes...') ");
			wp_schedule_single_event(time(), 'couponapi_process_batch_event'); // process next batch
			return 'OK';
		} else {
			$wpdb->query('ROLLBACK');
			$wpdb->query('SET autocommit = 1;');
			$wpdb->query("INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES
											(" . microtime(true) . ",'debug','" . esc_sql($result['error_msg']) . "'),
											(" . microtime(true) . ",'error','Error uploading feed to local database')");
			return new WP_Error('db_error', __('Failed to upload feed to DB'), array('status' => 400));
		}
	}
}


add_action('rest_api_init', function () {
	register_rest_route("feedcallback/v1", "posts", [
		'callback' => 'pull_full_feed',
		'permission_callback' => '__return_true',
	]);
});


add_filter('cron_schedules', 'couponapi_custom_wpcron_schedules');

add_action('couponapi_check_wpcron_event', 'couponapi_check_wpcron');
add_action('admin_menu', 'couponapi_admin_menu');
add_action('admin_post_capi_save_api_config', 'couponapi_save_api_config');
add_action('admin_post_capi_save_import_config', 'couponapi_save_import_config');
add_action('admin_post_capi_save_brandlogos_config', 'couponapi_save_brandlogos_config');
add_action('admin_post_capi_brandlogos_resync', 'couponapi_brandlogos_resync');
add_action('admin_post_capi_sync_offers', 'couponapi_submit_sync_offers');
add_action('admin_post_capi_delete_offers', 'couponapi_submit_delete_offers');
add_action('admin_post_capi_pull_incremental_feed', 'couponapi_submit_pull_incremental_feed');
add_action('admin_post_capi_file_upload', 'couponapi_file_upload');
add_action('admin_post_capi_download_logs', 'couponapi_download_logs');
add_action('admin_post_capi_custom_template', 'couponapi_save_custom_template');
add_action('couponapi_pull_incremental_feed_event', 'couponapi_pull_incremental_feed');
add_action('couponapi_process_batch_event', 'couponapi_process_batch');
add_action('rest_api_init', 'couponapi_register_api');

register_activation_hook(__FILE__, 'couponapi_activate');
register_deactivation_hook(__FILE__, 'couponapi_deactivate');

// TODO: Remove this in later versions
add_action('plugins_loaded', 'couponapi_update_to_3_point_2_point_1');
add_action('plugins_loaded', 'couponapi_update_to_3_point_4_point_3');
add_action('plugins_loaded', 'couponapi_update_to_4_point_0_point_2');
add_action('plugins_loaded', 'couponapi_update_to_4_point_1');
add_action('plugins_loaded', 'couponapi_update_to_4_point_2');
add_action('plugins_loaded', 'couponapi_update_to_4_point_2_point_1');
add_action('plugins_loaded', 'couponapi_update_to_6_point_0_point_7');



// Schedule an action if it's not already scheduled
if (!wp_next_scheduled('couponapi_check_wpcron_event')) {
	couponapi_check_wpcron();
	wp_schedule_event(time(), 'every_five_minutes', 'couponapi_check_wpcron_event');
}

