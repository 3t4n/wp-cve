<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Availability_Date {

	private $min_date = null;
	private $max_date = null;

	protected $used_for_obj = null;

	protected $data = array(
		'day_index_list' => array(),
		'date_range_type' => '',
		'from_date' => '',
		'to_date' => '',
		'date_misc' => '',
		'type' => 'default',
		'is_available' => '',
		'added_ts' => '',
		'updated_ts' => '',
		'periods' => array(),
	);

	public function __construct($id = 0, $used_for_obj = '') {
		if (is_numeric($id) && $id > 0) {
			$this->set_id($id);
		}

		if ($used_for_obj instanceof WPCal_Service) {
			$this->used_for_obj = $used_for_obj;
		} else {
			throw new WPCal_Exception('invalid_used_for_obj');
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

	public function load() {
		global $wpdb;
		$table = $wpdb->prefix . 'wpcal_availability_dates';
		$query = "SELECT * FROM `$table` WHERE id = %s";
		$query = $wpdb->prepare($query, $this->get_id());
		$result = $wpdb->get_row($query);
		if (empty($result)) {
			throw new WPCal_Exception('availability_date_id_not_exists');
		}

		foreach ($result as $prop => $value) {
			if (is_string($prop) && isset($this->data[$prop]) && method_exists($this, 'set_' . $prop)) {
				$this->{'set_' . $prop}($value);
			}
		}
		$this->load_periods();
	}

	private function load_periods() {
		if ($this->get_is_available() != 1) { //if not available then no periods
			return;
		}
		global $wpdb;
		$table = $wpdb->prefix . 'wpcal_availability_periods';
		$query = "SELECT `id` FROM `$table` WHERE `availability_date_id` = %s ORDER BY `from_time`";
		$query = $wpdb->prepare($query, $this->get_id());
		$results = $wpdb->get_col($query);
		if (empty($results)) {
			throw new WPCal_Exception('availability_periods_not_exists_availability_date_id');
		}

		$periods = array();

		foreach ($results as $availability_period_id) {
			$periods[] = new WPCal_Availability_Date_Period($availability_period_id);
		}
		$this->set_periods($periods);
	}

	private function set_prop($prop, $value) {
		if (isset($this->data[$prop])) {
			$this->data[$prop] = $value;
		}
	}

	public function get_periods() {
		return $this->get_prop('periods');
	}

	public function set_periods($value) {
		$this->set_prop('periods', $value);
	}

	public function set_day_index_list($value) {
		$list = array();
		if (!empty($value)) {
			if (is_array($value)) {
				$list = $value;
			} elseif (is_string($value)) {
				$list = explode(',', $value);
			}
		}
		$this->set_prop('day_index_list', $list);
	}

	public function set_date_range_type($value) {
		$this->set_prop('date_range_type', $value);
	}

	public function set_from_date($value) {
		$value = WPCal_DateTime_Helper::maybe_get_DateTime_obj($value, $this->used_for_obj->get_tz());
		$this->set_prop('from_date', $value);
	}

	public function set_to_date($value) {
		$value = WPCal_DateTime_Helper::maybe_get_DateTime_obj($value, $this->used_for_obj->get_tz());
		$this->set_prop('to_date', $value);
	}

	public function set_date_misc($value) {
		$this->set_prop('date_misc', $value);
	}

	public function set_type($value) {
		$this->set_prop('type', $value);
	}

	public function set_is_available($value) {
		$this->set_prop('is_available', $value);
	}

	private function get_prop($prop) {
		if (isset($this->data[$prop])) {
			return $this->data[$prop];
		}
	}

	public function get_day_index_list() {
		return $this->get_prop('day_index_list');
	}

	public function get_date_range_type() {
		return $this->get_prop('date_range_type');
	}

	public function get_from_date() {
		return $this->get_prop('from_date');
	}

	public function get_to_date() {
		return $this->get_prop('to_date');
	}

	public function get_date_misc() {
		return $this->get_prop('date_misc');
	}

	public function get_type() {
		return $this->get_prop('type');
	}

	public function get_is_available() {
		return $this->get_prop('is_available');
	}

	public function get_plain_prop($prop) {
		if (isset($this->data[$prop])) {
			if (method_exists($this, 'get_plain_' . $prop)) {
				return call_user_func(array($this, 'get_plain_' . $prop));
			}
			return $this->data[$prop];
		}
	}

	public function get_plain_from_date() {
		$value = $this->get_prop('from_date');
		return WPCal_DateTime_Helper::maybe_get_date_from_DateTime_Obj($value);
	}

	public function get_plain_to_date() {
		$value = $this->get_prop('to_date');
		return WPCal_DateTime_Helper::maybe_get_date_from_DateTime_Obj($value);
	}

	public function _get_plain_periods() {
		$periods = $this->get_prop('periods');
		$plain_periods = array();
		if (!empty($periods)) {
			foreach ($periods as $period_obj) {
				$plain_periods[] = $period_obj->get_plain_data();
			}
		}
		return $plain_periods;
	}

	public function get_periods_data_for_admin_client() {
		$periods = $this->get_prop('periods');
		$plain_periods = array();
		if (!empty($periods)) {
			foreach ($periods as $period_obj) {
				$plain_periods[] = $period_obj->get_data_for_admin_client();
			}
		}
		return $plain_periods;
	}

	public function get_plain_data() {
		$_data = [];
		foreach ($this->data as $prop => $value) {
			$_data[$prop] = $this->get_plain_prop($prop);
		}
		if (isset($_data['periods'])) {
			$_data['periods'] = $this->_get_plain_periods();
		}
		return $_data;
	}

	public function get_data_for_admin_client() {
		$_data = $this->get_plain_data();
		if (isset($_data['periods'])) {
			$_data['periods'] = $this->get_periods_data_for_admin_client();
		}
		$allowed_keys = array(
			'day_index_list',
			'date_range_type',
			'from_date',
			'to_date',
			'date_misc',
			'type',
			'is_available',
			'periods',
		);

		$data_for_admin_client = wpcal_get_allowed_fields($_data, $allowed_keys);
		return $data_for_admin_client;
	}

	public function is_available_by_date(DateTime $date_obj) {

		if (empty($this->get_is_available())) {
			//probbally throw it as error
			return false;
		}

		$date_range_type = $this->get_date_range_type();
		$day_index_list = $this->get_day_index_list();
		$required_date_index = $date_obj->format('N');

		if (!empty($day_index_list)) {
			//is available by date index
			if (!in_array($required_date_index, $day_index_list)) {
				return false;
			}
		}

		$min_date = $this->get_min_date();
		$max_date = $this->get_max_date();

		if ($date_obj >= $min_date && $date_obj <= $max_date) {
			return true;
		}
		return false;
	}

	private function calc_min_max_available_dates() {
		$min_date = null;
		$max_date = null;

		if ($this->min_date != null && $this->max_date != null) {
			return; //already done;
		}

		$date_range_type = $this->get_date_range_type();
		if ($date_range_type === 'from_to') {
			$min_date = $this->get_from_date();
			$max_date = $this->get_to_date();
		} elseif ($date_range_type === 'relative') {
			$_min_date = new DateTime('now', $this->used_for_obj->get_tz());
			$_min_date->setTime(0, 0, 0);

			$date_misc = $this->get_date_misc();
			$relative_days = wpcal_get_relative_days_from_pattern($date_misc);
			if ($relative_days === false) {
				throw new WPCal_Exception('invalid_date_misc_format');
			}
			$relative_days_final = $relative_days - 1;
			if ($relative_days_final < 0) {
				throw new WPCal_Exception('invalid_date_misc_relative_value');
			}
			$min_date = $_min_date;
			$max_date = clone $_min_date;
			$max_date->modify('+' . $relative_days_final . ' days');
		} elseif ($date_range_type === 'infinite') {

			$from_date = $this->get_from_date();
			if ($from_date instanceof DateTime) {
				$min_date = clone $from_date;
			} else {
				$min_date = new DateTime('now', $this->used_for_obj->get_tz());
				$min_date->setTime(0, 0, 0);
			}
			$max_date = clone $min_date;
			$max_date->modify('+10 years'); //if infinite assume 10 years
		} else {
			throw new WPCal_Exception('unexpected_date_range_type');
		}
		$this->min_date = $min_date;
		$this->max_date = $max_date;
	}

	public function get_min_date() {
		$this->calc_min_max_available_dates();
		return $this->min_date;
	}

	public function get_max_date() {
		$this->calc_min_max_available_dates();
		return $this->max_date;
	}
}

class WPCal_Availability_Default extends WPCal_Availability_Date {

	//we may need to bring get_min_date(), get_max_date() but currently it is working fine, there is need in the parent class. Need to improve later

	private $current_available_from_date = null;
	private $current_available_to_date = null;
	private $is_service_currently_having_active_dates = null;

	public function __construct($id = 0, $used_for_obj = '') {
		parent::__construct($id, $used_for_obj);
		$this->calc_current_availability_etc();
	}

	private function calc_current_availability_etc() { //this is not about slot availability, this is not able about service active or disabled,  it is about service settings from and to date

		$service_min_date = $this->get_min_date();
		$service_max_date = $this->get_max_date();
		list($current_available_from_date, $current_available_to_date) = WPCal_Service_Availability_Details::get_current_available_from_and_to_dates($service_min_date, $service_max_date, $this->used_for_obj->get_tz());

		$this->current_available_from_date = $current_available_from_date;
		$this->current_available_to_date = $current_available_to_date;

		if ($this->current_available_from_date === false || $this->current_available_to_date === false) {
			$this->is_service_currently_having_active_dates = false;
		} else {
			$this->is_service_currently_having_active_dates = true;
		}
	}

	public function is_service_currently_having_active_dates() { //this is not about slot availability, this is not able about service active or disabled,  it is about service settings from and to date

		return $this->is_service_currently_having_active_dates;
	}

	public function get_current_available_from_date() {
		return $this->current_available_from_date;
	}

	public function get_current_available_to_date() {
		return $this->current_available_to_date;
	}
}

//=========================================================>

class WPCal_Availability_Date_Period {

	protected $data = array(
		'availability_date_id' => '',
		'from_time' => '',
		'to_time' => '',
	);

	public function __construct($id = 0) {
		if (is_numeric($id) && $id > 0) {
			$this->set_id($id);
		}

		if ($this->get_id() > 0) {
			$this->load();
		}
	}

	public function load() {
		global $wpdb;
		$table = $wpdb->prefix . 'wpcal_availability_periods';
		$query = "SELECT * FROM `$table` WHERE `id` = %s";
		$query = $wpdb->prepare($query, $this->get_id());
		$result = $wpdb->get_row($query);
		if (empty($result)) {
			throw new WPCal_Exception('availability_period_id_not_exists');
		}

		foreach ($result as $prop => $value) {
			if (is_string($prop) && isset($this->data[$prop]) && method_exists($this, 'set_' . $prop)) {
				$this->{'set_' . $prop}($value);
			}
		}
	}

	public function set_id($id) {
		$this->id = $id;
	}

	public function get_id() {
		return $this->id;
	}

	private function set_prop($prop, $value) {
		if (isset($this->data[$prop])) {
			$this->data[$prop] = $value;
		}
	}

	public function set_availability_date_id($value) {
		$this->set_prop('availability_date_id', $value);
	}

	public function set_from_time($value) {
		$value = WPCal_DateTime_Helper::get_Time_obj($value);
		$this->set_prop('from_time', $value);
	}

	public function set_to_time($value) {
		$value = WPCal_DateTime_Helper::get_Time_obj($value);
		$this->set_prop('to_time', $value);
	}

	private function get_prop($prop) {
		if (isset($this->data[$prop])) {
			return $this->data[$prop];
		}
	}

	public function get_availability_date_id() {
		$this->get_prop('availability_date_id');
	}

	public function get_from_time() {
		return $this->get_prop('from_time');
	}

	public function get_to_time() {
		return $this->get_prop('to_time');
	}

	public function get_plain_prop($prop) {
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

	public function get_plain_from_time() {
		$value = $this->get_prop('from_time');
		return WPCal_DateTime_Helper::maybe_get_time_from_Time_Obj($value);
	}

	public function get_plain_to_time() {
		$value = $this->get_prop('to_time');
		return WPCal_DateTime_Helper::maybe_get_time_from_Time_Obj($value);
	}

	public function get_data_for_admin_client() {
		$_data = $this->get_plain_data();
		$allowed_keys = array(
			'availability_date_id',
			'from_time',
			'to_time',
		);

		$data_for_admin_client = wpcal_get_allowed_fields($_data, $allowed_keys);
		return $data_for_admin_client;
	}
}
