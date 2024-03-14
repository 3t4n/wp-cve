<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Init {

	public static function init() { //dedicated for this class

		include_once WP_PLUGIN_DIR . '/' . basename(dirname(dirname(__FILE__))) . '/includes/constants.php';

		include_once WPCAL_PATH . '/includes/class_db_manage.php';

		register_activation_hook('wpcal/wpcal.php', 'WPCal_Init::on_plugin_activate');

		register_deactivation_hook('wpcal/wpcal.php', 'WPCal_Init::on_plugin_deactivate');

		add_action('init', 'WPCal_DB_Manage::update');

		add_action('init', function () {
			load_plugin_textdomain('wpcal');
		});

		add_action('after_setup_theme', function () {
			add_image_size('wpcal-admin-avatar', 75, 75, true);
		});

		self::tp_compatibility_on_init();

		if (is_admin()) {
			if (wp_doing_ajax() && isset($_POST['action']) && $_POST['action'] === 'wpcal_process_user_ajax_request') {
				include_once WPCAL_PATH . '/includes/class_init_user.php';
				WPCal_User_Init::ajax_only_init();
			} else {
				include_once WPCAL_PATH . '/includes/class_init_admin.php';
				WPCal_Admin_Init::init();
			}
		} else {
			include_once WPCAL_PATH . '/includes/class_init_user.php';
			WPCal_User_Init::init();
		}
	}

	public static function is_dev_env() {
		return defined('WPCAL_ENV') && WPCAL_ENV === 'DEV';
	}

	public static function on_plugin_activate() {
		//going for wordpress option because autoload will be optimized and it will not override if already exists
		add_option('wpcal_first_activation_redirect', true);

		WPCal_DB_Manage::on_plugin_activate();
		wpcal_may_add_sample_services_on_plugin_activation();
		wpcal_on_plugin_activation_user_if_not_wpcal_admin_add_notice();
	}

	public static function on_plugin_deactivate() {
		WPCal_Cron::on_plugin_deactivate();
	}

	public static function set_js_var_dist_url() {
		//implementation https://wordpress.stackexchange.com/a/311279/16388
		wp_register_script('wpcal_common_path', '');
		wp_enqueue_script('wpcal_common_path');
		wp_add_inline_script('wpcal_common_path', 'var __wpcal_dist_url = "' . WPCAL_PLUGIN_DIST_URL . '";');
/* 		?>
<script>
var __wpcal_dist_url = "<?php echo WPCAL_PLUGIN_DIST_URL; ?>";
</script>
<?php */
	}

	public static function tp_compatibility_on_init() {

		// plugin async-javascript starts here
		add_filter('option_aj_exclusions', 'WPCal_Init::tp_async_javascript_add_exclusions');
		add_filter('pre_update_option_aj_exclusions', 'WPCal_Init::tp_async_javascript_remove_exclusions_in_db');
		// plugin async-javascript ends here

		// Following rocket_exclude_js commented as longer required
		// add_filter('rocket_exclude_js', 'WPCal_Init::tp_wp_rocket_exclude_js', 10, 1);
	}

	private static function _tp_async_javascript_exclude_plugin_url() {
		$plugin_js_path_url = trailingslashit(WPCAL_PLUGIN_DIST_URL) . 'js';
		$site_url = trailingslashit(site_url());
		$plugin_relative_url = wpcal_may_remove_prefix_str($plugin_js_path_url, $site_url);
		return $plugin_relative_url;
	}

	private static function _tp_async_javascript_get_modify_list() {

		$plugin_relative_url = self::_tp_async_javascript_exclude_plugin_url();
		$suffix = '.min';
		if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
			$suffix = '';
		}
		$list = [
			$plugin_relative_url,
			'i18n' . $suffix . '.js', // translation
			'hooks' . $suffix . '.js', // i18n.js's dependency
			'wp-polyfill' . $suffix . '.js', // hooks.js's dependency
			'wp-polyfill-inert' . $suffix . '.js', // wp-polyfill.js's dependency
			'regenerator-runtime' . $suffix . '.js', // wp-polyfill.js's dependency
		];

		return $list;
	}

	public static function tp_async_javascript_add_exclusions($value) {

		$modify_list = self::_tp_async_javascript_get_modify_list();
		$value_array = empty($value) ? [] : explode(',', $value);
		foreach ($modify_list as $modify_entry) {
			if (!in_array($modify_entry, $value_array)) {
				$value_array[] = $modify_entry;
			}
		}

		$final_value = implode(',', $value_array);
		return $final_value;
	}

	public static function tp_async_javascript_remove_exclusions_in_db($value) {
		//basically lets not store the wpcal exclusions in DB, so we can update the exclusions setting later within php

		$value_array = empty($value) ? [] : explode(',', $value);
		$remove_values = self::_tp_async_javascript_get_modify_list();
		foreach ($remove_values as $remove_value) {
			$wpcal_index = array_search($remove_value, $value_array, true);
			if ($wpcal_index !== false) {
				unset($value_array[$wpcal_index]);
			}
		}

		$final_value = implode(',', $value_array);
		return $final_value;
	}

	public static function tp_wp_rocket_exclude_js($exclude_js) {
		// /wp-rocket/inc/Engine/Optimization/Minify/JS/AbstractJSOptimization.php get_excluded_files() already excluded i18n.min.js (wp-i18n) which is this plugin dependency.
		// hooks.min.js (wp-hooks) is dependency of i18n.min.js (wp-i18n) therefore the following is required.
		$exclude_js[] = '/wp-includes/js/dist/hooks(.min)?.js';

		// /wp-rocket/inc/Engine/Optimization/DeferJS/DeferJS.php get_excluded() defers both these files,so when "Load JavaScript deferred" is on, there is no issues, no need of above exlusion
		return $exclude_js;
	}

	public static function has_common_request_processor($action) {
		static $supported_processors = [
			'get_service_details_for_booking',
			'get_current_user_details_for_booking',
			'get_initial_service_availabile_slots',
			'get_booking_by_unique_link',
			'get_service_availabile_slots_by_month',
			'add_booking',
			'reschedule_booking',
			'save_user_tz',
			'get_user_tz',
			'get_general_settings_by_options',
			'run_background_task',
			'initial_common_data',
			'run_booking_background_tasks_by_unique_link',
			'get_is_debug',
			'dismiss_notice_for_current_user',
			'get_non_wpcal_admin_details_to_show',
			'add_current_user_as_wpcal_admin_while_no_active_wpcal_admins',
		];

		if (in_array($action, $supported_processors)) {
			return true;
		}
		return false;
	}

	public static function process_single_action($action, $request_data, $initiated_for) {
		if (!self::has_common_request_processor($action)) {
			return false;
		}

		$response = [];

		if ($action === 'get_service_details_for_booking') {

			$service_id = sanitize_text_field($request_data['service_id']);

			$service_obj = wpcal_get_service($service_id);

			$service_data = $service_obj->get_data_for_user_client();

			if (!empty($service_data)) {
				$response['status'] = 'success';
				$response['service_data'] = $service_data;
				$response['service_admin_data'] = $service_obj->get_owner_admin_details();
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_current_user_details_for_booking') {

			$user_data = wpcal_get_current_user_for_booking_in_user_client();

			if (!empty($user_data)) {
				$response['status'] = 'success';
				$response['user_data'] = $user_data;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_initial_service_availabile_slots') {

			$service_id = sanitize_text_field($request_data['service_id']);

			$service_obj = wpcal_get_service($service_id);

			$exclude_booking_id = null;
			if (isset($request_data['exclude_booking_unique_link'])) {
				$exclude_booking_unique_link = sanitize_text_field($request_data['exclude_booking_unique_link']);

				$old_booking_obj = wpcal_get_booking_by_unique_link($exclude_booking_unique_link); //throws exception on failure

				$exclude_booking_id = $old_booking_obj->get_id();
			}

			$availability_details_obj = new WPCal_Service_Availability_Details($service_obj);

			$default_availability_obj = $availability_details_obj->get_default_availability();

			//initial load - 2 months
			//minus one day from from_date and add one day to to_date to cover all timezone
			//for intial load minus one day from from_date not necessary

			$service_min_date = $default_availability_obj->get_min_date();
			$service_max_date = $default_availability_obj->get_max_date();
			$current_available_from_date = $default_availability_obj->get_current_available_from_date();
			$current_available_to_date = $default_availability_obj->get_current_available_to_date();

			$_from_date = new DateTime('now', $service_obj->get_tz());
			$_from_date->setTime(0, 0, 0);
			if ($_from_date < $service_min_date) {
				//say service service_min_date in future let that be from_date
				$_from_date = clone $service_min_date;
			}

			$_to_date = clone $_from_date;
			$_to_date->modify('+62 days');

			list($available_from_date, $available_to_date) = WPCal_Service_Availability_Details::get_final_from_and_to_dates($_from_date, $_to_date, $service_min_date, $service_max_date);

			$from_date = clone $available_from_date;
			$to_date = clone $from_date;
			$to_date->modify('+62 days');

			$service_availability_slots_obj = new WPCal_Service_Availability_Slots($service_obj, $exclude_booking_id);

			$all_slots = $service_availability_slots_obj->get_slots($from_date, $to_date);

			if (is_array($all_slots)) {
				$response['status'] = 'success';
				$response['availabile_slots_details'] = [];
				$response['availabile_slots_details']['slots'] = $all_slots;

				//list($current_available_from_date, $current_available_to_date) = WPCal_Service_Availability_Details::get_current_available_from_and_to_dates($service_min_date, $service_max_date, $service_obj->get_tz());

				$availability_date_ranges = [];

				$availability_date_ranges['current_available_from_date'] = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB_allow_exception($current_available_from_date, [false]);

				$availability_date_ranges['current_available_to_date'] = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB_allow_exception($current_available_to_date, [false]);

				$response['availability_date_ranges'] = $availability_date_ranges;

				// $details = [];
				// $details['available_min_date'] = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB($service_min_date);
				// $details['available_max_date'] = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB($service_min_date);

				// $response['availabile_slots_details']['details'] = $details;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_service_availabile_slots_by_month') {

			$month = $year = '';
			if (isset($request_data['current_month_view']['month'])) {
				$month = sanitize_text_field($request_data['current_month_view']['month']);
			}
			if (isset($request_data['current_month_view']['year'])) {
				$year = sanitize_text_field($request_data['current_month_view']['year']);
			}

			if (!$month || !$year) {
				$response['status'] = 'error';
				return $response;
			}

			$service_id = sanitize_text_field($request_data['service_id']);
			$service_obj = wpcal_get_service($service_id);

			$exclude_booking_id = null;
			if (isset($request_data['exclude_booking_unique_link'])) {
				$exclude_booking_unique_link = sanitize_text_field($request_data['exclude_booking_unique_link']);

				$old_booking_obj = wpcal_get_booking_by_unique_link($exclude_booking_unique_link); //throws exception on failure

				$exclude_booking_id = $old_booking_obj->get_id();
			}

			//initial load - 2 months
			//minus one day from from_date and add one day to to_date to cover all timezone

			$service_availability_slots_obj = new WPCal_Service_Availability_Slots($service_obj, $exclude_booking_id);
			$from_date = new DateTime($year . '-' . $month . '-01', $service_obj->get_tz());
			$total_days_of_month = $from_date->format('t');
			$to_date = new DateTime($year . '-' . $month . '-' . $total_days_of_month, $service_obj->get_tz());

			$from_date->modify('-1 day');
			$to_date->modify('+1 day');

			$all_slots = $service_availability_slots_obj->get_slots($from_date, $to_date);

			if (is_array($all_slots)) {
				$response['status'] = 'success';
				$response['month_availabile_slots_details'] = [];
				$response['month_availabile_slots_details']['slots'] = $all_slots;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'add_booking') {

			$booking_id = wpcal_add_booking($request_data['form']);

			if (!empty($booking_id)) {
				$booking_obj = new WPCal_Booking($booking_id);
				$booking_data = array('unique_link' => $booking_obj->get_unique_link());

				$response['status'] = 'success';
				$response['booking_data'] = $booking_data;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'reschedule_booking') {

			$old_booking_id = null;
			$old_booking_unique_link = sanitize_text_field($request_data['old_booking_unique_link']);
			$old_booking_obj = wpcal_get_booking_by_unique_link($old_booking_unique_link); //throws exception on failure

			$old_booking_id = $old_booking_obj->get_id();

			if (!$old_booking_id) {
				$response['status'] = 'error';
				return;
			}

			//$old_booking_id = sanitize_text_field($request_data['old_booking_id']);
			$new_booking_data = $request_data['form'];

			$booking_id = wpcal_reschedule_booking($old_booking_id, $new_booking_data);

			if (!empty($booking_id)) {

				$booking_obj = wpcal_get_booking($booking_id);
				$response['status'] = 'success';
				$response['new_booking_data'] = $booking_obj->get_data_for_admin_client();
				$response['new_booking_id'] = $booking_id;
				$response['old_booking_id'] = $old_booking_id;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_booking_by_unique_link') {

			$unique_link = sanitize_text_field($request_data['unique_link']);
			$booking_obj = wpcal_get_booking_by_unique_link($unique_link); //throws exception on failure

			$result = $booking_obj->get_data_for_user_client();

			if (!empty($result)) {
				$response['status'] = 'success';
				$response['booking_data'] = $result;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'save_user_tz') {

			$tz = sanitize_text_field($request_data['tz']);

			$result = WPCal_Manage_User_Timezone::save($tz);

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_user_tz') {

			$result = WPCal_Manage_User_Timezone::get();

			//if(!empty($result)){
			$response['status'] = 'success';
			$response['tz'] = $result;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'get_general_settings_by_options') {
			$options = $request_data['options'];
			$general_settings = WPCal_General_Settings::get_all_by_options($options);

			if (!empty($general_settings)) {
				$response['status'] = 'success';
				$response['general_settings'] = $general_settings;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'run_background_task') {
			WPCal_Cron::run_api_tasks();
			$is_task_waiting_now = wpcal_is_task_waiting_now();
			$result = true;

			if (!empty($result)) {
				$response['status'] = 'success';
				$response['is_task_waiting_now'] = $is_task_waiting_now;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'initial_common_data') {
			$locale = get_locale();
			$locale_intl = str_replace(['_formal', '_informal', '_ao90'], '', $locale);
			$locale_intl = str_replace('_', '-', $locale_intl);

			$result = [];
			$result['site_tz'] = wp_timezone()->getName();
			$result['site_locale'] = $locale;
			$result['site_locale_intl'] = $locale_intl;

			if (!empty($result)) {
				$response['status'] = 'success';
				$response['data'] = $result;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'run_booking_background_tasks_by_unique_link') {
			$result = [];
			$unique_link = sanitize_text_field($request_data['unique_link']);
			$booking_obj = wpcal_get_booking_by_unique_link($unique_link);

			$result = WPCal_Background_Tasks::run_tasks_by_main_args('booking_id', $booking_obj->get_id());

			if (!wpcal_is_time_out(10)) {
				//if not even 10 secs reached
				WPCal_Background_Tasks::run_booking_based_tasks();
			}

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_is_debug') {
			$response['status'] = 'success';
			$response['is_debug'] = WPCAL_DEBUG;
		} elseif ($action === 'dismiss_notice_for_current_user') {

			$notice_id = isset($request_data['notice_id']) ? sanitize_text_field($request_data['notice_id']) : '';

			$result = WPCal_Manage_Notices::dismiss_notice_for_current_user($notice_id);

			$response['status'] = !empty($result) ? 'success' : 'error';
		} elseif ($action === 'get_non_wpcal_admin_details_to_show') { // Should be logged in wp administrator
			if (!current_user_can('administrator')) {
				$response['status'] = 'error';
			} else {
				$response['status'] = 'success';
				$response['non_wpcal_admin_details_to_show'] = wpcal_get_non_wpcal_admin_details_to_show_for_client_admin();

				// FOLLOWING NEED TO BE REMOVED ^^^^^^^^^^^^^^^^^^^^^^^^
				// $response['non_wpcal_admin_details_to_show']['list_of_admins_to_contact'] = [];
				// ABOVE NEED TO BE REMOVED ^^^^^^^^^^^^^^^^^^^^^^^^
			}
		} elseif ($action === 'add_current_user_as_wpcal_admin_while_no_active_wpcal_admins') { // Should be logged in wp administrator

			if (!current_user_can('administrator')) {
				$response['status'] = 'error';
			} else {
				$current_user_id = get_current_user_id();

				$wp_user_id = isset($request_data['wp_user_id']) ? sanitize_text_field($request_data['wp_user_id']) : '';

				if (empty($current_user_id) || empty($wp_user_id) || $current_user_id != $wp_user_id) {
					$result = false;
				} else {
					$result = WPCal_Admins::may_add_current_user_as_wpcal_admin_while_no_active_wpcal_admins();
				}

				$response['status'] = !empty($result) ? 'success' : 'error';
			}
		}

		return $response;
	}

}
