<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Admins {

	private static $is_current_user_is_wpcal_admin_cache = [];

	public static function add_wpcal_admin($admin_data) {
		return self::update_wpcal_admin($admin_data, $admin_user_id = 0);
	}

	public static function update_wpcal_admin($admin_data, $admin_user_id) {
		global $wpdb;

		if ($admin_user_id) {
			$update = true;
		} else {
			$update = false;
		}

		$allowed_keys = [
			'admin_user_id',
			'admin_type',
			'status',
		];

		// $default_data = [
		// 	'status' => 1,
		// ];

		// $data = array_merge($default_data, $update_data);
		$data = $admin_data;

		$data = wpcal_get_allowed_fields($data, $allowed_keys);

		$sanitize_rules = [
			//'descr' => 'sanitize_textarea_field',
		];

		$data = wpcal_sanitize_all($data, $sanitize_rules);

		if (!$update) {
			$data['status'] = 1;
		}

		$validate_obj = new WPCal_Validate($data);
		$validate_obj->rules([
			'required' => [
				'admin_user_id',
				'admin_type',
				'status',
			],
			'integer' => [
				'admin_user_id',
			],
			'in' => [
				['admin_type', ['administrator']],
				['status', [1, -1, -2]], //-1: disabled, -2: deleted
			],
		]);

		$validation_errors = [];
		if (!$validate_obj->validate()) {
			$validation_errors = $validate_obj->errors();
		}

		if (!self::is_min_wpcal_admin_req_met($data['admin_user_id'])) {
			$validation_errors['admin_capabilities'][] = 'Admin doesn\'t have required WP admin capability(Edit post).';
		}

		$make_a_free_way = WPCal_License::get_public();
		if (!$update && $make_a_free_way && $data['status'] == 1) {
			$_service_id = wpcal_process_get_active_service_ids();
			if (is_numeric($_service_id)) {
				throw new WPCal_Exception('only_one_active_admin_allowed_as_per_plan');
			}
		}

		if (!empty($validation_errors)) {
			throw new WPCal_Exception('validation_errors', '', $validation_errors);
		}

		$data['updated_ts'] = time();
		if (!$update) {
			$data['status'] = 1;
			$data['added_ts'] = time();
		}

		$table_admins = $wpdb->prefix . 'wpcal_admins';

		$already_added = self::get_wpcal_admin($data['admin_user_id']);

		self::clear_is_current_user_is_wpcal_admin_cache();

		if ($already_added === false) {
			$result = $wpdb->insert($table_admins, $data);
			if ($result === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			} else {
				$admin_user_inc_id = $wpdb->insert_id;
				if (!$admin_user_inc_id) {
					throw new WPCal_Exception('db_error_insert_id_missing');
				}
			}
			wpcal_mark_onboarding_checklist_completed_for_current_admin('add_wpcal_admin');
			return true;

		} elseif ($already_added->status == -2) { //-2: deleted
			$result = $wpdb->replace($table_admins, $data);
			if ($result === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			} else {
				$admin_user_inc_id = $wpdb->insert_id;
				if (!$admin_user_inc_id) {
					throw new WPCal_Exception('db_error_insert_id_missing');
				}
			}
			wpcal_mark_onboarding_checklist_completed_for_current_admin('add_wpcal_admin');
			return true;
		} else {
			$validation_errors['admin_exists'][] = 'Admin already exists, also check for disabled admins.';
			throw new WPCal_Exception('validation_errors', '', $validation_errors);
		}
	}

	public static function enable_wpcal_admin($admin_user_id) {
		return self::update_wpcal_admin_status($admin_user_id, $status = 1);
	}

	public static function disable_wpcal_admin($admin_user_id) {
		self::check_admin_can_be_disabled_or_deleted($admin_user_id, $new_status = -1);
		return self::update_wpcal_admin_status($admin_user_id, $status = -1);
	}

	public static function delete_wpcal_admin($admin_user_id) {
		self::check_admin_can_be_disabled_or_deleted($admin_user_id, $new_status = -2);
		self::delete_all_tp_integrations_and_deletable_data_by_admin($admin_user_id);
		return self::update_wpcal_admin_status($admin_user_id, $status = -2);
	}

	private static function update_wpcal_admin_status($admin_user_id, $status) {

		global $wpdb;

		if (!in_array($status, [1, -1, -2])) {
			throw new WPCal_Exception('invalid_status');
		}

		$admin_user_data = self::get_wpcal_admin($admin_user_id);
		if (!is_object($admin_user_data) || !property_exists($admin_user_data, 'id') || !$admin_user_data->id) {
			throw new WPCal_Exception('admin_not_exists');
		}

		$make_a_free_way = WPCal_License::get_public();
		if ($status == 1 && $make_a_free_way) {
			$_admin_user_id = wpcal_process_get_active_admin_ids();
			if (is_numeric($_admin_user_id) && $_admin_user_id != $admin_user_id) {
				throw new WPCal_Exception('only_one_active_admin_allowed_as_per_plan');
			}
		}

		$data = [];
		$data['status'] = $status;
		$data['updated_ts'] = time();

		$table_admins = $wpdb->prefix . 'wpcal_admins';
		$result = $wpdb->update($table_admins, $data, ['admin_user_id' => $admin_user_id]);

		self::clear_is_current_user_is_wpcal_admin_cache();

		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return true;
	}

	public static function get_wpcal_admin($admin_user_id) {
		global $wpdb;

		$table_admins = $wpdb->prefix . 'wpcal_admins';

		$query = "SELECT * FROM `$table_admins` WHERE `admin_user_id` = %s";
		$query = $wpdb->prepare($query, $admin_user_id);

		$result = $wpdb->get_row($query);
		if ($result) {
			return $result;
		}
		return false;
	}

	public static function is_wpcal_admin_active_check($admin_user_id) {
		//in case of negative, error will be thrown

		if (!self::is_wpcal_admin_active($admin_user_id, $error_throw = true)) {
			throw new WPCal_Exception('user_doesnt_have_admin_rights');
		}
		return true;
	}

	public static function is_wpcal_admin_active($admin_user_id, $error_throw = false) {
		global $wpdb;

		if (empty($admin_user_id) || !is_numeric($admin_user_id)) {
			if ($error_throw) {
				throw new WPCal_Exception('invalid_admin_user_id');
			}
			return false;
		}

		if (!self::is_min_wpcal_admin_req_met($admin_user_id, $error_throw)) {
			return false;
		}

		$table_admins = $wpdb->prefix . 'wpcal_admins';

		$query = "SELECT `admin_user_id` FROM `$table_admins` WHERE `admin_user_id` = %s AND `status` = '1' ";
		$query = $wpdb->prepare($query, $admin_user_id);

		$result = $wpdb->get_var($query);
		if ($result) {
			return true;
		}
		return false;
	}

	// public static function is_wpcal_admin($admin_user_id) {

	// }

	private static function is_min_wpcal_admin_req_met($admin_user_id, $error_throw = false) {
		$admin_user = get_user_by('id', $admin_user_id);

		if (empty($admin_user) || !($admin_user instanceof WP_User)) {
			if ($error_throw) {
				throw new WPCal_Exception('invalid_admin_user_id');
			}
			return false;
		}

		if ($admin_user->has_cap('edit_posts')) {
			return true;
		}
		return false;
	}

	public static function get_all_active_admins($options = []) {
		$result = self::get_all_admins($status = '1', $options);
		return $result;
	}

	public static function get_all_admins($status = '', $options = []) {
		global $wpdb;

		if ($status && !in_array($status, ['1'])) {
			throw new WPCal_Exception('invalid_admin_status');
		}

		$fields = ['admin_user_id'];

		if (!empty($options['admin_details'])) {
			$fields = ['admin_user_id', 'admin_type', 'status'];
		}

		$fields_str = '`' . implode('`, `', $fields) . '`'; //admin_user_id should be the first field

		$table_admins = $wpdb->prefix . 'wpcal_admins';
		$query = "SELECT $fields_str FROM `$table_admins` WHERE 1 = 1";

		if ($status == 1) {
			$query .= " AND `status` = 1";
		} else {
			$query .= " AND `status` != -2";
		}

		$query .= " ORDER BY `status` DESC";

		if (!empty($options['admin_details'])) {
			$result = $wpdb->get_results($query, OBJECT_K);
		} else {
			$result = $wpdb->get_col($query);
		}

		if (!empty($options['admin_details']) && !empty($options['extended_admin_details'])) { //mostly it will create multiple DB calls.
			foreach ($result as $key => &$admin_details) {
				$extended_admin_details = wpcal_get_admin_details($admin_details->admin_user_id);

				$admin_details = (object) array_merge((array) $extended_admin_details, (array) $admin_details);
			}
		}
		return $result;
	}

	public static function is_current_user_is_wpcal_admin($error_throw = false) {
		$current_user_id = get_current_user_id();

		if (!empty(self::$is_current_user_is_wpcal_admin_cache) && self::$is_current_user_is_wpcal_admin_cache['current_user_id'] === $current_user_id) {
			return self::$is_current_user_is_wpcal_admin_cache['is_allowed'];
		}

		if (empty($current_user_id)) {
			return false;
		}

		$result = self::is_wpcal_admin_active($current_user_id, $error_throw);

		self::$is_current_user_is_wpcal_admin_cache['is_allowed'] = $result;
		self::$is_current_user_is_wpcal_admin_cache['current_user_id'] = $current_user_id;

		return $result;
	}

	public static function is_current_user_is_wpcal_admin_check() { //throw error
		if (!self::is_current_user_is_wpcal_admin($error_throw = true)) {
			throw new WPCal_Exception('current_admin_id_missing_or_doesnt_have_enough_privilege_or_not_a_wpcal_admin');
		}
		return true;
	}

	private static function clear_is_current_user_is_wpcal_admin_cache() {
		self::$is_current_user_is_wpcal_admin_cache = [];
	}

	private static function check_admin_can_be_disabled_or_deleted($admin_user_id, $new_status) {

		if (!in_array($new_status, [-1, -2])) {
			throw new WPCal_Exception('invalid_status');
		}

		$admin_user_details = wpcal_get_wpcal_admin_users_details_with_stats(); //disabled and active admins only
		if (!isset($admin_user_details[$admin_user_id])) {
			throw new WPCal_Exception('admin_not_found_not_active_or_disabled');
		}

		$admin_user_detail = $admin_user_details[$admin_user_id];

		if (
			!isset($admin_user_detail->status) ||
			!isset($admin_user_detail->services_active_count) ||
			!isset($admin_user_detail->bookings_upcoming_count)) {
			throw new WPCal_Exception('some_admin_details_missing');
		}

		if ($new_status == -1 && !in_array($admin_user_detail->status, [1])) {
			throw new WPCal_Exception('invalid_status');
		} elseif ($new_status == 1 && !in_array($admin_user_detail->status, [-1])) {
			throw new WPCal_Exception('invalid_status');
		}

		if ($admin_user_detail->services_active_count > 0 || $admin_user_detail->bookings_upcoming_count > 0) {
			throw new WPCal_Exception('admin_have_active_event_types_or_bookings');
		}

		$active_admins_count = 0;
		$last_active_admin_user_id_if_count_one = null;
		foreach ($admin_user_details as $admin_user_detail) {
			if (isset($admin_user_detail->status) && $admin_user_detail->status == 1) {
				$active_admins_count++;
				$last_active_admin_user_id_if_count_one = $admin_user_detail->id;
			}
		}
		if ($active_admins_count <= 1 && $last_active_admin_user_id_if_count_one == $admin_user_id) {
			throw new WPCal_Exception('cannot_disable_or_delete_the_last_active_admin');
		}

		if (!WPCal_Admins::is_current_user_is_wpcal_admin()) { //additional check for security - already checked start of ajax call
			throw new WPCal_Exception('invalid_request');
		}

		$current_admin_user_id = get_current_user_id();
		if ($admin_user_id == $current_admin_user_id) {
			throw new WPCal_Exception('you_cannot_disable_or_delete_yourself');
		}

		return true; //all ok
	}

	private static function delete_all_tp_integrations_and_deletable_data_by_admin($admin_user_id) {

		//assunming $admin_user_id is already validated

		//remove the calendar accounts
		$calendar_accounts = wpcal_get_calendar_accounts_details_by_admin($admin_user_id);
		foreach ($calendar_accounts as $calendar_account) {
			wpcal_remove_calendar_without_revoke_by_id($calendar_account->id, $calendar_account->provider);
		}

		$tp_accounts = wpcal_get_tp_accounts_by_admin($admin_user_id);
		foreach ($tp_accounts as $tp_account) {
			wpcal_remove_tp_account_without_revoke_by_id($tp_account->id, $tp_account->provider);
		}
	}

	public static function is_anyone_wpcal_admin_is_active() {
		global $wpdb;
		$table_admins = $wpdb->prefix . "wpcal_admins";

		$query = "SELECT `id`  FROM `$table_admins` WHERE `status` = '1'";
		$active_wpcal_admins = $wpdb->get_col($query);

		if (empty($active_wpcal_admins)) {
			return false;
		}

		// case: active wpcal admins are there - validate are they active in WP
		foreach ($active_wpcal_admins as $admin_uder_id) {
			$is_min_wpcal_admin_req_met = self::is_min_wpcal_admin_req_met($admin_uder_id); // make sure user exits and having required role
			if ($is_min_wpcal_admin_req_met) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Only WP Administrator is eligble to be added as WPCal Admin, when no WPCal admins are active.
	 */
	private static function while_no_active_wpcal_admins_current_user_can_be_added_as_wpcal_admin() {
		// FOLLOWING NEED TO BE REMOVED ^^^^^^^^^^^^^^^^^^^^^^^^
		// return true;
		// ABOVE NEED TO BE REMOVED ^^^^^^^^^^^^^^^^^^^^^^^^
		if (self::is_anyone_wpcal_admin_is_active()) {
			return false;
		}

		if (self::is_current_user_is_wpcal_admin()) {
			return false;
		}

		if (!current_user_can('administrator')) {
			return false;
		}

		$user_id = get_current_user_id();

		// additional check, whether the current admin is already active as WPCal Admin
		$admin_user_details = self::get_wpcal_admin($user_id);
		if ($admin_user_details && $admin_user_details->status == '1') {
			return false;
		}

		//Not considering about past, whether the user is already deleted or disabled
		return true;
	}

	public static function while_no_active_wpcal_admins_current_user_can_be_self_added_as_wpcal_admin() {
		return self::while_no_active_wpcal_admins_current_user_can_be_added_as_wpcal_admin();
	}

	/**
	 * Add only WP Administrator as WPCal Admin, when no WPCal admins are active.
	 */
	public static function may_add_current_user_as_wpcal_admin_while_no_active_wpcal_admins() {
		global $wpdb;

		if (!self::while_no_active_wpcal_admins_current_user_can_be_added_as_wpcal_admin()) {
			return false;
		}

		$table_admins = $wpdb->prefix . "wpcal_admins";
		$current_user_id = get_current_user_id();
		if (!$current_user_id) {
			return false;
		}

		$is_min_wpcal_admin_req_met = self::is_min_wpcal_admin_req_met($current_user_id);

		if (!$is_min_wpcal_admin_req_met) {
			return false;
		}

		$insert_data = [
			'admin_user_id' => $current_user_id,
			'admin_type' => 'administrator',
			'status' => '1',
			'added_ts' => time(),
			'updated_ts' => time(),
		];

		$result = $wpdb->replace($table_admins, $insert_data);
		return $result;
	}
}
