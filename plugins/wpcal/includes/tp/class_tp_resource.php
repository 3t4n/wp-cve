<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_TP_Resource {

	private $id = null;

	private $data = array(
		'for_type' => '',
		'for_id' => '',
		'type' => '',
		'status' => '',
		'provider' => '',
		'tp_account_id' => '',
		'tp_user_id' => '',
		'tp_account_email' => '',
		'tp_id' => '',
		'tp_data' => '',
		'added_ts' => '',
		'updated_ts' => '',
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

	public function load() {
		global $wpdb;
		$table = $wpdb->prefix . 'wpcal_tp_resources';
		$query = "SELECT * FROM `$table` WHERE id = %s";
		$query = $wpdb->prepare($query, $this->get_id());
		$result = $wpdb->get_row($query);
		if (empty($result)) {
			throw new WPCal_Exception('tp_resource_id_not_exists');
		}

		foreach ($result as $prop => $value) {
			if (is_string($prop) && isset($this->data[$prop]) && (method_exists($this, 'set_' . $prop) || $this->can_call('set_' . $prop))) {
				$this->{'set_' . $prop}($value);
			}
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

	public function can_call(string $method_name) {
		static $get_allowed_keys = [
			'for_type',
			'for_id',
			'type',
			'status',
			'provider',
			'tp_account_id',
			'tp_user_id',
			'tp_account_email',
			'tp_id',
			'tp_data',
		];
		static $set_allowed_keys = [
			'for_type',
			'for_id',
			'type',
			'status',
			'provider',
			'tp_account_id',
			'tp_user_id',
			'tp_account_email',
			'tp_id',
			'tp_data',
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

	public function set_tp_data($value) {
		if (!empty($value)) {
			$value = json_decode($value, true);
		}
		return $this->set_prop('tp_data', $value);
	}

	public static function create_resource($details) {
		$allowed_keys = [
			'for_type',
			'for_id',
			'type',
			'status',
			'provider',
			'tp_account_id',
			'tp_user_id',
			'tp_account_email',
			'tp_id',
			'tp_data',
		];

		$data = wpcal_get_allowed_fields($details, $allowed_keys);

		if (
			empty($data['for_type']) ||
			empty($data['for_id']) ||
			empty($data['type']) ||
			empty($data['provider']) ||
			empty($data['tp_account_id']) ||
			empty($data['tp_id'])
		) {
			return false;
		}

		if (isset($data['tp_data'])) {
			$data['tp_data'] = json_encode($data['tp_data']);
		}

		$data['added_ts'] = $data['updated_ts'] = time();

		global $wpdb;
		$table_tp_resources = $wpdb->prefix . 'wpcal_tp_resources';

		$result = $wpdb->insert($table_tp_resources, $data);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		$tp_resource_id = $wpdb->insert_id;
		return $tp_resource_id;
	}

	public static function update_resource($details, $tp_resource_id) {
		$allowed_keys = [
			'for_type',
			'for_id',
			'type',
			'status',
			'provider',
			'tp_account_id',
			'tp_user_id',
			'tp_account_email',
			'tp_id',
			'tp_data',
		];

		$data = wpcal_get_allowed_fields($details, $allowed_keys);

		if (isset($data['tp_data'])) {
			$data['tp_data'] = json_encode($data['tp_data']);
		}

		$data['updated_ts'] = time();

		global $wpdb;
		$table_tp_resources = $wpdb->prefix . 'wpcal_tp_resources';

		$result = $wpdb->update($table_tp_resources, $data, array('id' => $tp_resource_id));
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return $tp_resource_id;
	}
}
