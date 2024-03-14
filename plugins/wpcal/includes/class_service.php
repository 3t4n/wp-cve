<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Service {

	private $id = null;

	private $default_data = array(
		'name' => '',
	);

	protected $data = array(
		'name' => '',
		'status' => '',
		'locations' => '',
		'descr' => '',
		'post_id' => '',
		'color' => '',
		'relationship_type' => '',
		'timezone' => '',
		'duration' => '',
		'display_start_time_every' => '',
		'max_booking_per_day' => '',
		'min_schedule_notice' => '',
		'event_buffer_before' => '',
		'event_buffer_after' => '',
		'is_manage_private' => '',
		'invitee_notify_by' => '',
		'invitee_questions' => '',
		'refresh_cache' => '',
		'added_ts' => '',
		'updated_ts' => '',
		'owner_admin_id' => null,
	);

	public function __construct($id = 0) {
		if (is_numeric($id) && $id > 0) {
			$this->set_id($id);
		}

		if ($this->get_id() > 0) {
			$this->load();
		}
	}

	public function set_id($id) {
		$this->id = $id;
	}

	public function get_id() {
		return $this->id;
	}

	public function add() {

	}

	public function update() {

	}

	public function load() {
		global $wpdb;
		$table = $wpdb->prefix . 'wpcal_services';
		$query = "SELECT * FROM `$table` WHERE id = %s";
		$query = $wpdb->prepare($query, $this->get_id());
		$result = $wpdb->get_row($query);
		if (empty($result)) {
			throw new WPCal_Exception('service_id_not_exists');
		}

		foreach ($result as $prop => $value) {
			if (is_string($prop) && isset($this->data[$prop]) && method_exists($this, 'set_' . $prop)) {
				$this->{'set_' . $prop}($value);
			}
		}
	}

	private function set_prop($prop, $value) {
		if (isset($this->data[$prop])) {
			$this->data[$prop] = $value;
		}
	}

	public function set_name($value) {
		return $this->set_prop('name', $value);
	}

	public function set_status($value) {
		$value = $value == -7 ? -1 : $value;
		return $this->set_prop('status', $value);
	}

	public function set_locations($value) {
		if (!empty($value)) {
			$value = json_decode($value, true);
		}
		if (!is_array($value)) {
			$value = [];
		}
		return $this->set_prop('locations', $value);
	}

	public function set_descr($value) {
		return $this->set_prop('descr', $value);
	}

	public function set_post_id($value) {
		return $this->set_prop('post_id', $value);
	}

	public function set_color($value) {
		return $this->set_prop('color', $value);
	}

	public function set_relationship_type($value) {
		return $this->set_prop('relationship_type', $value);
	}

	public function set_timezone($value) {
		$value = new DateTimeZone($value);
		return $this->set_prop('timezone', $value);
	}

	public function set_duration($value) {
		$value = WPCal_DateTime_Helper::get_DateInterval_obj($value);
		return $this->set_prop('duration', $value);
	}

	public function set_display_start_time_every($value) {
		$value = WPCal_DateTime_Helper::get_DateInterval_obj($value);
		return $this->set_prop('display_start_time_every', $value);
	}

	public function set_max_booking_per_day($value) {
		return $this->set_prop('max_booking_per_day', $value);
	}

	public function set_min_schedule_notice($value) {
		if (!empty($value)) {
			$value = json_decode($value, true);
		}
		if (!is_array($value) || !isset($value['type'])) {
			$value = [
				'type' => 'none',
				'time_units' => "4",
				'time_units_in' => "hrs",
				'days_before_time' => "00:00:00",
				'days_before' => "0",
			];
		}
		return $this->set_prop('min_schedule_notice', $value);
	}

	public function set_event_buffer_before($value) {
		$value = WPCal_DateTime_Helper::get_DateInterval_obj($value);
		return $this->set_prop('event_buffer_before', $value);
	}

	public function set_event_buffer_after($value) {
		$value = WPCal_DateTime_Helper::get_DateInterval_obj($value);
		return $this->set_prop('event_buffer_after', $value);
	}

	public function set_is_manage_private($value) {
		return $this->set_prop('is_manage_private', $value);
	}

	public function set_invitee_notify_by($value) {
		return $this->set_prop('invitee_notify_by', $value);
	}

	public function set_invitee_questions($value) {
		if (!empty($value)) {
			$value = json_decode($value, true);
		}
		if (!is_array($value) || !isset($value['questions']) || !is_array($value['questions'])) {
			$value = ['questions' => []];
		}
		return $this->set_prop('invitee_questions', $value);
	}

	public function set_refresh_cache($value) {
		return $this->set_prop('refresh_cache', $value);
	}

	private function get_prop($prop) {
		if (isset($this->data[$prop])) {
			return $this->data[$prop];
		}
	}

	public function get_name() {
		return $this->get_prop('name');
	}

	public function get_status() {
		return $this->get_prop('status');
	}

	public function get_locations() {
		return $this->get_prop('locations');
	}

	public function get_descr() {
		return $this->get_prop('descr');
	}

	public function get_post_id() {
		return $this->get_prop('post_id');
	}

	public function get_color() {
		return $this->get_prop('color');
	}

	public function get_relationship_type() {
		return $this->get_prop('relationship_type');
	}

	public function get_timezone() {
		return $this->get_prop('timezone');
	}

	public function get_tz() { //alias
		return $this->get_timezone();
	}

	public function get_duration() {
		return $this->get_prop('duration');
	}

	public function get_display_start_time_every() {
		return $this->get_prop('display_start_time_every');
	}

	public function get_max_booking_per_day() {
		return $this->get_prop('max_booking_per_day');
	}

	public function get_min_schedule_notice() {
		return $this->get_prop('min_schedule_notice');
	}

	public function get_event_buffer_before() {
		return $this->get_prop('event_buffer_before');
	}

	public function get_event_buffer_after() {
		return $this->get_prop('event_buffer_after');
	}

	public function get_is_manage_private() {
		return $this->get_prop('is_manage_private');
	}

	public function get_invitee_notify_by() {
		return $this->get_prop('invitee_notify_by');
	}

	public function get_invitee_questions() {
		return $this->get_prop('invitee_questions');
	}

	public function get_refresh_cache() {
		return $this->get_prop('refresh_cache');
	}

	public function get_added_ts() {
		return $this->get_prop('added_ts');
	}

	public function get_updated_ts() {
		return $this->get_prop('updated_ts');
	}

	private function get_plain_prop($prop) {
		if (isset($this->data[$prop])) {
			if (method_exists($this, 'get_plain_' . $prop)) {
				return call_user_func(array($this, 'get_plain_' . $prop));
			}
			return $this->data[$prop];
		}
	}

	public function get_plain_timezone() {
		$value = $this->get_prop('timezone');
		if ($value instanceof DateTimeZone) {
			$value = $value->getName();
		}
		return $value;
	}

	public function get_plain_duration() {
		$value = $this->get_prop('duration');
		return WPCal_DateTime_Helper::get_mins_from_DateInterval_obj($value);
	}

	public function get_plain_display_start_time_every() {
		$value = $this->get_prop('display_start_time_every');
		return WPCal_DateTime_Helper::get_mins_from_DateInterval_obj($value);
	}

	public function get_plain_event_buffer_before() {
		$value = $this->get_prop('event_buffer_before');
		return WPCal_DateTime_Helper::get_mins_from_DateInterval_obj($value);
	}

	public function get_plain_event_buffer_after() {
		$value = $this->get_prop('event_buffer_after');
		return WPCal_DateTime_Helper::get_mins_from_DateInterval_obj($value);
	}

	public function get_owner_admin_id() {
		$owner_admin_id = $this->get_prop('owner_admin_id');
		if ($owner_admin_id !== null) {
			return $owner_admin_id;
		}
		global $wpdb;
		$table = $wpdb->prefix . 'wpcal_service_admins';
		$query = "SELECT `admin_user_id` FROM `$table` WHERE `service_id` = %s ORDER BY `id` LIMIT 1";
		$query = $wpdb->prepare($query, $this->get_id());
		$owner_admin_id = $wpdb->get_var($query);
		if (empty($owner_admin_id)) {
			throw new WPCal_Exception('service_admin_user_id_missing');
		}
		$this->set_prop('owner_admin_id', $owner_admin_id);
		return $owner_admin_id;
	}

	public function get_owner_admin_details() {
		$owner_admin_id = $this->get_owner_admin_id();
		$admin_user_details = wpcal_get_admin_details($owner_admin_id);
		return $admin_user_details;
	}

	public function get_manage_admins() {
		$owner_admin_id = $this->get_owner_admin_id(); //applicable only for one-on-one meetings, for other meetings bring it
		if ($this->is_manage_private()) {
			return [$owner_admin_id];
		} else {
			$active_admins = WPCal_Admins::get_all_active_admins();
			if (!in_array($owner_admin_id, $active_admins)) {
				$active_admins[] = $owner_admin_id;
			}
			return $active_admins;
		}
	}

	public function is_having_active_admin() {
		$owner_admin_id = $this->get_owner_admin_id();
		$is_wpcal_admin_active = WPCal_Admins::is_wpcal_admin_active($owner_admin_id);
		return $is_wpcal_admin_active;
	}

	public function get_plain_data() {
		$_data = [];
		foreach ($this->data as $prop => $value) {
			$_data[$prop] = $this->get_plain_prop($prop);
		}
		return $_data;
	}

	public function get_data_for_admin_client() {
		$_data = $this->get_plain_data();
		$allowed_keys = array(
			'name',
			'status',
			'locations',
			'descr',
			'post_id',
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
			'invitee_notify_by',
			'invitee_questions',
		);

		$data_for_admin_client = wpcal_get_allowed_fields($_data, $allowed_keys);
		$data_for_admin_client['post_details'] = $this->get_post_details();
		$data_for_admin_client['service_admin_user_ids'] = [$this->get_owner_admin_id()];
		return $data_for_admin_client;
	}

	public function get_data_for_user_client() {
		$_data = $this->get_plain_data();

		$this->remove_disabled_invitee_questions_for_user_client($_data['invitee_questions']);
		$this->remove_not_connected_or_not_active_locations_for_user_client($_data['locations']);

		$allowed_keys = array(
			'name',
			'status',
			'locations',
			'descr',
			'post_id',
			'color',
			'relationship_type',
			'timezone',
			'duration',
			'invitee_questions',
		);

		$data_for_admin_client = wpcal_get_allowed_fields($_data, $allowed_keys);
		if (!$this->is_having_active_admin()) {
			$data_for_admin_client['status'] = -1; //temporarily set status -1, to not allow bookings
		}
		$data_for_admin_client['post_details'] = $this->get_post_details();
		return $data_for_admin_client;
	}

	private function remove_disabled_invitee_questions_for_user_client(&$invitee_questions) {
		if (empty($invitee_questions) || !isset($invitee_questions['questions']) || !is_array($invitee_questions['questions'])) {
			return;
		}
		$invitee_questions['questions'] = array_filter($invitee_questions['questions'], function ($v) {
			if (isset($v['is_enabled']) && $v['is_enabled'] == '0') {
				return false;
			}
			return true;
		});
		$invitee_questions['questions'] = array_values($invitee_questions['questions']); //to avoid json converting it to object from array
	}

	private function remove_not_connected_or_not_active_locations_for_user_client(&$locations) {
		if (empty($locations)) {
			return;
		}
		$admin_user_id = $this->get_owner_admin_id();
		$tp_locations = wpcal_get_tp_locations_by_admin($admin_user_id);

		foreach ($locations as $location_key => $location) {
			if (isset($tp_locations[$location['type']])) {
				if (!$tp_locations[$location['type']]['is_connected']) { //currently not connected alone taken similarly like in admin end
					unset($locations[$location_key]);
				}
			}
		}
		$locations = array_values($locations); //to reset keys - other wise JS will consider it has an object
	}

	public function get_post_details() {
		$post_id = $this->get_post_id();
		$result = WPCal_Service::get_post_details_by_post_id($post_id);
		return $result;
	}

	public static function get_post_details_by_post_id($post_id) {
		$result = [
			'status' => '',
			'link' => '',
		];

		if (empty($post_id) || !is_numeric($post_id)) {
			return $result;
		}

		$post = get_post($post_id);
		if (empty($post) || !is_object($post)) {
			return $result;
		}

		$result['status'] = $post->post_status;
		$result['link'] = get_page_link($post);

		return $result;
	}

	public function is_new_booking_allowed() {
		if (!$this->is_having_active_admin()) {
			return false;
		}
		$status = $this->get_status();
		if ($status == 1) {
			return true;
		}
		return false;
	}

	public function is_reschedule_booking_allowed() {
		if (!$this->is_having_active_admin()) {
			return false;
		}
		$status = $this->get_status();
		if ($status == 1) {
			return true;
		}
		return false;
	}

	public function is_cancellation_allowed() {
		$status = $this->get_status();
		$cancellation_allowed_statuses = [1, -1, -2];
		if (in_array($status, $cancellation_allowed_statuses)) {
			return true;
		}
		return false;
	}

	public function is_manage_private() {
		$is_manage_private = $this->get_is_manage_private();
		if ($is_manage_private == 1) {
			return true;
		}
		return false;
	}

	public function is_invitee_notify_by_calendar_invitation() {
		if ($this->get_invitee_notify_by() === 'calendar_invitation') {
			return true;
		}
		return false;
	}

	public function is_invitee_notify_by_email() {
		if ($this->get_invitee_notify_by() === 'email') {
			return true;
		}
		return false;
	}
}
