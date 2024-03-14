<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_License {
	private static $show_notice = false;

	private static $notice_class = 'notice notice-info';
	private static $notice_message = '';
	private static $it_is_public = null;
	private static $last_validated_deep = false;

	private static $enable_feature = true;

	private static function do_auth_with_action($creds, $action, $data = false) {

		if (empty($creds['email']) || empty($action) ||
			($action === 'check_validity' && empty($creds['site_token']))) {
			throw new WPCal_Exception('invalid_request');
		}

		if (!in_array($action, array('check_validity', 'add_site', 'signup', 'signup_and_add_site', 'deactivate_feedback'))) {
			throw new WPCal_Exception('invalid_request');
		}

		$url = WPCAL_AUTH_URL;

		$request_data = array();
		if (!empty($data) && is_array($data)) {
			$request_data = $data;
		}

		$request_data['email'] = base64_encode($creds['email']);
		$request_data['action'] = $action;
		$request_data['site_url'] = trailingslashit(site_url());
		$request_data['plugin_slug'] = WPCAL_PLUGIN_SLUG;
		$request_data['plugin_version'] = WPCAL_VERSION;

		$_wp_version = get_bloginfo('version'); //from v0.9.3.0
		if (!$_wp_version) {
			@include_once ABSPATH . WPINC . '/version.php';
			$_wp_version = $GLOBALS['wp_version'];
		}
		$request_data['wp_version'] = $_wp_version;

		if (isset($creds['password'])) {
			$request_data['password'] = base64_encode($creds['password']);
		}

		if (isset($creds['site_token'])) {
			$request_data['site_token'] = base64_encode($creds['site_token']);
		}

		$body = $request_data;

		$http_args = array(
			'method' => "POST",
			'timeout' => 10,
			'body' => $body,
		);

		try {
			$response = wp_remote_request($url, $http_args);

			//wpcal_debug::log($response,'-----------$license_response----------------');
			$response_data = wpcal_check_and_get_data_from_response_json($response);
		} catch (WPCal_Exception $e) {
			throw $e;
		}

		if (empty($response_data) || !is_array($response_data)) {
			throw new WPCal_Exception('invalid_response');
		}

		return $response_data;
	}

	private static function save_creds_info($creds) {

		// if(empty($creds['email']) || empty($creds['site_token']) || empty($creds['last_validated'])){
		// 	return false;
		// }

		if (isset($creds['site_token'])) {
			$creds['site_token'] = base64_encode($creds['site_token']);
		}

		$whitelist = array('email', 'site_token', 'last_validated', 'last_checked', 'last_validate_failed', 'status', 'expiry', 'validity_error', 'issue_deducted', 'plan_slug', 'plan_name', 'additional_details');
		$creds = array_intersect_key($creds, array_flip($whitelist));

		return update_option('wpcal_license_auth_info', $creds);

	}

	public static function get_account_info() {
		$creds = self::get_creds_info();

		$whitelist = array('email', 'status', 'plan_slug', 'plan_name');
		$info = array_intersect_key($creds, array_flip($whitelist));
		return $info;
	}

	public static function get_site_token_and_account_email() {
		$creds = self::get_creds_info();

		$whitelist = array('email', 'site_token');
		$info = array_intersect_key($creds, array_flip($whitelist));
		return $info;
	}

	private static function get_creds_info() {

		$creds = get_option('wpcal_license_auth_info');
		if (empty($creds) || empty($creds['email']) || empty($creds['site_token'])) {
			return is_array($creds) ? $creds : [];
		}

		$creds['site_token'] = base64_decode($creds['site_token']);

		return $creds;
	}

	public static function signup($creds) {
		//santizing of email and password taken care at license side, beware of using it/displaying here.
		$creds['email'] = trim($creds['email']);
		$response_data = self::do_auth_with_action($creds, 'signup');

		if (!isset($response_data['status'])) {
			throw new WPCal_Exception('license__invalid_response');
		}

		if ($response_data['status'] === 'success' && $response_data['success'] === 'user_added') {
			return true;
		} elseif ($response_data['status'] === 'error' && $response_data['error']) {
			if (isset($response_data['error_msg']) && $response_data['error_msg']) {
				throw new WPCal_Exception('license__' . $response_data['error'], $response_data['error_msg']);
			}
			throw new WPCal_Exception('license__' . $response_data['error']);
		} else {
			throw new WPCal_Exception('license__invalid_response');
		}
	}

	public static function login($creds, $do_signup = false) {
		//santizing of email and password taken care at license side, beware of using it/displaying here.
		$old_creds = self::get_creds_info();
		$creds['email'] = trim($creds['email']);
		$auth_action = $do_signup ? 'signup_and_add_site' : 'add_site';
		$response_data = self::do_auth_with_action($creds, $auth_action);

		if (!isset($response_data['status'])) {
			throw new WPCal_Exception('license__invalid_response');
		}

		$expected_responses = ['added'];
		if ($do_signup) {
			$expected_responses = ['signedup_and_added', 'user_added'];
		}

		//$expected_responses requires both 'user_added' and 'signedup_and_added' because while signup_and_site if signup success but add site or login fails. Then this will be helpful

		if ($response_data['status'] === 'success' && in_array($response_data['success'], $expected_responses, true)) {
			$creds_to_save = $creds;
			unset($creds_to_save['password']);
			$creds_to_save['status'] = 'valid';
			$creds_to_save['plan_slug'] = isset($response_data['plan_slug']) ? $response_data['plan_slug'] : '';
			$creds_to_save['plan_name'] = isset($response_data['plan_name']) ? $response_data['plan_name'] : '';
			$creds_to_save['expiry'] = isset($response_data['expiry']) ? $response_data['expiry'] : '';
			$creds_to_save['last_checked'] = time();
			$creds_to_save['last_validated'] = time();
			if (isset($response_data['last_validated_deep'])) {
				$creds_to_save['last_validated_deep'] = $response_data['last_validated_deep'];
			}

			if (empty($response_data['site_token']) || !is_string($response_data['site_token'])) {
				throw new WPCal_Exception('license__invalid_token');
			}

			$creds_to_save['site_token'] = $response_data['site_token'];
			unset($creds_to_save['issue_deducted']);

			self::process_additional_details($creds_to_save, $response_data);
			self::save_creds_info($creds_to_save);
			self::check_response_for_changes($old_creds); //trigger after saving creds

			//wpcal_dev_remove_cron();
			//wpcal_dev_add_cron();
			return ['status' => 'success', 'success' => $response_data['success']];
		} elseif ($response_data['status'] === 'error' && $response_data['error']) {
			if (isset($response_data['error_msg']) && $response_data['error_msg']) {
				throw new WPCal_Exception('license__' . $response_data['error'], $response_data['error_msg']);
			}
			throw new WPCal_Exception('license__' . $response_data['error']);
		} else {
			throw new WPCal_Exception('license__invalid_response');
		}
	}

	public static function check_validity($error_throw = false) { //check the license

		try {
			$old_creds = $creds = self::get_creds_info();
			if (empty($creds)) {
				return false;
			}

			// file_put_contents((defined('ABSPATH') ? ABSPATH : '') . '__debug.php', "\n\nTime: " . date('c') . "(" . round(microtime(1), 4) . "): " . var_export(['license_validate_attempt'], true), FILE_APPEND);

			$response_data = self::do_auth_with_action($creds, 'check_validity');

			if (!isset($response_data['status'])) {
				throw new WPCal_Exception('license__invalid_response');
			}

			if ($response_data['status'] === 'success' && $response_data['success'] === 'valid') {
				$creds['status'] = 'valid';
				$creds['expiry'] = isset($response_data['expiry']) ? $response_data['expiry'] : '';
				$creds['plan_slug'] = isset($response_data['plan_slug']) ? $response_data['plan_slug'] : '';
				$creds['plan_name'] = isset($response_data['plan_name']) ? $response_data['plan_name'] : '';
				$creds['last_checked'] = time();
				$creds['last_validated'] = time();
				if (isset($response_data['last_validated_deep'])) {
					$creds['last_validated_deep'] = $response_data['last_validated_deep'];
				}
				unset($creds['issue_deducted']);
				unset($creds['last_validate_failed']);
				self::process_additional_details($creds, $response_data);
				self::save_creds_info($creds);
				self::check_response_for_changes($old_creds); //trigger after saving creds
				return true;
			} elseif ($response_data['status'] === 'error' && in_array($response_data['error'], array('invalid_user', 'expired', 'not_valid'), true)) {

				if (isset($response_data['plan_slug'])) {
					$creds['plan_slug'] = $response_data['plan_slug'];
				}
				if (isset($response_data['plan_name'])) {
					$creds['plan_name'] = $response_data['plan_name'];
				}

				$creds['status'] = 'error';
				$creds['validity_error'] = $response_data['error'];
				if (isset($response_data['expiry'])) {
					$creds['expiry'] = $response_data['expiry'];
				}
				if ($response_data['error'] === 'not_valid') {
					$creds['issue_deducted'] = empty($creds['issue_deducted']) ? time() : $creds['issue_deducted'];
				}
				$creds['last_checked'] = time();
				if (isset($response_data['last_validated_deep'])) {
					$creds['last_validated_deep'] = $response_data['last_validated_deep'];
				}
				unset($creds['last_validate_failed']);
				self::process_additional_details($creds, $response_data);
				self::save_creds_info($creds);
				self::check_response_for_changes($old_creds); //trigger after saving creds
				return false;
			} else {
				$creds['last_validate_failed'] = time();
				self::save_creds_info($creds);
				throw new WPCal_Exception('license__invalid_response');
			}
		} catch (WPCal_Exception $e) {
			$creds['last_validate_failed'] = time();
			self::save_creds_info($creds);
			// $error = $e->getError();
			// $error_msg = $e->getErrorMessage();
			if ($error_throw) {
				throw $e;
			}
		}
	}

	private static function get_last_validate_attempt() {
		global $wpdb;
		$value = $wpdb->get_var("SELECT `option_value` FROM `$wpdb->options` WHERE `option_name` = 'wpcal_last_validate_attempt' ");
		return $value;
	}

	private static function may_update_last_validate_attempt($new_time, $old_time) {
		global $wpdb;
		if (get_option('wpcal_last_validate_attempt') === false) {
			add_option('wpcal_last_validate_attempt', 1);
		}
		$result = $wpdb->update($wpdb->options, ['option_value' => $new_time], ['option_name' => 'wpcal_last_validate_attempt', 'option_value' => $old_time]);
		return (bool) $result; // (bool) $result to check get truthy of affected rows
	}

	public static function may_check_validity($creds) {
		// to avoid parallel calls.
		$time = round(microtime(true), 4);
		$last_attempt = self::get_last_validate_attempt();

		$force_check = !empty($creds['force_check']);

		if (!$force_check && !empty($creds['last_validate_failed']) && $creds['last_validate_failed'] > time() - (6 * 60 * 60)) {
			return false;
		}

		$last_interaction = max($creds['last_validated'], $creds['last_checked'], $creds['last_validate_failed'] ?? 0);

		if ($force_check && $last_attempt > ($time - 35) && $last_attempt < $last_interaction) { // it means uncompleted call might be there
			//let it run check_validity(); so do nothing here

		} elseif ($last_attempt > ($time - 35)) { //if last attempt is within 35 seconds then check later
			return false;
		}

		$is_successfully_updated = self::may_update_last_validate_attempt($new_time = $time, $old_time = $last_attempt);
		if (!$is_successfully_updated) {
			return false;
		}

		if ($force_check) {
			// file_put_contents((defined('ABSPATH') ? ABSPATH : '') . '__debug.php', "\n\nTime: " . date('c') . "(" . round(microtime(1), 4) . "): " . var_export(['license_validate_force_check'], true), FILE_APPEND);
			self::force_validate_next_instance(false);
		}
		self::check_validity();
	}

	private static function process_additional_details(&$creds_to_save, $response_data) {
		if (!isset($response_data['additional_details'])) {
			return false;
		}
		$creds_to_save['additional_details'] = $response_data['additional_details'];

		if (isset($response_data['additional_details']['notifications'])) {
			WPCal_Manage_Notices::sync_server_notices($response_data['additional_details']['notifications']);
		}
	}

	private static function check_response_for_changes($old_creds) {

		self::is_valid(true);
		wpcal_process_features($_force = true);

		$new_creds = self::get_creds_info();
		if (empty($old_creds)) {
			return false;
		}
		if (empty($old_creds['plan_slug']) || empty($new_creds['plan_slug'])) {
			return false;
		}

		if ($old_creds['plan_slug'] == $new_creds['plan_slug']) {
			return true;
		} else {
			if (self::get_public()) {
				//add notice
				$notice_data = [
					'slug' => 'converted_to_free_plan',
					'category' => 'converted_to_free_plan_notice',
					'type' => 'info',
					'title' => 'Plan changed',
					'descr' => 'WPCal.io plan changed, plan limitations are applied. For more details check with WPCal support <a href="https://wpcal.io/support/">https://wpcal.io/support/<a>.',
					'display_in' => 'wp_admin_and_wpcal_admin',
					'display_to' => 'wpcal_admins',
					'dismiss_type' => 'dismissible',
					'dismiss_by' => 'individual',
				];
				$options = ['remove_old_notice_by' => 'category_and_user'];
				WPCal_Manage_Notices::add_notice($notice_data, $options);
			}
		}
	}

	public static function get_feature_details($feature) {
		$creds = self::get_creds_info();
		if (empty($creds['additional_details']['features'][$feature])) {
			return false;
		}
		return $creds['additional_details']['features'][$feature];
	}

	public static function send_deactivate_feedback($feedback_details) {

		$creds = self::get_creds_info();
		if (empty($creds)) {
			return false;
		}

		$submit_data = ['deactivate_feedback' => $feedback_details];

		$response_data = self::do_auth_with_action($creds, 'deactivate_feedback', $submit_data);

		if (!isset($response_data['status'])) {
			throw new WPCal_Exception('license__invalid_response');
		}

		if ($response_data['status'] === 'success') {
			return true;
		} elseif ($response_data['status'] === 'error') {
			return false;
		} else {
			throw new WPCal_Exception('license__invalid_response');
		}
	}

	public static function is_valid($cache = false) { //check the db

		$creds = self::get_creds_info();
		if (isset($creds['plan_slug'])) {
			if (stripos($creds['plan_slug'], 'free') !== false) {
				self::$it_is_public = true;
			} else {
				self::$it_is_public = false;
			}
		}
		if (isset($creds['last_validated_deep'])) {
			self::$last_validated_deep = true;
		}
		if (!isset($creds['last_validated']) || !isset($creds['last_checked']) || !isset($creds['status'])) {
			return false;
		}

		if (
			empty($creds['force_check']) &&
			$creds['status'] === 'valid' && $creds['last_validated'] > time() - (13 * 60 * 60)
		) {
			return true;
		} elseif (
			!empty($creds['force_check']) ||
			($cache === false && $creds['last_checked'] < time() - (13 * 60 * 60))
		) {
			// self::check_validity();
			self::may_check_validity($creds);
			return self::is_valid(true); //(true) to avoid recurring
		}

		return false;
	}

	public static function force_validate_next_instance(bool $is_enable = true) {
		$creds = self::get_creds_info();
		if (empty($creds)) {
			return false;
		}
		$creds['force_check'] = $is_enable;
		self::save_creds_info($creds);
	}

	public static function is_required_license_login() {
		if (self::is_valid()) {
			return false;
		}
		return true;
	}

	public static function check() {
		if (self::is_valid()) {
			return;
		}
		if (!self::$last_validated_deep) {
			return;
		}
		$notice_class = 'notice notice-info';
		$notice_message = '';
		$show_notice = true;

		$license_login_url = admin_url('admin.php?page=wpcal_admin#/settings/account/login');

		$license_setup_notice = sprintf('WPCal.io - <a href="%s">Setup license now</a>.', $license_login_url);

		$license_mismatch_notice = sprintf('WPCal.io - License mismatch. Features will be disabled soon. Please <a href="%s">Re-activate your license</a>.', $license_login_url);

		$license_mismatch_features_disabled_notice = sprintf('WPCal.io - License mismatch. Features are disabled. Please <a href="%s">Re-activate your license</a>.', $license_login_url);

		$expired_within_n_days_notice = sprintf('WPCal.io - License has expired. Please <a href="%s" target="_blank">Renew your license</a> now. After 15 days of expiry, Features will be disabled.', WPCAL_MY_ACCOUNT_URL);

		$expired_after_n_days_features_disabled_notice = sprintf('WPCal.io - License has expired. Features are disabled.  Please <a href="%s" target="_blank">Renew your license</a> now.', WPCAL_MY_ACCOUNT_URL);

		$creds = self::get_creds_info();

		if (isset($creds['validity_error']) && in_array($creds['validity_error'], array('invalid_user', 'expired', 'not_valid'))) {
			if ($creds['validity_error'] === 'invalid_user') {
				$notice_class = 'notice notice-error';
				$notice_message = $license_mismatch_features_disabled_notice;
				self::$enable_feature = false;
			} elseif ($creds['validity_error'] === 'not_valid') {

				if (isset($creds['issue_deducted']) && is_int($creds['issue_deducted']) && $creds['issue_deducted'] > 0 && time() >= $creds['issue_deducted']) {
					if (time() < ($creds['issue_deducted'] + (86400 * 15))) { //from issue deducted time, less than 15 days
						$notice_class = 'notice notice-warning';
						$notice_message = $license_mismatch_notice;
					} elseif (time() > ($creds['issue_deducted'] + (86400 * 15))) { //from issue deducted time, after 15 days
						$notice_class = 'notice notice-error';
						$notice_message = $license_mismatch_features_disabled_notice;
						self::$enable_feature = false;
					} else {
						$notice_class = 'notice notice-error';
						$notice_message = $license_mismatch_features_disabled_notice;
						self::$enable_feature = false;
					}
				} else {
					$notice_class = 'notice notice-error';
					$notice_message = $license_mismatch_features_disabled_notice;
					self::$enable_feature = false;
				}
			} elseif ($creds['validity_error'] === 'expired') {

				if (isset($creds['expiry']) && is_int($creds['expiry']) && $creds['expiry'] > 0 && time() > $creds['expiry']) {
					if (time() < ($creds['expiry'] + (86400 * 2))) { //expired, less than 2 days
						$show_notice = false;
					} elseif (time() < ($creds['expiry'] + (86400 * 15))) { //expired, after 2 days less than 15 days
						$notice_class = 'notice notice-error';
						$notice_message = $expired_within_n_days_notice;
					} elseif (time() > ($creds['expiry'] + (86400 * 15))) { //after 15 days
						$notice_class = 'notice notice-error';
						$notice_message = $expired_after_n_days_features_disabled_notice;
						self::$enable_feature = false;
					} else {
						$notice_class = 'notice notice-error';
						$notice_message = $expired_after_n_days_features_disabled_notice;
						self::$enable_feature = false;
					}
				} else {
					$notice_class = 'notice notice-error';
					$notice_message = $expired_after_n_days_features_disabled_notice;
					self::$enable_feature = false;
				}
			}
		} elseif (empty($creds)) {
			$notice_class = 'notice notice-info';
			$notice_message = $license_setup_notice;
			self::$enable_feature = false;
		}

		if ($show_notice) {
			if (empty($notice_message)) { //fall back
				$notice_class = 'notice notice-warning';
				$notice_message = $license_mismatch_notice;
			}
			self::$show_notice = $show_notice;
			self::$notice_class = $notice_class;
			self::$notice_message = $notice_message;
		}
	}

	public static function get_public() {
		return self::$it_is_public;
	}

	public static function maybe_show_license_related_notice() {
		if (!self::$show_notice || empty(self::$notice_message)) {
			return;
		}

		$wpcal_admin_ids = WPCal_Admins::get_all_admins($_status = 1);
		$current_user_id = get_current_user_id();
		if (!in_array($current_user_id, $wpcal_admin_ids)) {
			return;
		}

		$is_license_login_page = false;
		// if (isset($_GET['page']) && isset($_GET['tab']) && isset($_GET['show_license_login']) && $_GET['page'] === 'wc-settings' && $_GET['tab'] === 'wpcal_checkopt_settings' && $_GET['show_license_login'] === '1') {
		// 	$is_license_login_page = true;
		// }
		if (self::$notice_class && self::$notice_message && !$is_license_login_page) {
			printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr(self::$notice_class), self::$notice_message);
		}
	}

	public static function init() {
		self::check();
		add_action('admin_notices', __CLASS__ . '::maybe_show_license_related_notice');
	}

	public static function is_features_ok() {
		return self::$enable_feature ? true : false;
	}
}
