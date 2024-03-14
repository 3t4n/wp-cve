<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_General_Settings {
	private static $options = [
		'working_hours',
		'working_days',
		'time_format',
		'timezone',
		'branding_color',
		'branding_font',
		'use_wp_mail',
		'hide_premium_info_badges',
	];

	private static $option_defaults = [
		'working_hours' => ['from_time' => '09:00:00', 'to_time' => '17:00:00'],
		'working_days' => [1, 2, 3, 4, 5],
		'time_format' => '12hrs',
		'timezone' => '', //WP default later
		'branding_color' => '',
		'branding_font' => '',
		'use_wp_mail' => '0',
		'hide_premium_info_badges' => '0',
	];

	public static function get($option) {
		if (!in_array($option, self::$options, true)) {
			return false;
		}

		if ($option === 'timezone' && empty(self::$option_defaults[$option]) && isset(self::$option_defaults[$option])) {
			self::$option_defaults[$option] = wp_timezone()->getName();
		}

		$option_default = isset(self::$option_defaults[$option]) ? self::$option_defaults[$option] : false; //false according to get_option()
		return get_option('wpcal_setting_' . $option, $option_default);
	}

	public static function get_all_by_options($options) {

		$flipped_options = array_flip($options);
		$flipped_options = wpcal_get_allowed_fields($flipped_options, self::$options);

		$_options = array_flip($flipped_options);

		$result = self::get_all($_options);
		return $result;
	}

	public static function get_all($options = false) {
		if ($options === false) {
			$options = self::$options;
		}

		$make_a_free_way = WPCal_License::get_public();

		$result = [];
		foreach ($options as $option) {
			$result[$option] = self::get($option);
			if ($make_a_free_way) {
				if ($option == 'branding_color' || $option == 'branding_font') {
					$result[$option] = '';
				} elseif ($option == 'use_wp_mail') {
					$result[$option] = 0;
				}
			}
		}

		return $result;
	}

	public static function update_all($data) {
		// 1 to N setting can be updated here

		$data = wpcal_sanitize_all($data);

		$_data = wpcal_get_allowed_fields($data, self::$options);

		$validate_obj = new WPCal_Validate($_data);
		$validate_obj->rules([
			'subset' => [
				['working_days', [1, 2, 3, 4, 5, 6, 7]],
			],
			'dateFormat' => [
				['working_hours.from_time', 'H:i:s'],
				['working_hours.to_time', 'H:i:s'],
			],
			'in' => [
				['time_format', ['24hrs', '12hrs']],
				['branding_font', ['', 'inherit']],
				['use_wp_mail', ['1', '0']],
				['hide_premium_info_badges', ['1', '0']],
			],
			'validColor' => ['branding_color'],
			'periodsToTimeAfterFromTime' => [
				['working_hours', 'single'],
			],
		]);

		if (!$validate_obj->validate()) {
			$validation_errors = $validate_obj->errors();
			throw new WPCal_Exception('validation_errors', '', $validation_errors);
		}

		$result = true;
		foreach ($_data as $option => $value) {
			$updated = update_option('wpcal_setting_' . $option, $value);
			//no change also coming false
			//improve code to handle error
			//$result = !$updated ? false : $result;
		}
		return $result;
	}

}
