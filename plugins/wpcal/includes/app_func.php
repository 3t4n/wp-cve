<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

function wpcal_add_service($data) {
	return wpcal_update_service($data);
}

function wpcal_update_service($update_data, $service_id = 0) {
	global $wpdb;

	if ($service_id) {
		$update = true;
	} else {
		$update = false;
	}

	$allowed_keys = [
		'name',
		'status',
		'locations',
		'descr',
		//'post_id',
		'color',
		'relationship_type',
		'timezone',
		'duration',
		'display_start_time_every',
		'max_booking_per_day',
		'min_schedule_notice',
		'event_buffer_before',
		'event_buffer_after',
		'is_manage_private',
		'service_admin_user_ids',
		'invitee_questions',
		'invitee_notify_by',
		'default_availability_details',
	];

	$default_data = [
		'status' => 1,
		'max_booking_per_day' => null,
		'min_schedule_notice' => ["type" => "units", "time_units" => 4, "time_units_in" => "hrs", "days_before_time" => "00:00:00", "days_before" => 0],
		'event_buffer_before' => 0,
		'event_buffer_after' => 0,
	];

	$data = array_merge($default_data, $update_data);

	$_fix_default = ['max_booking_per_day',
		'min_schedule_notice',
		'event_buffer_before',
		'event_buffer_after'];

	foreach ($_fix_default as $_fix_key) {
		if ($data[$_fix_key] == '') {
			$data[$_fix_key] = $default_data[$_fix_key];
		}
	}

	$data = wpcal_get_allowed_fields($data, $allowed_keys);

	if (isset($data['locations'])) {
		$data['locations'] = wpcal_service_locations_get_allowed_fields($data['locations']);
	}

	$sanitize_rules = [
		'descr' => 'sanitize_textarea_field',
		'locations' => [
			'*' => [
				'form' => ['location_extra' => 'sanitize_textarea_field'],
			],
		],
		'invitee_questions' => [
			'questions' => [
				'*' => [
					'question' => 'sanitize_textarea_field',
				],
			],
		],
	];

	$data = wpcal_sanitize_all($data, $sanitize_rules);

	if (!$update) {
		$data['status'] = 1;
	}

	$validate_obj = new WPCal_Validate($data);
	$validate_obj->rules([
		'required' => [
			'name',
			'status',
			'relationship_type',
			'color',
			'timezone',
			'duration',
			'display_start_time_every',
			//'max_booking_per_day',
			'min_schedule_notice.type',
			'event_buffer_before',
			'event_buffer_after',
			'is_manage_private',
			'default_availability_details.date_range_type',
			'default_availability_details.day_index_list',
			'service_admin_user_ids',
			'invitee_notify_by',
		],
		'requiredWithIf' => [
			['default_availability_details.from_date', ['default_availability_details.date_range_type' => 'from_to']],
			['default_availability_details.to_date', ['default_availability_details.date_range_type' => 'from_to']],
			['default_availability_details.date_misc', ['default_availability_details.date_range_type' => 'relative']],
			['min_schedule_notice.time_units', ['min_schedule_notice.type' => 'units']],
			['min_schedule_notice.time_units_in', ['min_schedule_notice.type' => 'units']],
			['min_schedule_notice.days_before_time', ['min_schedule_notice.type' => 'time_days_before']],
			['min_schedule_notice.days_before', ['min_schedule_notice.type' => 'time_days_before']], //Is it working???
		],
		'integer' => [
			'duration',
			'display_start_time_every',
			'max_booking_per_day',
			'event_buffer_before',
			'event_buffer_after',
			'service_admin_user_ids.*',
		],
		'lengthMin' => [
			['invitee_questions.questions.*.question', 1],
		],
		// 'lengthMax' => [
		// 	['locations.*.form.location', 500],
		// 	['locations.*.form.location_extra', 500]
		// ],
		'min' => [
			['status', -10],
			['duration', 1],
			['display_start_time_every', 1],
			['invitee_questions.questions.*.is_enabled', 0],
			['invitee_questions.questions.*.is_required', 0],
			['min_schedule_notice.time_units', 0],
			['min_schedule_notice.days_before', 0],
			['is_manage_private', 0],
			['service_admin_user_ids.*', 1],
		],
		'max' => [
			['status', 10],
			['duration', 1440],
			['display_start_time_every', 1440],
			['invitee_questions.questions.*.is_enabled', 1],
			['invitee_questions.questions.*.is_required', 1],
			['min_schedule_notice.days_before', 7],
			['is_manage_private', 1],

		],
		'in' => [
			['relationship_type', ['1to1', '1ton']],
			['invitee_questions.questions.*.answer_type', ['textarea', 'input_text', 'input_phone']],
			['min_schedule_notice.type', ['none', 'units', 'time_days_before']],
			['min_schedule_notice.time_units_in', ['mins', 'hrs', 'days']],
			// ['locations.*.type', ['physical', 'phone', 'googlemeet_meeting', 'zoom_meeting', 'gotomeeting_meeting', 'custom', 'ask_invitee']],
			// ['locations.*.form.who_calls', ['admin', 'invitee']],
			['invitee_notify_by', ['calendar_invitation', 'email']],
		],
		'arrayHasKeys' => [
			//['invitee_questions', ['questions']],
			['min_schedule_notice', ['type', 'time_units', 'time_units_in', 'days_before_time', 'days_before']],
		],
		'dateFormat' => [
			['default_availability_details.from_date', 'Y-m-d'],
			['default_availability_details.to_date', 'Y-m-d'],
			['default_availability_details.periods.*.from_time', 'H:i:s'],
			['default_availability_details.periods.*.to_time', 'H:i:s'],
			['min_schedule_notice.days_before_time', 'H:i:s'],
		],
		'subset' => [
			['default_availability_details.day_index_list', [1, 2, 3, 4, 5, 6, 7]],
		],
		'array' => [
			'service_admin_user_ids',
		],
		'arrayLength' => [
			['service_admin_user_ids', 1],
		],
		'containsUnique' => [
			'service_admin_user_ids',
		],
		'toDateAfterFromDate' => [
			['default_availability_details.from_date', 'default_availability_details.to_date'],
		],
		'periodsToTimeAfterFromTime' => [
			['default_availability_details.periods'],
		],
		'periodsCheckCollide' => [
			['default_availability_details.periods'],
		],
		'checkDateMisc' => [
			['default_availability_details.date_misc'],
		],
	]);

	unset($data['default_availability_details']); //currently just to pass validation so that after service table insertion/updatation it should not show error
	$service_admin_user_ids = isset($data['service_admin_user_ids']) ? $data['service_admin_user_ids'] : [];
	unset($data['service_admin_user_ids']); //currently just to pass validation

	$validation_errors = [];
	if (!$validate_obj->validate()) {
		$validation_errors = $validate_obj->errors(); //output should be an array
	}

	if (isset($data['locations'])) {
		$locations_validation_result = wpcal_validate_service_locations($data['locations']);
		if (is_array($locations_validation_result)) {
			$validation_errors = array_merge($validation_errors, $locations_validation_result);
		}
	}

	$service_admin_users_validation_result = wpcal_validate_wpcal_admin_users($service_admin_user_ids);
	if (is_array($service_admin_users_validation_result)) {
		$validation_errors = array_merge($validation_errors, $service_admin_users_validation_result);
	}

	$make_a_free_way = WPCal_License::get_public();
	if (!$update && $make_a_free_way && $data['status'] == 1) {
		$_service_id = wpcal_process_get_active_service_ids();
		if (is_numeric($_service_id)) {
			throw new WPCal_Exception('only_one_active_event_allowed_as_per_plan');
		}
	}

	$current_admin_user_id = get_current_user_id();
	if (isset($data['is_manage_private']) && $data['is_manage_private'] == '1' && !in_array($current_admin_user_id, $service_admin_user_ids)) {
		//is_manage_private == 1 and $current_admin_user_id is not part of $service_admin_user_ids then disallow

		$validation_errors['is_manage_private'][] = wpcal__('You cannot set private event type option enabled for other host.', 'wpcal');
	}

	if (!empty($validation_errors)) {
		throw new WPCal_Exception('validation_errors', '', $validation_errors);
	}

	$data['updated_ts'] = time();
	if (!$update) {
		$data['status'] = 1;
		$data['added_ts'] = time();
	}

	if (isset($data['invitee_questions'])) {
		unset($data['invitee_questions']['__questions_count']); //workaround to post this array if 'questions' array is empty, currently that is only key
		$data['invitee_questions'] = json_encode($data['invitee_questions']);
	}

	if (!empty($data['min_schedule_notice'])) {
		$data['min_schedule_notice'] = json_encode($data['min_schedule_notice']);
	}

	if (empty($data['locations']) || !is_array($data['locations'])) {
		$data['locations'] = [];
	}
	$data['locations'] = array_values($data['locations']); //to reset keys
	foreach ($data['locations'] as $location_key => $location) {
		if (isset($data['locations'][$location_key]['form'])) {
			if (!empty($location['type']) && $location['type'] == 'phone' && !empty($location['form']['who_calls']) && $location['form']['who_calls'] == 'admin') {
				$data['locations'][$location_key]['form']['location'] = '';
			}
		}
	}
	$data['locations'] = json_encode($data['locations']);

	if (empty($data['max_booking_per_day'])) {
		$data['max_booking_per_day'] = null;
	}

	$table_service = $wpdb->prefix . 'wpcal_services';

	if ($update) {
		$result = $wpdb->update($table_service, $data, array('id' => $service_id));
	} else {
		$result = $wpdb->insert($table_service, $data);
		if ($result !== false) {
			$service_id = $wpdb->insert_id;
			if (!$service_id) {
				throw new WPCal_Exception('db_error_insert_id_missing');
			}
		}
	}

	if ($result !== false) {
		$service_obj = new WPCal_Service($service_id);
	} else {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	wpcal_connect_service_admin_users($service_id, $service_admin_user_ids);

	wpcal_add_or_update_default_availability_details($service_obj, $update_data['default_availability_details'], $update);

	wpcal_service_may_add_page($service_obj);

	wpcal_service_availability_slots_mark_refresh_cache($service_id);

	wpcal_mark_onboarding_checklist_completed_for_current_admin('create_or_edit_service');

	return $service_obj;

}

function wpcal_service_locations_get_allowed_fields($locations) {
	if (!is_array($locations)) {
		return $locations;
	}
	$locations = array_values($locations); //resetting any keys here
	foreach ($locations as $keys => $location) {
		$locations[$keys] = wpcal_service_location_get_allowed_fields($location);
	}
	return $locations;
}

function wpcal_service_location_get_allowed_fields($location) {
	$allowed_keys = [
		'type',
		'form',
	];
	$location = wpcal_get_allowed_fields($location, $allowed_keys);

	$form_allowed_keys = [
		'location',
		'location_extra',
	];
	$form_allowed_keys_w_phone = [
		'location',
		'location_extra',
		'who_calls',
	];

	$form_allowed_keys_final = $location['type'] === 'phone' ? $form_allowed_keys_w_phone : $form_allowed_keys;

	if (!empty($location['form']) && is_array($location['form'])) {
		$location['form'] = wpcal_get_allowed_fields($location['form'], $form_allowed_keys_final);
	}
	return $location;
}

function wpcal_validate_service_locations($locations) {
	if (empty($locations) || !is_array($locations)) {
		return true; //will reset before saving
	}

	$errors = [];

	if (count($locations) > 10) {
		$errors['locations'][] = 'Max 10 locations only allowed';
	}

	$location_type_count = [];
	$allowed_location_types = ['physical', 'phone', 'googlemeet_meeting', 'zoom_meeting', 'gotomeeting_meeting', 'custom', 'ask_invitee'];

	$types_more_than_one_allowed = ['physical', 'custom'];

	foreach ($locations as $key => $location) {
		!isset($location_type_count[$location['type']]) ? $location_type_count[$location['type']] = 0 : '';
		$location_type_count[$location['type']]++;
		$validation_result = wpcal_validate_service_location($location);
		if ($validation_result === true) {
			continue;
		} elseif (is_array($validation_result)) {
			foreach ($validation_result as $error_key => $error_details) {
				!isset($errors['locations.' . $key . '.' . $error_key]) ? $errors['locations.' . $key . '.' . $error_key] = [] : '';
				foreach ($error_details as $_error_index => $_error) {
					$errors['locations.' . $key . '.' . $error_key][$_error_index] = 'Location.' . $key . '.' . $_error;
				}
			}
		}
	}
	foreach ($location_type_count as $location_type => $count) {
		if (!in_array($location_type, $allowed_location_types)) {
			continue; //this error will handled by wpcal_validate_service_location()
		}
		if ($count > 1 && !in_array($location_type, $types_more_than_one_allowed)) {
			$errors['locations'][] = 'Location type ' . $location_type . ' allowed only once';
		}
	}

	return empty($errors) ? true : $errors;
}

function wpcal_validate_service_location($location) {

	$validate_obj = new WPCal_Validate($location);
	$validation_rules = [
		'required' => [
			'type',
		],
		'lengthMax' => [
			['form.location', 500],
			['form.location_extra', 500],
		],
		'in' => [
			['type', ['physical', 'phone', 'googlemeet_meeting', 'zoom_meeting', 'gotomeeting_meeting', 'custom', 'ask_invitee']],
			['form.who_calls', ['admin', 'invitee']],
		],
	];
	if (!empty($location['type']) && in_array($location['type'], ['physical', 'phone', 'custom', 'ask_invitee'])) {
		$validation_rules['required'][] = 'form';
		if ($location['type'] === 'phone') {
			if (
				!empty($location['type']['form']) &&
				!empty($location['type']['form']['who_calls']) &&
				$location['type']['form']['who_calls'] === 'invitee'
			) {
				$validation_rules['required'][] = 'form.location';
			}
		} elseif (in_array($location['type'], ['physical', 'custom'])) {
			$validation_rules['required'][] = 'form.location';
		}
	}
	$validate_obj->rules($validation_rules);

	if (!$validate_obj->validate()) {
		$validation_errors = $validate_obj->errors();
		return $validation_errors;
	}
	return true;
}

function wpcal_service_may_add_page($service_obj) {

	$post_id = $service_obj->get_post_id();
	if (is_numeric($post_id)) {
		//already post created
		return;
	}

	$service_name = $service_obj->get_name();
	$service_id = $service_obj->get_id();

	if (!$service_id || empty($service_name)) {
		throw new WPCal_Exception('invalid_input_service_page');
	}

	$add_page_data = [
		'post_author' => 1,
		'post_title' => $service_name,
		'post_name' => $service_name, //slug WP take care converting normal text into unique slug
		'post_status' => 'publish',
		'post_content' => '[wpcal id=' . $service_id . ']',
		'post_type' => 'page',
		'post_parent' => 0,
		'comment_status' => 'close',
		'ping_status' => 'close',
	];

	add_filter('option_nav_menu_options', 'wpcal_temperoraily_disable_auto_adding_new_page_to_menu', 10, 2);

	$page_id = wp_insert_post($add_page_data);

	remove_filter('option_nav_menu_options', 'wpcal_temperoraily_disable_auto_adding_new_page_to_menu', 10);

	if (!is_numeric($page_id) || !$page_id || is_wp_error($post_id)) { //on fail 0 or WP_Error
		$_error_msg = is_wp_error($post_id) ? $post_id->get_error_message() : '';
		throw new WPCal_Exception('service_page_insert_error', '', $_error_msg);
	}

	global $wpdb;
	$table_service = $wpdb->prefix . 'wpcal_services';
	$result = $wpdb->update($table_service, array('post_id' => $page_id), array('id' => $service_id));
	return $result !== false;
}

function wpcal_temperoraily_disable_auto_adding_new_page_to_menu($value, $option) {
	$menu = $value;
	if (empty($menu) || !is_array($menu) || !isset($menu['auto_add'])) {
		//already no auto add
		return $value;
	}
	$auto_add = $menu['auto_add'];
	if (empty($auto_add) || !is_array($auto_add)) {
		//already no auto add
		return $value;
	}

	$menu['auto_add'] = '';
	return $menu;
}

function wpcal_validate_wpcal_admin_users(array $admin_user_ids) {
	//other basic validation should be already done

	$errors = [];
	foreach ($admin_user_ids as $admin_user_id) {
		if (!WPCal_Admins::is_wpcal_admin_active($admin_user_id)) {
			$errors['service_admin_user_ids'][] = sprintf(
				/* translators: 1: user_id  */
				wpcal__('%s not a valid/active WPCal admin ID.', 'wpcal'), $admin_user_id);
		}
	}
	return !empty($errors) ? $errors : true;
}

function wpcal_connect_service_admin_users($service_id, $admin_user_ids) {
	//validaiton should take care of number of admin_user_ids coming here, it should be based on event types
	global $wpdb;

	if (empty($admin_user_ids)) {
		return;
	}

	$table_service_admins = $wpdb->prefix . 'wpcal_service_admins';

	//Delete old admin_user_ids not found in the new list
	$admin_user_ids_escaped = esc_sql($admin_user_ids);
	$admin_user_ids_escaped_imploded = wpcal_implode_for_sql($admin_user_ids_escaped);

	$delete_query = "DELETE FROM `$table_service_admins` WHERE `service_id` = %s AND `admin_user_id` NOT IN ($admin_user_ids_escaped_imploded)";
	$delete_query = $wpdb->prepare($delete_query, $service_id);
	$delete_query_result = $wpdb->query($delete_query);
	if ($delete_query_result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	foreach ($admin_user_ids as $admin_user_id) {
		$query = "SELECT `id` FROM `$table_service_admins` WHERE `service_id` = %s";
		$query = $wpdb->prepare($query, $service_id);
		$is_admin_user_already_assigned = $wpdb->get_var($query);
		if ($is_admin_user_already_assigned) {
			continue;
		}

		$insert_row = ['service_id' => $service_id, 'admin_user_id' => $admin_user_id];
		$result = $wpdb->insert($table_service_admins, $insert_row);
		if ($result === false) {
			//it will break, broken data might get saved during the loop
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
	}
	return true;
}

function wpcal_add_or_update_default_availability_details($service_obj, $update_data, $is_update) {
	global $wpdb;

	//validation already done in wpcal_update_service()

	//basic check
	if (empty($update_data) || !isset($update_data['date_range_type'])) {
		throw new WPCal_Exception('invalid_input_default_availability');
	}

	$allowed_keys = [
		'day_index_list',
		'date_range_type',
		'from_date',
		'to_date',
		'date_misc',
		// 'type',
		// 'is_available',
		// 'added_ts',
		// 'updated_ts',
	];

	$data = wpcal_get_allowed_fields($update_data, $allowed_keys);

	$data['updated_ts'] = time();

	// the following code committed after day_index_list in service options given to user in UI
	// if( !$is_update && empty($data['day_index_list']) ){
	// 	$data['day_index_list'] = WPCal_General_Settings::get('working_days');
	// }

	if (!empty($data['day_index_list']) && is_array($data['day_index_list'])) {
		sort($data['day_index_list']);
		$data['day_index_list'] = implode(',', $data['day_index_list']);
	}

	if ($data['date_range_type'] === 'from_to') {
		$data['date_misc'] = null;
	} elseif ($data['date_range_type'] === 'relative') {
		$data['from_date'] = null;
		$data['to_date'] = null;
	} elseif ($data['date_range_type'] === 'infinite') {
		$data['date_misc'] = null;
		$data['from_date'] = null;
		$data['to_date'] = null;
	}

	$table_availability_dates = $wpdb->prefix . 'wpcal_availability_dates';

	if ($is_update) {

		$service_availability_details_obj = new WPCal_Service_Availability_Details($service_obj);

		$default_availability_details = $service_availability_details_obj->get_default_availability();

		if (empty($default_availability_details) || !($default_availability_details instanceof WPCal_Availability_Date)) { //mostly this check may not require. As it will throwed already.
			throw new WPCal_Exception('service_default_availability_data_missing');
		}

		$default_availability_date_id = $default_availability_details->get_id();

		$update_where = array('id' => $default_availability_date_id);
		$update_result = $wpdb->update($table_availability_dates, $data, $update_where);
		if ($update_result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
	} else {
		$data['added_ts'] = $data['updated_ts'];
		$data['type'] = 'default';

		$insert_result = $wpdb->insert($table_availability_dates, $data);
		if ($insert_result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		$default_availability_date_id = $wpdb->insert_id;

		$table_service_availability = $wpdb->prefix . 'wpcal_service_availability';

		$service_id = $service_obj->get_id();

		$link_service_availability = [
			'service_id' => $service_id,
			'availability_date_id' => $default_availability_date_id,
		];

		$link_insert_result = $wpdb->insert($table_service_availability, $link_service_availability);
		if ($link_insert_result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
	}

	$update_data_periods = $update_data['periods'];
	wpcal_update_default_availability_periods($service_obj, $update_data_periods, $is_update);
}

function wpcal_update_default_availability_periods($service_obj, $update_data_periods, $is_update) {

	global $wpdb;

	//validation already done in wpcal_update_service()

	if (!is_array($update_data_periods)) {
		throw new WPCal_Exception('invalid_input_default_availability');
	}

	$service_availability_details_obj = new WPCal_Service_Availability_Details($service_obj);

	if ($is_update) {

		$default_availability_details = $service_availability_details_obj->get_default_availability();

		$saved_periods_objs = $default_availability_details->get_periods();
		$default_availability_date_id = $default_availability_details->get_id();
	} else {
		$saved_periods_objs = [];
		$default_availability_date_id = $service_availability_details_obj->get_default_availability_id();
	}

	foreach ($update_data_periods as &$period) {
		$period['from_time'] = WPCal_DateTime_Helper::get_Time_obj($period['from_time']);
		$period['to_time'] = WPCal_DateTime_Helper::get_Time_obj($period['to_time']);
	}
	unset($period);

	$delete_periods_ids = [];
	$add_periods = [];

	foreach ($saved_periods_objs as $saved_period_obj) {
		$saved_period_id = $saved_period_obj->get_id();
		$saved_from_time = $saved_period_obj->get_from_time();
		$saved_to_time = $saved_period_obj->get_to_time();
		$is_exists = false;

		foreach ($update_data_periods as $_key => $update_period) {
			if ($update_period['from_time'] == $saved_from_time && $update_period['to_time'] == $saved_to_time) {
				$is_exists = true;
				unset($update_data_periods[$_key]);
				break;
			}
		}

		if (!$is_exists) {
			$delete_periods_ids[] = $saved_period_id;
		}
	}

	$add_periods = $update_data_periods;
	$table_availability_periods = $wpdb->prefix . 'wpcal_availability_periods';

	if (!empty($delete_periods_ids)) {
		$delete_periods_ids_imploded = wpcal_implode_for_sql($delete_periods_ids);
		$query = "DELETE FROM `$table_availability_periods` WHERE `id` IN($delete_periods_ids_imploded) AND `availability_date_id` = '" . $default_availability_date_id . "'";

		$query_result = $wpdb->query($query);

		if ($query_result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
	}

	if (!empty($add_periods)) {

		foreach ($add_periods as $add_period) {
			$insert_data = [];
			$insert_data['from_time'] = $add_period['from_time']->DB_format();
			$insert_data['to_time'] = $add_period['to_time']->DB_format();
			$insert_data['availability_date_id'] = $default_availability_date_id;
			$insert_result = $wpdb->insert($table_availability_periods, $insert_data);
			if ($insert_result === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			}
		}

	}

}

function wpcal_get_default_availability_details_for_admin_client($service_obj) {
	$service_availability_details_obj = new WPCal_Service_Availability_Details($service_obj);

	$default_availability_obj = $service_availability_details_obj->get_default_availability();

	$periods = $default_availability_obj->get_data_for_admin_client();
	return $periods;
}

function wpcal_customize_availability_dates($service_id, $data) {

	global $wpdb;

	//$service_obj = new WPCal_Service($service_id);

	//check service is editable by current user
	//validate nonce
	/**
	 * validate data
	 * 1) check the date(s) are within default availability date range
	 * 2) check periods with 24 hrs
	 * 3) check correct inputs date_range_type 'from_to', from_date & to_date should be present, similarly for all cases
	 * */
	/**
	 * What to do with old customized availability details
	 * 1) How to verify it is old and no longer useful
	 * 2) What to do for already expired customization
	 * 3) Do we need old data for history purpose
	 */

	//is_multiple for multiple dates

	//days
	//dates - single date is also fine.
	//apply_to_days_or_dates - 'days' | 'dates'

	if (
		isset($data['is_available']) &&
		$data['is_available'] == 1 &&
		isset($data['use_previous_periods']) &&
		$data['use_previous_periods'] == 1 &&
		!empty($data['dates']) &&
		is_array($data['dates'])
	) {
		unset($data['periods']);
		$_availability_details = wpcal_get_periods_for_prefill_for_marking_available_by_date($service_id, $data['dates'][0]);
		if (isset($_availability_details['periods'])) {
			$data['periods'] = $_availability_details['periods'];
		}
		//no need to worry about $data['periods'] if not properly set, validation will take care. even for non default sunday it will give default period, if not it should be out of availability I guess
		unset($_availability_details);
	}

	$validate_obj = new WPCal_Validate($data);
	$validate_obj->rules([
		'required' => [
			'apply_to_days_or_dates',
			'is_available',
		],
		'requiredWithIf' => [
			['dates', ['apply_to_days_or_dates' => 'dates']],
			['day_index_list', ['apply_to_days_or_dates' => 'days']],
			['from_date', ['apply_to_days_or_dates' => 'days']],
			['periods', ['is_available' => '1']],

		],
		'min' => [
			['is_available', 0],
		],
		'max' => [
			['is_available', 1],
		],
		'in' => [
			['apply_to_days_or_dates', ['days', 'dates']],
		],
		'subset' => [
			['day_index_list', [1, 2, 3, 4, 5, 6, 7]],
		],
		'array' => [
			'dates',
			'day_index_list',
		],
		'dateFormat' => [
			['from_date', 'Y-m-d'],
			['dates.*', 'Y-m-d'],
			['periods.*.from_time', 'H:i:s'],
			['periods.*.to_time', 'H:i:s'],
		],
		'periodsToTimeAfterFromTime' => [
			['periods'],
		],
		'periodsCheckCollide' => [
			['periods'],
		],
	]);

	if (!$validate_obj->validate()) {
		$validation_errors = $validate_obj->errors();
		throw new WPCal_Exception('validation_errors', '', $validation_errors);
	}

	$allowed_keys = [
		'day_index_list',
		'date_range_type',
		'from_date',
		'to_date',
		'date_misc',
		'type',
		'is_available',
	];

	$allowed_keys_periods = [
		'from_time',
		'to_time',
	];

	$save_data = $data;
	//unset($save_data['apply_to_days_or_dates'], $save_data['from_dates'], $save_data['current_view_month']);

	$save_data = wpcal_get_allowed_fields($data, $allowed_keys);

	$save_data = wpcal_sanitize_all($save_data);

	$save_data['type'] = 'custom';
	$save_data['updated_ts'] = $save_data['added_ts'] = time();

	$save_data_array = [];
	if ($data['apply_to_days_or_dates'] === 'dates') {
		$save_data['date_range_type'] = 'from_to';

		foreach ($data['dates'] as $date) {
			$row = $save_data;
			$row['to_date'] = $row['from_date'] = $date;
			unset($row['day_index_list']);
			$save_data_array[] = $row;
		}
	} elseif ($data['apply_to_days_or_dates'] === 'days') {
		$save_data['date_range_type'] = 'infinite';
		$save_data['day_index_list'] = implode(',', $save_data['day_index_list']);
		unset($save_data['to_date']);
		$save_data_array[] = $save_data;
	}

	$save_periods = (isset($data['periods']) && is_array($data['periods'])) ? $data['periods'] : [];

	$table_availability_dates = $wpdb->prefix . 'wpcal_availability_dates';
	$table_availability_periods = $wpdb->prefix . 'wpcal_availability_periods';
	$table_service_availability = $wpdb->prefix . 'wpcal_service_availability';

	wpcal_service_availability_slots_mark_refresh_cache($service_id);

	foreach ($save_data_array as $save_data_row) {
		$dates_insert_result = $wpdb->insert($table_availability_dates, $save_data_row);
		if ($dates_insert_result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}

		$availability_date_id = $wpdb->insert_id;
		if (!$availability_date_id) {
			throw new WPCal_Exception('db_error_insert_id_missing');
		}

		foreach ($save_periods as $save_period) {
			$save_period_final = wpcal_get_allowed_fields($save_period, $allowed_keys_periods);

			$save_period_final['availability_date_id'] = $availability_date_id;
			$period_insert_result = $wpdb->insert($table_availability_periods, $save_period_final);
			if ($period_insert_result === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			}
		}

		$link_service_availability = [
			'service_id' => $service_id,
			'availability_date_id' => $availability_date_id,
		];
		$link_insert_result = $wpdb->insert($table_service_availability, $link_service_availability);
		if ($link_insert_result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
	}

	return true;
}

function wpcal_get_periods_for_prefill_for_marking_available_by_date($service_id, $date) {
	$service_obj = wpcal_get_service($service_id);
	$availability_details_obj = new WPCal_Service_Availability_Details($service_obj);
	$date_obj = WPCal_DateTime_Helper::Date_DB_to_DateTime_obj($date, $service_obj->get_tz());
	$result = $availability_details_obj->get_availability_by_date_except_not_available_for_admin_client($date_obj);
	return $result;
}

function wpcal_update_service_status($status, $service_id) {
	global $wpdb;

	$allowed_status_change = [1, -1, -2];
	if (!in_array($status, $allowed_status_change)) {
		throw new WPCal_Exception('invalid_input_service_status');
	}

	$service_obj = wpcal_get_service($service_id);
	//can check current status before updating new status
	$service_current_status = $service_obj->get_status();
	if ($service_current_status == -2) { //already deleted
		throw new WPCal_Exception('invalid_request');
	}

	if ($status == 1) { //on enabling the service - make sure host id active
		$admin_user_id = $service_obj->get_owner_admin_id();
		$is_wpcal_admin_active = WPCal_Admins::is_wpcal_admin_active($admin_user_id);
		if (!$is_wpcal_admin_active) {
			throw new WPCal_Exception('host_admin_not_active_wpcal_or_wp', 'Host admin is not active in WPCal or WordPress.');
		}

		$make_a_free_way = WPCal_License::get_public();
		if ($make_a_free_way) {
			$_service_id = wpcal_process_get_active_service_ids();
			if (is_numeric($_service_id) && $_service_id != $service_id) {
				throw new WPCal_Exception('only_one_active_event_allowed_as_per_plan');
			}
		}
	}

	$table_service = $wpdb->prefix . 'wpcal_services';
	$data = ['status' => $status];
	$result = $wpdb->update($table_service, $data, array('id' => $service_id));
	if ($result === false) {
		return false;
	}
	return true;
}

function wpcal_delete_service($service_id) {
	return wpcal_update_service_status($status = -2, $service_id);
}

function wpcal_admin_page() {
	$is_requiements_met = true;
	$requirement_result = wpcal_check_min_version_requirements();

	if ($requirement_result !== true) {
		$is_requiements_met = false;
	}

	if ($is_requiements_met === true) {
		include WPCAL_PATH . '/templates/admin_page.php';
	} else {
		include WPCAL_PATH . '/templates/requirements_not_met.php';
	}
}

function wpcal_admin_test_page() {
	include WPCAL_PATH . '/includes/_test.php';
}

function wpcal_get_service($service_id) {
	$service_obj = new WPCal_Service($service_id);
	return $service_obj;
}

function wpcal_service_booking_shortcode_cb($attr) {
	$args = shortcode_atts(array(
		'id' => null,
		'service_id' => null,
	), $attr);

	if (!$args['id'] && !$args['service_id']) {
		return;
	}

	if (!empty($args['service_id'])) {
		$service_id = $args['service_id'];
	} elseif (!empty($args['id'])) {
		$service_id = $args['id'];
	} else {
		return;
	}

	$service_id = sanitize_text_field($service_id);

	if (empty($service_id)) {
		return;
	}

	$license_info = WPCal_License::get_account_info();
	if (empty($license_info) || !isset($license_info['email']) || !isset($license_info['status'])) {
		return;
	}

	try {
		$service_obj = wpcal_get_service($service_id);
	} catch (WPCal_Exception $e) {
		return;
	}

	$is_proper_obj = $service_obj instanceof WPCal_Service;
	if (!$is_proper_obj) {
		return;
	}

	$post_id = '';
	global $post;
	if (is_object($post)) {
		$post_id = $post->ID;
	}

	$branding_color_css = '';
	$setting_branding_color_hex = WPCal_General_Settings::get('branding_color');
	if ($setting_branding_color_hex) {
		$rgb_txt = wpcal_hex_to_rgb_txt($setting_branding_color_hex);
		if ($rgb_txt) {
			$branding_color_css = '
			#wpcal_user_app{
				--accentClrRGB: ' . $rgb_txt . '
			}
			';
		}
	}

	$js_set_timeout = 10;

	//check service is active and availability is still available
	/* Similar loading code in php client/src/assets/css/user_booking.css */
	// wpcal_load_trigger is fallback for popup which keep moving/insert or replace the html
	$return = '
	<script type="text/javascript">
	var wpcal_booking_service_id = "' . $service_id . '";
	var wpcal_post_id = "' . $post_id . '";
	try{
		var wpcal_load_trigger = new Event(\'wpcal_load_booking_widget\');

		if (/complete|interactive|loaded/.test(document.readyState)) {
			setTimeout(() => {
				document.dispatchEvent(wpcal_load_trigger);
			}, ' . $js_set_timeout . ');
		} else {
			document.addEventListener("DOMContentLoaded", () => {
				setTimeout(() => {
					document.dispatchEvent(wpcal_load_trigger);
				}, ' . $js_set_timeout . ');
			});
		}
	}
	catch(err){}
	</script>
	<style>
	@keyframes rotation {
		from {
		  -webkit-transform: rotate(0deg);
		}
		to {
		  -webkit-transform: rotate(359deg);
		}
	}
	' . $branding_color_css . '
	#wpcal_user_app.loading-indicator-initial {
		min-height: 60px;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	#wpcal_user_app.loading-indicator-initial::before {
		content: "";
		position: absolute;
		width: 20px;
		height: 20px;
		border-radius: 50%;
		border: 4px solid rgba(var(--accentClrRGB, 86, 123, 243), 1);
		border-top-color: #fff;
		background-color: #fff;
		box-shadow: 0 0 0px 3px #fff, 0 0 7px rgba(0, 0, 0, 0.9);
		z-index: 2;
		-webkit-animation: rotation 0.8s infinite linear;
		animation: rotation 0.8s infinite linear;
	}
	#wpcal_user_app.loading-indicator-initial::after {
		content: "";
		width: 90%;
		max-width: 700px;
		height: 450px;
		background-color: #fff;
		border-radius: 20px;
		box-shadow: 0 0 30px 0px rgb(153 165 189 / 20%);
		border: 1px solid #e9ecf2;
	}
	</style>
	';
	$return .= '<div id="wpcal_user_app" class="loading-indicator-initial"></div>';

	//following scripts and styles needed only if wpcal content page
	WPCal_User_Init::enqueue_styles();
	WPCal_User_Init::enqueue_scripts();
	return $return;
}

function wpcal_get_current_user_for_booking_in_user_client() {
	$_user_data = [];
	$_user_data['id'] = 0;
	if (is_user_logged_in()) {
		$user = wp_get_current_user();
		$_user_data['id'] = $user->ID;
		$_user_data['name'] = trim($user->user_firstname . ' ' . $user->user_lastname);
		$_user_data['email'] = $user->user_email;
		$_user_data['is_pre_fill_user_details'] = wpcal_is_pre_fill_user_details_for_current_admin();
		$_user_data['is_wpcal_admin'] = WPCal_Admins::is_current_user_is_wpcal_admin();
	}
	return $_user_data;
}

function wpcal_is_pre_fill_user_details_for_current_admin() {
	if (WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return false;
	}
	return true;
}

// This is commented no longer used
// function wpcal_get_service_admin_id($service_id){
// 	global $wpdb;
// 	$table_service_admins =  $wpdb->prefix . 'wpcal_service_admins';

// 	$query =  "SELECT `admin_user_id` FROM `$table_service_admins` WHERE `service_id` = '".$service_id."' ORDER BY `id` LIMIT 1";
// 	$admin_id = $wpdb->get_var($query);
// 	if( empty($admin_id) ){
// 		throw new WPCal_Exception('service_admin_user_id_missing');
// 	}
// 	return $admin_id;
// }

// This is commented no longer used
// function wpcal_get_service_admin_details($service_id){
// 	$admin_user_id = wpcal_get_service_admin_id($service_id);
// 	$admin_user_details = wpcal_get_admin_details($admin_user_id);
// 	return $admin_user_details;
// }

function wpcal_get_admin_details($admin_user_id) {
	$admin_user_data = [];
	$admin_user = get_user_by('id', $admin_user_id);
	if (!$admin_user) {
		//user not exists in WP - possibly deleted
		$wpcal_user_detail = WPCal_Admins::get_wpcal_admin($admin_user_id); //make sure not calling this(wpcal_get_admin_details) recursively
		if (!empty($wpcal_user_detail) && in_array($wpcal_user_detail->status, [1, -1])) {
			$admin_user_data['name'] = 'WP User id: ' . $admin_user_id . ' (Deleted)';
		}
		return $admin_user_data;
	}

	$admin_profile_obj = new WPCal_Admin_Profile_Settings($admin_user_id);
	$admin_profile = $admin_profile_obj->get_all(); //wpcal admin user profile

	$admin_avatar = wpcal_get_admin_avatar($admin_user_id);

	$name = trim($admin_profile['first_name'] . ' ' . $admin_profile['last_name']);
	$name = !empty($name) ? $name : $admin_profile['display_name'];

	$wp_name = trim($admin_user->first_name . ' ' . $admin_user->last_name);
	$wp_name = !empty($wp_name) ? $wp_name : $admin_user->display_name;

	$admin_user_data['id'] = (string) $admin_user->ID; //(string) all DB number are in string, in js this int not matching with string(int) in type to search
	$admin_user_data['username'] = $admin_user->user_login;
	$admin_user_data['first_name'] = $admin_profile['first_name'];
	$admin_user_data['last_name'] = $admin_profile['last_name'];
	$admin_user_data['email'] = $admin_user->user_email;
	$admin_user_data['name'] = $name;
	$admin_user_data['wp_name'] = $wp_name;
	$admin_user_data['display_name'] = $admin_profile['display_name'];
	$admin_user_data['profile_picture'] = isset($admin_avatar['url']) ? $admin_avatar['url'] : '';
	return $admin_user_data;
}

function wpcal_get_admin_details_of_current_admin() {
	$admin_user_id = get_current_user_id();

	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	$admin_user_details = wpcal_get_admin_details($admin_user_id);
	return $admin_user_details;
}

function wpcal_get_admin_profile_settings($admin_user_id) {

	$admin_profile_obj = new WPCal_Admin_Profile_Settings($admin_user_id);
	$settings = $admin_profile_obj->get_all();
	return $settings;
}

function wpcal_get_admin_profile_settings_of_current_admin() {
	$admin_user_id = get_current_user_id();
	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}
	return wpcal_get_admin_profile_settings($admin_user_id);
}

function wpcal_update_admin_profile_settings($admin_user_id, $profile_settings) {

	$admin_profile_obj = new WPCal_Admin_Profile_Settings($admin_user_id);
	$result = $admin_profile_obj->update_all($profile_settings);
	return $result;
}

function wpcal_update_admin_profile_settings_of_current_admin($profile_settings) {
	$admin_user_id = get_current_user_id();
	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	return wpcal_update_admin_profile_settings($admin_user_id, $profile_settings);
}

function wpcal_get_admin_settings($admin_user_id) {

	$admin_setting_obj = new WPCal_Admin_Settings($admin_user_id);
	$settings = $admin_setting_obj->get_all();
	return $settings;
}

function wpcal_get_admin_settings_of_current_admin() {
	$admin_user_id = get_current_user_id();
	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}
	return wpcal_get_admin_settings($admin_user_id);
}

function wpcal_update_admin_settings($admin_user_id, $admin_settings) {

	$admin_setting_obj = new WPCal_Admin_Settings($admin_user_id);
	$result = $admin_setting_obj->update_all($admin_settings);
	return $result;
}

function wpcal_update_admin_settings_of_current_admin($admin_settings) {
	$admin_user_id = get_current_user_id();
	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	return wpcal_update_admin_settings($admin_user_id, $admin_settings);
}

function wpcal_get_admin_avatar($admin_user_id, $override_attachment_id = null) {

	$result_avatar = [];

	$admin_profile_obj = new WPCal_Admin_Profile_Settings($admin_user_id);
	$attachment_id = $admin_profile_obj->get('avatar_attachment_id');
	if ($override_attachment_id !== null) {
		$attachment_id = $override_attachment_id;
	}
	if (!empty($attachment_id)) {
		$result_avatar = wpcal_get_admin_avatar_attachment($attachment_id);
	}
	if (empty($result_avatar) || empty($result_avatar['url'])) { //fallback
		$result_avatar = wpcal_get_admin_avatar_from_gravatar($admin_user_id);
	}
	return $result_avatar;
}

function wpcal_get_admin_avatar_of_current_admin($override_attachment_id = null) {
	$admin_user_id = get_current_user_id();

	return wpcal_get_admin_avatar($admin_user_id, $override_attachment_id);
}

function wpcal_get_admin_avatar_attachment($attachment_id) {
	$_img = wp_get_attachment_image_src($attachment_id, 'wpcal-admin-avatar');
	if ($_img === false) {
		//handle error
		return false;
	}
	$img = [
		'url' => $_img[0],
		'width' => $_img[1],
		'height' => $_img[2],
		'is_resized' => $_img[3],
		'is_gravatar' => false,
	];
	return $img;
}

function wpcal_get_admin_avatar_from_gravatar($admin_user_id) {
	$avatar = get_avatar_data($admin_user_id, array('size' => 75));
	$img = [
		'url' => $avatar['url'],
		'width' => $avatar['width'],
		'height' => $avatar['height'],
		'is_gravatar' => true,
		'found_avatar' => $avatar['found_avatar'],
	];
	return $img;
}

/**
 * If profile picture(avatar) WPCal required size(thumbnail) is missing for an attachment,
 * all the sizes for the same attachment will be regenerated in this process.
 */
function wpcal_may_generate_avatar_attachment($attachment_id) {
	// This will regenerate all the thumbnails for registered sizes for the attachment
	// Depends on number of images sizes registered. This process may take more CPU and Memory, as well as time
	// add_image_size('wpcal-admin-avatar', 75, 75, true); need to be called before, in add_action('after_setup_theme')

	if (empty($attachment_id)) {
		return false;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$required_image_sizes = ['wpcal-admin-avatar'];

	$attachment = get_post($attachment_id);
	if (!$attachment) {
		return false;
	}

	$mime_type = get_post_mime_type($attachment);
	if (!preg_match('!^image/!', $mime_type)) {
		return false;
	}

	$old_meta = wp_get_attachment_metadata($attachment_id);
	if (!is_array($old_meta)) {
		$old_meta = [];
	}
	$generate_required = false;
	foreach ($required_image_sizes as $image_size) {
		if (empty($old_meta['sizes'][$image_size])) {
			$generate_required = true;
			break;
		}
	}
	if (!$generate_required) {
		return true;
	}

	$file = get_attached_file($attachment_id);
	if (!$file) {
		return false;
	}
	$new_meta = wp_generate_attachment_metadata($attachment_id, $file);
	$meta = array_merge($old_meta, $new_meta);

	return wp_update_attachment_metadata($attachment_id, $meta);
}

function wpcal_may_save_admin_profile_settings_from_wp_user() {

}

function wpcal_get_wp_admins_to_add() {
	$role__in = [];
	foreach (wp_roles()->roles as $role_slug => $role) {
		if (!empty($role['capabilities']['edit_posts'])) {
			$role__in[] = $role_slug;
		}
	}

	$exclude = WPCal_Admins::get_all_admins();

	if (empty($role__in)) {
		return []; //safer side
	}

	$users = get_users([
		'role__in' => $role__in,
		'fields' => ['ID', 'display_name', 'user_login', 'user_email'],
		'exclude' => $exclude,
		'orderby' => 'display_name', 'order' => 'DESC',
	]);

	foreach ($users as &$user) {
		$img = wpcal_get_admin_avatar_from_gravatar($user->ID);
		$user->profile_picture = $img['url'];
	}
	return $users;
}

function wpcal_get_notices_for_current_admin() {
	$admin_user_id = get_current_user_id();

	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	$admin_notices = get_user_meta($admin_user_id, 'wpcal_admin_notices', true);
	if (empty($admin_notices)) {
		$admin_notices = [];
	}

	if (!isset($admin_notices['onboarding_checklist'])) {
		$default_onboarding_checklist_notice = [
			'status' => "pending",
			'details' => [
				'create_or_edit_service' => "pending",
				'add_calendar_account' => "pending",
				'add_meeting_app' => "pending",
				'test_booking' => "pending",
				'add_wpcal_admin' => "pending",
			],
		];

		$admin_notices['onboarding_checklist'] = $default_onboarding_checklist_notice;
	}

	return $admin_notices;
}

function wpcal_update_notices_for_current_admin($options) {
	//need validation Improve later
	$options = wpcal_sanitize_all($options);

	$admin_notices = wpcal_get_notices_for_current_admin();
	$final_admin_notices = array_merge($admin_notices, $options);

	$admin_user_id = get_current_user_id();

	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	if (!empty($final_admin_notices['onboarding_checklist']) && !empty($final_admin_notices['onboarding_checklist']['details'])) {
		$is_all_done = true;
		foreach ($final_admin_notices['onboarding_checklist']['details'] as $todo_status) {
			if ($todo_status !== 'dismissed' && $todo_status !== 'completed') {
				$is_all_done = false;
				break;
			}
		}
		if ($is_all_done && $final_admin_notices['onboarding_checklist']['status'] !== 'dismissed') {
			$final_admin_notices['onboarding_checklist']['status'] = 'dismissed';
		}
	}

	update_user_meta($admin_user_id, 'wpcal_admin_notices', $final_admin_notices); //if no change it will returen false

	return true;
}

function wpcal_mark_onboarding_checklist_completed_for_current_admin($task) {
	$admin_user_id = get_current_user_id();
	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return;
	}

	if (!in_array($task, ['create_or_edit_service', 'add_calendar_account', 'add_meeting_app', 'test_booking', 'add_wpcal_admin'])) {
		//invalid task
		return;
	}

	$_admin_notices = wpcal_get_notices_for_current_admin();

	if (!isset($_admin_notices['onboarding_checklist'])) {
		//no onboarding checklist
		return;
	}
	$admin_notices = [
		'onboarding_checklist' => $_admin_notices['onboarding_checklist'],
	];

	if (!isset($admin_notices['onboarding_checklist']['details'][$task])) {
		//no task details
		return;
	}

	$old_task_status = $admin_notices['onboarding_checklist']['details'][$task];

	if (in_array($old_task_status, ['completed', 'dismissed'])) {
		//task already done
		return;
	}
	$admin_notices['onboarding_checklist']['details'][$task] = 'completed';
	wpcal_update_notices_for_current_admin($admin_notices);
}

function wpcal_dismiss_onboarding_checklist_for_current_admin() {
	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return;
	}

	$_admin_notices = wpcal_get_notices_for_current_admin();

	if (!isset($_admin_notices['onboarding_checklist'])) {
		//no onboarding checklist
		return;
	}
	$admin_notices = [
		'onboarding_checklist' => $_admin_notices['onboarding_checklist'],
	];

	$old_task_status = $admin_notices['onboarding_checklist']['status'];

	if (in_array($old_task_status, ['completed', 'dismissed'])) {
		//task already done
		return;
	}
	$admin_notices['onboarding_checklist']['status'] = 'dismissed';
	wpcal_update_notices_for_current_admin($admin_notices);
}

function wpcal_add_booking($input_data, $old_booking_id = null) {
	global $wpdb;

	$allowed_keys = [
		'service_id',
		//'unique_link',
		//'admin_user_id',
		//'invitee_wp_user_id',
		'invitee_name',
		'invitee_email',
		'invitee_question_answers',
		'invitee_tz',
		//'booking_from_time',
		//'booking_to_time',
		//'booking_ip',
		'booking_slot',
		'location',
		'booking_page_current_url',
		'booking_page_post_id',
	];

	//$default_data = [];

	//$data = array_merge($default_data, $input_data);

	$data = wpcal_get_allowed_fields($input_data, $allowed_keys);

	$invitee_question_answers_sanitize_rules = [];
	if (!empty($data['invitee_question_answers']) && is_array($data['invitee_question_answers'])) {
		foreach ($data['invitee_question_answers'] as $_key => $_question_answers) {

			$invitee_question_answers_sanitize_rules[$_key] = ['question' => 'sanitize_textarea_field'];

			if (!empty($_question_answers['answer_type']) && $_question_answers['answer_type'] === 'textarea') {
				$invitee_question_answers_sanitize_rules[$_key]['answer'] = 'sanitize_textarea_field';
			}
		}
	}

	$sanitize_rules = [
		'invitee_email' => 'sanitize_email',
		// 'invitee_question_answers' => [
		// 	'*' => [
		// 		'question' => 'sanitize_textarea_field',
		// 		'answer' => 'sanitize_textarea_field'
		// 	]
		// ],
		'invitee_question_answers' => $invitee_question_answers_sanitize_rules,
		'location' => [
			'form' => ['location_extra' => 'sanitize_textarea_field'],
		],
	];

	$data = wpcal_sanitize_all($data, $sanitize_rules);

	$validate_obj = new WPCal_Validate($data);
	$validation_rules = [
		'required' => [
			'service_id',
			'invitee_name',
			'invitee_email',
			'booking_slot',
		],
		'email' => [
			['invitee_email'],
		],
		'integer' => [
			'service_id',
		],
		'dateFormat' => [
			['booking_slot.from_time', 'U'],
			['booking_slot.to_time', 'U'],
		],
		'arrayHasKeys' => [
			['invitee_question_answers.*', ['question', 'answer', 'is_required']],
			['location', ['type']],
		],
		'in' => [
			['location.type', ['physical', 'phone', 'googlemeet_meeting', 'zoom_meeting', 'gotomeeting_meeting', 'custom', 'ask_invitee']],
			['location.form.who_calls', ['admin', 'invitee']],
		],
		//following commented because not working, NEED TO IMPROVE
		// 'requiredWithIf' => [
		// 	['invitee_question_answers.*.answer', ['invitee_question_answers.*.is_required' => '1']]
		// ],
	];

	if (!empty($data['location']['type'])) {
		if (
			$data['location']['type'] === 'ask_invitee'
			||
			(
				$data['location']['type'] === 'phone' &&
				!empty($data['location']['form']['who_calls']) &&
				$data['location']['form']['who_calls'] === 'admin'
			)
		) {
			$validation_rules['required'][] = 'location.form.location';
		}

		if ($data['location']['type'] === 'ask_invitee') {
			$validation_rules['lengthMax'][] = ['location.form.location', 500];
		}
	}

	$validate_obj->rules($validation_rules);

	if (!$validate_obj->validate()) {
		$validation_errors = $validate_obj->errors();
		throw new WPCal_Exception('validation_errors', '', $validation_errors);
	}

	$old_booking_obj = null;
	if (!empty($old_booking_id)) {
		$old_booking_obj = wpcal_get_booking($old_booking_id);
	}

	$invitee_wp_user_id = null;

	$_current_user_id = get_current_user_id();
	if ($_current_user_id &&
		($old_booking_obj == null ||
			(
				$old_booking_obj && empty($old_booking_obj->get_invitee_wp_user_id())
			)
		)
	) {
		if (WPCal_Admins::is_wpcal_admin_active($_current_user_id)) {
			$_current_admin_details = wpcal_get_admin_details($_current_user_id);
			if ($data['invitee_email'] == $_current_admin_details['email']) {
				$invitee_wp_user_id = $_current_user_id;
			}
		} else {
			$invitee_wp_user_id = $_current_user_id;
		}
	}

	if ($old_booking_obj && $old_booking_obj->get_invitee_wp_user_id()) {
		$invitee_wp_user_id = $old_booking_obj->get_invitee_wp_user_id();
	}

	if (!empty($data['booking_page_current_url'])) {
		$tmp_booking_page_current_url = explode('#', $data['booking_page_current_url']);
		$data['booking_page_current_url'] = $tmp_booking_page_current_url[0];
		unset($tmp_booking_page_current_url);
	}
	$page_used_for_booking = [
		'url' => !empty($data['booking_page_current_url']) ? $data['booking_page_current_url'] : '',
		'post_id' => !empty($data['booking_page_post_id']) ? $data['booking_page_post_id'] : '',
	];
	$page_used_for_booking = array_filter($page_used_for_booking);

	$row_data = $data;
	unset($row_data['booking_slot']);
	unset($row_data['booking_page_current_url']);
	unset($row_data['booking_page_post_id']);

	$service_id = $data['service_id'];
	$service_obj = wpcal_get_service($service_id);

	$row_data['status'] = 1;
	$row_data['admin_user_id'] = $service_obj->get_owner_admin_id();
	$row_data['invitee_wp_user_id'] = $invitee_wp_user_id;
	$row_data['booking_ip'] = $_SERVER['REMOTE_ADDR'];
	$row_data['added_ts'] = time();
	$row_data['updated_ts'] = time();
	$row_data['booking_from_time'] = $data['booking_slot']['from_time'];
	$row_data['booking_to_time'] = $data['booking_slot']['to_time'];

	if (!empty($page_used_for_booking)) {
		$row_data['page_used_for_booking'] = json_encode($page_used_for_booking);
	}

	if (!empty($row_data['invitee_question_answers'])) {
		$row_data['invitee_question_answers'] = json_encode($row_data['invitee_question_answers']);
	}

	if (empty($row_data['location']) || !is_array($row_data['location'])) {
		$row_data['location'] = [];
	}
	$row_data['location'] = wpcal_get_allowed_fields($row_data['location'], ['type', 'form']);
	if (isset($row_data['location']['form']) && is_array($row_data['location']['form'])) {
		$row_data['location']['form'] = wpcal_get_allowed_fields($row_data['location']['form'], ['location', 'location_extra', 'who_calls']);
	}
	if (!empty($row_data['location'])) {
		$row_data['location'] = json_encode($row_data['location']);
	} else {
		$row_data['location'] = null;
	}

	//Validate the slot availability and service is active

	if ($old_booking_obj == null && !$service_obj->is_new_booking_allowed()) {
		throw new WPCal_Exception('service_new_booking_not_allowed');
	} else if ($old_booking_obj != null && !$service_obj->is_reschedule_booking_allowed()) {
		throw new WPCal_Exception('service_reschedule_booking_not_allowed');
	}

	$service_availability_slots_obj = new WPCal_Service_Availability_Slots($service_obj);

	$booking_date = WPCal_DateTime_Helper::unix_to_DateTime_obj($row_data['booking_from_time'], $service_obj->get_tz());
	$booking_date->setTime(0, 0);

	$is_max_booking_per_day_reached = $service_availability_slots_obj->is_max_booking_per_day_reached(clone $booking_date);

	if ($is_max_booking_per_day_reached) {
		throw new WPCal_Exception('service_max_booking_per_day_reached');
	}

	$is_slot_still_available = $service_availability_slots_obj->is_slot_still_available($data['booking_slot']);

	if (!$is_slot_still_available) {
		throw new WPCal_Exception('service_booking_slot_not_avaialble');
	}

	$table_bookings = $wpdb->prefix . 'wpcal_bookings';

	$result = $wpdb->insert($table_bookings, $row_data);
	if ($result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	$booking_id = $wpdb->insert_id;
	if (!$booking_id) {
		throw new WPCal_Exception('db_error_insert_id_missing');
	}

	$service_admin_user_id = $service_obj->get_owner_admin_id();
	wpcal_service_availability_slots_mark_refresh_cache_by_admin($service_admin_user_id);
	wpcal_booking_assign_unique_link($booking_id);

	$booking_obj = wpcal_get_booking($booking_id);
	$booking_action = 'new';

	if (!empty($old_booking_obj)) {
		$booking_action = 'reschedule';
		wpcal_update_new_booking_id_in_rescheduled_booking($old_booking_obj, $booking_obj);
		$booking_obj = new WPCal_Booking($booking_obj->get_id()); //load new data

		wpcal_may_reuse_resources_of_old_booking_on_reschedule($old_booking_obj, $booking_obj);

		$booking_obj = new WPCal_Booking($booking_obj->get_id()); //load new data

		wpcal_after_cancel_booking_add_background_tasks($old_booking_obj, 'reschedule', $booking_obj);
	}

	wpcal_after_add_booking_add_background_tasks($booking_obj, $booking_action);

	wpcal_mark_onboarding_checklist_completed_for_current_admin('test_booking');

	return $booking_id;
}

function wpcal_booking_assign_unique_link($booking_id) { //its as unique string will be used in the link
	global $wpdb;
	$table_bookings = $wpdb->prefix . 'wpcal_bookings';

	$query = "SELECT * FROM `$table_bookings` WHERE `id` = %s";
	$query = $wpdb->prepare($query, $booking_id);
	$booking_data = $wpdb->get_row($query, ARRAY_A);
	if (empty($booking_data)) {
		throw new WPCal_Exception('invalid_booking_id');
	}
	if (!empty($booking_data['unique_link'])) {
		//invalid unique_link already assigned
		return false;
	}

	$i = 0;
	while ($i < 100) {
		$unique_link = sha1(implode('|', array($booking_data['id'], $booking_data['service_id'], $booking_data['booking_from_time'], uniqid('', true))));

		$query2 = "SELECT `id` FROM `$table_bookings` WHERE `unique_link` = %s";
		$query2 = $wpdb->prepare($query2, $unique_link);
		$same_unique_link_data = $wpdb->get_row($query2);
		if (empty($same_unique_link_data)) {
			break;
		}
		$i++;
		if ($i >= 100) {
			$unique_link = '';
			throw new WPCal_Exception('booking_unable_to_find_unique_link');
		}
	}

	if (empty($unique_link)) {
		throw new WPCal_Exception('booking_unique_link_missing');
	}

	$update_result = $wpdb->update($table_bookings, array('unique_link' => $unique_link), array('id' => $booking_data['id']));

	if ($update_result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}
	return true;
}

function wpcal_may_reuse_resources_of_old_booking_on_reschedule(WPCal_Booking $old_booking_obj, WPCal_Booking $new_booking_obj) {
	global $wpdb;

	$is_old_and_new_booking_having_different_admins = $new_booking_obj->is_old_rescheduled_and_new_booking_having_different_admins();

	if ($is_old_and_new_booking_having_different_admins) {
		return false;
	}

	$row_data = [];

	$add_bookings_to_calendar = wpcal_get_add_bookings_to_calendar_by_admin($new_booking_obj->get_admin_user_id());

	if (!empty($add_bookings_to_calendar)) {

		$can_reuse_same_calendar_event_id_on_reschedule = wpcal_can_reuse_same_calendar_event_id_on_reschedule($old_booking_obj, $new_booking_obj, $add_bookings_to_calendar);

		if ($can_reuse_same_calendar_event_id_on_reschedule) {
			$row_data['event_added_calendar_provider'] = $old_booking_obj->get_event_added_calendar_provider();
			$row_data['event_added_calendar_id'] = $add_bookings_to_calendar->calendar_id; //let it be dynamic, if calendar readded
			$row_data['event_added_tp_cal_id'] = $old_booking_obj->get_event_added_tp_cal_id();
			$row_data['event_added_tp_event_id'] = $old_booking_obj->get_event_added_tp_event_id();
		}
	}

	$can_reuse_same_meeting_tp_resource_id_on_reschedule = wpcal_can_reuse_same_meeting_tp_resource_id_on_reschedule($old_booking_obj, $new_booking_obj, $add_bookings_to_calendar);
	if (
		$can_reuse_same_meeting_tp_resource_id_on_reschedule
	) {
		$row_data['meeting_tp_resource_id'] = $old_booking_obj->get_meeting_tp_resource_id();
	}

	if (empty($row_data)) {
		return false;
	}

	$table_bookings = $wpdb->prefix . 'wpcal_bookings';
	$result = $wpdb->update($table_bookings, $row_data, array('id' => $new_booking_obj->get_id()));

	if ($result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	return $result ? true : false;
}

function wpcal_can_reuse_same_calendar_event_id_on_reschedule(WPCal_Booking $old_booking_obj, WPCal_Booking $new_booking_obj, $add_bookings_to_calendar = null) {

	$is_old_and_new_booking_having_different_admins = $new_booking_obj->is_old_rescheduled_and_new_booking_having_different_admins();

	if ($is_old_and_new_booking_having_different_admins) {
		return false;
	}

	if ($add_bookings_to_calendar === null) {
		$new_booking_admin_user_id = $new_booking_obj->get_admin_user_id();

		$add_bookings_to_calendar = wpcal_get_add_bookings_to_calendar_by_admin($new_booking_admin_user_id);
	}

	if (
		empty($add_bookings_to_calendar) ||
		$add_bookings_to_calendar->provider != $old_booking_obj->get_event_added_calendar_provider()
	) {
		return false;
	}

	if ($add_bookings_to_calendar->calendar_id != $old_booking_obj->get_event_added_calendar_id()) {
		if ($add_bookings_to_calendar->tp_cal_id != $old_booking_obj->get_event_added_tp_cal_id()) {
			return false;
		}
	}
	return true;
}

function wpcal_can_reuse_same_meeting_tp_resource_id_on_reschedule(WPCal_Booking $old_booking_obj, WPCal_Booking $new_booking_obj, $add_bookings_to_calendar = null) {

	$is_old_and_new_booking_having_different_admins = $new_booking_obj->is_old_rescheduled_and_new_booking_having_different_admins();

	if ($is_old_and_new_booking_having_different_admins) {
		return false;
	}

	//$old_booking_obj->get_location_type() == 'googlemeet_meeting' should be taken care by wpcal_can_reuse_same_calendar_event_id_on_reschedule(indirectly) NOT HERE

	if (
		!$new_booking_obj->is_location_needs_tp_account_service() ||
		!$old_booking_obj->is_location_needs_tp_account_service() ||
		empty($old_booking_obj->get_meeting_tp_resource_id()) ||
		$old_booking_obj->get_location_type() !== $new_booking_obj->get_location_type() ||
		$old_booking_obj->get_location_type() == 'googlemeet_meeting'
	) {
		return false;
	}

	// 'googlemeet_meeting' not required here, it depends on add booking to calendar only, currently this is not checked because above empty($old_booking_obj->get_meeting_tp_resource_id()) then return check.

	// Following code commented as no longer used

	// if ($old_booking_obj->get_location_type() == 'googlemeet_meeting') {
	// 	if ($add_bookings_to_calendar === null) {
	// 		$new_booking_admin_user_id = $new_booking_obj->get_admin_user_id();

	// 		$add_bookings_to_calendar = wpcal_get_add_bookings_to_calendar_by_admin($new_booking_admin_user_id);
	// 	}
	// 	if (empty($add_bookings_to_calendar)) {
	// 		return false;
	// 	}

	// 	if (
	// 		$add_bookings_to_calendar->tp_cal_id &&
	// 		$add_bookings_to_calendar->tp_cal_id != $old_booking_obj->get_event_added_tp_cal_id()
	// 	) {
	// 		return false;
	// 	}
	// } else {

	$provider = $new_booking_obj->get_location_type(); //
	$tp_account = wpcal_get_tp_account_by_admin_and_provider($new_booking_obj->get_admin_user_id(), $provider);

	if (empty($tp_account)) {
		return false;
	}

	$old_meeting_tp_resource_id = $old_booking_obj->get_meeting_tp_resource_id();
	$old_tp_resource_obj = new WPCal_TP_Resource($old_meeting_tp_resource_id);

	if (
		$tp_account->id != $old_tp_resource_obj->get_tp_account_id()
	) {
		if (
			$tp_account->tp_user_id != $old_tp_resource_obj->get_tp_user_id()
		) {
			return false;
		}
	}

	// }

	return true;
}

function wpcal_after_add_booking_add_background_tasks(WPCal_Booking $booking_obj, $booking_action) {

	if ($booking_action !== 'new' && $booking_action !== 'reschedule') {
		throw new WPCal_Exception('invalid_action');
	}

	$to_time_obj = $booking_obj->get_booking_to_time();
	$expiry_ts = WPCal_DateTime_Helper::DateTime_Obj_to_unix($to_time_obj) + WPCAL_ADD_BOOKING_BG_TASK_RELATIVE_EXPIRY; // N seconds after booking "to" time
	$admin_user_id = $booking_obj->get_admin_user_id();
	if (empty($admin_user_id)) {
		throw new WPCal_Exception('booking_admin_user_id_missing');
	}

	$dependant_task_id = null;

	$is_location_needs_tp_account_service = $booking_obj->is_location_needs_tp_account_service();
	if ($is_location_needs_tp_account_service) {

		$provider = $booking_obj->get_location_type();

		$tp_account = wpcal_get_active_tp_account_by_admin_and_provider($admin_user_id, $provider);

		if (!empty($tp_account)) {
			$task_details = [
				'task_name' => 'add_or_update_online_meeting_for_booking',
				'main_arg_name' => 'booking_id',
				'main_arg_value' => $booking_obj->get_id(),
				'expiry_ts' => $expiry_ts,
			];
			$added_task_id = WPCal_Background_Tasks::add_task($task_details);
			if ($added_task_id) {
				$dependant_task_id = $added_task_id;
			}
		}

	}

	$cal_details = wpcal_get_active_add_bookings_to_calendar_by_admin($admin_user_id);

	if (!empty($cal_details)) {
		$task_details = [
			'task_name' => 'add_or_update_booking_to_tp_calendar',
			'main_arg_name' => 'booking_id',
			'main_arg_value' => $booking_obj->get_id(),
			'expiry_ts' => $expiry_ts,
			'dependant_id' => $dependant_task_id,
		];
		$added_task_id = WPCal_Background_Tasks::add_task($task_details);
		if ($cal_details->provider === 'google_calendar' && $booking_obj->get_location_type() === 'googlemeet_meeting') {

			$dependant_task_id = $added_task_id;

			$task_details = [
				'task_name' => 'get_and_set_meeting_url_from_google_calendar',
				'main_arg_name' => 'booking_id',
				'main_arg_value' => $booking_obj->get_id(),
				'expiry_ts' => $expiry_ts,
				'dependant_id' => $dependant_task_id,
			];
			$added_task_id = WPCal_Background_Tasks::add_task($task_details);
			$dependant_task_id = $added_task_id;
		}
	}

	if (empty($cal_details) || $booking_obj->service_obj->is_invitee_notify_by_email() || ($booking_action === 'reschedule' && $booking_obj->get_old_resceduled_booking_reason())) {

		$task_name = 'send_invitee_booking_confirmation_mail';
		if ($booking_action === 'reschedule') {
			$task_name = 'send_invitee_reschedule_booking_confirmation_mail';
		}

		$task_details = [
			'task_name' => $task_name,
			'main_arg_name' => 'booking_id',
			'main_arg_value' => $booking_obj->get_id(),
			'expiry_ts' => $expiry_ts,
			'dependant_id' => $dependant_task_id,
		];
		WPCal_Background_Tasks::add_task($task_details);
	}

	$task_details = [
		'task_name' => 'schedule_invitee_booking_reminder_mail',
		'main_arg_name' => 'booking_id',
		'main_arg_value' => $booking_obj->get_id(),
		'expiry_ts' => $expiry_ts,
		'dependant_id' => $dependant_task_id,
	];
	WPCal_Background_Tasks::add_task($task_details);

	$task_name = 'send_admin_new_booking_info_mail';
	if ($booking_action === 'reschedule' && !$booking_obj->is_old_rescheduled_and_new_booking_having_different_admins()) {
		$task_name = 'send_admin_reschedule_booking_info_mail';
	}
	$task_details = [
		'task_name' => $task_name,
		'main_arg_name' => 'booking_id',
		'main_arg_value' => $booking_obj->get_id(),
		'expiry_ts' => $expiry_ts,
		'dependant_id' => $dependant_task_id,
	];
	WPCal_Background_Tasks::add_task($task_details);
}

function wpcal_after_cancel_booking_add_background_tasks(WPCal_Booking $booking_obj, $booking_action, WPCal_Booking $new_booking_obj = null) {

	if ($booking_action !== 'cancel' && $booking_action !== 'reschedule') {
		throw new WPCal_Exception('invalid_action');
	}

	if ($booking_action == 'reschedule' && empty($new_booking_obj)) {
		throw new WPCal_Exception('invalid_input');
	}

	$to_time_obj = $booking_obj->get_booking_to_time();
	$expiry_ts = WPCal_DateTime_Helper::DateTime_Obj_to_unix($to_time_obj) + WPCAL_CANCEL_BOOKING_BG_TASK_RELATIVE_EXPIRY; // N seconds after booking "to" time

	$admin_user_id = $booking_obj->get_admin_user_id();
	if (empty($admin_user_id)) {
		throw new WPCal_Exception('booking_admin_user_id_missing');
	}

	$meeting_tp_resource_id = $booking_obj->get_meeting_tp_resource_id();
	if (
		!empty($meeting_tp_resource_id) &&
		(
			$booking_action === 'cancel' ||
			(
				$booking_action === 'reschedule' && !wpcal_can_reuse_same_meeting_tp_resource_id_on_reschedule($booking_obj, $new_booking_obj)
			)
		)
	) {
		$task_details = [
			'task_name' => 'delete_online_meeting_for_booking',
			'main_arg_name' => 'booking_id',
			'main_arg_value' => $booking_obj->get_id(),
			'expiry_ts' => $expiry_ts,
		];
		WPCal_Background_Tasks::add_task($task_details);
	}

	$event_added_tp_event_id = $booking_obj->get_event_added_tp_event_id();

	if (!empty($event_added_tp_event_id)) {
		if (
			$booking_action === 'cancel' ||
			(
				$booking_action === 'reschedule' && !wpcal_can_reuse_same_calendar_event_id_on_reschedule($booking_obj, $new_booking_obj)
			)
		) {
			$task_details = [
				'task_name' => 'delete_booking_to_tp_calendar',
				'main_arg_name' => 'booking_id',
				'main_arg_value' => $booking_obj->get_id(),
				'expiry_ts' => $expiry_ts,
			];
			WPCal_Background_Tasks::add_task($task_details);
		}
	}

	if (empty($event_added_tp_event_id) || $booking_obj->service_obj->is_invitee_notify_by_email() || $booking_obj->get_reschedule_cancel_reason()) {
		if ($booking_action === 'cancel') {
			$task_details = [
				'task_name' => 'send_invitee_booking_cancellation_mail',
				'main_arg_name' => 'booking_id',
				'main_arg_value' => $booking_obj->get_id(),
				'expiry_ts' => $expiry_ts,
			];
			WPCal_Background_Tasks::add_task($task_details);
		}
	}

	$task_details = [
		'task_name' => 'delete_scheduled_invitee_booking_reminder_mail',
		'main_arg_name' => 'booking_id',
		'main_arg_value' => $booking_obj->get_id(),
		'expiry_ts' => $expiry_ts,
	];
	WPCal_Background_Tasks::add_task($task_details);

	if (
		$booking_action === 'cancel' ||
		(
			$booking_action === 'reschedule' && $new_booking_obj->is_old_rescheduled_and_new_booking_having_different_admins()
		)
	) {
		$task_details = [
			'task_name' => 'send_admin_booking_cancellation_mail',
			'main_arg_name' => 'booking_id',
			'main_arg_value' => $booking_obj->get_id(),
			'expiry_ts' => $expiry_ts,
		];
		WPCal_Background_Tasks::add_task($task_details);
	}
}

// function wpcal_booking_may_run_add_or_update_online_meeting_task(WPCal_Booking $booking_obj){
// 	$is_location_needs_tp_account_service = $booking_obj->is_location_needs_tp_account_service();
// 	if($is_location_needs_tp_account_service){
// 		WPCal_Background_Tasks::run_task_by_task_and_main_args('add_or_update_online_meeting_for_booking', 'booking_id', $booking_obj->get_id());
// 	}
// }

function wpcal_get_booking($booking) {
	if ($booking instanceof WPCal_Booking) {
		return $booking;
	} else {
		$booking_obj = new WPCal_Booking($booking);
		return $booking_obj;
	}
}

function wpcal_get_booking_by_unique_link($unique_link) {
	global $wpdb;
	$table_bookings = $wpdb->prefix . 'wpcal_bookings';

	$query2 = "SELECT `id` FROM `$table_bookings` WHERE `unique_link` = %s";
	$query2 = $wpdb->prepare($query2, $unique_link);
	$booking_id = $wpdb->get_var($query2);
	if ($booking_id) {
		return wpcal_get_booking($booking_id);
	}
	throw new WPCal_Exception('booking_unable_to_get_booking_id');
}

function wpcal_get_booking_unique_link_by_id($booking_id) {
	$booking_obj = wpcal_get_booking($booking_id);
	$link = $booking_obj->get_unique_link();
	return $link;
}

function wpcal_service_availability_slots_mark_refresh_cache($service_id, $do = 'on') {
	global $wpdb;
	$table_service = $wpdb->prefix . 'wpcal_services';

	if ($do === 'on') {
		$refresh_cache = '1';
	} elseif ($do === 'off') {
		$refresh_cache = '0';
	} else {
		throw new WPCal_Exception('invalid_input');
	}

	$result = $wpdb->update($table_service, array('refresh_cache' => $refresh_cache), array('id' => $service_id));

	return $result;
}

function wpcal_service_availability_slots_mark_refresh_cache_for_all() {
	global $wpdb;
	$table_service = $wpdb->prefix . 'wpcal_services';

	$refresh_cache = '1';

	$result = $wpdb->update($table_service, array('refresh_cache' => $refresh_cache), array('refresh_cache' => 0));

	return $result;
}

function wpcal_service_availability_slots_mark_refresh_cache_by_admin($admin_user_id, $do = 'on') {
	$services = wpcal_get_services_by_admin($admin_user_id);
	if (empty($services)) {
		return true;
	}

	foreach ($services as $service) {
		wpcal_service_availability_slots_mark_refresh_cache($service->id, $do);
	}
	return true;
}

function wpcal_service_availability_slots_mark_refresh_cache_for_current_admin($do = 'on') {
	$admin_user_id = get_current_user_id();
	wpcal_service_availability_slots_mark_refresh_cache_by_admin($admin_user_id, $do);
	return true;
}

function wpcal_on_wp_setting_timezone_changes() {
	wpcal_service_availability_slots_mark_refresh_cache_for_all();
}

function wpcal_cancel_booking($booking, $cancel_reason = null, $cancel_type = null) {
	global $wpdb;

	$booking_obj = wpcal_get_booking($booking);

	if (!$booking_obj->is_active()) {
		throw new WPCal_Exception('booking_is_not_active');
	}

	//check service is cancellable
	$service_id = $booking_obj->get_service_id();
	$service_obj = wpcal_get_service($service_id);
	if (!$service_obj->is_cancellation_allowed()) {
		throw new WPCal_Exception('booking_cancellation_not_allowed');
	}

	$table_bookings = $wpdb->prefix . 'wpcal_bookings';

	$cancel_status = -1;
	if ($cancel_type === 'reschedule') {
		$cancel_status = -5;
	} elseif ($cancel_type === 'invitee_cancel_via_tp_cal') {
		$cancel_status = -2;
	}

	$current_user_id = get_current_user_id();
	$action_time = time();

	$update_data = [
		'status' => $cancel_status,
		'reschedule_cancel_reason' => $cancel_reason,
		'reschedule_cancel_user_id' => $current_user_id ? $current_user_id : null,
		'reschedule_cancel_action_ts' => $action_time,
		'updated_ts' => $action_time,
	];
	$result = $wpdb->update($table_bookings, $update_data, array('id' => $booking_obj->get_id()));
	if ($result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	$admin_user_id = $booking_obj->get_admin_user_id();
	wpcal_service_availability_slots_mark_refresh_cache_by_admin($admin_user_id);

	if ($cancel_status === -1) { //normal cancellation
		wpcal_after_cancel_booking_add_background_tasks($booking_obj, $booking_action = 'cancel');
	}

	// Following code commented will be called in wpcal_add_booking()
	// elseif ($cancel_status === -5) { //reschedule cancellation
	// 	wpcal_after_cancel_booking_add_background_tasks($booking_obj, $booking_action = 'reschedule');
	// }
	return true;
}

function wpcal_update_new_booking_id_in_rescheduled_booking(WPCal_Booking $old_booking_obj, WPCal_Booking $new_booking_obj) {
	global $wpdb;

	if (!$old_booking_obj->is_rescheduled()) {
		throw new WPCal_Exception('booking_not_a_rescheduled');
	}

	$update_data = [
		'rescheduled_booking_id' => $new_booking_obj->get_id(),
	];
	$table_bookings = $wpdb->prefix . 'wpcal_bookings';

	$result = $wpdb->update($table_bookings, $update_data, array('id' => $old_booking_obj->get_id()));
	if ($result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}
	return true;
}

function wpcal_get_old_booking_if_rescheduled($booking_id) {
	global $wpdb;

	$table_bookings = $wpdb->prefix . 'wpcal_bookings';
	$query = "SELECT `id` FROM `$table_bookings` WHERE `rescheduled_booking_id` = %s";
	$query = $wpdb->prepare($query, $booking_id);
	$old_booking_id = $wpdb->get_var($query);
	if (!empty($old_booking_id)) {
		return wpcal_get_booking($old_booking_id);
	}
	return false;
}

function wpcal_reschedule_booking($old_booking_id, $new_booking_data) {
	$old_booking_obj = wpcal_get_booking($old_booking_id);

	//check service is reschedulable
	$service_id = $old_booking_obj->get_service_id();
	$service_obj = wpcal_get_service($service_id);
	if (!$service_obj->is_reschedule_booking_allowed()) {
		throw new WPCal_Exception('service_reschedule_booking_not_allowed');
	}

	if (!$old_booking_obj->is_active()) {
		throw new WPCal_Exception('booking_old_not_active');
	}

	$reschedule_reason = !empty($new_booking_data['reschedule_reason']) ? $new_booking_data['reschedule_reason'] : null;

	$cancel_reason = sanitize_textarea_field($reschedule_reason);

	$is_cancelled = wpcal_cancel_booking($old_booking_obj, $cancel_reason, $cancel_type = 'reschedule');

	if (!$is_cancelled) {
		throw new WPCal_Exception('booking_not_cancelled');
	}

	return wpcal_add_booking($new_booking_data, $old_booking_id);
}

// The following function no longer in use
// function wpcal_get_add_events_calendar_by_admin($admin_user_id) {
// 	global $wpdb;

// 	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
// 	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

// 	$query = "SELECT `cals`.`id` as `calendar_id`, `cals`.`calendar_account_id`, `cal_accs`.`provider`, `cals`.`tp_cal_id` FROM `$table_calendar_accounts` as `cal_accs` JOIN `$table_calendars` as `cals` ON `cal_accs`.`id` = `cals`.`calendar_account_id` WHERE `cal_accs`.`status` = '1' AND `cals`.`status` = '1' AND `cals`.`is_add_events_calendar` = '1' AND `cal_accs`.`admin_user_id` = %s";
// 	$query = $wpdb->prepare($query, $admin_user_id);
// 	$result = $wpdb->get_row($query);

// 	return $result;
// }

function wpcal_get_calendar_details_by_id($calendar_id) {
	global $wpdb;

	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	$query = "SELECT `cals`.`id` as `calendar_id`, `cals`.`calendar_account_id`, `cal_accs`.`provider`, `cals`.`tp_cal_id` FROM `$table_calendar_accounts` as `cal_accs` JOIN `$table_calendars` as `cals` ON `cal_accs`.`id` = `cals`.`calendar_account_id` WHERE `cal_accs`.`status` != '' AND `cals`.`status` = '1' AND `cals`.`id` = %s";
	$query = $wpdb->prepare($query, $calendar_id);
	$result = $wpdb->get_row($query);

	return $result;
}

function wpcal_may_add_or_update_booking_to_tp_calendar(WPCal_Booking $booking_obj) {

	$admin_user_id = $booking_obj->get_admin_user_id();
	if (empty($admin_user_id)) {
		throw new WPCal_Exception('booking_admin_user_id_missing');
	}

	$cal_details = wpcal_get_add_bookings_to_calendar_by_admin($admin_user_id);

	if (empty($cal_details)) {
		throw new WPCal_Exception('unable_to_get_calendar_details');
	}

	$tp_calendar_class = wpcal_include_and_get_tp_calendar_class($cal_details->provider);

	$tp_calendar_obj = new $tp_calendar_class($cal_details->calendar_account_id);

	wpcal_throw_error_if_tp_api_status_broken($tp_calendar_obj);

	if (!empty($booking_obj->get_event_added_tp_event_id())) {
		wpcal_may_update_or_delete_booking_to_tp_calendar($booking_obj, $do = 'update');
	} else {
		try {
			$tp_calendar_obj->api_add_event($cal_details, $booking_obj);
		} catch (\WPCal\GoogleAPI\Google_Service_Exception $e) {
			if (method_exists($tp_calendar_obj, 'handle_api_exceptions')) {
				$tp_calendar_obj->handle_api_exceptions($e);
				throw $e; //let throw again background task will handle it
			}
		}
	}
}

function wpcal_may_update_or_delete_booking_to_tp_calendar(WPCal_Booking $booking_obj, $do) {

	$calendar_id = wpcal_get_calendar_id_from_booking_event_added_may_use_alternate_methods_to_find($booking_obj);

	$cal_details = wpcal_get_calendar_details_by_id($calendar_id);
	if (empty($cal_details)) {
		throw new WPCal_Exception('unable_to_get_calendar_details');
	}

	$tp_calendar_class = wpcal_include_and_get_tp_calendar_class($cal_details->provider);

	$tp_calendar_obj = new $tp_calendar_class($cal_details->calendar_account_id);

	wpcal_throw_error_if_tp_api_status_broken($tp_calendar_obj);

	try {
		if ($do == 'update') {
			$tp_calendar_obj->api_update_event($cal_details, $booking_obj);
		} elseif ($do == 'delete') {
			$tp_calendar_obj->api_delete_event($cal_details, $booking_obj);
		}
	} catch (\WPCal\GoogleAPI\Google_Service_Exception $e) {
		if (method_exists($tp_calendar_obj, 'handle_api_exceptions')) {
			$tp_calendar_obj->handle_api_exceptions($e);
			throw $e; //let throw again background task will handle it
		}
	}
}

function wpcal_may_delete_booking_to_tp_calendar(WPCal_Booking $booking_obj) {
	return wpcal_may_update_or_delete_booking_to_tp_calendar($booking_obj, $do = 'delete');
}

function wpcal_get_calendar_id_from_booking_event_added_may_use_alternate_methods_to_find(WPCal_Booking $booking_obj) {
	// this is only for calendar get, update or delete event(after event is created)
	// Say booking is added when add booking to calendar is set to google_calendar with tp_cal_id 'zyzzzzz' and calendar_id = 31
	// Add booking to calendar now can be changed within same calendar account or different calendar account or from different provider
	// lets find calendar_id if exists if not match it with tp_cal_id of same admin

	global $wpdb;

	$booking_tp_event_id = $booking_obj->get_event_added_tp_event_id();
	$booking_tp_cal_id = $booking_obj->get_event_added_tp_cal_id();
	$booking_calendar_id = $booking_obj->get_event_added_calendar_id();

	if (empty($booking_tp_event_id) || empty($booking_calendar_id) || empty($booking_tp_cal_id)) {
		//no calendar event added
		throw new WPCal_Exception('booking_calendar_event_added_data_missing', '', [], 700);
	}

	$admin_user_id = $booking_obj->get_admin_user_id();
	if (empty($admin_user_id)) {
		throw new WPCal_Exception('booking_admin_user_id_missing', '', [], 700);
	}
	$add_booking_to_cal_details = wpcal_get_add_bookings_to_calendar_by_admin($admin_user_id);

	//Need to validate do we still have access (read and write) to the calendar_account, calendar. is_still_have_access TO DO Improve Code
	//if admin setting for add events to calendar changed it ok. let the previously booked active bookings still go through old calendar to avoid another event in end user calendar

	if (!empty($add_booking_to_cal_details)) {
		//check whether it same as add_booking_to calendar
		if ($add_booking_to_cal_details->calendar_id == $booking_calendar_id) {
			return $add_booking_to_cal_details->calendar_id;
		}
	}

	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	// check the same calendar_id exists for the same admin, this time add_booking_to calendar might be changed
	$query = "SELECT `calendars`.`id` FROM `$table_calendars` as `calendars` JOIN `$table_calendar_accounts` as `calendar_accounts` ON `calendar_accounts`.`id` = `calendars`.`calendar_account_id` WHERE `calendar_accounts`.`status` != '' AND `calendars`.`id` = %s AND `calendars`.`status` = '1' AND `calendar_accounts`.`admin_user_id` = %s";
	$query = $wpdb->prepare($query, $booking_calendar_id, $admin_user_id);

	$calendar_id = $wpdb->get_var($query);
	if (!empty($calendar_id)) {
		return $calendar_id;
	}

	// check the same tp_cal_id exists for the same admin
	$query2 = "SELECT `calendars`.`id` FROM `$table_calendars` as `calendars` JOIN `$table_calendar_accounts` as `calendar_accounts` ON `calendar_accounts`.`id` = `calendars`.`calendar_account_id` WHERE `calendar_accounts`.`status` != '' AND `calendars`.`tp_cal_id` = %s AND `calendars`.`status` = '1' AND `calendar_accounts`.`admin_user_id` = %s ORDER BY `calendar_accounts`.`status` DESC";
	$query2 = $wpdb->prepare($query2, $$booking_tp_cal_id, $admin_user_id);

	$calendar_id = $wpdb->get_var($query);
	if (!empty($calendar_id)) {
		return $calendar_id;
	}

	throw new WPCal_Exception('unable_to_find_event_added_calendar');
}

function wpcal_get_and_set_meeting_url_from_google_calendar(WPCal_Booking $booking_obj) {

	$location_details = $booking_obj->get_location();
	if (!empty($location_details['form']['location'])) {
		return true; //location already updated
	}

	$calendar_id = wpcal_get_calendar_id_from_booking_event_added_may_use_alternate_methods_to_find($booking_obj);

	$cal_details = wpcal_get_calendar_details_by_id($calendar_id);

	$tp_calendar_class = wpcal_include_and_get_tp_calendar_class($cal_details->provider);

	$tp_calendar_obj = new $tp_calendar_class($cal_details->calendar_account_id);

	try {
		$tp_calendar_obj->get_and_set_meeting_url_from_event($cal_details, $booking_obj);
	} catch (\WPCal\GoogleAPI\Google_Service_Exception $e) {
		if (method_exists($tp_calendar_obj, 'handle_api_exceptions')) {
			$tp_calendar_obj->handle_api_exceptions($e);
			throw $e; //let throw again background task will handle it
		}
	}
}

function wpcal_include_and_get_tp_calendar_class($provider) {
	$list_providers = [
		'google_calendar',
	];

	$provider_class = [
		'google_calendar' => 'WPCal_TP_Google_Calendar',
	];

	if (!in_array($provider, $list_providers, true)) {
		throw new WPCal_Exception('invalid_tp_calendar_provider');
	}

	$include_file = WPCAL_PATH . '/includes/tp_calendars/class_' . $provider . '.php';
	include_once $include_file;

	return $provider_class[$provider];
}

function wpcal_booking_update_tp_calendar_event_details($booking_id, $provider, $calendar_id, $tp_cal_id, $tp_event_id) {
	global $wpdb;

	$booking_obj = new WPCal_Booking($booking_id);

	$table_bookings = $wpdb->prefix . 'wpcal_bookings';

	$update_data = [
		'event_added_calendar_provider' => $provider,
		'event_added_calendar_id' => $calendar_id,
		'event_added_tp_cal_id' => $tp_cal_id,
		'event_added_tp_event_id' => $tp_event_id,
	];
	$result = $wpdb->update($table_bookings, $update_data, array('id' => $booking_obj->get_id()));
	if ($result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}
	return true;
}

function wpcal_get_managed_services_for_current_admin($options) {
	$current_admin_user_id = get_current_user_id();

	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	$allowed_fields = [
		'filter_by_admin_id',
	];

	$options = wpcal_get_allowed_fields($options, $allowed_fields);

	$options = wpcal_sanitize_all($options);

	$validate_obj = new WPCal_Validate($options);
	$validate_obj->rules([
		'required' => [
			'filter_by_admin_id',
		],
		'integer' => [
			'filter_by_admin_id',
		],
		'min' => [
			['filter_by_admin_id', 0],
		],
	]);

	if (!$validate_obj->validate()) {
		$validation_errors = $validate_obj->errors();
	}

	if (!empty($validation_errors)) {
		throw new WPCal_Exception('validation_errors', '', $validation_errors);
	}

	$options['statuses'] = [1, -1, -7];

	return wpcal_get_managed_services_for_admin($current_admin_user_id, $options);
}

function wpcal_get_managed_services_for_admin($current_admin_user_id, $options) {
	$_options = [
		'current_admin_user_id' => $current_admin_user_id,
		'other_admins_services' => true,
	];

	$options = array_merge($options, $_options);

	return wpcal_get_services($options);
}

function wpcal_get_services_of_current_admin() {
	$admin_user_id = get_current_user_id();

	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	return wpcal_get_services_by_admin($admin_user_id);
}

function wpcal_get_services_by_admin($admin_user_id) {
	$options = [
		'admin_user_id' => $admin_user_id,
	];

	return wpcal_get_services($options);
}

function wpcal_get_services($options = []) {
	global $wpdb;

	$table_services = $wpdb->prefix . 'wpcal_services';
	$table_service_admins = $wpdb->prefix . 'wpcal_service_admins';

	$statuses = [1, -1];
	if (isset($options['statuses'])) {
		$statuses = (array) $options['statuses'];
	}

	$statuses_escaped = esc_sql($statuses);
	$statuses_escaped_imploded = wpcal_implode_for_sql($statuses_escaped);

	$query = "SELECT `service`.`id`, `service`.`name`, `service`.`status`, `service`.`duration`, `service`.`relationship_type`, `service`.`post_id`, `service`.`color`, `service`.`is_manage_private`, `service_admin`.`admin_user_id`  FROM `$table_services` as `service` JOIN `$table_service_admins` as `service_admin` ON `service`.`id` = `service_admin`.`service_id` WHERE `service`.`status`IN ($statuses_escaped_imploded)";

	//$options['filter_by_admin_id'] can be 0 (zero) to show all admins services
	if (!empty($options['other_admins_services'])) {
		if (!isset($options['filter_by_admin_id']) || empty($options['current_admin_user_id'])) {
			//something wrong
			return [];
		}

		if ($options['filter_by_admin_id'] == 0) {

			$query .= $wpdb->prepare(" AND ( (`service`.`is_manage_private` = 1 AND `service_admin`.`admin_user_id` = %s) OR (`service`.`is_manage_private` = 0) ) ", $options['current_admin_user_id']);

		} elseif ($options['filter_by_admin_id'] == $options['current_admin_user_id']) {
			$query .= $wpdb->prepare(" AND `service_admin`.`admin_user_id` = %s", $options['current_admin_user_id']);

		} else {
			$query .= $wpdb->prepare(" AND `service`.`is_manage_private` = 0 AND `service_admin`.`admin_user_id` = %s ", $options['filter_by_admin_id']);

		}

	} elseif (!empty($options['admin_user_id'])) {
		$query .= $wpdb->prepare(" AND `service_admin`.`admin_user_id` = %s", $options['admin_user_id']);
	}

	$results = $wpdb->get_results($query);
	if (empty($results)) {
		return [];
	}
	foreach ($results as $key => $service) {
		$results[$key]->status = $service->status == -7 ? -1 : $service->status;
		$results[$key]->post_details = WPCal_Service::get_post_details_by_post_id($service->post_id);
	}
	return $results;
}

function wpcal_add_calendar_account_redirect($provider, $action = 'add') {

	if (!in_array($action, ['add', 'reauth'])) {
		throw new WPCal_Exception('invalid_request');
	}

	if ($action == 'add') {
		if (wpcal_is_calendar_accounts_limit_reached_of_current_admin()) {
			echo "Max calendar account limit reached.";
			exit;
		}
	}

	$list = ['google_calendar'];
	if (!in_array($provider, $list)) {
		throw new WPCal_Exception('invalid_tp_calendar_provider');
	}

	//verify plan limits before going down
	$tp_calendar_class = wpcal_include_and_get_tp_calendar_class($provider);
	$tp_calendar_obj = new $tp_calendar_class(0);
	$url = $tp_calendar_obj->get_add_account_url($action);

	if (empty($url)) {
		throw new WPCal_Exception('tp_calendar_auth_url_data_missing');
	}

	$redirect_site_url = ['google_calendar' => WPCAL_GOOGLE_OAUTH_REDIRECT_SITE_URL];

	$final_url = $redirect_site_url[$provider] . 'cal-api/?calendar_provider=google_calendar&plugin_slug=' . WPCAL_PLUGIN_SLUG . '&plugin_verion=' . WPCAL_VERSION . '&passed_data=' . urlencode(base64_encode($url));

	wp_redirect($final_url);
	exit;
}

function wpcal_google_calendar_receive_token_and_add_account($action = 'add') {
	//having temporarily saving add request and use it again when auth code comes  it will be useful

	if ($action == 'add' && wpcal_is_calendar_accounts_limit_reached_of_current_admin()) {
		echo "Max calendar account limit reached.";
		exit;
	}

	//verify plan limits before going down
	$tp_calendar_class = wpcal_include_and_get_tp_calendar_class('google_calendar');
	$tp_calendar_obj = new $tp_calendar_class(0);
	$add_account_result = $tp_calendar_obj->add_account_after_auth($action);
	$calendar_account_id = $add_account_result['calendar_account_id'] ?? false;
	$is_default_calendars_added = $add_account_result['is_default_calendars_added'] ?? false;

	if ($calendar_account_id) { // to mark completed in onboarding checklist
		wpcal_mark_onboarding_checklist_completed_for_current_admin('add_calendar_account');
	}

	$calendar_page_link = 'admin.php?page=wpcal_admin#/settings/calendars';
	if ($calendar_account_id) {
		$calendar_page_link .= $calendar_account_id ? '/connected/' . $calendar_account_id . ($is_default_calendars_added ? '/default_calendars_added' : '') : '';
	}
	wp_redirect($calendar_page_link);
	exit;
}

function wpcal_remove_calendar_without_revoke_by_id($calendar_account_id, $provider) {
	//to avoid getting disconencted from other login or site where API access with WPCal is authorised. $revoke set to false
	return wpcal_disconnect_calendar_by_id($calendar_account_id, $provider, $force = true, $revoke = false);
}

function wpcal_disconnect_calendar_by_id($calendar_account_id, $provider, $force = false, $revoke = true) {
	$list = ['google_calendar'];
	if (!in_array($provider, $list)) {
		throw new WPCal_Exception('invalid_tp_calendar_provider');
	}

	//verify plan limits before going down
	$tp_calendar_class = wpcal_include_and_get_tp_calendar_class($provider);
	$tp_calendar_obj = new $tp_calendar_class($calendar_account_id);

	$result = $tp_calendar_obj->revoke_access_and_delete_its_data($force, $revoke);
	return $result;
}

function wpcal_get_calendar_accounts_details_of_current_admin() {
	$admin_user_id = get_current_user_id();

	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	return wpcal_get_calendar_accounts_details_by_admin($admin_user_id);
}

function wpcal_get_calendar_accounts_details_by_admin($admin_user_id) {

	if (empty($admin_user_id)) {
		return [];
	}

	$options = [
		'admin_user_id' => $admin_user_id,
	];

	return wpcal_get_calendar_accounts_details($options);
}

function wpcal_get_calendar_accounts_details($options = []) {
	global $wpdb;

	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	$query = "SELECT `id`, `admin_user_id`, `provider`, `status`, `account_email` FROM `$table_calendar_accounts` WHERE `status` != ''";

	if (isset($options['admin_user_id'])) {
		$query .= $wpdb->prepare(" AND `admin_user_id` = %s", $options['admin_user_id']);
	}

	$calendar_accounts = $wpdb->get_results($query, OBJECT_K);
	if (empty($calendar_accounts)) {
		return [];
	}

	foreach ($calendar_accounts as $key => $calendar_account) {

		$query2 = "SELECT `id`, `calendar_account_id`, `name`, `tp_cal_id`, `is_conflict_calendar`, `is_add_events_calendar`, `is_primary`, `is_writable` FROM `$table_calendars` WHERE `status` = '1' AND `calendar_account_id` = %s";
		$query2 = $wpdb->prepare($query2, $calendar_account->id);

		$calendars = $wpdb->get_results($query2, OBJECT_K);
		if (empty($calendars)) {
			//no calendars - very case - not sure it is an error
			$calendar_accounts[$key]->calendars = [];
			continue;
		}
		$calendar_accounts[$key]->calendars = $calendars;
	}
	return $calendar_accounts;
}

function wpcal_get_count_of_calendar_accounts_of_current_admin() {
	global $wpdb;

	$admin_user_id = get_current_user_id();

	if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return [];
	}

	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$query = "SELECT count(`id`) FROM `$table_calendar_accounts` WHERE `admin_user_id` = %s";
	$query = $wpdb->prepare($query, $admin_user_id);

	$result = $wpdb->get_var($query);
	return $result;
}

function wpcal_is_calendar_accounts_limit_reached_of_current_admin() {
	$current_count = wpcal_get_count_of_calendar_accounts_of_current_admin();
	$make_a_free_way = WPCal_License::get_public();

	if ($make_a_free_way) {
		if ($current_count >= 1) {
			return true;
		}
		return false;
	}
	return false;
}

function wpcal_get_add_bookings_to_calendar_of_current_admin() {

	$admin_user_id = get_current_user_id();

	WPCal_Admins::is_current_user_is_wpcal_admin_check();

	return wpcal_get_add_bookings_to_calendar_by_admin($admin_user_id);
}

function wpcal_get_active_add_bookings_to_calendar_by_admin($admin_user_id) {
	$cal_details = wpcal_get_add_bookings_to_calendar_by_admin($admin_user_id);
	if (empty($cal_details)) {
		return $cal_details; //mostly return null
	}

	$cal_details = $cal_details->calendar_account_status == 1 ? $cal_details : null;
	return $cal_details;
}

function wpcal_get_add_bookings_to_calendar_by_admin($admin_user_id) {
	global $wpdb;
	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	$query = "SELECT `calendars`.`id` as `calendar_id`, `calendars`.`calendar_account_id`, `calendar_accounts`.`provider`, `calendar_accounts`.`status` as `calendar_account_status`, `calendar_accounts`.`account_email`  as `calendar_account_email`, `calendars`.`tp_cal_id` FROM `$table_calendars` as `calendars` JOIN `$table_calendar_accounts` as `calendar_accounts` ON `calendar_accounts`.`id` = `calendars`.`calendar_account_id` WHERE `calendar_accounts`.`status` != '' AND `calendars`.`status` = '1' AND `calendars`.`is_add_events_calendar` = '1' AND `calendar_accounts`.`admin_user_id` = %s";
	$query = $wpdb->prepare($query, $admin_user_id);
	$add_bookings_to_calendar = $wpdb->get_row($query);

	return $add_bookings_to_calendar;
}

function wpcal_get_conflict_calendar_ids_of_current_admin() {
	$admin_user_id = get_current_user_id();

	WPCal_Admins::is_current_user_is_wpcal_admin_check();

	return wpcal_get_conflict_calendar_ids_by_admin($admin_user_id);
}

function wpcal_get_conflict_calendar_ids_by_admin($admin_user_id) {
	global $wpdb;
	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	$query = "SELECT `calendars`.`id` FROM `$table_calendars` as `calendars` JOIN `$table_calendar_accounts` as `calendar_accounts` ON `calendar_accounts`.`id` = `calendars`.`calendar_account_id` WHERE `calendar_accounts`.`status` != '' AND `calendars`.`status` = '1' AND `calendars`.`is_conflict_calendar` = '1' AND `calendar_accounts`.`admin_user_id` = %s";
	$query = $wpdb->prepare($query, $admin_user_id);
	$conflict_calendar_ids = $wpdb->get_col($query);

	if (empty($conflict_calendar_ids)) {
		return [];
	}
	return $conflict_calendar_ids;
}

function wpcal_update_add_bookings_to_calendar_id_for_current_admin($add_bookings_to_calendar_id) {
	global $wpdb;

	$admin_user_id = get_current_user_id();

	WPCal_Admins::is_current_user_is_wpcal_admin_check();

	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	$query = "SELECT `id` FROM `$table_calendar_accounts` WHERE `admin_user_id` = %s";
	$query = $wpdb->prepare($query, $admin_user_id);

	$calendar_account_ids = $wpdb->get_col($query);
	if (empty($calendar_account_ids)) {
		return false;
	}

	$calendar_account_ids_imploded = wpcal_implode_for_sql($calendar_account_ids);

	$query2 = "UPDATE `$table_calendars` SET `is_add_events_calendar` = '0' WHERE `is_add_events_calendar` = '1' AND `calendar_account_id` IN($calendar_account_ids_imploded)"; //$calendar_account_ids no prepare sql required

	$set_zero_for_all = $wpdb->query($query2);
	if ($set_zero_for_all === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	if ($add_bookings_to_calendar_id == 'no') {
		return true;
	}

	$updated_row_count = $wpdb->update($table_calendars, array('is_add_events_calendar' => '1'), array('id' => $add_bookings_to_calendar_id));
	if ($updated_row_count === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	return true;
}

function wpcal_update_conflict_calendar_ids_for_current_admin($conflict_calendar_ids, $conflict_calendar_ids_length) {
	global $wpdb;

	if ($conflict_calendar_ids_length > 0 && is_array($conflict_calendar_ids) && count($conflict_calendar_ids) == $conflict_calendar_ids_length) {
		//this is good
	} elseif ($conflict_calendar_ids_length == 0 && (!is_array($conflict_calendar_ids) || count($conflict_calendar_ids) == 0)) {
		//this is good
	} else {
		throw new WPCal_Exception('invalid_input');
	}

	$admin_user_id = get_current_user_id();

	WPCal_Admins::is_current_user_is_wpcal_admin_check();

	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	$query = "SELECT `id` FROM `$table_calendar_accounts` WHERE `admin_user_id` = %s";
	$query = $wpdb->prepare($query, $admin_user_id);

	$calendar_account_ids = $wpdb->get_col($query);
	if (empty($calendar_account_ids)) {
		return false;
	}

	$calendar_account_ids_imploded = wpcal_implode_for_sql($calendar_account_ids);

	$conflict_calendar_ids_escaped = esc_sql($conflict_calendar_ids);
	$conflict_calendar_ids_escaped_imploded = wpcal_implode_for_sql($conflict_calendar_ids_escaped);

	$query_removed_conflict_calendars = "SELECT `id` FROM `$table_calendars` WHERE `is_conflict_calendar` = '1' AND `calendar_account_id` IN($calendar_account_ids_imploded)";
	if (!empty($conflict_calendar_ids)) {
		$query_removed_conflict_calendars .= " AND `id` NOT IN($conflict_calendar_ids_escaped_imploded)"; //$calendar_account_ids no prepare sql required
	}
	$removed_conflict_calendar_ids = $wpdb->get_col($query_removed_conflict_calendars);

	if (!empty($removed_conflict_calendar_ids)) {
		$removed_conflict_calendar_ids_imploded = wpcal_implode_for_sql($removed_conflict_calendar_ids);

		$query2 = "UPDATE `$table_calendars` SET
		`is_conflict_calendar` = '0',
		`list_events_sync_token` = NULL,
		`list_events_sync_status` = NULL,
		`list_events_sync_status_update_ts` = NULL,
		`list_events_sync_last_update_ts` = NULL
		 WHERE `is_conflict_calendar` = '1' AND `id` IN($removed_conflict_calendar_ids_imploded)";

		$reset_removed_conflict_ids = $wpdb->query($query2);
		if ($reset_removed_conflict_ids === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}

		foreach ($removed_conflict_calendar_ids as $removed_conflict_calendar_id) {
			wpcal_delete_all_calendar_events_recursively_in_blocks($removed_conflict_calendar_id);
		}
	}

	if (empty($conflict_calendar_ids) || !is_array($conflict_calendar_ids)) {
		wpcal_service_availability_slots_mark_refresh_cache_for_current_admin();
		return true;
	}

	$query3 = "UPDATE `$table_calendars` SET `is_conflict_calendar` = '1' WHERE `id` IN($conflict_calendar_ids_escaped_imploded)";

	$updated_row_count = $wpdb->query($query3);
	if ($updated_row_count === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	wpcal_service_availability_slots_mark_refresh_cache_for_current_admin();
	return true;
}

function wpcal_get_primary_calendar_of_calendar_account($calendar_account_id) {
	global $wpdb;

	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	$query = "SELECT `id`, `calendar_account_id`, `name`, `tp_cal_id`, `is_conflict_calendar`, `is_add_events_calendar`, `is_primary`, `is_writable` FROM `$table_calendars` WHERE `status` = '1' AND `calendar_account_id` = %s AND `is_primary` = '1' ORDER BY `id` LIMIT 1 ";
	$query = $wpdb->prepare($query, $calendar_account_id);

	return $wpdb->get_row($query);
}

function wpcal_check_and_add_default_calendars_for_current_admin($recently_added_calendar_account_id) {
	//this will check and add default calendar for add_bookings and conflict calendar

	//check is this the only calendar for the this admin
	$calendar_accounts_details = wpcal_get_calendar_accounts_details_of_current_admin();
	if (!is_array($calendar_accounts_details) || count($calendar_accounts_details) !== 1) {
		return false;
	}

	$_calendar_account_details = array_shift($calendar_accounts_details);

	//verify new calendar for current admin
	if ($_calendar_account_details->id != $recently_added_calendar_account_id) {
		return false;
	}

	//check any one has a calendar set for add_bookings and conflict calendar - if yes then no need to continue
	$add_bookings_to_calendar = wpcal_get_add_bookings_to_calendar_of_current_admin();
	if (!empty($add_bookings_to_calendar)) {
		return false;
	}

	$conflict_calendar_ids = wpcal_get_conflict_calendar_ids_of_current_admin();
	if (!empty($conflict_calendar_ids)) {
		return false;
	}

	// get primary calendar
	$calendar_details = wpcal_get_primary_calendar_of_calendar_account($recently_added_calendar_account_id);

	if (empty($calendar_details)) {
		return false;
	}

	$primary_calendar_id = $calendar_details->id;

	//all ok now add the primary calendar of calendar_account_id as add_bookins_calendar and conflict_calendar

	wpcal_update_add_bookings_to_calendar_id_for_current_admin($primary_calendar_id);

	wpcal_update_conflict_calendar_ids_for_current_admin($conflict_calendar_ids = [$primary_calendar_id], $conflict_calendar_ids_length = 1);
	return true;
}

function wpcal_reset_stuck_tp_calendar_sync_events_task() {
	global $wpdb;
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';
	$n_mins_ago = time() - (10 * 60);
	$query = "UPDATE `$table_calendars` SET `list_events_sync_status` = NULL WHERE `list_events_sync_status` = 'running' AND `list_events_sync_status_update_ts` < '" . $n_mins_ago . "' ";
	$result = $wpdb->query($query);
	return $result;
}

function wpcal_sync_all_calendar_api_for_current_admin() {
	$admin_user_id = get_current_user_id();

	WPCal_Admins::is_current_user_is_wpcal_admin_check();

	WPCal_Cron::set_run_full_now(true);
	WPCal_Cron::sync_all_tp_calendars_and_may_events_by_admin($admin_user_id);
	WPCal_Cron::may_remove_calendar_events_webhooks_by_admin($admin_user_id);
	WPCal_Cron::set_run_full_now(false);
}

function wpcal_delete_all_calendar_events_recursively_in_blocks($calendar_id) {
	global $wpdb;
	$limit = 1000;

	$table_calendar_events = $wpdb->prefix . 'wpcal_calendar_events';
	$query = "DELETE FROM `$table_calendar_events` WHERE `calendar_id` = %s ORDER BY `id` LIMIT $limit";
	$query = $wpdb->prepare($query, $calendar_id);

	while (true) {
		$num_of_rows_affected = $wpdb->query($query);
		if ($num_of_rows_affected < $limit) {
			break;
		}
	}
}

function wpcal_get_unique_calendar_account_ids_by_calendar_ids(array $calendar_ids) {
	global $wpdb;

	$table_calendars = $wpdb->prefix . 'wpcal_calendars';
	$calendar_ids_escaped = esc_sql($calendar_ids);
	$calendar_ids_escaped_imploded = wpcal_implode_for_sql($calendar_ids_escaped);
	$query = "SELECT DISTINCT `calendar_account_id` FROM `$table_calendars` WHERE  `id` IN($calendar_ids_escaped_imploded)";

	$calendar_account_ids = $wpdb->get_col($query);
	if ($calendar_account_ids === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}
	return $calendar_account_ids;
}

function wpcal_throw_error_if_tp_api_status_broken($tp_object) {
	// $tp_object can be calendar or other tp accounts
	// It won't check the api, it only checks the API status. API status will be updated based on previous calls

	if (
		!is_object($tp_object) ||
		(
			!($tp_object instanceof WPCal_Abstract_TP) &&
			!($tp_object instanceof WPCal_Abstract_TP_Calendar)
		)
	) {
		throw new WPCal_Exception('unexpected_object_or_input');
	}

	if (!method_exists($tp_object, 'is_status_ok')) {
		throw new WPCal_Exception('object_doesnt_have_required_method');
	}

	if (!$tp_object->is_status_ok()) {
		throw new WPCal_Exception('tp_api_status_broken');
	}
	return true;
}

function wpcal_get_wpcal_admin_users_details_for_admin_client() {
	$admin_user_details = wpcal_get_wpcal_admin_users_details_with_stats();
	$admin_user_details = array_values($admin_user_details); //resetting index
	return $admin_user_details;
}

function wpcal_get_wpcal_admin_users_details_with_stats() {
	//wpcal admin users only
	global $wpdb;

	$admin_user_details = WPCal_Admins::get_all_admins('', ['admin_details' => 1, 'extended_admin_details' => 1]);
	if (empty($admin_user_details)) {
		return [];
	}

	$admin_user_ids = array_keys($admin_user_details);

	// get service stats
	$table_services = $wpdb->prefix . 'wpcal_services';
	$table_service_admins = $wpdb->prefix . 'wpcal_service_admins';

	$query = "SELECT `service_admins`.`admin_user_id`, count(`services`.`id`) as `services_count`, SUM(`services`.`status` = 1) as `services_active_count`  FROM `$table_services` as `services` JOIN `$table_service_admins` as `service_admins`  ON `services`.id = `service_admins`.`service_id` WHERE `services`.`status` IN(1, -1) AND `service_admins`.`admin_user_id` IN (" . implode(',', $admin_user_ids) . ") GROUP BY `service_admins`.`admin_user_id`";
	$service_stats_result = $wpdb->get_results($query, OBJECT_K);

	// get booking stats
	$now_ts = time();
	$table_bookings = $wpdb->prefix . 'wpcal_bookings';
	$query2 = "SELECT `admin_user_id`, COUNT(*) as `bookings_upcoming_count` FROM `$table_bookings` WHERE `admin_user_id` IN (" . implode(',', $admin_user_ids) . ") AND `status` = '1' AND `booking_to_time` >= $now_ts GROUP BY `admin_user_id`";
	$booking_stats_result = $wpdb->get_results($query2, OBJECT_K);

	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
	$query3 = "SELECT `admin_user_id`, COUNT(*) as `calendar_acccounts_count` FROM `$table_calendar_accounts` WHERE `admin_user_id` IN (" . implode(',', $admin_user_ids) . ") GROUP BY `admin_user_id`";
	$calendar_account_stats_result = $wpdb->get_results($query3, OBJECT_K);

	$table_calendars = $wpdb->prefix . 'wpcal_calendars';
	$query4 = "SELECT `cal_accs`.`admin_user_id`, COUNT(*) as `add_bookings_to_count` FROM `$table_calendar_accounts` as `cal_accs` JOIN `$table_calendars` as `cals` ON `cal_accs`.`id` = `cals`.`calendar_account_id` WHERE `cals`.`is_add_events_calendar` = '1' AND `cal_accs`.`admin_user_id` IN (" . implode(',', $admin_user_ids) . ") GROUP BY `cal_accs`.`admin_user_id`";
	$add_bookings_to_stats_result = $wpdb->get_results($query4, OBJECT_K);

	$query5 = "SELECT `cal_accs`.`admin_user_id`, COUNT(*) as `conflict_calendars_count` FROM `$table_calendar_accounts` as `cal_accs` JOIN `$table_calendars` as `cals` ON `cal_accs`.`id` = `cals`.`calendar_account_id` WHERE `cals`.`is_conflict_calendar` = '1' AND `cal_accs`.`admin_user_id` IN (" . implode(',', $admin_user_ids) . ") GROUP BY `cal_accs`.`admin_user_id`";
	$conflict_calendar_stats_result = $wpdb->get_results($query5, OBJECT_K);

	$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';
	$query6 = "SELECT CONCAT(`admin_user_id`, '__', `provider`) as `admin_user_id__provider`, COUNT(*) as `provider_count` FROM `$table_tp_accounts` WHERE `admin_user_id` IN (" . implode(',', $admin_user_ids) . ") GROUP BY `admin_user_id`, `provider`";
	$tp_account_stats_result = $wpdb->get_results($query6, OBJECT_K);

	foreach ($admin_user_details as $_admin_user_id => $_admin_user_detail) {

		$admin_user_details[$_admin_user_id]->status = $admin_user_details[$_admin_user_id]->status == -7 ? -1 : $admin_user_details[$_admin_user_id]->status;

		$admin_user_details[$_admin_user_id]->services_count = $service_stats_result[$_admin_user_id]->services_count ?? 0;

		$admin_user_details[$_admin_user_id]->services_active_count = $service_stats_result[$_admin_user_id]->services_active_count ?? 0;

		$admin_user_details[$_admin_user_id]->bookings_upcoming_count = $booking_stats_result[$_admin_user_id]->bookings_upcoming_count ?? 0;

		$admin_user_details[$_admin_user_id]->calendar_acccounts_count = $calendar_account_stats_result[$_admin_user_id]->calendar_acccounts_count ?? 0;

		$admin_user_details[$_admin_user_id]->add_bookings_to_calendar = $add_bookings_to_stats_result[$_admin_user_id]->add_bookings_to_count ?? 0;

		$admin_user_details[$_admin_user_id]->conflict_calendars_count = $conflict_calendar_stats_result[$_admin_user_id]->conflict_calendars_count ?? 0;

		$admin_user_details[$_admin_user_id]->zoom_meeting = $tp_account_stats_result[$_admin_user_id . '__' . 'zoom_meeting']->provider_count ?? 0;

		$admin_user_details[$_admin_user_id]->gotomeeting_meeting = $tp_account_stats_result[$_admin_user_id . '__' . 'gotomeeting_meeting']->provider_count ?? 0;

	}

	return $admin_user_details;
}

function wpcal_get_managed_active_admin_users_details_for_current_admin() {
	// futuristic - current wpcal admin type is administrator only

	$admin_users_details = WPCal_Admins::get_all_active_admins(['admin_details' => 1, 'extended_admin_details' => 1]);

	//sorting by name
	$admin_users_details = array_values($admin_users_details); //to reset the keys
	usort($admin_users_details, function ($a, $b) {return strnatcasecmp($a->name, $b->name);});

	//put current admin first
	$current_admin_user_id = get_current_user_id();
	$current_admin_key = array_search($current_admin_user_id, array_column($admin_users_details, 'admin_user_id'));
	if ($current_admin_key !== false) {
		$current_admin_details = $admin_users_details[$current_admin_key];
		unset($admin_users_details[$current_admin_key]);
		array_unshift($admin_users_details, $current_admin_details);
	}

	return $admin_users_details;
}

function wpcal_related_get_user_full_name($purpose, $wp_user_id, $who_views, WPCal_Booking $booking_obj = null, array $admin_details = [], array $invitee_details = []) {

	if (!in_array($purpose, ['reschedule_cancel_person_name', 'admin_full_name'])) {
		return '';
	}

	$is_possible_invitee_name = false;
	$is_possible_admin_name = false;

	if (in_array($purpose, ['reschedule_cancel_person_name'])) {
		$is_possible_invitee_name = true;
		$is_possible_admin_name = true;
	}

	if (in_array($purpose, ['admin_full_name'])) {
		$is_possible_admin_name = true;
	}

	$name = '';

	if (empty($wp_user_id)) {
		if ($is_possible_invitee_name) {
			//case related to reschedule_cancel_by
			if (empty($name) && $booking_obj) {
				$name = $booking_obj->get_invitee_name();

			} elseif (!empty($invitee_details['invitee_name'])) {
				$name = $invitee_details['invitee_name'];
			}
		}
		return $name;
	}

	//before cache check
	if (empty($name) && $is_possible_invitee_name && $booking_obj) { //each booking can have different name. so better to use it
		if ($wp_user_id == $booking_obj->get_invitee_wp_user_id()) {
			return $booking_obj->get_invitee_name();
		}
	}

	if (empty($name) && $is_possible_invitee_name && !empty($invitee_details['invitee_wp_user_id']) && $wp_user_id == $invitee_details['invitee_wp_user_id']) { //each booking can have different name. so better to use it
		$name = $invitee_details['invitee_name'];
		return $name;
	}

	if (empty($name) && $is_possible_admin_name && !empty($admin_details['id']) && $wp_user_id == $admin_details['id']) { //as this data comes for each better to return it if matches
		$name = $who_views === 'admin' ? $admin_details['name'] : $admin_details['display_name'];
		return $name;
	}

	static $cache_names = [];

	if (isset($cache_names[$wp_user_id][$who_views])) {
		return $cache_names[$wp_user_id][$who_views];
	}

	if (empty($name) && $is_possible_admin_name && !isset($admin_details['id']) && WPCal_Admins::is_wpcal_admin_active($wp_user_id)) {
		$admin_details = wpcal_get_admin_details($wp_user_id); //so following admin_details will work

		if (!empty($admin_details['id'])) {
			$name = $who_views === 'admin' ? $admin_details['name'] : $admin_details['display_name'];
		}
	}

	if (empty($name)) {
		$_user = get_user_by('id', $wp_user_id);
		if ($_user) {
			$_name = $_user->first_name . ' ' . $_user->last_name;
			$_name = trim($_name);
			$name = $_name ?? $_user->display_name;
		}
	}

	$cache_names[$wp_user_id][$who_views] = $name;
	return $name;
}

function wpcal_get_non_wpcal_admin_details_to_show_for_client_admin() {
	//WP Administrators who are not WPCal Admins can call this

	if (!current_user_can('administrator')) {
		return false;
	}
	$wp_user = wp_get_current_user();
	$wp_user_details = [];
	$wp_user_details['wp_user_id'] = (string) $wp_user->ID; //(string) all DB number are in string, in js this int not matching with string(int) in type to search
	$wp_user_details['is_active_wpcal_admin'] = WPCal_Admins::is_current_user_is_wpcal_admin();
	$wp_user_details['while_no_active_wpcal_admins_current_user_can_be_self_added_as_wpcal_admin'] = WPCal_Admins::while_no_active_wpcal_admins_current_user_can_be_self_added_as_wpcal_admin();
	$wp_user_details['list_of_admins_to_contact'] = wpcal_get_list_of_wpcal_admins_to_contact_for_client_admin();

	return $wp_user_details;
}

function wpcal_get_list_of_wpcal_admins_to_contact_for_client_admin() {
	//WP Administrators who are not WPCal Admins can call this

	$admin_user_details = WPCal_Admins::get_all_admins('1', ['admin_details' => 1, 'extended_admin_details' => 1]);
	if (empty($admin_user_details)) {
		return [];
	}

	foreach ($admin_user_details as $key => $admin_user_detail) {
		if (!WPCal_Admins::is_wpcal_admin_active($admin_user_detail->admin_user_id)) {
			unset($admin_user_details[$key]);
		}
	}
	return array_values($admin_user_details);
}

function wpcal_listen_and_may_redirect() {
	if (empty($_GET['wpcal_action'])) {
		return;
	}
	$action = trim($_GET['wpcal_action']);
	$allowed_actions = [
		//booking related redirect to a page
		'booking_cancel',
		'booking_reschedule',
		'booking_view',

		//booking related redirect to add_link or download ics file
		'booking_tp_add_event',

		//booking related - tp meeting url(web conference) redirect
		'booking_meeting_redirect',

		//receive and process calendar events webhook for google calendar
		'google_calendar_events_webhook',
	];
	if (!in_array($action, $allowed_actions, true)) {
		return;
	}

	if ($action === 'google_calendar_events_webhook') {
		wpcal_receive_calendar_events_webhook();
		return;
	}

	//following booking related

	if (empty($_GET['booking_id'])) {
		return;
	}
	$booking_unqiue_link = sanitize_text_field($_GET['booking_id']);

	if ($action === 'booking_tp_add_event') {
		$tp = sanitize_text_field($_GET['tp']);
		$booking_obj = wpcal_get_booking_by_unique_link($booking_unqiue_link);
		WPCal_TP_Calendars_Add_Event::redirect_to_add_link_or_download($tp, $booking_obj);
		return;
	}

	if ($action === 'booking_meeting_redirect') {
		$booking_obj = wpcal_get_booking_by_unique_link($booking_unqiue_link);
		if ($booking_obj->is_location_needs_online_meeting()) {
			$redirect_url = $booking_obj->get_location_str();
			if ($redirect_url) {
				if (wp_redirect($redirect_url)) {
					exit;
				}
			} else {
				exit('Meeting URL not found please contact meeting host.');
			}
		}
		return;
	}

	try {
		$booking_obj = wpcal_get_booking_by_unique_link($booking_unqiue_link);
		$page_used_for_booking = $booking_obj->get_page_used_for_booking();

		// If current logged in admin trying to view the booking, redirect to admin end view booking
		if (wpcal_is_current_admin_owns_resource('booking', $booking_obj->get_id(), $on_error_throw = false)) {
			$admin_view_url = $booking_obj->get_admin_view_booking_url() . '/redirected-to-admin-end-booking/';
			if ($admin_view_url && wp_redirect($admin_view_url)) {
				exit;
			}
		}

		if (!empty($page_used_for_booking['url'])) {
			$booking_page_url = $page_used_for_booking['url'];
		} else { //page_used_for_booking will not be available if the reschedule happens in admin end
			$post_details = $booking_obj->service_obj->get_post_details();
			$booking_page_url = !empty($post_details['link']) ? $post_details['link'] : '';
		}
		if (empty($booking_page_url)) {
			return;
		}

		$url_paths = [
			'booking_reschedule' => 'reschedule',
			'booking_cancel' => 'cancel',
			'booking_view' => 'view',
		];

		$v_route = '/booking/' . $url_paths[$action] . '/' . $booking_unqiue_link;
		if ($url_paths[$action] === 'cancel') {
			$v_route = '/booking/view/' . $booking_unqiue_link . '/' . $url_paths[$action];
		}
		$redirect_url = wpcal_add_v_route_to_url($booking_page_url, $v_route);
		if (wp_redirect($redirect_url)) {
			exit;
		}
		return;
	} catch (WPCal_Exception $e) {
		return;
	}
}

function wpcal_receive_calendar_events_webhook() {
	$requested = [];
	$requested['calendar_id'] = $_GET['calendar_id'] ?? '';
	$requested['channel_id'] = $_GET['channel_id'] ?? '';
	$requested = wpcal_sanitize_all($requested);

	wpcal_log_calendar_events_webhook_received($requested['calendar_id'], $requested['channel_id']);

	WPCal_Cron::set_run_full_now(true);
	$result = WPCal_Cron::sync_tp_calendars_and_events_by_calendar_id($requested['calendar_id']);
	WPCal_Cron::set_run_full_now(false);

	if (wpcal_remaining_time() > 5) {
		//run some other task if time permits
		WPCal_Cron::run_api_tasks();
	}

	$response = [];
	$response['status'] = $result ? 'success' : 'error';

	echo wpcal_prepare_response($response);
	exit();
}

function wpcal_log_calendar_events_webhook_received($calendar_id, $channel_id) {
	global $wpdb;
	$table_calendars = $wpdb->prefix . 'wpcal_calendars';

	$result = $wpdb->update($table_calendars, ['events_webhook_last_received_ts' => time()], ['id' => $calendar_id, 'events_webhook_channel_id' => $channel_id]); //only if exactly matches it will update. For other channel ids(if old) it won't work. We need to have separate logging for this.
}

function wpcal_add_v_route_to_url($url, $v_route) { //$v_route => vue router's route
	$modify_parsed_url = [];
	$modify_parsed_url['query_params'] = ['wpcal_r' => $v_route];

	$new_url = wpcal_modify_url($url, $modify_parsed_url);
	return $new_url;
}

function wpcal_may_add_sample_services_on_plugin_activation() {
	global $wpdb;
	$table_service = $wpdb->prefix . 'wpcal_services';

	$query = "SELECT `id` FROM $table_service LIMIT 1";
	$any_service_exists = $wpdb->get_row($query);
	if (!empty($any_service_exists)) {
		return false;
	}

	$admin_user_id = get_current_user_id();
	if (!WPCal_Admins::is_current_user_is_wpcal_admin() || !$admin_user_id) {
		return false;
	}

	$working_hours = WPCal_General_Settings::get('working_hours');
	$working_days = WPCal_General_Settings::get('working_days');
	$timezone = WPCal_General_Settings::get('timezone');

	$common_sample_data = [
		'name' => '',
		'status' => '1',
		'locations' => [],
		'descr' => '',
		'color' => '',
		'relationship_type' => '1to1',
		'timezone' => $timezone,
		'duration' => '',
		'display_start_time_every' => '',
		'max_booking_per_day' => null,
		'min_schedule_notice' => [
			'type' => "units",
			'time_units' => "4",
			'time_units_in' => "hrs",
			'days_before_time' => "00:00:00",
			'days_before' => 0,
		],
		'event_buffer_before' => 0,
		'event_buffer_after' => 0,
		'is_manage_private' => 0,
		'service_admin_user_ids' => [$admin_user_id],
		'invitee_notify_by' => 'calendar_invitation',
		'invitee_questions' => [
			'questions' => [
				[
					'is_enabled' => '1',
					'is_required' => '0',
					'question' => 'Please share anything that will help prepare for our meeting.',
					'answer_type' => 'textarea',
				],
			],
		],
		'default_availability_details' => [
			'date_range_type' => 'relative',
			'from_date' => null,
			'from_date' => null,
			'date_misc' => '+60D',
			'periods' => [
				$working_hours,
			],
			'day_index_list' => $working_days,
		],
	];

	$sample_1_data = $sample_2_data = $sample_3_data = $common_sample_data;

	$sample_1_data['name'] = '15 mins meeting';
	$sample_1_data['color'] = 'nephritis';
	$sample_1_data['duration'] = 15;
	$sample_1_data['display_start_time_every'] = 15;

	$sample_2_data['name'] = '30 mins meeting';
	$sample_2_data['color'] = 'belize';
	$sample_2_data['duration'] = 30;
	$sample_2_data['display_start_time_every'] = 15;

	$sample_3_data['name'] = '60 mins meeting';
	$sample_3_data['color'] = 'wisteria';
	$sample_3_data['duration'] = 60;
	$sample_3_data['display_start_time_every'] = 30;

	try {
		wpcal_add_service($sample_3_data);
		wpcal_add_service($sample_2_data);
		wpcal_add_service($sample_1_data); //to display this first in admin end this has been kept last here
	} catch (WPCal_Exception $e) {
	}
}

function wpcal_include_and_get_tp_class($provider) {
	$list_providers = [
		'zoom_meeting',
		'gotomeeting_meeting',
	];

	$provider_class = [
		'zoom_meeting' => 'WPCal_TP_Zoom_Meeting',
		'gotomeeting_meeting' => 'WPCal_TP_GoToMeeting_Meeting',
	];

	if (!in_array($provider, $list_providers, true)) {
		throw new WPCal_Exception('invalid_tp_provider');
	}

	$include_file = WPCAL_PATH . '/includes/tp/class_' . $provider . '.php';
	include_once $include_file;

	return $provider_class[$provider];
}

function wpcal_max_tp_account_limit_reached_error_msg($provider) {
	$provider_name = [
		'zoom_meeting' => 'Zoom',
		'gotomeeting_meeting' => 'GoToMeeting',
	];
	$msg = 'Max ' . $provider_name[$provider] . ' account limit reached. <a href="admin.php?page=wpcal_admin#/settings/integrations">Click here</a>.';
	return $msg;
}

function wpcal_add_tp_account_redirect($provider, $action = 'add') {
	if (!in_array($action, ['add', 'reauth'])) {
		throw new WPCal_Exception('invalid_request');
	}

	if ($action == 'add') {
		if (wpcal_tp_accounts_is_limit_reached_for_current_admin($provider)) {
			echo wpcal_max_tp_account_limit_reached_error_msg($provider);
			exit;
		}
	}

	//verify plan limits before going down
	$tp_class = wpcal_include_and_get_tp_class($provider);
	$tp_obj = new $tp_class(0);
	$url = $tp_obj->get_add_account_url($action);

	if (empty($url)) {
		throw new WPCal_Exception('tp_auth_url_data_missing');
	}

	$redirect_site_url = [
		'zoom_meeting' => WPCAL_ZOOM_OAUTH_REDIRECT_SITE_URL,
		'gotomeeting_meeting' => WPCAL_GOTOMEETING_OAUTH_REDIRECT_SITE_URL,
	];

	$final_url = $redirect_site_url[$provider] . 'cal-api/?tp_provider=' . $provider . '&plugin_slug=' . WPCAL_PLUGIN_SLUG . '&plugin_verion=' . WPCAL_VERSION . '&passed_data=' . urlencode(base64_encode($url));
	//$final_url = $url;

	wp_redirect($final_url);
	exit;
}

function wpcal_tp_account_receive_token_and_add_account($provider, $action = 'add') {
	//having temporarily saving add request and use it again when auth code comes  it will be useful

	if ($action == 'add' && wpcal_tp_accounts_is_limit_reached_for_current_admin($provider)) {
		echo wpcal_max_tp_account_limit_reached_error_msg($provider);
		exit;
	}

	//verify plan limits before going down
	$tp_class = wpcal_include_and_get_tp_class($provider);
	$tp_obj = new $tp_class(0);
	$tp_account_id = $tp_obj->add_account_after_auth($action);

	if ($tp_account_id && in_array($provider, ['gotomeeting_meeting', 'zoom_meeting'])) { // to mark completed in onboarding checklist
		wpcal_mark_onboarding_checklist_completed_for_current_admin('add_meeting_app');
	}

	$tp_page_link = 'admin.php?page=wpcal_admin#/settings/integrations';
	if ($tp_account_id) {
		$tp_page_link .= $tp_account_id ? '/connected/' . $tp_account_id : '';
	}
	wp_redirect($tp_page_link);
	exit;
}

function wpcal_tp_accounts_is_limit_reached_for_current_admin($provider) {
	$admin_user_id = get_current_user_id();

	WPCal_Admins::is_current_user_is_wpcal_admin_check();

	return wpcal_tp_accounts_is_limit_reached($provider, $admin_user_id);
}

function wpcal_tp_accounts_is_limit_reached($provider, $admin_user_id) {
	$providers_setting = [
		'zoom_meeting' => [
			'provider_slug' => 'zoom',
			'provider_type' => 'meeting',
			'limit_per' => 'user',
			'limit' => 1,
		],
		'gotomeeting_meeting' => [
			'provider_slug' => 'gotomeeting',
			'provider_type' => 'meeting',
			'limit_per' => 'user',
			'limit' => 1,
		],
	];

	if (!isset($providers_setting[$provider])) {
		throw new WPCal_Exception('invalid_tp_provider');
	}

	if (empty($admin_user_id) || !is_numeric($admin_user_id)) {
		throw new WPCal_Exception('invalid_admin_user_id');
	}

	$limit = $providers_setting[$provider]['limit'];

	global $wpdb;
	$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';
	$query = "SELECT count(*) FROM `$table_tp_accounts` WHERE `admin_user_id` = %s AND `provider` = %s";
	$query = $wpdb->prepare($query, $admin_user_id, $provider);
	$count = $wpdb->get_var($query);

	return $limit <= $count;
}

function wpcal_get_tp_accounts_of_current_admin() {
	$admin_user_id = get_current_user_id();

	WPCal_Admins::is_current_user_is_wpcal_admin_check();

	$result = wpcal_get_tp_accounts_by_admin($admin_user_id);
	return $result;
}

function wpcal_get_tp_accounts_by_admin($admin_user_id) {
	global $wpdb;
	$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';

	$query = "SELECT `id`, `admin_user_id`, `provider`, `provider_type`, `status`, `tp_user_id`, `tp_account_email` FROM `$table_tp_accounts` WHERE `admin_user_id` = %s";
	$query = $wpdb->prepare($query, $admin_user_id);
	$result = $wpdb->get_results($query);
	if ($result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}
	return $result;
}

function wpcal_remove_tp_account_without_revoke_by_id($tp_account_id, $provider) {
	//to avoid getting disconencted from other login or site where API access with WPCal is authorised. $revoke set to false
	return wpcal_disconnect_tp_account_by_id($tp_account_id, $provider, $force = true, $revoke = false);
}

function wpcal_disconnect_tp_account_by_id($tp_account_id, $provider, $force = false, $revoke = true) {

	$tp_account_class = wpcal_include_and_get_tp_class($provider);
	$tp_account_obj = new $tp_account_class($tp_account_id);

	$result = $tp_account_obj->revoke_access_and_delete_its_data($force, $revoke);
	return $result;
}

function wpcal_check_auth_if_fails_may_remove_tp_accounts_for_current_admin() {
	$tp_accounts = wpcal_get_tp_accounts_of_current_admin();
	$supported_auth_check = ['zoom_meeting', 'gotomeeting_meeting'];
	// when number checks increases it won't be enough check everything within the avaialble timeout.
	$result_accounts = [];

	//one removal per call - that is better - also because of exception
	foreach ($tp_accounts as $tp_account) {
		if ($tp_account->status != 1) {
			// if not status is 1, then don't check it again
			continue;
		}
		if (in_array($tp_account->provider, $supported_auth_check, true)) {
			$tp_class = wpcal_include_and_get_tp_class($tp_account->provider);
			$tp_obj = new $tp_class($tp_account->id);
			if (WPCAL_ON_AUTH_FAIL_MAY_REMOVE_ACCOUNT && method_exists($tp_obj, 'check_auth_if_fails_remove_account')) { // only for zoom
				$result = $tp_obj->check_auth_if_fails_remove_account();
				$result_accounts[] = ['id' => $tp_account->id, 'provider' => $tp_account->provider, 'auth_status' => $result];
			} elseif (method_exists($tp_obj, 'check_auth_and_process')) {
				$result = $tp_obj->check_auth_and_process(); // if auth fails, that account status will be changed from active
			}
		}
	}
	return $result_accounts;
}

function wpcal_add_or_update_online_meeting_for_booking(WPCal_Booking $booking_obj) {
	$admin_user_id = $booking_obj->get_admin_user_id();
	if (empty($admin_user_id)) {
		throw new WPCal_Exception('booking_admin_user_id_missing');
	}

	$is_location_needs_tp_account_service = $booking_obj->is_location_needs_tp_account_service();
	if (!$is_location_needs_tp_account_service) {
		throw new WPCal_Exception('booking_location_doesnt_need_online_meeting');
	}

	$provider = $booking_obj->get_location_type();
	$tp_account = wpcal_get_tp_account_by_admin_and_provider($admin_user_id, $provider);

	if (empty($tp_account)) {
		throw new WPCal_Exception('tp_account_missing');
	}

	$tp_class = wpcal_include_and_get_tp_class($tp_account->provider);
	$tp_obj = new $tp_class($tp_account->id);

	wpcal_throw_error_if_tp_api_status_broken($tp_obj);

	if (!empty($booking_obj->get_meeting_tp_resource_id())) {
		$tp_obj->update_meeting($booking_obj);
	} else {
		$tp_obj->create_meeting($booking_obj);
	}
}

function wpcal_delete_online_meeting_for_booking(WPCal_Booking $booking_obj) {

	$admin_user_id = $booking_obj->get_admin_user_id();
	if (empty($admin_user_id)) {
		throw new WPCal_Exception('booking_admin_user_id_missing');
	}

	$meeting_tp_resource_id = $booking_obj->get_meeting_tp_resource_id();
	$tp_resource_obj = new WPCal_TP_Resource($meeting_tp_resource_id);
	$provider = $tp_resource_obj->get_provider();

	$tp_account = wpcal_get_tp_account_by_admin_and_provider($admin_user_id, $provider);

	if (empty($tp_account)) {
		throw new WPCal_Exception('tp_account_missing');
	}

	$tp_class = wpcal_include_and_get_tp_class($tp_account->provider);
	$tp_obj = new $tp_class($tp_account->id);

	wpcal_throw_error_if_tp_api_status_broken($tp_obj);

	$tp_obj->delete_meeting($booking_obj);
}

function wpcal_get_active_tp_account_by_admin_and_provider($admin_user_id, $provider) {

	$result = wpcal_get_tp_account_by_admin_and_provider($admin_user_id, $provider, $options = ['status_type' => 'active']);
	return $result;
}

function wpcal_get_tp_account_by_admin_and_provider($admin_user_id, $provider, $options = []) {
	global $wpdb;
	$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';
	$query = "SELECT * FROM `$table_tp_accounts` WHERE `admin_user_id` = %s AND `provider` = %s";

	if (!empty($options['status_type']) && $options['status_type'] == 'active') {
		$query .= " AND `status` = 1";
	}
	$query = $wpdb->prepare($query, $admin_user_id, $provider);
	$result = $wpdb->get_row($query);

	if ($result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}

	if (!empty($result)) {
		unset($result->api_token);
	}
	return $result;
}

function wpcal_booking_update_online_meeting_details(WPCal_Booking $booking_obj, $location_type, $new_location_form_data, $meeting_tp_resource_id) {
	global $wpdb;
	if ($booking_obj->get_location_type() !== $location_type) {
		throw new WPCal_Exception('booking_location_type_mismatch');
	}
	$location_details = $booking_obj->get_location();
	if (!isset($location_details['form'])) {
		$location_details['form'] = [];
	}
	$new_location_form_data = wpcal_get_allowed_fields($new_location_form_data, ['location', 'password_data', 'display_meeting_id']);
	if (empty($new_location_form_data['location'])) {
		throw new WPCal_Exception('location_details_not_available');
	}
	$location_details['form'] = array_merge($location_details['form'], $new_location_form_data);

	$location_details = json_encode($location_details);

	$table_bookings = $wpdb->prefix . 'wpcal_bookings';
	$update_data = [
		'location' => $location_details,
		'meeting_tp_resource_id' => $meeting_tp_resource_id,
		'updated_ts' => time(),
	];
	$result = $wpdb->update($table_bookings, $update_data, array('id' => $booking_obj->get_id()));
	if ($result === false) {
		throw new WPCal_Exception('db_error', '', $wpdb->last_error);
	}
	return true;
}

function wpcal_get_tp_locations_for_current_admin() {

	$admin_user_id = get_current_user_id();

	WPCal_Admins::is_current_user_is_wpcal_admin_check();

	return wpcal_get_tp_locations_by_admin($admin_user_id);
}

function wpcal_get_tp_locations_by_admin($admin_user_id) {
	$tp_locations = [
		'zoom_meeting' => [
			'is_connected_and_active' => false,
			'is_connected' => false,
			'is_active' => false,
		],
		'gotomeeting_meeting' => [
			'is_connected_and_active' => false,
			'is_connected' => false,
			'is_active' => false,
		],
		'googlemeet_meeting' => [
			'is_connected_and_active' => false,
			'is_connected' => false,
			'is_active' => false,
		],
	];
	$tp_accounts = wpcal_get_tp_accounts_by_admin($admin_user_id);
	foreach ($tp_accounts as $tp_account) {
		foreach ($tp_locations as $tp_location_type => $tp_location_details) {
			if ($tp_location_type === 'googlemeet_meeting') {
				continue;
			}
			if ($tp_account->provider === $tp_location_type) {
				$tp_locations[$tp_location_type]['is_connected'] = true;
				if ($tp_account->status == '1') {
					$tp_locations[$tp_location_type]['is_active'] = true;
				}
				$tp_locations[$tp_location_type]['is_connected_and_active'] = $tp_locations[$tp_location_type]['is_connected'] && $tp_locations[$tp_location_type]['is_active'];
				break;
			}
		}
	}

	$add_bookings_to_calendar = wpcal_get_add_bookings_to_calendar_by_admin($admin_user_id);
	if (!empty($add_bookings_to_calendar) && $add_bookings_to_calendar->provider == 'google_calendar' && $add_bookings_to_calendar->calendar_id) {
		$tp_locations['googlemeet_meeting']['is_connected'] = true;
		$tp_locations['googlemeet_meeting']['is_active'] = false;
		$tp_locations['googlemeet_meeting']['account_email'] = '';
		if ($add_bookings_to_calendar->calendar_account_status == '1') {
			$tp_locations['googlemeet_meeting']['is_active'] = true;

			$tp_locations['googlemeet_meeting']['account_email'] = $add_bookings_to_calendar->calendar_account_email;
		}
		$tp_locations['googlemeet_meeting']['is_connected_and_active'] = $tp_locations['googlemeet_meeting']['is_connected'] && $tp_locations['googlemeet_meeting']['is_active'];
	}

	return $tp_locations;
}

function wpcal_get_booking_location_content($booking, $for, $whos_view, $provider = '', $options = []) {
	if (!in_array($for, ['calendar_event'])) {
		return '';
	}

	$is_html = false;

	$phone_html = true;
	if ($for === 'calendar_event') {
		$phone_html = false; //currently not working in google_calendar
	}

	if (isset($options['is_html']) && $options['is_html']) {
		$is_html = true;
	}

	if (!$is_html) {
		$phone_html = false;
	}

	$booking_obj = wpcal_get_booking($booking);
	$location = $booking_obj->get_location();

	if (empty($location) || empty($location['type'])) {
		return '';
	}

	$label_of_location_types = [
		'zoom_meeting' => 'Zoom',
		'gotomeeting_meeting' => 'GoToMeeting',
		'googlemeet_meeting' => 'Google Hangout / Meet',
	];

	$line_break = "\n";

	$location_html = '';
	$location_txt = '';

	if (empty($location['form']['location']) && $location['type'] === 'googlemeet_meeting' && !empty($options['override_location_if_empty'])) {
		$location['form']['location'] = $options['override_location_if_empty'];
	}

	if (in_array($location['type'], ['zoom_meeting', 'gotomeeting_meeting', 'googlemeet_meeting']) && !empty($location['form']['location'])) {

		$location_html .= '<b>' . $label_of_location_types[$location['type']] . ' ' . __('Web Conference', 'wpcal') . '</b>';
		$location_txt .= $label_of_location_types[$location['type']] . ' ' . __('Web Conference', 'wpcal') . '';
		$location_html .= $line_break . '<a href="' . $location['form']['location'] . '">' . $location['form']['location'] . '</a>';
		$location_txt .= $line_break . $location['form']['location'];

		$meeting_id_label = $location['type'] === 'googlemeet_meeting' ? __('Meeting code', 'wpcal') : __('Meeting ID', 'wpcal');

		if (!empty($location['form']['display_meeting_id'])) {
			$location_html .= $line_break . $label_of_location_types[$location['type']] . ' ' . $meeting_id_label . ': ' . $location['form']['display_meeting_id'];
			$location_txt .= $line_break . $label_of_location_types[$location['type']] . ' ' . $meeting_id_label . ': ' . $location['form']['display_meeting_id'];
		}

		if (!empty($location['form']['password_data']['password'])) {
			$password_label = $location['form']['password_data']['label'] ? __($location['form']['password_data']['label'], 'wpcal') : __('Password', 'wpcal');
			$location_html .= $line_break . $password_label . ': ' . $location['form']['password_data']['password'];
			$location_txt .= $line_break . $password_label . ': ' . $location['form']['password_data']['password'];
		}

		$location_html .= $line_break . __('You can join from any device.', 'wpcal');
		$location_txt .= $line_break . __('You can join from any device.', 'wpcal');
	} elseif ($location['type'] === 'phone' && !empty($location['form']['location']) && !empty($location['form']['who_calls'])) {
		//$location_html .= '<b>Phone call</b>';
		$location_str = $booking_obj->get_location_str($whos_view, $phone_html);
		$location_html .= $line_break . $location_str;
		$location_txt .= $line_break . $location_str;
	} elseif (in_array($location['type'], ['physical', 'custom', 'ask_invitee']) && !empty($location['form']['location'])) {
		$location_html .= '<b>' . $location['form']['location'] . '</b>';
		$location_txt .= $location['form']['location'];

		if (in_array($location['type'], ['physical', 'custom']) && !empty($location['form']['location_extra'])) {
			$location_html .= $line_break . $location['form']['location_extra'];
			$location_txt .= $line_break . $location['form']['location_extra'];
		}
	}

	if (!empty($location_html)) {
		$location_html = __('Location:', 'wpcal') . ' ' . $location_html;
	}
	if (!empty($location_txt)) {
		$location_txt = __('Location:', 'wpcal') . ' ' . $location_txt;
	}

	$return_str = $is_html ? $location_html : $location_txt;
	return $return_str;
}

// Following code commented - no longer in use
// function wpcal_get_booking_descr_for_calendar(WPCal_Service $service_obj, WPCal_Booking $booking_obj, $location_descr, $whos_view, $options = []) {

// 	$is_html = false;

// 	if (isset($options['is_html']) && $options['is_html']) {
// 		$is_html = true;
// 	}

// 	$descr_html = '';
// 	$descr_txt = '';

// 	$descr_html = __('Event:', 'wpcal') . ' <b>' . $service_obj->get_name() . '</b>
// ';
// 	$descr_txt = __('Event:', 'wpcal') . ' ' . $service_obj->get_name() . '
// ';
// 	if ($location_descr) {
// 		$descr_html .= "\n" . $location_descr . "\n";
// 		$descr_txt .= "\n" . $location_descr . "\n";
// 	}

// 	if ($whos_view == 'neutral' || $whos_view == 'user') {
// 		$descr_html .= '
// ' . __('Need to make changes to this event?', 'wpcal') . '
// <a href="' . $booking_obj->get_redirect_cancel_url() . '">' . __('Cancel this event click here', 'wpcal') . '</a>
// <a href="' . $booking_obj->get_redirect_reschedule_url() . '">' . __('Reschedule this event click here', 'wpcal') . '</a>

// ' . __('Powered by', 'wpcal') . ' <a href="' . WPCAL_SITE_URL . '?utm_source=gcal&utm_medium=event">WPCal.io</a>';

// 		$descr_txt .= '
// ' . __('Need to make changes to this event?', 'wpcal') . '
// ' . __('Cancel this event', 'wpcal') . ' - ' . $booking_obj->get_redirect_cancel_url() . '
// ' . __('Reschedule this event', 'wpcal') . ' - ' . $booking_obj->get_redirect_reschedule_url() . '

// ' . __('Powered by', 'wpcal') . ' ' . WPCAL_SITE_URL;

// 	} elseif ($whos_view == 'admin') {
// 		$descr_html .= '
// 		' . __('To cancel or reshedule the event please visit - ', 'wpcal') . '<a href="' . $booking_obj->get_admin_view_booking_url() . '">' . __('Cancel/Reschedule this event click here', 'wpcal') . '</a>';

// 		$descr_txt .= '
// 		' . __('To cancel or reshedule the event please visit - ', 'wpcal') . $booking_obj->get_admin_view_booking_url();
// 	}

// 	$return_str = $is_html ? $descr_html : $descr_txt;
// 	return $return_str;
// }

function wpcal_get_booking_contents_for_calendar(WPCal_Service $service_obj, WPCal_Booking $booking_obj, $location_descr, $admin_details, $whos_view, $options = []) {
	$args = compact(
		'service_obj',
		'booking_obj',
		'location_descr',
		'admin_details',
		'whos_view',
		'options'
	);
	$template_result = wpcal_get_template('calendar_events/common_calendar_event_plain.php', $args, '', $_options = ['return' => true]);
	if (empty($template_result['summary']) || empty($template_result['descr'])) {
		throw new WPCal_Exception('unable_get_data_from_calendar_event_template');
	}
	return $template_result;
}

function wpcal_process_get_active_admin_ids() {
	global $wpdb;

	$table_admins = $wpdb->prefix . 'wpcal_admins';

	$query = "SELECT `id` FROM `$table_admins`  WHERE `status` = 1 ORDER BY `id` ASC LIMIT 1";
	$admin_id = $wpdb->get_var($query);
	return $admin_id ? $admin_id : false;
}

function wpcal_process_get_active_service_ids() {
	global $wpdb;

	$table_services = $wpdb->prefix . 'wpcal_services';

	$services_query = "SELECT `id` FROM `$table_services`  WHERE `status` = 1 ORDER BY `id` ASC LIMIT 1";
	$service_id = $wpdb->get_var($services_query);
	return $service_id ? $service_id : false;
}

function wpcal_process_features($force = false) {
	global $wpdb;

	$current_time = time();
	$features_last_checked = get_option('wpcal_features_last_checked');
	if (!$force && $features_last_checked && $features_last_checked > ($current_time - (10 * 60)) && $features_last_checked < ($current_time + (20 * 60))) {
		return false;
	}

	$table_services = $wpdb->prefix . 'wpcal_services';
	$table_admins = $wpdb->prefix . 'wpcal_admins';
	$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';

	$current_db_version = get_option('wpcal_db_version', '0.0');
	if (version_compare($current_db_version, '0.9.5.0', '<')) {
		return;
	}

	$make_a_free_way = WPCal_License::get_public();

	if ($make_a_free_way) {

		$update_services_query = "UPDATE `$table_services` SET `status` = -7 WHERE `status` = 1 AND `id` NOT IN (SELECT * FROM (SELECT `id` FROM `$table_services` WHERE `status` = 1 ORDER BY `id` ASC LIMIT 1) as `tmp_table`) LIMIT 2000";

		$update_admins_query = "UPDATE `$table_admins` SET `status` = -7 WHERE `status` = 1 AND `id` NOT IN (SELECT * FROM (SELECT `id` FROM `$table_admins` WHERE `status` = 1 ORDER BY `id` ASC LIMIT 1) as `tmp_table`) LIMIT 2000";

		$update_calendar_accounts_query = "UPDATE `$table_calendar_accounts` SET `status` = -7 WHERE `status` = 1 AND `id` NOT IN (SELECT * FROM (SELECT `id` FROM `$table_calendar_accounts` WHERE `status` = 1 ORDER BY `id` ASC LIMIT 1) as `tmp_table`) LIMIT 2000";

	} else {
		$update_services_query = "UPDATE `$table_services` SET `status` = 1 WHERE `status` = -7 ORDER BY `id` ASC LIMIT 2000";

		$update_admins_query = "UPDATE `$table_admins` SET `status` = 1 WHERE `status` = -7 ORDER BY `id` ASC LIMIT 2000";

		$update_calendar_accounts_query = "UPDATE `$table_calendar_accounts` SET `status` = 1 WHERE `status` = -7 ORDER BY `id` ASC LIMIT 2000";
	}

	$wpdb->query($update_services_query);
	$wpdb->query($update_admins_query);
	$wpdb->query($update_calendar_accounts_query);

	update_option('wpcal_features_last_checked', $current_time);

}

function wpcal_on_plugin_activation_user_if_not_wpcal_admin_add_notice() {
	//this should be called on plugin activation only
	if (WPCal_Admins::is_current_user_is_wpcal_admin()) {
		return;
	}

	if (current_user_can('administrator')) { // we are showing menu to WP Administrators so no need of notice
		return;
	}

	$admin_user_id = get_current_user_id();
	if (empty($admin_user_id)) {
		return;
	}

	$notice_data = [
		'slug' => 'cant_see_wpcal_admin_area__' . $admin_user_id,
		'category' => 'user_not_wpcal_admin_cant_see_wpcal_admin_area_notice',
		'type' => 'info',
		'display_in' => 'wp_admin',
		'display_user_ids' => [$admin_user_id],
		'dismiss_type' => 'dismissible',
		'dismiss_by' => 'individual',
		'to_time_ts' => time() + (30 * 60), //30 mins
		'must_revalidate' => '1',
	];
	$options = ['remove_old_notice_by' => 'category_and_user'];
	WPCal_Manage_Notices::add_notice($notice_data, $options);
}

function wpcal_calendars_required_reauth_add_notice($admin_user_id) {

	$notice_data = [
		'slug' => 'calendars_required_reauth__' . $admin_user_id,
		'category' => 'calendars_required_reauth_notice',
		'type' => 'error',
		'display_to' => 'wpcal_admin', //if no longer wpcal admin, this notice will not display
		'display_in' => 'wp_admin_and_wpcal_admin',
		'display_user_ids' => [$admin_user_id],
		'dismiss_type' => 'dismissible',
		'dismiss_by' => 'individual',
		'must_revalidate' => '1',
	];
	$options = ['remove_old_notice_by' => 'category_and_user'];
	WPCal_Manage_Notices::add_notice($notice_data, $options);
}

function wpcal_tp_accounts_required_reauth_add_notice($admin_user_id) {

	$notice_data = [
		'slug' => 'tp_accounts_required_reauth__' . $admin_user_id,
		'category' => 'tp_accounts_required_reauth_notice',
		'type' => 'error',
		'display_to' => 'wpcal_admin', //if no longer wpcal admin, this notice will not display
		'display_in' => 'wp_admin_and_wpcal_admin',
		'display_user_ids' => [$admin_user_id],
		'dismiss_type' => 'dismissible',
		'dismiss_by' => 'individual',
		'must_revalidate' => '1',
	];
	$options = ['remove_old_notice_by' => 'category_and_user'];
	WPCal_Manage_Notices::add_notice($notice_data, $options);
}

function wpcal_is_current_admin_owns_resource($resource_type, $resource_id, $on_error_throw = true) {
	$admin_user_id = get_current_user_id();
	try {
		$is_owns = wpcal_is_admin_owns_resource($resource_type, $resource_id, $admin_user_id);
	} catch (WPCal_Exception $e) {
		if ($on_error_throw) {
			throw $e;
		}
		return false;
	}
	if (!$is_owns && $on_error_throw) {
		throw new WPCal_Exception('access_denied');
	}
	return $is_owns;
}

function wpcal_is_admin_owns_resource($resource_type, $resource_id, $admin_user_id) {
	global $wpdb;

	$allowed_resource_types = [
		'service',
		'booking',
		'calendar_account',
		'tp_account', //integration
	];
	if (!in_array($resource_type, $allowed_resource_types, true)) {
		throw new WPCal_Exception('invalid_resource_type');
	}
	if (empty($resource_id) || !is_numeric($resource_id)) {
		throw new WPCal_Exception('invalid_resource_id');
	}

	WPCal_Admins::is_wpcal_admin_active_check($admin_user_id);

	if ($resource_type === 'service') {
		try {
			$service_obj = new WPCal_Service($resource_id);
			$service_manage_admins = $service_obj->get_manage_admins(); //need this to check private_manage service
			$is_manage = in_array($admin_user_id, $service_manage_admins);
			return $is_manage;
		} catch (WPCal_Exception $e) {
			$error = $e->getError();
			if ($error === 'service_id_not_exists') {
				throw new WPCal_Exception('resource_id_not_exists');
			}
			throw $e;
		}
	} elseif ($resource_type === 'booking') {
		try {
			$booking_obj = new WPCal_Booking($resource_id);
			$service_manage_admins = $booking_obj->service_obj->get_manage_admins(); //need this to check private_manage service
			$is_manage = in_array($admin_user_id, $service_manage_admins);
			return $is_manage;
		} catch (WPCal_Exception $e) {
			$error = $e->getError();
			if ($error === 'booking_id_not_exists') {
				throw new WPCal_Exception('resource_id_not_exists');
			}
			throw $e;
		}
	} elseif ($resource_type === 'calendar_account') {
		$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
		$query = "SELECT `admin_user_id` FROM `$table_calendar_accounts` WHERE `id` = %s";
		$query = $wpdb->prepare($query, $resource_id);
		$calendar_account_details = $wpdb->get_row($query);
		if ($calendar_account_details === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		} elseif (empty($calendar_account_details)) {
			throw new WPCal_Exception('resource_id_not_exists');
		}
		$calendar_account_admin_id = $calendar_account_details->admin_user_id;
		$is_owns = ($calendar_account_admin_id == $admin_user_id);
		return $is_owns;
	} elseif ($resource_type === 'tp_account') {
		$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';
		$query = "SELECT `admin_user_id` FROM `$table_tp_accounts` WHERE `id` = %s";
		$query = $wpdb->prepare($query, $resource_id);
		$tp_account_details = $wpdb->get_row($query);
		if ($tp_account_details === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		} elseif (empty($tp_account_details)) {
			throw new WPCal_Exception('resource_id_not_exists');
		}
		$tp_account_admin_id = $tp_account_details->admin_user_id;
		$is_owns = ($tp_account_admin_id == $admin_user_id);
		return $is_owns;
	}
	return false;
}

function wpcal_is_task_waiting_now() {
	if (WPCal_Background_Tasks::is_task_waiting_now()) {
		return true;
	}

	// Improve check calendar event and list sync pending

	return false;
}

function wpcal_check_min_version_requirements() {

	static $cached_result = null;

	if ($cached_result !== null) {
		return $cached_result;
	}

	$required = array();
	$required['php']['version'] = '7.1';
	$required['mysql']['version'] = '5.5';
	$required['wp']['version'] = '5.0';

	$mysql_full_version = $GLOBALS['wpdb']->get_var("SELECT VERSION()");
	$mysql_tmp = explode('-', $mysql_full_version);
	$mysql_version = array_shift($mysql_tmp);

	$php_version = PHP_VERSION;
	$php_tmp = explode('-', $php_version);
	$php_version = array_shift($php_tmp);

	$installed = array();
	$installed['php']['version'] = $php_version;
	$installed['mysql']['version'] = $mysql_version;
	$installed['wp']['version'] = get_bloginfo('version');

	$is_all_ok = true;
	if (version_compare($php_version, $required['php']['version'], '<')) {
		//not ok
		$is_all_ok = false;
	}
	if (version_compare($mysql_version, $required['mysql']['version'], '<')) {
		//not ok
		$is_all_ok = false;
	}
	if (version_compare($installed['wp']['version'], $required['wp']['version'], '<')) {
		//not ok
		$is_all_ok = false;
	}
	if ($is_all_ok) {
		$result = true;
	} else {
		$result = array('required' => $required, 'installed' => $installed);
	}

	$cached_result = $result;
	return $result;
}

function wpcal_get_template_overrides() {

	$template_overrides = [];
	$templates = [
		'emails/header.php',
		'emails/footer.php',
		'emails/invitee_booking_new.php',
		'emails/invitee_booking_reschedule.php',
		'emails/invitee_booking_cancellation.php',
		'emails/invitee_booking_reminder.php',
		'calendar_events/common_calendar_event_plain.php',

		'emails/admin_booking_new.php',
		'emails/admin_booking_reschedule.php',
		'emails/admin_booking_cancellation.php',
		'emails/admin_api_error_need_action.php',
	];

	foreach ($templates as $template) {
		$result_template = wpcal_get_template($template, [], '', ['return_template' => true]);
		$full_template = WPCAL_PATH . '/templates/' . $template;
		if (!empty($result_template) && $result_template != $full_template) {
			$result_template_without_prefix = wpcal_may_remove_prefix_str($result_template, ABSPATH);
			$template_default = 'wpcal/templates/' . $template;
			$template_overrides[$template_default] = $result_template_without_prefix;
		}
	}
	return $template_overrides;
}

function wpcal_add_nerd_stats_to_request_result(&$ajax_request_result, $process_ajax_start_time) {
	$time_taken = microtime(1) - $process_ajax_start_time;
	$ajax_request_result['_nerd_stats'] = [
		'wpcal_ajax_time_taken' => number_format($time_taken, 4),
		'wp_total_time_taken' => timer_stop(0, 4),
	];
}

function wpcal_dev_preview_all_emails() {

	$locations = [

		[
			'type' => 'physical',
			'form' =>
			[
				'location' => 'City Center',
				'location_extra' => 'Mainland China, 3rd floor',
			],
		],

		[
			'type' => 'physical',
			'form' =>
			[
				'location' => 'Besant Nagar Beach',
				'location_extra' => '',
			],
		],

		[
			'type' => 'phone',
			'form' =>
			[
				'who_calls' => 'admin',
				'location' => '+1 555 555 7890',
			],
		],

		[
			'type' => 'phone',
			'form' =>
			[
				'who_calls' => 'invitee',
				'location' => '+1 666 666 7890',
			],
		],

		[
			'type' => 'googlemeet_meeting',
			'form' =>
			[
				'location' => 'https://meet.google.com/aaa-bbbb-ccc',
			],
		],

		[
			'type' => 'zoom_meeting',
			'form' =>
			[
				'location' => 'https://us04web.zoom.us/j/11112222333?pwd=cU11ZmFoRlFIV0lMZkgga25uSGV4dz09',
				'password_data' =>
				[
					'label' => 'Password',
					'password' => '78hhjCp',
				],

			],
		],

		[
			'type' => 'gotomeeting_meeting',
			'form' =>
			[
				'location' => 'https://global.gotomeeting.com/join/1111222333',
			],
		],

		[
			'type' => 'custom',
			'form' =>
			[
				'location' => 'Skype',
				'location_extra' => 'Skype ID: eureka',
			],
		],

		[
			'type' => 'custom',
			'form' =>
			[
				'location' => 'Yahoo Messenger',
				'location_extra' => '',
			],
		],

		[
			'type' => 'ask_invitee',
			'form' =>
			[
				'location' => 'Cafe',
				'location_extra' => 'Anywhere within chennai.',
			],
		],
	];

	$services = wpcal_get_services_of_current_admin();
	if (empty($services)) {
		echo 'Current admin should have atleast one active Event Type to see the preview';
		return;
	}
	$service_id = $services[0]->id;

	$booking_from = new DateTime('now', wp_timezone());
	$booking_from->add(new DateInterval('P2D'));
	$booking_from->setTime(10, 15, 0);
	$booking_to = clone $booking_from;
	$booking_to->setTime(10, 45, 0);

	$booking_obj = new WPCal_Booking(0);
	$booking_obj->set_service_id($service_id);
	$booking_obj->set_status(1);
	$booking_obj->set_unique_link('593cd87bcd498f41231de722f9425bd65a9929fd');
	$booking_obj->set_admin_user_id(wp_get_current_user()->ID);
	$booking_obj->set_invitee_wp_user_id('');
	$booking_obj->set_invitee_name('John Doe');
	$booking_obj->set_invitee_email('john@example.com');
	$booking_obj->set_invitee_tz('Asia/Kolkata');
	$booking_obj->set_invitee_question_answers([]);
	$booking_obj->set_booking_from_time($booking_from->format('U'));
	$booking_obj->set_booking_to_time($booking_to->format('U'));
	$booking_obj->set_booking_ip('127.0.0.1');
	$booking_obj->set_location([]);

	$mail_categories = [
		'new_booking' => [
			'send_admin_new_booking_info',
			'send_invitee_booking_confirmation',
			'schedule_invitee_booking_reminder',
		],
		'reschedule_booking' => [
			'send_admin_reschedule_booking_info',
			'send_invitee_reschedule_booking_confirmation',
		],
		'cancel_booking' => [
			'send_admin_booking_cancellation',
			'send_invitee_booking_cancellation',
		],
	];

	WPCal_Mail::$dev_preview = true;

	foreach ($locations as $location) {
		var_dump($location);
		//foreach($mail_categories as $mail_category){
		$mail_category = 'new_booking';
		$mail_category_data = $mail_categories[$mail_category];
		foreach ($mail_category_data as $mail_type) {
			echo $mail_category . '<br>--------------------------<br>';
			echo $mail_type . '<br>--------------------------<br>';
			$booking_obj->set_location(json_encode($location));
			call_user_func(array('WPCal_Mail', $mail_type), $booking_obj);
		}
		//}
	}

	//atleast have a Google Calendar Connection

	if (!class_exists('WPCal_TP_Sample_Calendar')) {
		include_once WPCAL_PATH . '/includes/tp_calendars/abstract_tp_calendar.php';
		class WPCal_TP_Sample_Calendar extends WPCal_Abstract_TP_Calendar {
			public function get_cal_account_id() {
				return 0;
			}

			public function get_provider() {
				return 'google_calendar';
			}

			public function get_cal_account_details() {
				return (object) ['admin_user_id' => 0, 'account_email' => 'sample@example.com'];
			}

			public function api_refresh_calendars() {}
			public function api_add_event($cal_details, WPCal_Booking $booking_obj) {}
			public function api_update_event($cal_details, WPCal_Booking $booking_obj) {}
			public function api_delete_event($cal_details, WPCal_Booking $booking_obj) {}
		}
	}

	$sample_calendar_obj = new WPCal_TP_Sample_Calendar();

	echo '<br><br>send_admin_api_error_need_action' . '<br>--------------------------<br>';

	call_user_func(array('WPCal_Mail', 'send_admin_api_error_need_action'), $sample_calendar_obj);

	// foreach( $locations as $location){
	// 	$booking_obj->set_location($location);
	// 	$mail_type = '';
	// 	call_user_func(array(WPCal_Mail, $mail_type), $booking_obj);
	// }

	WPCal_Mail::$dev_preview = false;

}

// Following for translation purpose, these will come as variables
__('Passcode', 'wpcal');
