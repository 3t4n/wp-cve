<?php
/**
 * WPCal.io
 * Copyright (c) 2022 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

/**
 * Get new refresh token after 21 days - if the API last token fecthed is
 * greater 21 days.
 * Once attempted next call can be later than 4 hours.
 * GoTo API's Token getting expired around 30 days, doing this for all APIs.
 */
class WPCal_TP_Periodic_Fetch_Token {

	public static function check_and_fetch_token() {
		$tp_calendar_accounts = self::get_pending_tp_calendar_accounts();
		$tp_accounts = self::get_pending_tp_accounts();

		$pending_tps = array_merge($tp_calendar_accounts, $tp_accounts);
		if (empty($pending_tps)) {
			return true;
		}

		// give priority for former attempts
		usort($pending_tps, function ($a, $b) {
			return $a->last_token_fetch_or_attempt_ts - $b->last_token_fetch_or_attempt_ts;
		});

		foreach ($pending_tps as $pending_tp) {
			self::do_fetch_and_save_refresh_token($pending_tp);
			wpcal_is_time_out() ? exit() : '';
		}

		return true;
	}

	private static function do_fetch_and_save_refresh_token($tp) {
		if ($tp->tp_type == 'tp_calendar_account') {
			$tp_class = wpcal_include_and_get_tp_calendar_class($tp->provider);
		} elseif ($tp->tp_type == 'tp_account') {
			$tp_class = wpcal_include_and_get_tp_class($tp->provider);
		} else {
			throw new WPCal_Exception('unexpected_tp_type');
		}

		try {
			$tp_obj = new $tp_class($tp->id);
			if (method_exists($tp_obj, 'perodic_fetch_and_save_refresh_token')) {
				$tp_obj->perodic_fetch_and_save_refresh_token();
			}
			unset($tp_obj);
		} catch (Exception $e) {
		}
	}

	private static function get_pending_tp_calendar_accounts() {
		global $wpdb;
		$table_tp_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
		$table_admins = $wpdb->prefix . 'wpcal_admins';
		$table_wp_users = $wpdb->prefix . 'users';

		$n_time_token_fetched = 86400 * 21; // 21 days
		$n_time_token_attempt = 60 * 60 * 4; // 4 hours
		$time_now = time();
		$last_n_time_token_fetched = $time_now - $n_time_token_fetched;
		$last_n_time_token_attempt = $time_now - $n_time_token_attempt;

		$query = "SELECT `cal_accs`.`id`, `cal_accs`.`provider`, IF(`cal_accs`.`last_token_fetched_ts` >= `cal_accs`.`last_token_fetch_attempt_ts`,  `cal_accs`.`last_token_fetched_ts`, `cal_accs`.`last_token_fetch_attempt_ts`) as `last_token_fetch_or_attempt_ts`, 'tp_calendar_account' as `tp_type`
		FROM `$table_tp_calendar_accounts` `cal_accs` JOIN $table_admins `admins` ON `cal_accs`.`admin_user_id` = `admins`.`admin_user_id` JOIN `$table_wp_users` `wp_users` ON `wp_users`.`ID` = `cal_accs`.`admin_user_id`
		WHERE `cal_accs`.`status` = 1 AND `admins`.`status` = 1 AND `cal_accs`.`last_token_fetched_ts` < '$last_n_time_token_fetched' AND (`cal_accs`.`last_token_fetch_attempt_ts` IS NULL OR `cal_accs`.`last_token_fetch_attempt_ts` < '$last_n_time_token_attempt')
		ORDER BY `last_token_fetch_or_attempt_ts` ASC";
		$result = $wpdb->get_results($query);
		return $result;
	}

	private static function get_pending_tp_accounts() { // non-calendar accounts
		global $wpdb;
		$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';
		$table_admins = $wpdb->prefix . 'wpcal_admins';
		$table_wp_users = $wpdb->prefix . 'users';

		$n_time_token_fetched = 86400 * 21; // 21 days
		$n_time_token_attempt = 60 * 60 * 4; // 4 hours
		$time_now = time();
		$last_n_time_token_fetched = $time_now - $n_time_token_fetched;
		$last_n_time_token_attempt = $time_now - $n_time_token_attempt;

		$query = "SELECT `tp_accs`.`id`, `tp_accs`.`provider`, IF(`tp_accs`.`last_token_fetched_ts` >= `tp_accs`.`last_token_fetch_attempt_ts`,  `tp_accs`.`last_token_fetched_ts`, `tp_accs`.`last_token_fetch_attempt_ts`) as `last_token_fetch_or_attempt_ts`, 'tp_account' as `tp_type`
		FROM `$table_tp_accounts` `tp_accs` JOIN $table_admins `admins` ON `tp_accs`.`admin_user_id` = `admins`.`admin_user_id` JOIN `$table_wp_users` `wp_users` ON `wp_users`.`ID` = `tp_accs`.`admin_user_id`
		WHERE `tp_accs`.`status` = 1 AND `admins`.`status` = 1 AND `tp_accs`.`last_token_fetched_ts` < '$last_n_time_token_fetched' AND (`tp_accs`.`last_token_fetch_attempt_ts` IS NULL OR `tp_accs`.`last_token_fetch_attempt_ts` < '$last_n_time_token_attempt')
		ORDER BY `last_token_fetch_or_attempt_ts` ASC";
		$result = $wpdb->get_results($query);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return $result;
	}
}
