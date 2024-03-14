<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intl
 * @subpackage Intl/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intl
 * @subpackage Intl/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
interface IntelEntityInterface {
	/*
	 * Defines the table structure and default values
	 *
	 * @return
   *   array of field keys and default values
	 */
	public static function fields();
}

class IntelEntity {

	protected $id;

	protected $controller;

	//public $entity_info;

	public $entity_type;

	public function __construct(array $values = array(), $controller) {
		$this->controller = $controller;
		$this->entity_type = $controller->entity_type;

		// set id or is_new
		$key_id = $controller->key_id;
		if (isset($values[$key_id])) {
			$this->id = $values[$key_id];
		}
		else {
			$this->is_new = 1;
		}

		// initialize properties
		foreach ($controller->fields as $k => $v) {
			$this->{$k} = isset($values[$k]) ? $values[$k] : $v;
			// unserialize array data
			if (is_array($v) && is_string($this->{$k})) {
				$this->{$k} = unserialize($this->{$k});
			}
			else if (is_int($v)) {
				$this->{$k} = (int)$this->{$k};
			}
			else if (is_float($v)) {
				$this->{$k} = (float)$this->{$k};
			}
		}

		if (isset($this->data) && !isset($this->data['syncStatus'])) {
			$this->data['syncStatus'] = $this->syncStatusConstruct();
		}
	}

	public function label() {
		return $this->entity_type . ' ' . $this->id;
	}

	public function get_id() {
		return $this->id;
	}

	public function id() {
		return self::get_id();
	}

	public function uri() {
		//intel_d($this->entity_info);
		$entity_info = intel()->entity_info($this->entity_type);
		return $this->entity_type . '/' . $this->{$entity_info['entity keys']['id']};
		//return 'entity/' . $this->{$this->entity_info['entity keys']['id']};
	}

	public function save() {
		$this->controller->save($this);
	}

	public static function entity_uri($entity_type, $entity) {
		//$entity_info = self::entity_info($entity_type);
		if ($entity_type == 'post') {
			return array('id' => ':post:' . $entity->ID, 'options' => array());
		}
		else {
			return array('path' => $entity->uri(), 'options' => array());
		}
	}

	public static function entity_get($entity_type, $entity, $name, $default = NULL) {
		$entity_info = intel()->entity_info($entity_type);
		if (isset($entity_info['entity keys'][$name])) {
			$prop = $entity_info['entity keys'][$name];
			if (is_string($prop) && isset($entity->$prop)) {
				return $entity->$prop;
			}
			if (is_array($prop) && !empty($entity->{$prop['key']})) {
				$value = $entity->{$prop['key']};
				if (!empty($prop['formatter callback'])) {
					$func = $prop['formatter callback'];
					if (is_callable($func)) {
						$value = $func($value);
					}
				}
				return $value;
			}
		}
		if (isset($entity->$name)) {
			return $entity->$name;
		}
		return $default;
	}

	public function getVar($scope, $namespace = '', $keys = '', $default = null) {
		$data = $this->data;

		if (is_string($data)) {
			$data = unserialize($data);
		}
		if (empty($data[$namespace])) {
			return $default;
		}
		$data = $data[$namespace];
		intel_include_library_file("libs/class.intel_data.php");
		return \LevelTen\Intel\IntelData::getVar($data, $keys, $default);
	}

	public function __get($name) {
		if (isset($this->$name)) {
			return $this->$name;
		}
		if (isset($entity_info['entity keys'][$name])) {
			$prop = $entity_info['entity keys'][$name];

			if (is_string($prop) && isset($this->$prop)) {
				return $this->$prop;
			}
			if (is_array($prop) && !empty($this->$prop)) {

			}
		}
		return null;
	}

	public function syncStatusConstruct() {
		$ret = array(
			// time of last successful sync
			'synced' => 0,
			// any errors from last sync attempt
			'error' => array(),
			// list of statuses key by hook keys. 0=failed, 1=success
			'statuses' => array(),
			// returned results
			'result' => array(),
		);
		return $ret;
	}

	public function setSynced($value) {
		$this->data['syncStatus']['synced'] = $value;
	}

	public function getSynced() {
		return $this->data['syncStatus']['synced'];
	}

	public function setSyncProcessStatus($process, $status, $error_message = '', $error_code = 0) {
		// $error_code = 0 clears error
		$this->data['syncStatus']['statuses'][$process] = $status;
	}

	public function getSyncProcessStatus($process = NULL) {
		if (!empty($process)) {
			if (isset($this->data['syncStatus']['error'][$process])) {
				return $this->data['syncStatus']['statuses'][$process];
			}
			return NULL;
		}
		return $this->data['syncStatus']['statuses'];
	}

	/**
	 * Sets a error when a visitor sync was not complete
	 *
	 * @param $process Name of the plugin
	 * @param $message Message to relay
	 * @param int $error_code
	 */
	public function setSyncError($process, $message = '', $error_code = 1) {
		// $error_code = 0 clears error
		if ($error_code == 0) {
			if ($this->data['syncStatus']['error'][$process]) {
				unset($this->data['syncStatus']['error'][$process]);
			}
		}
		else {
			$this->data['syncStatus']['error'][$process] = array(
				'code' => $error_code,
				'message' => $message,
			);
		}
	}

	public function getSyncError($process = NULL) {
		if (!$process) {
			return $this->$this->data['syncStatus']['error'];
		}
		elseif (!empty($this->data['syncStatus']['error'][$process])) {
			return $this->data['syncStatus']['error'][$process];
		}
		return array();
	}

	public function getSyncResult($process) {
		if (isset($this->data['syncStatus']['result'][$process])) {
			return $this->data['syncStatus']['result'][$process];
		}
		return array();
	}

	public function addSyncResult($process, $message = '', $status_code = 0) {
		// $error_code = 0 clears error
		if (!isset($this->data['syncStatus']['result'][$process])) {
			$this->data['syncStatus']['result'][$process] = array();
		}
		$this->data['syncStatus']['result'][$process][] = array(
			'code' => $status_code,
			'message' => $message,
			'time' => time(),
		);
	}

	public function clearSyncResult($process) {
		// $error_code = 0 clears error
		$this->data['syncStatus']['result'][$process] = array();
	}

	public function syncData($options = array()) {
		$this->controller->syncData($this, $options);
	}
}
