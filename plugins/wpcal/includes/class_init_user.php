<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_User_Init {

	public static function init() {
		if (is_admin()) {
			return;
		}

		add_action('wp_head', 'WPCal_Init::set_js_var_dist_url', -1000);
		add_action('init', 'wpcal_listen_and_may_redirect', -1000);
		if (get_option('wpcal_setting_asset_preloading_is_enabled', '0')) {
			add_action('wp_head', 'WPCal_User_Init::optimize_assets_loading');
		}

		self::include_files();
		self::init_shortcode_hook();
		//self::enqueue_styles_and_scripts(); //commented for dynamic loading.
	}

	public static function ajax_only_init() {
		//to make ajax work as ajax file endpoint inside wp-admin i.e wp-admin/admin-ajax.php
		self::include_files();
		self::init_ajax();
	}

	protected static function include_files() {

		//libraries
		include_once WPCAL_PATH . '/lib/Valitron/Validator.php';

		include_once WPCAL_PATH . '/includes/class_general_settings.php';
		include_once WPCAL_PATH . '/includes/class_admin_settings.php';

		//functions
		include_once WPCAL_PATH . '/includes/common_func.php';
		include_once WPCAL_PATH . '/includes/app_func.php';

		//classes
		include_once WPCAL_PATH . '/includes/class_admins.php';
		include_once WPCAL_PATH . '/includes/class_admin_profile_settings.php';
		include_once WPCAL_PATH . '/includes/class_license_auth.php';
		include_once WPCAL_PATH . '/includes/class_date_time_helper.php';
		include_once WPCAL_PATH . '/includes/class_service.php';
		include_once WPCAL_PATH . '/includes/class_availability_date.php';
		include_once WPCAL_PATH . '/includes/class_service_availability_details.php';
		include_once WPCAL_PATH . '/includes/class_service_availability_slots.php';
		include_once WPCAL_PATH . '/includes/class_booking.php';
		include_once WPCAL_PATH . '/includes/class_bookings_query.php';

		include_once WPCAL_PATH . '/includes/tp_calendars/class_tp_calendars_add_event.php';
		include_once WPCAL_PATH . '/includes/tp/class_tp_resource.php';

		include_once WPCAL_PATH . '/includes/class_background_tasks.php';
		include_once WPCAL_PATH . '/includes/class_cron.php';
		include_once WPCAL_PATH . '/includes/class_mail.php';

		include_once WPCAL_PATH . '/includes/class_manage_notices.php'; //some notices need to be displayed to non WPCal admin, but can access admin of wp-admin for them it is include here
		include_once WPCAL_PATH . '/includes/class_notice.php';

		include_once WPCAL_PATH . '/includes/class_tp_periodic_fetch_token.php';
	}

	// private static function enqueue_styles_and_scripts(){
	// 	add_action('wp_enqueue_scripts', 'WPCal_User_Init::enqueue_styles', 10);
	// 	add_action('wp_enqueue_scripts', 'WPCal_User_Init::enqueue_scripts', 10);
	// }

	public static function enqueue_styles() {
		if (WPCal_Init::is_dev_env()) {
			//wp_enqueue_style( 'wpcal_user_css', WPCAL_PLUGIN_DIST_URL . 'css/user.css', [], WPCAL_VERSION, false );
		} else {
			wp_enqueue_style('wpcal_common_chunk_vendor_css', WPCAL_PLUGIN_DIST_URL . 'css/chunk-vendors.css', [], WPCAL_VERSION, false);
			wp_enqueue_style('wpcal_common_chunk_css', WPCAL_PLUGIN_DIST_URL . 'css/chunk-common.css', [], WPCAL_VERSION, false);
			wp_enqueue_style('wpcal_user_css', WPCAL_PLUGIN_DIST_URL . 'css/user.css', [], WPCAL_VERSION, false);
		}
	}

	public static function enqueue_scripts() {

		wp_enqueue_script('wpcal_user_chunk', WPCAL_PLUGIN_DIST_URL . 'js/chunk-vendors.min.js', ['wpcal_common_path'], WPCAL_VERSION, true);

		wp_enqueue_script('wpcal_user_chunk_common', WPCAL_PLUGIN_DIST_URL . 'js/chunk-common.min.js', ['wpcal_common_path', 'wp-i18n'], WPCAL_VERSION, true);

		$lang_path = defined('WPCAL_USE_PLUGIN_LANG_PATH') && WPCAL_USE_PLUGIN_LANG_PATH ? WPCAL_PATH . '/languages' : null;
		wp_set_script_translations('wpcal_user_chunk_common', 'wpcal', $lang_path);

		wp_enqueue_script('wpcal_user_app', WPCAL_PLUGIN_DIST_URL . 'js/user.min.js', ['wpcal_common_path', 'wpcal_user_chunk', 'wpcal_user_chunk_common', 'wp-i18n'], WPCAL_VERSION, true);

		wp_localize_script('wpcal_user_app', 'wpcal_ajax', ['ajax_url' => admin_url('admin-ajax.php'), 'admin_url' => admin_url(), 'is_debug' => WPCAL_DEBUG]);

		wp_set_script_translations('wpcal_user_app', 'wpcal', $lang_path);
	}

	private static function init_shortcode_hook() {
		add_shortcode('wpcal', 'wpcal_service_booking_shortcode_cb');
	}

	public static function init_ajax() {
		add_action('wp_ajax_nopriv_wpcal_process_user_ajax_request', 'WPCal_User_Init::process_ajax_request');
		add_action('wp_ajax_wpcal_process_user_ajax_request', 'WPCal_User_Init::process_ajax_request');
	}

	public static function optimize_assets_loading() {
		if (!defined('WPCAL_ENV') || WPCAL_ENV !== 'PROD') {
			return;
		}

		$load_type = 'prefetch';
		if (self::is_current_page_has_wpcal_shortcode()) {
			$load_type = 'preload';
		}

		$url_tail = '?ver=' . WPCAL_VERSION;

		$assets = [
			[
				'url' => WPCAL_PLUGIN_DIST_URL . 'css/chunk-vendors.css' . $url_tail,
				'as' => 'style',
			],
			[
				'url' => WPCAL_PLUGIN_DIST_URL . 'css/chunk-common.css' . $url_tail,
				'as' => 'style',
			],
			[
				'url' => WPCAL_PLUGIN_DIST_URL . 'css/user.css' . $url_tail,
				'as' => 'style',
			],
			[
				'url' => WPCAL_PLUGIN_DIST_URL . 'js/chunk-vendors.min.js' . $url_tail,
				'as' => 'script',
			],
			[
				'url' => WPCAL_PLUGIN_DIST_URL . 'js/chunk-common.min.js' . $url_tail,
				'as' => 'script',
			],
			[
				'url' => WPCAL_PLUGIN_DIST_URL . 'js/user.min.js' . $url_tail,
				'as' => 'script',
			],
			[
				'url' => WPCAL_PLUGIN_DIST_URL . 'fonts/Rubik-Medium.woff2',
				'as' => 'font',
				'attrs' => ['crossorigin'],
			],
			[
				'url' => WPCAL_PLUGIN_DIST_URL . 'fonts/Rubik-Regular.woff2',
				'as' => 'font',
				'attrs' => ['crossorigin'],
			],
		];

		$eol = "\n";
		$head = '';
		foreach ($assets as $asset) {
			$tag = '';
			$tag .= '<link href="' . $asset['url'] . '" rel="' . $load_type . '" as="' . $asset['as'] . '"';
			$tag .= !empty($asset['attrs']) ? ' ' . implode(' ', $asset['attrs']) : '';
			$tag .= '>' . $eol;
			$head .= $tag;
		}
		echo $head;
	}

	public static function is_current_page_has_wpcal_shortcode() {
		global $posts;
		if (empty($posts)) {
			return false;
		}

		foreach ($posts as $_post) {
			if (has_shortcode($_post->post_content, 'wpcal')) {
				return true;
			}
		}
		return false;
	}

	public static function process_ajax_request() {
		$start_time = microtime(1);
		$response = [];
		if (!isset($_POST['wpcal_request']) || !is_array($_POST['wpcal_request'])) {
			echo wpcal_prepare_response($response);
			exit();
		}

		$wpcal_request_post = stripslashes_deep($_POST['wpcal_request']);

		$wpcal_request_result = [];
		foreach ($wpcal_request_post as $action => $action_request_data) {
			try {
				if (WPCal_Init::has_common_request_processor($action)) {
					$wpcal_request_result[$action] = WPCal_Init::process_single_action($action, $action_request_data, 'user_end');
				} else {
					$wpcal_request_result[$action] = self::process_single_action($action, $action_request_data);
				}
			} catch (WPCal_Exception $e) {
				$single_action_result = wpcal_prepare_single_action_exception_result($e);
				$wpcal_request_result[$action] = $single_action_result;
			} catch (Exception $e) {
				$single_action_result = [
					'status' => 'error',
					'error' => 'unknow_error',
					'error_msg' => $e->getMessage(),
					'error_data' => [(string) $e],
				];
				$wpcal_request_result[$action] = $single_action_result;
			}
		}

		if (!empty($wpcal_request_result)) {
			$junk = ob_get_clean();
			$wpcal_request_result['junk'] = $junk;
			wpcal_add_nerd_stats_to_request_result($wpcal_request_result, $start_time);
			echo wpcal_prepare_response($wpcal_request_result);
			exit();
		}
	}

	private static function process_single_action($action, $request_data) {
		$response = [];

		if ($action === 'view_booking') {

			$booking_obj = wpcal_get_booking_by_unique_link($request_data['unique_link']); //throws exception on failure

			$result = $booking_obj->get_data_for_user_client();

			if (!empty($result)) {

				$service_obj = wpcal_get_service($result['service_id']);
				$service_data = $service_obj->get_data_for_user_client();

				$response['status'] = 'success';
				$response['booking_data'] = $result;
				$response['service_data'] = $service_data;
				$response['service_admin_data'] = wpcal_get_admin_details($booking_obj->get_admin_user_id());
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'cancel_booking') {

			$booking_unique_link = sanitize_text_field($request_data['booking_unique_link']);
			$cancel_reason = sanitize_textarea_field($request_data['cancel_reason']);

			$booking_obj = wpcal_get_booking_by_unique_link($booking_unique_link); //throws exception on failure

			$booking_id = $booking_obj->get_id();

			$result = wpcal_cancel_booking($booking_id, $cancel_reason);
			if ($result) {
				$response['status'] = 'success';
				$response['bookings_data'] = $result;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'initial_client_data') {

			$result = true; //dummy

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'update_enable_preloading') {

			$is_enable = $request_data == 1 ? 1 : 0;

			update_option('wpcal_setting_asset_preloading_is_enabled', $is_enable);

			$result = true; //dummy

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		}

		return $response;
	}

}
