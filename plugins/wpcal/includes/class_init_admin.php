<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Admin_Init {

	public static function init() {
		if (!is_admin()) {
			return;
		}

		self::include_files();
		add_action('admin_menu', 'WPCal_Admin_Init::init_admin_menu', 99999);

		if (wpcal_check_min_version_requirements() !== true) {
			return;
		}

		add_action('admin_enqueue_scripts', 'WPCal_Init::set_js_var_dist_url', -1000);

		add_action('wp_ajax_wpcal_process_admin_ajax_request', 'WPCal_Admin_Init::process_ajax_request');

		add_action('admin_init', 'WPCal_Admin_Init::process_admin_get_request');

		add_action('update_option_timezone_string', 'wpcal_on_wp_setting_timezone_changes');

		add_filter('plugin_action_links', 'WPCal_Admin_Init::add_plugin_action_links', 10, 4);

		add_action('admin_notices', 'WPCal_Manage_Notices::show_wp_admin_notices');

		self::enqueue_styles_and_scripts();

		if (get_option('wpcal_first_activation_redirect')) {
			add_action('admin_init', __CLASS__ . '::on_activate_redirect');
		}

		if (isset($_GET['page']) && $_GET['page'] === 'wpcal_admin') {
			add_filter('admin_body_class', function ($classes_str) {
				$classes_str .= " wpcal-admin-body ";
				return $classes_str;

			});
		}

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
		include_once WPCAL_PATH . '/includes/class_admin_data_format.php';

		include_once WPCAL_PATH . '/includes/tp_calendars/class_tp_calendars_add_event.php';
		include_once WPCAL_PATH . '/includes/tp/class_tp_resource.php';

		include_once WPCAL_PATH . '/includes/class_background_tasks.php';
		include_once WPCAL_PATH . '/includes/class_cron.php';
		include_once WPCAL_PATH . '/includes/class_mail.php';

		include_once WPCAL_PATH . '/includes/class_manage_notices.php';
		include_once WPCAL_PATH . '/includes/class_notice.php';

		include_once WPCAL_PATH . '/includes/class_tp_periodic_fetch_token.php';
	}

	private static function enqueue_styles_and_scripts() {
		add_action('admin_enqueue_scripts', 'WPCal_Admin_Init::enqueue_styles', 10);
		add_action('admin_enqueue_scripts', 'WPCal_Admin_Init::enqueue_scripts', 10);

		if (isset($_GET['page']) && $_GET['page'] === 'wpcal_admin') {
			add_action('admin_enqueue_scripts', function () {
				wp_enqueue_media(); //for media library usage - Profile pic uploads
			});
		}
	}

	public static function enqueue_styles() {

		if (WPCal_Init::is_dev_env()) {
			//wp_enqueue_style( 'wpcal_admin_css', WPCAL_PLUGIN_DIST_URL . 'css/admin.css', [], WPCAL_VERSION, false );
		} else {
			wp_enqueue_style('wpcal_common_chunk_vendor_css', WPCAL_PLUGIN_DIST_URL . 'css/chunk-vendors.css', [], WPCAL_VERSION, false);
			wp_enqueue_style('wpcal_common_chunk_css', WPCAL_PLUGIN_DIST_URL . 'css/chunk-common.css', [], WPCAL_VERSION, false);

			if (isset($_GET['page']) && $_GET['page'] === 'wpcal_admin') {
				wp_enqueue_style('wpcal_admin_css', WPCAL_PLUGIN_DIST_URL . 'css/admin.css', [], WPCAL_VERSION, false);
			} elseif (
				(isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) == 'plugins.php') ||
				(isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], '/plugins.php') !== false)
			) {
				wp_enqueue_style('wpcal_admin_other_css', WPCAL_PLUGIN_DIST_URL . 'css/admin_other.css', [], WPCAL_VERSION, false);
			}
		}
	}

	public static function enqueue_scripts() {

		//if(isset($_GET['page']) && $_GET['page'] === 'wpcal_admin' ){
		// if ( WPCal_Init::is_dev_env() ) {
		// 	wp_enqueue_script( 'wpcal_admin_chunk', WPCAL_PLUGIN_DIST_URL . 'js/chunk-vendors.js', [], WPCAL_VERSION, false );
		// 	wp_enqueue_script( 'wpcal_admin_app', WPCAL_PLUGIN_DIST_URL . 'js/admin.js', [], WPCAL_VERSION, false );
		// } else {
		wp_enqueue_script('wpcal_admin_chunk', WPCAL_PLUGIN_DIST_URL . 'js/chunk-vendors.min.js', ['wpcal_common_path'], WPCAL_VERSION, false);
		wp_enqueue_script('wpcal_admin_chunk_common', WPCAL_PLUGIN_DIST_URL . 'js/chunk-common.min.js', ['wpcal_common_path', 'wp-i18n'], WPCAL_VERSION, false);

		$lang_path = defined('WPCAL_USE_PLUGIN_LANG_PATH') && WPCAL_USE_PLUGIN_LANG_PATH ? WPCAL_PATH . '/languages' : null;
		wp_set_script_translations('wpcal_admin_chunk_common', 'wpcal', $lang_path);

		if (isset($_GET['page']) && $_GET['page'] === 'wpcal_admin') {
			wp_enqueue_script('wpcal_admin_app', WPCAL_PLUGIN_DIST_URL . 'js/admin.min.js', ['wpcal_common_path', 'wpcal_admin_chunk', 'wpcal_admin_chunk_common', 'wp-i18n'], WPCAL_VERSION, false);
			wp_localize_script('wpcal_admin_app', 'wpcal_ajax', ['ajax_url' => admin_url('admin-ajax.php'), 'admin_url' => admin_url(), 'is_debug' => WPCAL_DEBUG]);

			wp_set_script_translations('wpcal_admin_app', 'wpcal', $lang_path);
		} elseif (
			(isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) == 'plugins.php') ||
			(isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], '/plugins.php') !== false)
		) {

			wp_enqueue_script('wpcal_admin_other_app', WPCAL_PLUGIN_DIST_URL . 'js/admin_other.min.js', ['wpcal_common_path', 'wpcal_admin_chunk', 'wpcal_admin_chunk_common', 'wp-i18n'], WPCAL_VERSION, false);
			wp_localize_script('wpcal_admin_other_app', 'wpcal_ajax', ['ajax_url' => admin_url('admin-ajax.php'), 'admin_url' => admin_url(), 'is_debug' => WPCAL_DEBUG]);

			wp_set_script_translations('wpcal_admin_other_app', 'wpcal', $lang_path);
		}

		//}

	}

	public static function init_admin_menu() {
		$is_current_user_is_wpcal_admin = WPCal_Admins::is_current_user_is_wpcal_admin();
		if (!$is_current_user_is_wpcal_admin) {
			// allow administrators and super_admins to see the menu, when clicked to show notice to contact a one of the WPCal admin
			// current_user_can('administrator') may not be best practice, but single shot to cover administrator role and super admins.
			if (!current_user_can('administrator')) {
				return;
			}
		}

		add_menu_page($page_title = 'WPCal.io', $menu_title = 'WPCal.io', $capability = 'edit_posts', $menu_slug = 'wpcal_admin', $function = 'wpcal_admin_page', $icon_url = 'dashicons-calendar-alt', $position = 26);

		if (!$is_current_user_is_wpcal_admin) {
			return; //if not WPCal Admin show only main menu
		}

		//add_submenu_page(	$parent_slug = 'wpcal_admin', $page_title = 'WPCal Settings', $menu_title = 'Settings', $capability = 'edit_posts',  $menu_slug = 'wpcal_admin_settings', $function = 'wpcal_admin_settings_page');

		global $submenu;
		//if (current_user_can('edit_posts')) {
		$submenu['wpcal_admin'] = !isset($submenu['wpcal_admin']) ? [] : $submenu['wpcal_admin'];
		$submenu['wpcal_admin'][] = ['Bookings', 'edit_posts', 'admin.php?page=wpcal_admin#/bookings'];

		$submenu['wpcal_admin'][] = ['Event Types', 'edit_posts', 'admin.php?page=wpcal_admin#/event-types'];

		$submenu['wpcal_admin'][] = ['Settings', 'edit_posts', 'admin.php?page=wpcal_admin#/settings'];
		//}

		if (defined('WPCAL_DEBUG') && WPCAL_DEBUG) {
			add_submenu_page($parent_slug = 'wpcal_admin', $page_title = 'WPCal Test', $menu_title = 'Test(Debug only)', $capability = 'edit_posts', $menu_slug = 'wpcal_admin_test', $function = 'wpcal_admin_test_page');
		}
	}

	public static function on_activate_redirect() {

		if (get_option('wpcal_first_activation_redirect')) {
			update_option('wpcal_first_activation_redirect', false); //don't change to delete_option, as we are using add_option it will add only if slug not exisits that maintain 1 time use

			WPCal_Admins::may_add_current_user_as_wpcal_admin_while_no_active_wpcal_admins(); // in certain case on activation get_current_user_id() comes as 0 zero for adding first WPCal admin, to overcome this, doing this after 'init' hook, this is 'admin_init' comes after init hook, to run one off.

			//in rare case lets redirect to respective dev and prod page
			if (!isset($_GET['activate-multi'])) {
				wp_redirect(admin_url('admin.php?page=wpcal_admin#/bookings'));
				exit();
			}
		}
	}

	public static function add_plugin_action_links($actions, $plugin_file, $plugin_data, $context) {

		static $plugin;

		if (!$plugin) {
			$plugin = plugin_basename(WPCAL_PATH . '/wpcal.php');
		}

		if ($plugin != $plugin_file) {
			return $actions;
		}

		$support_link = ['support' => '<a href="https://wpcal.io/support/?utm_source=wpcal_plugin&utm_medium=plugins_page" target="_blank">Get support</a>'];

		$actions = array_merge($support_link, $actions);
		//<a onclick="wpcal_show_deactivate_feedback_form()" >Deactivate</a>
		$actions['deactivate'] .= '
		<script>
		var wpcal_deactivate_feedback_shown = false;
		jQuery(function() {
			jQuery("[data-slug=\'wpcal\'] .deactivate a").click(function(event){
				if(wpcal_deactivate_feedback_shown){
					return true;
				}
				event.preventDefault();

				var wpcal_vins =   wpcal_admin_other_vins;
				wpcal_vins.$children[0].load_component(\'DeactivateFeedback\');
			});

		});
		</script>
		<div id="wpcal_admin_other_app"></div>';

		return $actions;

	}

	public static function process_ajax_request() {
		$start_time = microtime(1);
		$response = [];
		if (!isset($_POST['wpcal_request']) || !is_array($_POST['wpcal_request'])) {
			echo wpcal_prepare_response($response);
			exit();
		}

		if (!WPCal_Admins::is_current_user_is_wpcal_admin()) { //admin ajax call check
			$response['request_result'] = 'access_denied';
			echo wpcal_prepare_response($response);
			exit();
		}

		$wpcal_request_post = stripslashes_deep($_POST['wpcal_request']);

		$wpcal_request_result = [];
		foreach ($wpcal_request_post as $action => $action_request_data) {
			try {
				if (WPCal_Init::has_common_request_processor($action)) {
					$wpcal_request_result[$action] = WPCal_Init::process_single_action($action, $action_request_data, 'admin_end');
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

		if ($action === 'edit_service') {

			$service_id = sanitize_text_field($request_data['service_id']);

			wpcal_is_current_admin_owns_resource('service', $service_id);

			$service_obj = wpcal_get_service($service_id);

			$service_status = $service_obj->get_status();
			if (!in_array($service_status, [1, -1])) {
				throw new WPCal_Exception('service_no_longer_editable');
			}

			$service_data = $service_obj->get_data_for_admin_client();

			//get and set 'default_availability_details'
			$service_data['default_availability_details'] = wpcal_get_default_availability_details_for_admin_client($service_obj);

			if (!empty($service_data)) {
				$response['status'] = 'success';
				$response['service_data'] = $service_data;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'update_service') {

			$service_id = sanitize_text_field($request_data['service_id']);

			wpcal_is_current_admin_owns_resource('service', $service_id);

			$service_obj = wpcal_get_service($service_id);

			$service_status = $service_obj->get_status();
			if (!in_array($service_status, [1, -1])) {
				throw new WPCal_Exception('service_no_longer_editable');
			}

			$data = $request_data['service_data'];

			$service_obj = wpcal_update_service($data, $service_id);

			$is_proper_obj = $service_obj instanceof WPCal_Service;

			$result = false;
			if ($is_proper_obj && $service_obj->get_id()) {
				$result = true;
			}

			if ($result) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}

		} elseif ($action === 'add_service') {

			$_service_id = sanitize_text_field($request_data['service_id']);
			if (is_numeric($_service_id)) { //checking this because update service will be sending service_id both via same function in js
				throw new WPCal_Exception('invalid_input');
			}

			$data = $request_data['service_data'];

			$service_obj = wpcal_add_service($data);

			$is_proper_obj = $service_obj instanceof WPCal_Service;

			$result = false;

			$service_id = $service_obj->get_id();
			if ($is_proper_obj && $service_id) {
				$result = true;
			}

			if ($is_proper_obj && $service_id) {
				$response['status'] = 'success';
				$response['service_id'] = $service_id;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'edit_service_availability') {

			$service_id = sanitize_text_field($request_data['service_id']);

			wpcal_is_current_admin_owns_resource('service', $service_id);

			$service_obj = wpcal_get_service($service_id);

			$service_availability_details_obj = new WPCal_Service_Availability_Details($service_obj);

			$default_availability_obj = $service_availability_details_obj->get_default_availability();

			$service_min_date = $default_availability_obj->get_min_date();
			$service_max_date = $default_availability_obj->get_max_date();

			$_from_date = new DateTime('now', $service_obj->get_tz());
			$_from_date->setTime(0, 0, 0);
			if ($_from_date > $service_max_date) {
				$response['status'] = 'error';
				$response['error'] = 'expired';
				return $response;
			} elseif ($_from_date < $service_min_date) {
				//say service service_min_date in future let that be from_date
				$_from_date = clone $service_min_date;
			}
			$year = $_from_date->format('Y');
			$month = $_from_date->format('m');

			$from_date = clone $_from_date;
			$total_days_of_month = $from_date->format('t');
			$to_date = new DateTime($year . '-' . $month . '-' . $total_days_of_month, $service_obj->get_tz());

			$service_availability_data = $service_availability_details_obj->get_availability_by_date_range_for_admin_client($from_date, $to_date);

			//format data for calendar
			$service_availability_data = WPCal_Admin_Data_Format::format_service_availability_for_cal($service_availability_data, $service_obj);

			if (!empty($service_availability_data)) {
				$response['status'] = 'success';
				$response['service_availability_data'] = $service_availability_data;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'edit_service_availability_by_month') {

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

			wpcal_is_current_admin_owns_resource('service', $service_id);

			$service_obj = wpcal_get_service($service_id);

			$_today_date = new DateTime('now', $service_obj->get_tz());
			$_today_date->setTime(0, 0, 0);

			$from_date = new DateTime($year . '-' . $month . '-01', $service_obj->get_tz());
			$total_days_of_month = $from_date->format('t');
			$to_date = new DateTime($year . '-' . $month . '-' . $total_days_of_month, $service_obj->get_tz());
			if ($from_date < $_today_date && $_today_date < $to_date) {
				$from_date = clone $_today_date;
			}

			$service_availability_details_obj = new WPCal_Service_Availability_Details($service_obj);

			$service_availability_data = $service_availability_details_obj->get_availability_by_date_range_for_admin_client($from_date, $to_date);

			//format data for calendar
			$service_availability_data = WPCal_Admin_Data_Format::format_service_availability_for_cal($service_availability_data, $service_obj);

			if (!empty($service_availability_data)) {
				$response['status'] = 'success';
				$response['service_availability_data'] = $service_availability_data;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'customize_service_availability') {

			$service_id = sanitize_text_field($request_data['service_id']);

			wpcal_is_current_admin_owns_resource('service', $service_id);

			$data = $request_data['request_data'];

			$result = wpcal_customize_availability_dates($service_id, $data);

			if ($result) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		}
		// elseif($action === 'get_periods_for_prefill_for_marking_available'){

		// 	$service_id = sanitize_text_field($request_data['service_id']);

		// 	$date = sanitize_text_field($request_data['date']);

		// 	$result = wpcal_get_periods_for_prefill_for_marking_available_by_date($service_id, $date);

		// 	if($result){
		// 		$response['status'] = 'success';
		// 		$response['availability_data'] =  $result;
		// 	}
		// 	else{
		// 		$response['status'] = 'error';
		// 	}
		// }
		elseif ($action === 'get_managed_bookings_for_current_admin') {
			$options = $request_data['options'] ?? [];

			$result = WPCal_Bookings_Query::get_managed_bookings_for_current_admin($options);

			if ($result) {
				$response['status'] = 'success';
				$response['bookings_data'] = $result;
			} else {
				$response['status'] = 'error';
			}

		}
		/* elseif ($action === 'get_upcoming_bookings') {

		$allowed_keys = [
		'page',
		'filters',
		'view_base_timing',
		];

		$options = wpcal_get_allowed_fields($request_data['options'], $allowed_keys);

		$options = wpcal_sanitize_all($options);

		$result = WPCal_Bookings_Query::get_upcoming_bookings_for_admin_client($options);

		if ($result) {
		$response['status'] = 'success';
		$response['bookings_data'] = $result;
		} else {
		$response['status'] = 'error';
		}
		} elseif ($action === 'get_past_bookings') {

		$allowed_keys = [
		'page',
		'filters',
		'view_base_timing',
		];

		$options = wpcal_get_allowed_fields($request_data['options'], $allowed_keys);

		$options = wpcal_sanitize_all($options);

		$result = WPCal_Bookings_Query::get_past_bookings_for_admin_client($options);

		if ($result) {
		$response['status'] = 'success';
		$response['bookings_data'] = $result;
		} else {
		$response['status'] = 'error';
		}
		} elseif ($action === 'get_custom_bookings') {

		$allowed_keys = [
		'page',
		'filters',
		];

		$options = wpcal_get_allowed_fields($request_data['options'], $allowed_keys);

		$options = wpcal_sanitize_all($options);

		$result = WPCal_Bookings_Query::get_custom_bookings_for_admin_client($options);

		if ($result) {
		$response['status'] = 'success';
		$response['bookings_data'] = $result;
		} else {
		$response['status'] = 'error';
		}
		} */
		elseif ($action === 'cancel_booking') {
			$booking_id = sanitize_text_field($request_data['booking_id']);
			$cancel_reason = sanitize_textarea_field($request_data['cancel_reason']);

			wpcal_is_current_admin_owns_resource('booking', $booking_id);

			$result = wpcal_cancel_booking($booking_id, $cancel_reason);
			if ($result) {
				$response['status'] = 'success';
				$response['bookings_data'] = $result;
			} else {
				$response['status'] = 'error';
			}
		}
		// Following commented as alternate is coded 'get_managed_services_for_current_admin' keeping it for furture use.
		// elseif ($action === 'get_services_of_current_admin') {

		// 	$service_list = wpcal_get_services_of_current_admin();

		// 	// if(!empty($booking_id)){

		// 	$response['status'] = 'success';
		// 	$response['service_list'] = $service_list;
		// 	// }
		// 	// else{
		// 	// 	$response['status'] = 'error';
		// 	// }
		// }
		elseif ($action === 'get_managed_services_for_current_admin') {

			$service_list = wpcal_get_managed_services_for_current_admin($request_data); //santizing will be taken care

			// if(!empty($booking_id)){

			$response['status'] = 'success';
			$response['service_list'] = $service_list;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'update_service_status_of_current_admin') {

			$status = sanitize_text_field($request_data['status']);
			$service_id = sanitize_text_field($request_data['service_id']);

			wpcal_is_current_admin_owns_resource('service', $service_id);

			//NEED to validate for service belogs to this admin

			if ($status == -2) {
				$result = wpcal_delete_service($service_id);
			} else {
				$result = wpcal_update_service_status($status, $service_id);
			}

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_calendar_accounts_details_of_current_admin') {

			$calendar_accounts_details = wpcal_get_calendar_accounts_details_of_current_admin();

			// if(!empty($booking_id)){

			$response['status'] = 'success';
			$response['calendar_accounts_details'] = $calendar_accounts_details;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'get_add_bookings_to_calendar_of_current_admin') {

			$add_bookings_to_calendar = wpcal_get_add_bookings_to_calendar_of_current_admin();

			// if(!empty($booking_id)){

			$response['status'] = 'success';
			$response['add_bookings_to_calendar'] = $add_bookings_to_calendar;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'get_conflict_calendar_ids_of_current_admin') {

			$conflict_calendar_ids = wpcal_get_conflict_calendar_ids_of_current_admin();

			// if(!empty($booking_id)){

			$response['status'] = 'success';
			$response['conflict_calendar_ids'] = $conflict_calendar_ids;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'update_add_bookings_to_calendar_id_for_current_admin') {

			$add_bookings_to_calendar_id = sanitize_text_field($request_data['add_bookings_to_calendar_id']);

			$calendar_account_ids = wpcal_get_unique_calendar_account_ids_by_calendar_ids([$add_bookings_to_calendar_id]);
			foreach ($calendar_account_ids as $calendar_account_id) {
				wpcal_is_current_admin_owns_resource('calendar_account', $calendar_account_id);
			}

			$is_updated = wpcal_update_add_bookings_to_calendar_id_for_current_admin($add_bookings_to_calendar_id);

			if (!empty($is_updated)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'update_conflict_calendar_ids_for_current_admin') {

			if (!isset($request_data['conflict_calendar_ids'])) {
				$request_data['conflict_calendar_ids'] = [];
			}
			$conflict_calendar_ids =
				array_map('sanitize_text_field', $request_data['conflict_calendar_ids']);

			$calendar_account_ids = wpcal_get_unique_calendar_account_ids_by_calendar_ids($conflict_calendar_ids);
			foreach ($calendar_account_ids as $calendar_account_id) {
				wpcal_is_current_admin_owns_resource('calendar_account', $calendar_account_id);
			}

			$conflict_calendar_ids_length =
				sanitize_text_field($request_data['conflict_calendar_ids_length']);

			$is_updated = wpcal_update_conflict_calendar_ids_for_current_admin($conflict_calendar_ids, $conflict_calendar_ids_length);

			if (!empty($is_updated)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'disconnect_calendar_by_id_for_current_admin') {

			$calendar_account_id =
				sanitize_text_field($request_data['calendar_account_id']);
			$provider =
				sanitize_text_field($request_data['provider']);
			$_force =
				sanitize_text_field($request_data['force']);
			$force = $_force === 'force_remove' ? true : false;

			wpcal_is_current_admin_owns_resource('calendar_account', $calendar_account_id);

			//to do verify admin have right on this

			$result = wpcal_disconnect_calendar_by_id($calendar_account_id, $provider, $force);

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		}
		// elseif($action === 'get_general_setting'){

		// 	$option = sanitize_text_field($request_data['general_setting']);
		// 	$general_setting = WPCal_General_Settings::get($option);

		// 	//if(!empty($general_settings)){
		// 		$response['status'] = 'success';
		// 		$response['general_setting'] = $general_setting;
		// 	// }
		// 	// else{
		// 	// 	$response['status'] = 'error';
		// 	// }
		// }
		elseif ($action === 'get_general_settings') {

			$general_settings = WPCal_General_Settings::get_all();

			if (!empty($general_settings)) {
				$response['status'] = 'success';
				$response['general_settings'] = $general_settings;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'update_general_settings') {

			$general_settings = $request_data['general_settings'];

			$result = WPCal_General_Settings::update_all($general_settings);

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'license_auth_login') {

			$result = WPCal_License::login($request_data);

			if (!empty($result['status']) && $result['status'] === 'success') {
				$response = $result;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'license_signup_and_login') {

			$result = WPCal_License::login($request_data, $do_signup = true);

			if (!empty($result['status']) && $result['status'] === 'success') {
				$response = $result;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'license_signup') {

			$result = WPCal_License::signup($request_data);

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'license_status') {

			$result = WPCal_License::get_account_info();

			if (isset($result)) {
				$response['status'] = 'success';
				$response['license_info'] = $result;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_wpcal_admin_users_details') {

			$result = wpcal_get_wpcal_admin_users_details_for_admin_client();

			//if(isset($result)){
			$response['status'] = 'success';
			$response['admin_users_details'] = $result;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'get_managed_active_admins_details_for_current_admin') {

			$result = wpcal_get_managed_active_admin_users_details_for_current_admin();

			//if(isset($result)){
			$response['status'] = 'success';
			$response['managed_active_admins_details'] = $result;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'initial_admin_data') {

			$result = [];
			$result['current_admin_details'] = wpcal_get_admin_details_of_current_admin();
			$result['current_admin_notices'] = wpcal_get_notices_for_current_admin();
			$result['wpcal_site_urls'] = [
				'lost_pass_url' => WPCAL_SITE_LOST_PASS_URL,
			];

			if (!empty($result)) {
				$response['status'] = 'success';
				$response['data'] = $result;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'update_admin_notices') {

			$result = wpcal_update_notices_for_current_admin($request_data);

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_admin_notices') {

			$current_admin_notices = wpcal_get_notices_for_current_admin();

			//if(!empty($result)){
			$response['status'] = 'success';
			$response['current_admin_notices'] = $current_admin_notices;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'get_tp_accounts_of_current_admin') {

			$tp_accounts = wpcal_get_tp_accounts_of_current_admin();

			//if(!empty($result)){
			$response['status'] = 'success';
			$response['tp_accounts'] = $tp_accounts;
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'disconnect_tp_account_by_id_for_current_admin') {

			$tp_account_id =
				sanitize_text_field($request_data['tp_account_id']);
			$provider =
				sanitize_text_field($request_data['provider']);
			$_force =
				sanitize_text_field($request_data['force']);
			$force = $_force === 'force_remove' ? true : false;

			wpcal_is_current_admin_owns_resource('tp_account', $tp_account_id);

			//to do verify admin have right on this

			$result = wpcal_disconnect_tp_account_by_id($tp_account_id, $provider, $force);

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_tp_locations_details_for_current_admin') {

			$response['status'] = 'success';
			$response['tp_locations'] = wpcal_get_tp_locations_for_current_admin();

		} elseif ($action === 'get_tp_locations_details_by_admin') {
			$admin_user_id = sanitize_text_field($request_data['admin_user_id']);

			$response['status'] = 'success';
			$response['tp_locations'] = wpcal_get_tp_locations_by_admin($admin_user_id);
		} elseif ($action === 'check_auth_if_fails_may_remove_tp_accounts_for_current_admin') {

			$response['status'] = 'success';
			$response['check_auth_tp_accounts'] = wpcal_check_auth_if_fails_may_remove_tp_accounts_for_current_admin();
		} elseif ($action === 'submit_plugin_deactivate_feedback') {

			$sanitize_rules = ['user_descr' => 'sanitize_textarea_field'];
			$deactivate_feedback = wpcal_sanitize_all($request_data['form'], $sanitize_rules);
			$result = WPCal_License::send_deactivate_feedback($deactivate_feedback);
			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_admin_profile_settings_of_current_admin') {

			$profile_settings = wpcal_get_admin_profile_settings_of_current_admin();
			$response['status'] = 'success';
			$response['profile_settings'] = $profile_settings;
		} elseif ($action === 'update_admin_profile_settings_of_current_admin') {

			$profile_settings = isset($request_data['profile_settings']) ? $request_data['profile_settings'] : []; //santize will be taken care

			$result = wpcal_update_admin_profile_settings_of_current_admin($profile_settings);

			if (!empty($result)) {
				$response['status'] = 'success';

				if (!empty($profile_settings['avatar_attachment_id'])) {
					// just in case, manual option to fix the thumnail missing
					wpcal_may_generate_avatar_attachment($profile_settings['avatar_attachment_id']);
				}
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_admin_settings_of_current_admin') {

			$admin_settings = wpcal_get_admin_settings_of_current_admin();
			$response['status'] = 'success';
			$response['admin_settings'] = $admin_settings;
		} elseif ($action === 'update_admin_settings_of_current_admin') {

			$admin_settings = isset($request_data['admin_settings']) ? $request_data['admin_settings'] : []; //santize will be taken care

			$result = wpcal_update_admin_settings_of_current_admin($admin_settings);

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'get_admin_avatar_of_current_admin') {

			$avatar_details = wpcal_get_admin_avatar_of_current_admin();
			$response['status'] = 'success';
			$response['avatar_details'] = $avatar_details;
		} elseif ($action === 'get_admin_avatar_override_of_current_admin') {
			$override_attachment_id = isset($request_data['override_attachment_id']) ? $request_data['override_attachment_id'] : '';

			//If WPCal required thumbnail(s) are missing generate entire attachment thumbnails
			wpcal_may_generate_avatar_attachment($override_attachment_id);

			$avatar_details = wpcal_get_admin_avatar_of_current_admin($override_attachment_id);

			if (!empty($avatar_details)) {
				$response['status'] = 'success';
				$response['avatar_details'] = $avatar_details;
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'sync_all_calendar_api_for_current_admin') {

			$result = wpcal_sync_all_calendar_api_for_current_admin();

			//if(!empty($avatar_details)){
			$response['status'] = 'success';
			// }
			// else{
			// 	$response['status'] = 'error';
			// }
		} elseif ($action === 'get_wp_admins_to_add') {

			$result = wpcal_get_wp_admins_to_add();

			$response['status'] = 'success';
			$response['wp_admins_to_add'] = $result;

		} elseif ($action === 'add_wpcal_admin') {

			$admin_data = isset($request_data['admin_data']) ? $request_data['admin_data'] : []; //santize will be taken care

			$result = WPCal_Admins::add_wpcal_admin($admin_data);

			if (!empty($result)) {
				$response['status'] = 'success';
			} else {
				$response['status'] = 'error';
			}
		} elseif ($action === 'enable_wpcal_admin') {
			$admin_user_id = isset($request_data['admin_user_id']) ? sanitize_text_field($request_data['admin_user_id']) : '';

			$result = WPCal_Admins::enable_wpcal_admin($admin_user_id);

			$response['status'] = !empty($result) ? 'success' : 'error';

		} elseif ($action === 'disable_wpcal_admin') {
			$admin_user_id = isset($request_data['admin_user_id']) ? sanitize_text_field($request_data['admin_user_id']) : '';

			$result = WPCal_Admins::disable_wpcal_admin($admin_user_id);

			$response['status'] = !empty($result) ? 'success' : 'error';

		} elseif ($action === 'delete_wpcal_admin') {
			$admin_user_id = isset($request_data['admin_user_id']) ? sanitize_text_field($request_data['admin_user_id']) : '';

			$result = WPCal_Admins::delete_wpcal_admin($admin_user_id);

			$response['status'] = !empty($result) ? 'success' : 'error';

		} elseif ($action === 'get_template_overrides') {

			$template_overrides = wpcal_get_template_overrides();

			$response['status'] = 'success';
			$response['template_overrides'] = $template_overrides;
		} elseif ($action === 'force_validate_next_instance') {

			$result = WPCal_License::force_validate_next_instance();
			$response['status'] = 'success';
		}

		return $response;
	}

	public static function process_admin_get_request() {
		//IMPROVE code using nonce

		if (!isset($_GET['wpcal_action']) || !isset($_GET['page']) || $_GET['page'] != 'wpcal_admin') {
			return;
		}

		if ($_GET['wpcal_action'] === 'add_calendar_account' && isset($_GET['provider']) && !empty($_GET['provider'])) {
			wpcal_add_calendar_account_redirect($_GET['provider']);

		} elseif ($_GET['wpcal_action'] === 'reauth_calendar_account' && isset($_GET['provider']) && !empty($_GET['provider'])) {
			wpcal_add_calendar_account_redirect($_GET['provider'], 'reauth');

		} elseif ($_GET['wpcal_action'] === 'google_calendar_receive_token') {
			$action = (isset($_GET['wpcal_reauth']) && $_GET['wpcal_reauth'] === '1') ? 'reauth' : 'add';
			wpcal_google_calendar_receive_token_and_add_account($action);

		} elseif ($_GET['wpcal_action'] === 'add_tp_account' && isset($_GET['provider']) && !empty($_GET['provider'])) {
			wpcal_add_tp_account_redirect($_GET['provider']);

		} elseif ($_GET['wpcal_action'] === 'reauth_tp_account' && isset($_GET['provider']) && !empty($_GET['provider'])) {
			wpcal_add_tp_account_redirect($_GET['provider'], 'reauth');

		} elseif ($_GET['wpcal_action'] === 'tp_account_receive_token' && isset($_GET['provider']) && !empty($_GET['provider'])) {
			$action = (isset($_GET['wpcal_reauth']) && $_GET['wpcal_reauth'] === '1') ? 'reauth' : 'add';
			wpcal_tp_account_receive_token_and_add_account($_GET['provider'], $action);
		}
	}

}
