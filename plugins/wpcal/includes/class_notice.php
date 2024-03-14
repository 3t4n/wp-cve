<?php
/**
 * WPCal.io
 * Copyright (c) 2021 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Notice {
	private $id = null;

	private $category_details = [
		'user_not_wpcal_admin_cant_see_wpcal_admin_area_notice' => [
			'revalidate' => 'user_not_wpcal_admin_cant_see_wpcal_admin_area_notice_revalidate',
			'get_contents' => 'user_not_wpcal_admin_cant_see_wpcal_admin_area_notice_get_contents',
		],
		'calendars_required_reauth_notice' => [
			'revalidate' => 'calendars_required_reauth_notice_revalidate',
			'get_contents' => 'calendars_required_reauth_notice_get_contents',
		],
		'tp_accounts_required_reauth_notice' => [
			'revalidate' => 'tp_accounts_required_reauth_notice_revalidate',
			'get_contents' => 'tp_accounts_required_reauth_notice_get_contents',
		],
	];

	private $title_final = null;
	private $descr_final = null;

	private $data = array(
		'slug' => '',
		'slug_version' => '',
		'status' => '',
		'category' => '',
		'title' => '',
		'descr' => '',
		'source' => '',
		'type' => '',
		'display_type' => '',
		'notice_data' => '',
		'display_in' => '',
		'display_in_condition' => '',
		'display_to' => '',
		'display_user_ids' => '',
		'dismiss_type' => '',
		'dismiss_by' => '',
		'dismissed_user_ids' => '',
		'from_time_ts' => '',
		'to_time_ts' => '',
		'sub_notices' => '',
		'must_revalidate' => '',
		'added_ts' => '',
		'updated_ts' => '',
	);

	private $raw_data = null;

	public function __construct($id = 0, $load_data = null) {
		if (is_numeric($id) && $id > 0) {
			$this->set_id($id);
		}

		if ($this->get_id() > 0) {
			$this->load($load_data);
		}
	}

	public function set_id($id) {
		$this->id = $id;
	}

	public function get_id() {
		return $this->id;
	}

	public function load($load_data = null) {
		global $wpdb;
		$final_data = null;
		if (!empty($load_data)) {
			if (isset($load_data->id) && $load_data->id == $this->get_id()) {
				//check all required properties(keys) exists
				$all_keys_exists = true;
				foreach ($this->data as $_key1 => $_val1) {
					if (!property_exists($load_data, $_key1)) {
						$all_keys_exists = false;
						break;
					}
				}
				if ($all_keys_exists) {
					$final_data = $load_data;
				}
			}
		}

		if ($final_data === null) {
			$table = $wpdb->prefix . 'wpcal_notices';
			$query = "SELECT * FROM `$table` WHERE id = %s";
			$query = $wpdb->prepare($query, $this->get_id());
			$result = $wpdb->get_row($query);
			if (empty($result)) {
				throw new WPCal_Exception('notice_id_not_exists');
			}
			$final_data = $result;
		}

		$this->title_final = null;
		$this->descr_final = null;

		foreach ($final_data as $prop => $value) {
			if (is_string($prop) && isset($this->data[$prop]) && (method_exists($this, 'set_' . $prop) || $this->can_call('set_' . $prop))) {
				$this->{'set_' . $prop}($value);
			}
		}
		$this->raw_data = $final_data;
	}

	public function can_call(string $method_name) {
		static $get_allowed_keys = [
			'slug',
			'slug_version',
			'status',
			'category',
			'title',
			'descr',
			'source',
			'type',
			'display_type',
			'notice_data',
			'display_in',
			'display_in_condition',
			'display_to',
			'display_user_ids',
			'dismiss_type',
			'dismiss_by',
			'dismissed_user_ids',
			'from_time_ts',
			'to_time_ts',
			'sub_notices',
			'must_revalidate',
		];
		static $set_allowed_keys = [
			'slug',
			'slug_version',
			'status',
			'category',
			'title',
			'descr',
			'source',
			'type',
			'display_type',
			'notice_data',
			'display_in',
			'display_in_condition',
			'display_to',
			'display_user_ids',
			'dismiss_type',
			'dismiss_by',
			'dismissed_user_ids',
			'from_time_ts',
			'to_time_ts',
			'sub_notices',
			'must_revalidate',
		];

		$method_name_parts = explode('_', $method_name, 2);
		if (
			count($method_name_parts) !== 2 ||
			!in_array($method_name_parts[0], array('get', 'set'))
		) {
			return false;
		}
		if ($method_name_parts[0] === 'get' && !in_array($method_name_parts[1], $get_allowed_keys)) {
			return false;
		}
		if ($method_name_parts[0] === 'set' && !in_array($method_name_parts[1], $set_allowed_keys)) {
			return false;
		}

		return true;
	}

	public function __call(string $method_name, $args) {
		$trace = debug_backtrace();

		if (!$this->can_call($method_name)) {
			try {
				throw new BadMethodCallException();
			} catch (BadMethodCallException $e) {
				trigger_error('Undefined method  ' . get_class($this) . '::' . $method_name . ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_ERROR);
				//trigger_error('Undefined method  ' . $method_name . ' in ' . $e->getFile() . ' on line ' . $e->getLine(), E_USER_ERROR);
			}
		}

		$method_name_parts = explode('_', $method_name, 2); //this should be sync with can_call check

		if ($method_name_parts[0] === 'get') {
			return $this->get_prop($method_name_parts[1]);
		} elseif ($method_name_parts[0] === 'set') {
			return $this->set_prop($method_name_parts[1], $args[0]);
		}
	}

	private function set_prop($prop, $value) {
		if (isset($this->data[$prop])) {
			$this->data[$prop] = $value;
		}
	}

	private function get_prop($prop) {
		if (isset($this->data[$prop])) {
			return $this->data[$prop];
		}
	}

	private function get_plain_prop($prop) {
		if (isset($this->data[$prop])) {
			if (method_exists($this, 'get_plain_' . $prop)) {
				return call_user_func(array($this, 'get_plain_' . $prop));
			}
			return $this->data[$prop];
		}
	}

	public function get_plain_data() {
		$_data = [];
		foreach ($this->data as $prop => $value) {
			$_data[$prop] = $this->get_plain_prop($prop);
		}
		return $_data;
	}

	//==============================================================>
	public function set_notice_data($value) {
		if (!empty($value)) {
			$value = json_decode($value, true);
		}
		if (!is_array($value)) {
			$value = [];
		}
		return $this->set_prop('notice_data', $value);
	}

	public function set_display_in_condition($value) {
		if (!empty($value)) {
			$value = json_decode($value, true);
		}
		if (!is_array($value)) {
			$value = [];
		}
		return $this->set_prop('display_in_condition', $value);
	}

	public function set_display_user_ids($value) {
		if (!empty($value)) {
			if (!is_array($value)) {
				$value = explode(',', $value);
			}
		} else {
			$value = [];
		}
		return $this->set_prop('display_user_ids', $value);
	}

	public function set_dismissed_user_ids($value) {
		if (!empty($value)) {
			if (!is_array($value)) {
				$value = explode(',', $value);
			}
		} else {
			$value = [];
		}
		return $this->set_prop('dismissed_user_ids', $value);
	}

	public function set_sub_notices($value) {
		if (!empty($value)) {
			$value = explode(',', $value);
		} else {
			$value = [];
		}
		return $this->set_prop('sub_notices', $value);
	}

	//==============================================================>

	public function get_title_final() {
		if ($this->title_final === null) {
			$this->load_contents();
		}
		return $this->title_final ?? $this->get_title();
	}

	public function get_descr_final() {
		if ($this->descr_final === null) {
			$this->load_contents();
		}
		return $this->descr_final ?? $this->get_descr();
	}

	public function must_revalidate() {
		return $this->get_must_revalidate() == 1;
	}

	public function revalidate() {
		// Process checking the notice purpose still exists or not
		$category = $this->get_category();

		if (empty($this->category_details[$category]['revalidate']) || !method_exists($this, $this->category_details[$category]['revalidate'])) {
			//revalidate method missing
			return false;
		}

		$result = call_user_func_array([$this, $this->category_details[$category]['revalidate']], []);
		return $result;

	}

	public function load_contents() {
		$category = $this->get_category();

		if (empty($this->category_details[$category]['get_contents']) || !method_exists($this, $this->category_details[$category]['get_contents'])) {
			//get_contents method missing
			return false;
		}

		$contents = call_user_func_array([$this, $this->category_details[$category]['get_contents']], []);
		$this->title_final = $contents['title'];
		$this->descr_final = $contents['descr'];
	}

	public function update_status($status, $error = '') {
		global $wpdb;

		if (!in_array($status, ['pending', 'started', 'completed', 'dismissed', 'error'])) {
			throw new WPCal_Exception('invalid_status');
		}

		$data = [
			'status' => $status,
			'updated_ts' => time(),
		];

		$table_notices = $wpdb->prefix . 'wpcal_notices';
		$result = $wpdb->update($table_notices, $data, ['id' => $this->get_id()]);

		$this->load();

		return $result !== false;
	}

	public function can_show() {
		//assuming all validation done using DB query are valid including status, display_in, display_in, display_user_ids, dismiss_type(except sub_notice_dismissible)

		if (!empty($display_in_condition = $this->get_display_in_condition())) {
			//handle this case
		}

		if ($this->get_dismiss_type() === 'sub_notice_dismissible') {
			//handle this case
		}

		if ($this->must_revalidate() && !$this->revalidate()) {
			return false;
		}

		return true;
	}

	public function process_dismiss() {
		global $wpdb;

		$is_dismissible = $this->get_dismiss_type() != 'not_dismissible';
		if (!$is_dismissible) {
			return false;
		}

		$current_user_id = get_current_user_id();

		if (!in_array($this->get_status(), ['pending', 'started'])) {
			return false;
		}

		if ($this->get_dismiss_type() == 'dismissible') {
			//validate
			$display_user_ids = $this->get_display_user_ids();
			if (!empty($display_user_ids) && !in_array($current_user_id, $display_user_ids)) {
				//user id list present, but not in the list
				return false;
			}

			$dismissed_user_ids = $this->get_dismissed_user_ids();
			if (!empty($dismissed_user_ids) && in_array($current_user_id, $dismissed_user_ids)) {
				//already dismissed
				return false;
			}

			$display_to = $this->get_display_to();
			if ($display_to == 'wpcal_admins' || $display_to == 'wpcal_admin') {
				if (!WPCal_Admins::is_current_user_is_wpcal_admin()) {
					return false;
				}
			}

			//do dismiss
			$updated_dismissed_user_ids = $dismissed_user_ids;
			$updated_dismissed_user_ids[] = (string) $current_user_id;
			$updated_dismissed_user_ids = array_unique($updated_dismissed_user_ids);
			sort($updated_dismissed_user_ids);

			$mark_as_dismissed = false;
			if (!empty($display_user_ids) && !empty($updated_dismissed_user_ids)) { //assuming all array values are in string even though they are numbers
				$diff_array = array_diff($display_user_ids, $updated_dismissed_user_ids);
				if (empty($diff_array)) {
					$mark_as_dismissed = true;
				}
			}

			$update_data = [
				'dismissed_user_ids' => implode(',', $updated_dismissed_user_ids),
				'updated_ts' => time(),
			];
			if ($mark_as_dismissed) {
				$update_data['status'] = 'dismissed';
			}

			$table_notices = $wpdb->prefix . 'wpcal_notices';

			$wpdb->update($table_notices, $update_data, ['id' => $this->get_id(), 'dismissed_user_ids' => $this->raw_data->dismissed_user_ids]); //dismissed_user_ids in where because say id two users dismissing a common notice in the same time. Still not both dismiss request will get success

			//if going to use this $notice_obj, make sure to call load() for updated data
			return true;
		}
	}

	//==============================================================>

	public function user_not_wpcal_admin_cant_see_wpcal_admin_area_notice_revalidate() {

		$admin_user_id = get_current_user_id();
		if (empty($admin_user_id)) {
			return false;
		}

		// $user_ids = $this->get_display_user_ids();
		// $user_id = $user_ids[0] ?? '';

		// if ($admin_user_id != $user_id) {
		// 	return false;
		// }

		// if (WPCal_Admins::is_current_user_is_wpcal_admin()) { //may be now become admin, lets not show this
		// 	$this->update_status('completed');
		// 	return false;
		// }

		return true;
	}

	public function user_not_wpcal_admin_cant_see_wpcal_admin_area_notice_get_contents() {

		$title = '';
		$descr = 'WPCal.io menu will be displayed only for WPCal admins. If you want to use or manage WPCal, please contact one of the WPCal administrators.';

		return ['title' => $title, 'descr' => $descr];
	}

	public function calendars_required_reauth_notice_revalidate() {

		$admin_user_id = get_current_user_id();
		if (empty($admin_user_id)) {
			return false;
		}

		$affected_calendar_account_ids = [];

		$calendar_accounts = wpcal_get_calendar_accounts_details_by_admin($admin_user_id);
		foreach ($calendar_accounts as $calendar_account) {
			if ($calendar_account->status == -5) {
				$affected_calendar_account_ids[] = $calendar_account->id;
			}
		}

		if (empty($affected_calendar_account_ids)) {
			$this->update_status('completed');
			return false;
		}

		return true;
	}

	public function calendars_required_reauth_notice_get_contents() {

		$admin_user_id = get_current_user_id();
		if (empty($admin_user_id)) {
			return false;
		}

		$affected_calendar_accounts = [];
		$add_bookings_to_affected = false;
		$conflict_checking_affected = false;

		$calendar_accounts = wpcal_get_calendar_accounts_details_by_admin($admin_user_id);
		foreach ($calendar_accounts as $calendar_account) {
			if ($calendar_account->status == -5) {
				if (!isset($affected_calendar_accounts[$calendar_account->provider])) {
					$affected_calendar_accounts[$calendar_account->provider] = [];
				}
				$affected_calendar_accounts[$calendar_account->provider][] = ['account_email' => $calendar_account->account_email];
				if (!empty($calendar_account->calendars)) {
					foreach ($calendar_account->calendars as $calendars) {
						if ($calendars->is_add_events_calendar) {
							$add_bookings_to_affected = true;
						}
						if ($calendars->is_conflict_calendar) {
							$conflict_checking_affected = true;
						}
					}
				}
			}
		}
		if (empty($affected_calendar_accounts)) {
			return false;
		}

		$label_providers = [
			'zoom_meeting' => 'Zoom',
			'gotomeeting_meeting' => 'GoToMeeting',
			'google_calendar' => 'Google Calendar',
		];

		$descr = 'Please reconnect the following calendar account(s) to fix the broken API connection.';
		$descr .= '<br>';
		foreach ($affected_calendar_accounts as $provider => $details) {
			$descr .= '<strong>' . $label_providers[$provider] . ' - </strong><br>';
			foreach ($details as $_key => $account_detail) {
				$descr .= '<strong>' . $account_detail['account_email'] . '</strong><br>';
			}
			$descr .= '<br>';
		}
		$descr .= 'Due to this, the following features are affected - <br>';

		if ($add_bookings_to_affected) {
			$descr .= '<strong>&bull; ' . 'Add booking to calendar' . '</strong><br>';
		}

		if ($conflict_checking_affected) {
			$descr .= '<strong>&bull; ' . 'Conflict calendar(s)' . '</strong><br>';
		}

		$calendar_settings_url = admin_url('admin.php?page=wpcal_admin#/settings/calendars');

		$descr .= '<a class="btn" href="' . $calendar_settings_url . '"> Go to calendar settings</a><br>';

		$title = 'Action required: Calendar API connection broken';
		return ['title' => $title, 'descr' => $descr];
	}

	public function tp_accounts_required_reauth_notice_revalidate() {

		$admin_user_id = get_current_user_id();
		if (empty($admin_user_id)) {
			return false;
		}

		$affected_tp_account_ids = [];

		$tp_accounts = wpcal_get_tp_accounts_by_admin($admin_user_id);
		foreach ($tp_accounts as $tp_account) {
			if ($tp_account->status == -5) {
				$affected_tp_account_ids[] = $tp_account->id;
			}
		}

		if (empty($affected_tp_account_ids)) {
			$this->update_status('completed');
			return false;
		}

		return true;
	}

	public function tp_accounts_required_reauth_notice_get_contents() {

		$admin_user_id = get_current_user_id();
		if (empty($admin_user_id)) {
			return false;
		}

		$affected_tp_accounts = [];

		$tp_accounts = wpcal_get_tp_accounts_by_admin($admin_user_id);
		foreach ($tp_accounts as $tp_account) {
			if ($tp_account->status == -5) {
				if (!isset($affected_tp_accounts[$tp_account->provider])) {
					$affected_tp_accounts[$tp_account->provider] = [];
				}
				$affected_tp_accounts[$tp_account->provider][] = ['account_email' => $tp_account->tp_account_email];
			}
		}
		if (empty($affected_tp_accounts)) {
			return false;
		}

		$label_providers = [
			'zoom_meeting' => 'Zoom',
			'gotomeeting_meeting' => 'GoToMeeting',
			'google_calendar' => 'Google Calendar',
		];

		$descr = 'Please reconnect the following integration account(s) to fix the broken API connection.';
		$descr .= '<br>';
		foreach ($affected_tp_accounts as $provider => $details) {
			$descr .= '<strong>' . $label_providers[$provider] . ' - </strong>';
			foreach ($details as $_key => $account_detail) {
				$descr .= '<strong>' . $account_detail['account_email'] . '</strong><br>';
			}
			//$descr .= '<br>';
		}

		$integration_settings_url = admin_url('admin.php?page=wpcal_admin#/settings/integrations');

		$descr .= '<a class="btn" href="' . $integration_settings_url . '"> Go to integrations settings</a><br>';

		$title = 'Action required: Integration API connection broken';
		return ['title' => $title, 'descr' => $descr];
	}
}
WPCal_License::init();
wpcal_process_features();
