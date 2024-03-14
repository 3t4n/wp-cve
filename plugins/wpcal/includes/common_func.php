<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Validate extends Valitron\Validator {

	public function __construct($data = array(), $fields = array(), $lang = null, $langDir = null) {
		// Allows filtering of used input fields against optional second array of field names allowed
		// This is useful for limiting raw $_POST or $_GET data to only known fields
		$this->_fields = !empty($fields) ? array_intersect_key($data, array_flip($fields)) : $data;

		static::load_lang();
	}

	private static function load_lang() {
		$langMessages = array(
			'required' => "is required",
			'equals' => "must be the same as '%s'",
			'different' => "must be different than '%s'",
			'accepted' => "must be accepted",
			'numeric' => "must be numeric",
			'integer' => "must be an integer",
			'length' => "must be %d characters long",
			'min' => "must be at least %s",
			'max' => "must be no more than %s",
			'listContains' => "contains invalid value",
			'in' => "contains invalid value",
			'notIn' => "contains invalid value",
			'ip' => "is not a valid IP address",
			'ipv4' => "is not a valid IPv4 address",
			'ipv6' => "is not a valid IPv6 address",
			'email' => "is not a valid email address",
			'url' => "is not a valid URL",
			'urlActive' => "must be an active domain",
			'alpha' => "must contain only letters a-z",
			'alphaNum' => "must contain only letters a-z and/or numbers 0-9",
			'slug' => "must contain only letters a-z, numbers 0-9, dashes and underscores",
			'regex' => "contains invalid characters",
			'date' => "is not a valid date",
			'dateFormat' => "must be date with format '%s'",
			'dateBefore' => "must be date before '%s'",
			'dateAfter' => "must be date after '%s'",
			'contains' => "must contain %s",
			'boolean' => "must be a boolean",
			'lengthBetween' => "must be between %d and %d characters",
			'creditCard' => "must be a valid credit card number",
			'lengthMin' => "must be at least %d characters long",
			'lengthMax' => "must not exceed %d characters",
			'instanceOf' => "must be an instance of '%s'",
			'containsUnique' => "must contain unique elements only",
			'requiredWith' => "is required",
			'requiredWithout' => "is required",
			'subset' => "contains an item that is not in the list",
			'arrayHasKeys' => "does not contain all required keys",
		);

		static::$_ruleMessages = array_merge(static::$_ruleMessages, $langMessages);
	}

	public function validate() {
		$set_to_break = false;
		foreach ($this->_validations as $v) {
			foreach ($v['fields'] as $field) {
				list($values, $multiple) = $this->getPart($this->_fields, explode('.', $field), false);

				// Don't validate if the field is not required and the value is empty and we don't have a conditionally required rule present on the field
				if (($this->hasRule('optional', $field) && isset($values))
					|| (
						(
							$this->hasRule('requiredWith', $field) && !$this->validate_single_without_error_log('requiredWith', $field, $values, $multiple) //!$this->validate_single_without_error_log('requiredWith', $field, $values, $multiple) is a fix for requiredWith to work correctly, when other validation rules present for field. only when required or the field has value then only other rules need to be checked simiarlarly for requiredWithout and requiredWithIf
						)
						||
						(
							$this->hasRule('requiredWithout', $field) && !$this->validate_single_without_error_log('requiredWithout', $field, $values, $multiple)
						)
						||
						(
							$this->hasRule('requiredWithIf', $field) && !$this->validate_single_without_error_log('requiredWithIf', $field, $values, $multiple)
						)
					)
				) {
					//Continue with execution below if statement
				} elseif (
					$v['rule'] !== 'required' && !$this->hasRule('required', $field) &&
					$v['rule'] !== 'accepted' &&
					(!isset($values) || $values === '' || ($multiple && count($values) == 0))
				) {
					continue;
				}

				// Callback is user-specified or assumed method on class
				$errors = $this->getRules();
				if (isset($errors[$v['rule']])) {
					$callback = $errors[$v['rule']];
				} else {
					$callback = array($this, 'validate' . ucfirst($v['rule']));
				}

				if (!$multiple) {
					$values = array($values);
				} else if (!$this->hasRule('required', $field)) {
					$values = array_filter($values);
				}

				$result = true;
				foreach ($values as $value) {
					$result = $result && call_user_func($callback, $field, $value, $v['params'], $this->_fields);
				}

				if (!$result) {
					$this->error($field, $v['message'], $v['params']);
					if ($this->stop_on_first_fail) {
						$set_to_break = true;
						break;
					}
				}
			}
			if ($set_to_break) {
				break;
			}
		}

		return count($this->errors()) === 0;
	}

	private function validate_single_without_error_log($rule, $field, $values, $multiple) {

		$v = false;
		foreach ($this->_validations as $validation) {
			if ($validation['rule'] == $rule && in_array($field, $validation['fields'])) {
				$v = $validation;
				break;
			}
		}
		if (empty($v)) {
			return true;
		}

		//list($values, $multiple) = $this->getPart($this->_fields, explode('.', $field), false);

		$errors = $this->getRules();
		if (isset($errors[$v['rule']])) {
			$callback = $errors[$v['rule']];
		} else {
			$callback = array($this, 'validate' . ucfirst($v['rule']));
		}

		if (!$multiple) {
			$values = array($values);
		} else if (!$this->hasRule('required', $field)) {
			$values = array_filter($values);
		}

		$result = true;
		foreach ($values as $value) {
			$result = $result && call_user_func($callback, $field, $value, $v['params'], $this->_fields);
		}
		return $result;

	}

	/***
	 * this is exactly save as "getPart()" protected method, except '$this->getPart' changed to 'self::getPartExt'
	 */
	public static function getPartExt($data, $identifiers, $allow_empty = false) {
		// Catches the case where the field is an array of discrete values
		if (is_array($identifiers) && count($identifiers) === 0) {
			return array($data, false);
		}
		// Catches the case where the data isn't an array or object
		if (is_scalar($data)) {
			return array(null, false);
		}
		$identifier = array_shift($identifiers);
		// Glob match
		if ($identifier === '*') {
			$values = array();
			foreach ($data as $row) {
				list($value, $multiple) = self::getPartExt($row, $identifiers, $allow_empty);
				if ($multiple) {
					$values = array_merge($values, $value);
				} else {
					$values[] = $value;
				}
			}
			return array($values, true);
		} // Dead end, abort
		elseif ($identifier === null || !isset($data[$identifier])) {
			if ($allow_empty) {
				//when empty values are allowed, we only care if the key exists
				return array(null, array_key_exists($identifier, $data));
			}
			return array(null, false);
		} // Match array element
		elseif (count($identifiers) === 0) {
			if ($allow_empty) {
				//when empty values are allowed, we only care if the key exists
				return array(null, array_key_exists($identifier, $data));
			}
			return array($data[$identifier], $allow_empty);
		} // We need to go deeper
		else {
			return self::getPartExt($data[$identifier], $identifiers, $allow_empty);
		}
	}
}

Valitron\Validator::addRule('requiredWithIf', function ($field, $value, array $params, array $fields) {
	$conditionallyReq = false;
	// if we actually have conditionally required with fields to check against
	if (isset($params[0])) {
		// convert single value to array if it isn't already
		$reqParams = is_array($params[0]) ? $params[0] : array($params[0]);
		// check for the flag indicating if all fields are required
		$allRequired = isset($params[1]) && (bool) $params[1];
		$emptyFields = 0;
		foreach ($reqParams as $requiredField => $requiredValue) {
			// check the field is set, not null, and not the empty string
			if (isset($fields[$requiredField]) && $fields[$requiredField] == $requiredValue) {
				if (!$allRequired) {
					$conditionallyReq = true;
					break;
				} else {
					$emptyFields++;
				}
			}
		}
		// if all required fields are present in strict mode, we're requiring it
		if ($allRequired && $emptyFields === count($reqParams)) {
			$conditionallyReq = true;
		}
	}
	// if we have conditionally required fields
	if ($conditionallyReq && (is_null($value) ||
		is_string($value) && trim($value) === '')) {
		return false;
	}
	return true;
}, 'is required');

Valitron\Validator::addRule('periodsToTimeAfterFromTime', function ($field, $value, array $params, array $fields) {
	if (!is_array($value)) {
		return false;
	}
	if (isset($params[0]) && $params[0] == 'single') {
		$value = [$value];
	}
	foreach ($value as $key => $period) {
		if (!isset($period['from_time']) || !isset($period['to_time'])) {
			//lets not do anything required, time formats will be taken care bye validators
			continue;
		}
		$from_time = new DateTime('1000-01-01 ' . $period['from_time']);
		$to_time = new DateTime('1000-01-01 ' . $period['to_time']);
		if ($to_time < $from_time) {
			return false;
		}
	}
	return true;
}, 'to time should be greater than from time');

Valitron\Validator::addRule('periodsCheckCollide', function ($field, $value, array $params, array $fields) {
	if (!is_array($value)) {
		return false;
	}
	foreach ($value as $key => $period) {
		if (!isset($period['from_time']) || !isset($period['to_time'])) {
			//lets not do anything required, time formats will be taken care bye validators
			continue;
		}
		$from1_time = new DateTime('1000-01-01 ' . $period['from_time']);
		$to1_time = new DateTime('1000-01-01 ' . $period['to_time']);

		foreach ($value as $key2 => $period2) {
			if ($key == $key2) {
				continue;
			}
			if (!isset($period2['from_time']) || !isset($period2['to_time'])) {
				//lets not do anything required, time formats will be taken care bye validators
				continue;
			}
			$from2_time = new DateTime('1000-01-01 ' . $period2['from_time']);
			$to2_time = new DateTime('1000-01-01 ' . $period2['to_time']);

			$is_collied = WPCal_DateTime_Helper::is_two_slots_collide($from1_time, $to1_time, $from2_time, $to2_time);
			if ($is_collied) {
				return false;
			}
		}
	}
	return true;
}, 'sets should not collide each other.');

Valitron\Validator::addRule('toDateAfterFromDate', function ($field, $value, array $params, array $fields) {
	list($field2_value, $multiple) = WPCal_Validate::getPartExt($fields, explode('.', $params[0]));

	$from_date = new DateTime($value);
	$to_date = new DateTime($field2_value);

	return $from_date <= $to_date;
}, 'to date should be greater than from date');

Valitron\Validator::addRule('checkDateMisc', function ($field, $value, array $params, array $fields) {
	$relative_days = wpcal_get_relative_days_from_pattern($value);
	if ($relative_days === false) {
		return false;
	}
	return true;
}, 'date misc invalid format');

Valitron\Validator::addRule('validColor', function ($field, $value, array $params, array $fields) {

	$reg = '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';
	if (preg_match($reg, $value, $matches) === 1) {
		return true;
	}
	return false;

}, 'invalid color hex code');

Valitron\Validator::addRule('arrayLength', function ($field, $value, array $params, array $fields) {

	if (!is_array($value)) {
		return false;
	}

	return count($value) == $params[0];

}, 'invalid array length');

function wpcal_get_allowed_fields($data_array, $allowed_keys) {
	return array_intersect_key($data_array, array_flip($allowed_keys));
}

function wpcal_remove_values_from_array(array $array, array $remove_values) {
	foreach ($remove_values as $remove_value) {
		if (($key = array_search($remove_value, $array)) !== false) {
			unset($array[$key]);
		}
	}
	return $array;
}

/**
 * All datas will be santized with 'sanitize_text_field' unless specified.
 * $rules format ['descr' => 'sanitize_text_field', ['periods' => ['*' => ['answer' => 'sanitize_textarea_field']]]];
 * '*' for an array of N repeating similar fields
 */
function wpcal_sanitize_all(array $data_array, array $rules = []) {
	static $allowed_sanitize_funcs = ['sanitize_text_field', 'sanitize_email', 'sanitize_textarea_field'];
	$sanitized_data = [];

	foreach ($data_array as $data_field => $data_value) {
		if (is_array($data_value)) {

			$this_array_rules = isset($rules[$data_field]) ? $rules[$data_field] : (
				isset($rules['*']) ? $rules['*'] : array()
			);

			$sanitized_data[$data_field] = wpcal_sanitize_all($data_value, $this_array_rules);
		} else {
			if (isset($rules[$data_field])) {
				$rule = $rules[$data_field];

				if (empty($rule) || !in_array($rule, $allowed_sanitize_funcs)) {
					throw new WPCal_Exception('invalid_sanitize_rule');
				}

				$sanitized_data[$data_field] = call_user_func($rule, $data_value);
			} else { //default santize by sanitize_text_field
				$sanitized_data[$data_field] = call_user_func('sanitize_text_field', $data_value);
			}
		}
	}
	return $sanitized_data;
}

function wpcal_prepare_response($response) { //to send response in form json with a wrapper
	$json = json_encode($response);
	return '<wpcal_response>' . $json . '</wpcal_response>';
	//return $json;
}

function wpcal_check_and_get_data_from_response_json($response, $with_response_tags = true) {
	wpcal_check_response_error($response);
	wpcal_check_http_error($response);

	$response_str = wp_remote_retrieve_body($response);
	if ($with_response_tags) {
		$clean_response_str = wpcal_remove_response_junk($response_str);
	} else {
		$clean_response_str = $response_str;
	}
	$response_data = json_decode($clean_response_str, true);

	if ($response_data === null) {
		//if required use json_last_error()
		throw new WPCal_Exception('invalid_response_json_failed');
	}

	return $response_data;
}

function wpcal_check_and_get_data_from_rest_api_response_json($response) {
	wpcal_check_response_error($response);

	$response_str = wp_remote_retrieve_body($response);
	$response_data = json_decode($response_str, true);
	if ($response_data === null) {
		//if required use json_last_error()
		throw new WPCal_Exception('invalid_response_json_failed');
	}

	return $response_data;
}

function wpcal_check_response_error($response) {
	if (is_wp_error($response)) {
		throw new WPCal_Exception($response->get_error_code(), $response->get_error_message());
	}
}

function wpcal_check_http_error($response) {
	$http_code = wp_remote_retrieve_response_code($response);
	if ($http_code !== 200) {
		$response_msg = wp_remote_retrieve_response_message($response);
		throw new WPCal_Exception('http_error', 'HTTP status code: (' . $http_code . ') ' . $response_msg);
	}
}

function wpcal_get_response_body($response) {
	$response_str = wp_remote_retrieve_body($response);
	if (empty(trim($response_str))) {
		throw new WPCal_Exception('invalid_response_empty');
	}
	return $response_str;
}

function wpcal_remove_response_junk($response) {
	$start_tag_len = strlen('<wpcal_response>');
	$start_pos = stripos($response, '<wpcal_response');
	$end_pos = stripos($response, '</wpcal_response');
	if ($start_pos === false || $end_pos === false) {
		throw new WPCal_Exception('invalid_response_format');
	}

	$response = substr($response, $start_pos); //clearing anything before start tag
	$end_pos = stripos($response, '</wpcal_response'); //new end_pos
	$response = substr($response, $start_tag_len, $end_pos - $start_tag_len);

	return $response;
}

function wpcal_get_tag_content($string, $tag_name) {
	$pattern = "#<\s*?$tag_name\b[^>]*>(.*?)</$tag_name\b[^>]*>#s";
	preg_match($pattern, $string, $matches);
	return $matches[1];
}

function wpcal_base64_url_encode($input) {
	return strtr(base64_encode($input), '+/=', '._-');
}

function wpcal_base64_url_decode($input) {
	return base64_decode(strtr($input, '._-', '+/='));
}

class WPCal_Exception extends Exception {
	protected $error_slug;
	protected $error_data;

	public function __construct($error_slug = '', $message = '', $error_data = [], $code = 0, $previous_throwable = null) {
		$this->error_slug = $error_slug;
		$this->error_data = $error_data;
		$message = trim($message);
		if (empty($message)) {
			$message = $this->getFormatedError(); //this will show the error message in PHP error log and printed uncaught error.
		}
		parent::__construct($message, $code, $previous_throwable);
	}
	public function getError() {
		return $this->error_slug;
	}
	public function getFormatedError() {
		return wpcal_get_error_msg($this->error_slug);
	}
	public function getErrorMessage() {
		$msg = $this->getMessage();
		return empty($msg) ? $this->getFormatedError() : $msg;
	}
	public function getErrorData() {
		return $this->error_data;
	}
	public function getAll() {
		$all = [
			'error' => $this->getError(),
			'error_msg' => $this->getErrorMessage(),
			'error_data' => $this->getErrorData(),
		];
		return $all;
	}
}

function wpcal_get_error_msg($error_slug) {
	return wpcal_get_lang($error_slug);
}

function wpcal_get_lang($lang_slug) {
	static $lang;
	if (!isset($lang)) {
		include_once WPCAL_PATH . '/includes/lang.php';
		$lang = $wpcal_lang;
	}
	return isset($lang[$lang_slug]) ? $lang[$lang_slug] : $lang_slug;
}

function wpcal_prepare_single_action_exception_result(WPCal_Exception $e) {
	$single_action_result = ['status' => 'error'];
	$error_details = $e->getAll();
	$single_action_result = array_merge($single_action_result, $error_details);
	return $single_action_result;
}

function wpcal_get_relative_days_from_pattern($string) {
	$reg = '/^\+(\d+)D$/m';
	if (preg_match($reg, $string, $matches) === 1 && is_numeric($matches[1]) && $matches[1] > 0) {
		return $matches[1];
	}
	return false;
}

function wpcal_is_time_out($time_limit = '') {
	$default_time_limit = WPCAL_TIMEOUT;
	if (!defined('WPCAL_START_TIME')) {
		return true;
	}
	$time_limit = empty($time_limit) ? $default_time_limit : $time_limit;
	$time_taken = microtime(true) - WPCAL_START_TIME;
	if ($time_taken >= $time_limit) {
		return true;
	}
	return false;
}

/**
 * return 0, if no remaining_time, otherwise remaining time.
 */
function wpcal_remaining_time($time_limit = '') {
	$default_time_limit = WPCAL_TIMEOUT;
	if (!defined('WPCAL_START_TIME')) {
		return 0;
	}
	$time_limit = empty($time_limit) ? $default_time_limit : $time_limit;
	$time_taken = microtime(true) - WPCAL_START_TIME;
	if ($time_taken >= $time_limit) {
		return 0;
	}
	return $time_limit - $time_taken;
}

function wpcal_is_subset($all, $search_array) {
	return !array_diff($search_array, $all);
}

function wpcal_get_phone_link($phone) {
	$link = '<a href="tel:%1$s">%1$s</a>';
	$phone_link = sprintf($link, $phone);
	return $phone_link;
}

function wpcal_encode_token($token) {
	if (!empty($token) && is_string($token)) {
		$encoded_token = base64_encode($token);
		return $encoded_token;
	}
	return $token;
}

function wpcal_decode_token($encoded_token) {
	if (!empty($encoded_token) && is_string($encoded_token)) {
		$token = base64_decode($encoded_token);
		return $token;
	}
	return $encoded_token;
}

function wpcal_build_url($parsed_url) { //unparse_url()
	$scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
	$pass = ($user || $pass) ? "$pass@" : '';
	$path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
	$query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
	return "$scheme$user$pass$host$port$path$query$fragment";
}

function wpcal_modify_url($url, $modify_parsed_url) {
	/* Currently supports modifying query of the url */

	$modified_parsed_url = $parsed_url = parse_url($url);

	if (!empty($modify_parsed_url['query_params']) && is_array($modify_parsed_url['query_params'])) {
		$parsed_url_query_params = [];
		!empty($parsed_url['query']) ? parse_str($parsed_url['query'], $parsed_url_query_params) : '';
		$modify_parsed_url_query_params = array_merge($parsed_url_query_params, $modify_parsed_url['query_params']);
		$modified_parsed_url['query'] = http_build_query($modify_parsed_url_query_params);
	}

	$modified_url = wpcal_build_url($modified_parsed_url);
	return $modified_url;
}

function wpcal_may_remove_prefix_str($str, $prefix) {
	if (substr($str, 0, strlen($prefix)) == $prefix) {
		$str = substr($str, strlen($prefix));
	}
	return $str;
}

function wpcal_hex_to_rgb_txt($hex) {
	list($r, $g, $b) = (strlen($hex) === 4) ? sscanf($hex, "#%1x%1x%1x") : sscanf($hex, "#%2x%2x%2x");
	$rgb = [$r, $g, $b];
	$rgb_txt = implode(',', $rgb);
	return $rgb_txt;
}

function wpcal_get_template($template_name, $args = array(), $specific_id = '', $options = []) {

	static $cache_templates = [];
	$template = '';

	$cache_key = sanitize_key(implode('-', ['template', $template_name, WPCAL_VERSION]));
	if (!empty($cache_templates[$cache_key])) {
		$template = $cache_templates[$cache_key];
	}

	if (!$template) {

		$template_path = 'wpcal/';

		$template_names = [];
		if (!empty($specific_id)) {
			$template_names[] = $template_path . str_replace('.php', '-' . $specific_id . '.php', $template_name); //1st specific
		}
		$template_names[] = $template_path . $template_name; //2nd general

		$locate_template = locate_template($template_names);

		if (!$locate_template) {
			//use default
			$template = WPCAL_PATH . '/templates/' . $template_name;
		} else {
			$template = $locate_template;
		}

		$cache_templates[$cache_key] = $template;
	}

	if (!empty($options['return_template'])) {
		return $template;
	}

	$__return = !empty($options['return']) ? true : false;

	if (!empty($args) && is_array($args)) {
		extract($args); //IMMPORTANT it can OVERWRITE above values
	}

	$template_return = include $template;

	if ($__return) {
		return $template_return;
	}
}

function wpcal_get_template_html($template_name, $args = array(), $specific_id = '') {

	ob_start();
	wpcal_get_template($template_name, $args, $specific_id);
	return ob_get_clean();
}

function wpcal_is_int($val) {
	if (filter_var($val, FILTER_VALIDATE_INT) === false) {
		return false;
	}
	return true;
}

//WP lang functions alias, to avoid string scanning
function wpcal__(...$params) {
	return __(...$params);
}

function wpcal_x(...$params) {
	return _x(...$params);
}

function wpcal_n(...$params) {
	return _n(...$params);
}

function wpcal_nx(...$params) {
	return _nx(...$params);
}

if (!function_exists('wp_timezone')) {
	function wp_timezone() {
		return new DateTimeZone(wp_timezone_string());
	}
}
if (!function_exists('wp_timezone_string')) {
	function wp_timezone_string() {
		$timezone_string = get_option('timezone_string');

		if ($timezone_string) {
			return $timezone_string;
		}

		$offset = (float) get_option('gmt_offset');
		$hours = (int) $offset;
		$minutes = ($offset - $hours);

		$sign = ($offset < 0) ? '-' : '+';
		$abs_hour = abs($hours);
		$abs_mins = abs($minutes * 60);
		$tz_offset = sprintf('%s%02d:%02d', $sign, $abs_hour, $abs_mins);

		return $tz_offset;
	}
}

function wpcal_get_timezone_name($tz) {
	static $tz_list = null;
	if ($tz_list === null) {
		$contents = file_get_contents(WPCAL_PATH . '/includes/time_zone_list.json');
		$tz_data = json_decode($contents, true);
		if ($tz_data === null) {
			$tz_list = [];
		} else {
			$tz_list = $tz_data;
		}
	}
	if (isset($tz_list[$tz])) {
		return $tz_list[$tz];
	}

	$tz = trim($tz);
	if (substr($tz, 0, 1) == '-') { //could be -05:30 like format
		return $tz;
	}
	$tz_name = str_replace(['/', '_'], [' - ', ' '], $tz);
	return $tz_name;
}

function wpcal_implode_for_sql($array, $wrap_with = "'") {
	array_walk($array, function (&$x, $key, $wrap_with) {$x = $wrap_with . $x . $wrap_with;}, $wrap_with);
	$str = implode(', ', $array);
	return $str;
}

class WPCal_Manage_User_Timezone {

	public static function save($tz) {
		return self::save_user_timezone_in_usermeta_or_cookie($tz);
	}

	public static function save_user_timezone_in_usermeta_or_cookie($tz) {
		//validate tz IMPROVE LATER //accept empty string for browser timezone
		if (is_user_logged_in()) {
			//save in usermeta
			return self::save_user_timezone_in_usermeta($tz);
		} else {
			//save in cookie
			$cookie_expiry = time() + (86400 * 100);
			$cookie_data = ['value' => $tz, '_expiry' => $cookie_expiry];
			$cookie_value = json_encode($cookie_data);
			$result = setcookie("wpcal_tz", $cookie_value, $cookie_expiry);
			if ($result === false) {
				//handle error
				return false;
			}
		}
		return true;
	}

	private static function save_user_timezone_in_usermeta($tz) {
		if (is_user_logged_in()) {
			//save in usermeta
			$user_id = get_current_user_id(); //maybe admin or enduser
			$result = update_user_meta($user_id, 'wpcal_user_tz', $tz);
			if ($result === false) {
				return false;
			}
			return true;
		}
		return false;
	}

	public static function get() {
		return self::get_user_timezone_from_usermeta_or_cookie();
	}

	private static function get_user_timezone_from_usermeta_or_cookie() {
		$result = null;
		if (is_user_logged_in()) {
			//from usermeta
			$user_id = get_current_user_id(); //maybe admin or enduser
			$result = get_user_meta($user_id, 'wpcal_user_tz', true);
			if (!empty($result)) {
				return $result;
			} else {
				$tz = self::get_user_timezone_from_cookie();
				if (!empty($tz)) {
					self::user_timezone_move_from_cookie_to_usermeta($tz);
					return $tz;
				}
			}
		}
		return self::get_user_timezone_from_cookie();
	}

	private static function get_user_timezone_from_cookie() {
		if (isset($_COOKIE['wpcal_tz']) && $_COOKIE['wpcal_tz']) {
			$cookie_value = stripslashes($_COOKIE['wpcal_tz']);
			$cookie_data = @json_decode($cookie_value, true);
			if (isset($cookie_data['value']) && $cookie_data['value']) {
				return $cookie_data['value'];
			}
		}
		return;
	}

	private static function user_timezone_move_from_cookie_to_usermeta($tz) {
		$result = self::save_user_timezone_in_usermeta($tz);
		if ($result) {
			//delete cookie
			unset($_COOKIE['wpcal_tz']);
			setcookie("wpcal_tz", '', (time() - 3600));
		}
	}

}
