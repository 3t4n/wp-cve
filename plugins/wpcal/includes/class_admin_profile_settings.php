<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Admin_Profile_Settings {
	private $options = [
		'first_name',
		'last_name',
		'display_name',
		'avatar_attachment_id',
	];

	private $option_defaults = [
		'first_name' => '',
		'last_name' => '',
		'display_name' => '',
		'avatar_attachment_id' => '',
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
		$settings = $this->get_all();

		$option_value = isset($settings[$option]) ? $settings[$option] : $this->option_defaults[$option];
		return $option_value;
	}

	// public function get_all_by_options($options){

	// 	$flipped_options = array_flip($options);
	// 	$_flipped_options = wpcal_get_allowed_fields($flipped_options, $this->options);

	// 	$_options = array_flip($flipped_options);

	// 	$result = $this->get_all($_options);
	// 	return $result;
	// }

	public function get_all( /* $options=false */) {
		// if($options === false){
		// 	$options = $this->options;
		// }

		$settings = $settings_db = get_user_meta($this->admin_user_id, 'wpcal_admin_profile_settings', true);
		if (empty($settings)) {
			$settings = [];
		}

		$settings = array_merge($this->option_defaults, $settings);

		$admin_user_obj = get_user_by('id', $this->admin_user_id);

		if ($admin_user_obj) {

			$settings['first_name'] = !empty($settings['first_name']) ? $settings['first_name'] : $admin_user_obj->first_name;
			$settings['last_name'] = !empty($settings['last_name']) ? $settings['last_name'] : $admin_user_obj->last_name;
			$settings['display_name'] = !empty($settings['display_name']) ? $settings['display_name'] : $admin_user_obj->display_name;
		}

		//save these details once so that, it won't look like getting updates from WP user profile
		if ($settings_db === '') { //nothing saved in db, so save it from WP user profile
			$this->update_all($settings, $do_validation = false);
		}

		return $settings;
	}

	public function update_all($data, $do_validation = true) {
		// 1 to N setting can be updated here

		$data = wpcal_sanitize_all($data);

		$_data = wpcal_get_allowed_fields($data, $this->options);

		$validate_obj = new WPCal_Validate($_data);
		$validate_obj->rules([
			'required' => [
				'first_name',
				'last_name',
				'display_name',
			],
			'integer' => [
				['avatar_attachment_id'],
			],
		]);

		if ($do_validation && !$validate_obj->validate()) {
			$validation_errors = $validate_obj->errors();
			throw new WPCal_Exception('validation_errors', '', $validation_errors);
		}

		$result = true;
		update_user_meta($this->admin_user_id, 'wpcal_admin_profile_settings', $_data); //if no change it will returen false
		return $result;
	}
}
