<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

/**
 * Admin based settings
 */
class WPCal_Admin_Settings {

	/**
	 * Each option should be less than 235 chars. 20 chars for prefix "wpcal_admin_setting_". Total 255 chars
	 */
	private $options = [
		'cal_conflict_free_as_busy',
	];

	private $option_defaults = [
		'cal_conflict_free_as_busy' => '0', // by default busy events will be considered as conflict, when this set to 1(or true) free events will also be considers as busy
	];

	private $admin_user_id;

	public function __construct($admin_user_id) {
		if (empty($admin_user_id)) {
			throw new WPCal_Exception('invalid_admin_user_id');
		}
		$this->admin_user_id = $admin_user_id;
	}

	public function get($option) {
		if (!in_array($option, $this->options, true)) {
			return false;
		}

		$option_default = isset($this->option_defaults[$option]) ? $this->option_defaults[$option] : ''; // '' empty string according to get_user_meta()
		$option_data = get_user_meta($this->admin_user_id, 'wpcal_admin_setting_' . $option, true);
		if ($option_data === '' || $option_data === false) {
			return $option_default;
		}
		return $option_data;
	}

	public function get_all_by_options($options) {

		$flipped_options = array_flip($options);
		$flipped_options = wpcal_get_allowed_fields($flipped_options, $this->options);

		$_options = array_flip($flipped_options);

		$result = self::get_all($_options);
		return $result;
	}

	public function get_all($options = false) {
		if ($options === false) {
			$options = $this->options;
		}

		$result = [];
		foreach ($options as $option) {
			$result[$option] = self::get($option);
		}

		return $result;
	}

	public function update_all($data) {
		// 1 to N setting can be updated here

		$data = wpcal_sanitize_all($data);

		$_data = wpcal_get_allowed_fields($data, $this->options);

		$validate_obj = new WPCal_Validate($_data);
		$validate_obj->rules([

			'in' => [

				['cal_conflict_free_as_busy', ['1', '0']],
			],

		]);

		if (!$validate_obj->validate()) {
			$validation_errors = $validate_obj->errors();
			throw new WPCal_Exception('validation_errors', '', $validation_errors);
		}

		$result = true;
		foreach ($_data as $option => $value) {
			$updated = update_user_meta($this->admin_user_id, 'wpcal_admin_setting_' . $option, $value);
			if (!empty($updated) && $option === 'cal_conflict_free_as_busy') { // setting changed
				wpcal_service_availability_slots_mark_refresh_cache_by_admin($this->admin_user_id);
			}
			//no change also coming false
			//improve code to handle error
			//$result = !$updated ? false : $result;
		}
		return $result;
	}
}
