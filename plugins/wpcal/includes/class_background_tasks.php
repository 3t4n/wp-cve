<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Background_Tasks {

	private static $task_data_allowed_keys = [
		'task_name',
		'status',
		'scheduled_time_ts',
		'expiry_ts',
		'main_arg_name',
		'main_arg_value',
		'task_args',
		'error_info',
		'dependant_id',
		'retry_attempts',
		'next_retry',
	];

	public static function add_task($options) {
		global $wpdb;

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';
		$added_ts = $updated_ts = time();
		$data_row = wpcal_get_allowed_fields($options, self::$task_data_allowed_keys);
		$data_row['added_ts'] = $added_ts;
		$data_row['updated_ts'] = $updated_ts;

		if (!isset($data_row['task_args']) || $data_row['task_args'] == '') {
			unset($data_row['task_args']);
		} else {
			$data_row['task_args'] = json_encode($data_row['task_args']);
		}

		if (empty($data_row['expiry_ts'])) {
			unset($data_row['expiry_ts']);
		}

		if (!isset($data_row['status'])) {
			$data_row['status'] = 'pending';
		}

		$result = $wpdb->insert($table_background_tasks, $data_row);

		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		$task_id = $wpdb->insert_id;
		return $task_id;
	}

	public static function run_tasks_by_main_args($main_arg_name, $main_arg_value) {

		$options = [
			'main_arg_name' => $main_arg_name,
			'main_arg_value' => $main_arg_value,
		];

		return self::run_tasks($options);
	}

	public static function run_task_by_task_and_main_args($task, $main_arg_name, $main_arg_value) {

		$options = [
			'task_name' => 'add_or_update_online_meeting_for_booking',
			'main_arg_name' => $main_arg_name,
			'main_arg_value' => $main_arg_value,
			'status' => 'manual',
		];

		$tasks = self::get_tasks($options);
		if (empty($tasks[0])) {
			return false;
		}
		$task_id = $tasks[0]->id;
		self::run_task($task_id);
	}

	public static function run_booking_based_tasks() {

		$options = [
			'main_arg_name' => 'booking_id',
		];

		return self::run_tasks($options);
	}

	public static function is_task_waiting_now() {
		$options = ['limit' => 1];
		$waiting_task = self::get_tasks($options);
		if (!empty($waiting_task)) {
			return true;
		}
		return false;
	}

	private static function get_tasks($options = []) {
		global $wpdb;

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';
		$current_ts = time();
		$query = "SELECT `id` FROM `$table_background_tasks` WHERE `scheduled_time_ts` < '" . $current_ts . "' AND (`expiry_ts` IS NULL OR `expiry_ts` > '" . $current_ts . "' )";

		$status_condition = " AND (`status` = 'pending' OR (`status` = 'retry' AND `next_retry` < '" . $current_ts . "'))";

		if (isset($options['status'])) {
			if (!in_array($options['status'], array('pending', 'manual'))) {
				throw new WPCal_Exception('unexpected_status_background_task_query');
			}
			$status_condition = $wpdb->prepare(" AND `status` = %s", $options['status']);
		}
		$query .= $status_condition;

		if (!empty($options['main_arg_name'])) {
			$query .= $wpdb->prepare(" AND `main_arg_name` = %s", $options['main_arg_name']);
		}
		if (!empty($options['main_arg_value'])) {
			$query .= $wpdb->prepare(" AND `main_arg_value` = %s", $options['main_arg_value']);
		}
		if (!empty($options['task_name'])) {
			$query .= $wpdb->prepare(" AND `task_name` = %s", $options['task_name']);
		}

		$query .= " ORDER BY `id`";

		if (!empty($options['limit']) && wpcal_is_int($options['limit'])) {
			$query .= $wpdb->prepare(" LIMIT %d", $options['limit']);
		}

		$results = $wpdb->get_results($query);
		if ($results === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return $results;
	}

	public static function run_tasks($options = []) {

		$results = self::get_tasks($options);

		foreach ($results as $row) {
			self::run_task($row->id);
			if (wpcal_is_time_out()) {
				return true;
			}
		}
		return true;
	}

	public static function get_task_details($background_task_id) {
		global $wpdb;

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';
		$query = "SELECT * FROM `$table_background_tasks` WHERE `id` = %s";
		$query = $wpdb->prepare($query, $background_task_id);
		$task_details = $wpdb->get_row($query);
		return $task_details;
	}

	public static function run_task($background_task_id) {
		global $wpdb;

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';
		$current_ts = $updated_ts = time();

		$task_details = self::get_task_details($background_task_id);

		$allowed_task_statuses_to_run = ['pending', 'retry', 'manual'];

		if (
			empty($task_details) ||
			!in_array($task_details->status, $allowed_task_statuses_to_run) ||
			$task_details->scheduled_time_ts > $current_ts
		) {
			return;
		}

		if (
			$task_details->status == 'retry' && $task_details->next_retry > $current_ts
		) {
			return;
		}

		if (!empty($task_details->dependant_id)) {
			$dependant_task_details = self::get_task_details($task_details->dependant_id);
			if ($dependant_task_details->status != 'completed') {
				return;
			}
		}

		$update_data = [
			'status' => 'running',
			'updated_ts' => $updated_ts,
		];

		$where = ['id' => $background_task_id, 'status' => $task_details->status]; //status only 'pending' & 'retry' strictly

		$mark_as_started = $wpdb->update($table_background_tasks, $update_data, $where);
		if ($mark_as_started === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		} elseif (!$mark_as_started) {
			//task status already changed
			return;
		}
		//all ok run the task
		if (!empty($task_details->task_args)) {
			$task_details->task_args = json_decode($task_details->task_args, true);
		}
		self::do_run_task($background_task_id, $task_details->task_name, $task_details);

	}

	/**
	 * This function should be used when task status just now changed from pending to running only
	 */
	private static function do_run_task($background_task_id, $task_name, $task_details) {
		static $allowed_tasks = [
			//'task_name', //if only value is given it will considered as method name.
			//'task_name' => 'method_name' then method_name will used
			'add_or_update_booking_to_tp_calendar',
			'delete_booking_to_tp_calendar',
			'add_or_update_online_meeting_for_booking',
			'delete_online_meeting_for_booking',
			'get_and_set_meeting_url_from_google_calendar',
			'send_invitee_booking_confirmation_mail' => 'booking_mail_task',
			'send_invitee_booking_reminder_mail' => 'booking_mail_task', //deprecated slug 'send_invitee_booking_reminder_mail'
			'schedule_invitee_booking_reminder_mail' => 'booking_mail_task',
			'send_invitee_reschedule_booking_confirmation_mail' => 'booking_mail_task',
			'send_invitee_booking_cancellation_mail' => 'booking_mail_task',
			'send_admin_new_booking_info_mail' => 'booking_mail_task',
			'send_admin_reschedule_booking_info_mail' => 'booking_mail_task',
			'send_admin_booking_cancellation_mail' => 'booking_mail_task',
			'delete_invitee_booking_reminder_mail' => 'booking_mail_task', //deprecated slug delete_invitee_booking_reminder_mail
			'delete_scheduled_invitee_booking_reminder_mail' => 'booking_mail_task',
			'send_scheduled_invitee_reminder_mail' => 'send_scheduled_mail',
			'send_mail' => 'send_mail',
			'generate_missing_thumnails' => 'generate_missing_thumnails',
		];

		try {
			if (isset($allowed_tasks[$task_name])) {
				$method_name = $allowed_tasks[$task_name];
			} elseif (in_array($task_name, $allowed_tasks, true)) {
				$method_name = $task_name;
			} else {
				throw new WPCal_Exception('unknown_task');
			}

			call_user_func('WPCal_Background_Tasks::' . $method_name, $task_name, $task_details);
		} catch (\WPCal\GoogleAPI\Google_Service_Exception $e) {
			$code = $e->getCode();
			$errors = $e->getErrors();
			$message = $e->getMessage();
			$additional_info = ['code' => $code];

			if (!empty($errors)) {
				$error = $errors[0]['reason'] ?? '';
				$message = empty($message) ? ($errors[0]['message'] ?? '') : '';
				$additional_info['errors'] = $errors;
			} elseif (!empty($message)) {
				$message_array = json_decode($message, true);
				if (is_array($message_array) && !empty($message_array['error'])) {
					$error = $message_array['error'];
					$message = empty($message) ? ($message_array['message'] ?? '') : ''; //this i am not sure
					$additional_info['errors'] = $errors;
				}
			}

			$error_info = [
				'error' => $error,
				'error_msg' => $message,
				'additional_info' => $additional_info,
			];
			self::update_task_as_retry($background_task_id, $error_info);

		} catch (WPCal_Exception $e) {
			$error_info = [
				'error' => $e->getError(),
				'error_msg' => $e->getErrorMessage(),
			];
			if ($e->getCode() == 700) {
				self::update_task_as_error($background_task_id, $error_info);
			} else {
				self::update_task_as_retry($background_task_id, $error_info);
			}
		} catch (Exception $e) {
			$error_info = [
				'error' => $e->getCode(),
				'error_msg' => $e->getMessage(),
				'additional_info' => [
					'error_in_file' => $e->getFile(),
					'error_in_line' => $e->getLine(),
					'trace_string' => substr($e->getTraceAsString(), 0, 10000),
				],
			];

			self::update_task_as_retry($background_task_id, $error_info);
		}
	}

	private static function update_task_as_completed($background_task_id) {

		$status = 'completed';
		$result = self::update_task($background_task_id, $status);
		return $result;
	}

	private static function update_task_as_error($background_task_id, $error_info) {

		$status = 'error';
		$result = self::update_task($background_task_id, $status, $error_info);
		return $result;
	}

	private static function update_task_as_retry($background_task_id, $error_info) {
		$task_details = self::get_task_details($background_task_id);
		if (empty($task_details)) {
			return false;
		}

		$status = 'retry';
		$retry_attempts = $task_details->retry_attempts + 1;

		$add_n_mins = 30;
		if ($retry_attempts <= 1) {
			$add_n_mins = 5;
		} elseif ($retry_attempts <= 2) {
			$add_n_mins = 15;
		}
		$next_retry = time() + ($add_n_mins * 60);

		$result = self::update_task($background_task_id, $status, $error_info, $retry_attempts, $next_retry);
		return $result;
	}

	private static function update_task($background_task_id, $status, $error_info = [], $retry_attempts = null, $next_retry = null) {
		global $wpdb;

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';
		$current_ts = $updated_ts = time();

		$update_data = [
			'status' => $status,
			'updated_ts' => $updated_ts,
		];

		if ($status == 'error' || $status == 'retry') {
			$update_data['error_info'] = json_encode($error_info);
		}

		if (isset($retry_attempts) && isset($retry_attempts)) {
			$update_data['retry_attempts'] = $retry_attempts;
			$update_data['next_retry'] = $next_retry;
		}

		$where = ['id' => $background_task_id];

		$update_result = $wpdb->update($table_background_tasks, $update_data, $where);

		return $update_result;
	}

	public static function is_task_completed_by_main_args($task_name, $main_arg_name, $main_arg_value) {
		//assuming task_name, main_arg_name and main_arg_value together unique
		global $wpdb;

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';
		$query = "SELECT `id` FROM `$table_background_tasks` WHERE `status` = 'completed' AND `task_name` = %s AND `main_arg_name` = %s AND `main_arg_value` = %s";
		$query = $wpdb->prepare($query, $task_name, $main_arg_name, $main_arg_value);
		$task_success = $wpdb->get_var($query);
		if ($task_success) {
			return true;
		}
		return false;
	}

	public static function is_task_exists_by_main_args($task_name, $main_arg_name, $main_arg_value) {
		//assuming task_name, main_arg_name and main_arg_value together unique
		global $wpdb;

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';
		$query = "SELECT `id` FROM `$table_background_tasks` WHERE `task_name` = %s AND `main_arg_name` = %s AND `main_arg_value` = %s";
		$query = $wpdb->prepare($query, $task_name, $main_arg_name, $main_arg_value);
		$task_success = $wpdb->get_var($query);
		if ($task_success) {
			return true;
		}
		return false;
	}

	public static function mark_stuck_tasks_as_retry() {
		global $wpdb;
		$updated_ts = time();

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';
		$n_mins_ago = time() - (10 * 60);
		$query = "UPDATE `$table_background_tasks` SET `status` = 'retry', retry_attempts = retry_attempts + 1, `next_retry` = IF(retry_attempts > 0, $updated_ts + 1200, $updated_ts + 600), `updated_ts` = '" . $updated_ts . "' WHERE `status` = 'running' AND `updated_ts` > 0 AND `updated_ts` < '" . $n_mins_ago . "' ";
		$result = $wpdb->query($query);
		return $result;
	}

	//==========================================================================>
	//tasks code below

	private static function add_or_update_booking_to_tp_calendar($task_name, $task_details) {
		if ($task_name !== 'add_or_update_booking_to_tp_calendar') {
			return false;
		}
		$booking_id = $task_details->main_arg_value;
		$booking_obj = wpcal_get_booking($booking_id);
		wpcal_may_add_or_update_booking_to_tp_calendar($booking_obj);

		self::update_task_as_completed($task_details->id);
	}

	private static function delete_booking_to_tp_calendar($task_name, $task_details) {
		if ($task_name !== 'delete_booking_to_tp_calendar') {
			return false;
		}
		$booking_id = $task_details->main_arg_value;
		$booking_obj = wpcal_get_booking($booking_id);
		wpcal_may_delete_booking_to_tp_calendar($booking_obj);

		self::update_task_as_completed($task_details->id);
	}

	private static function add_or_update_online_meeting_for_booking($task_name, $task_details) {
		if ($task_name !== 'add_or_update_online_meeting_for_booking') {
			return false;
		}
		$booking_id = $task_details->main_arg_value;
		$booking_obj = wpcal_get_booking($booking_id);
		wpcal_add_or_update_online_meeting_for_booking($booking_obj);

		self::update_task_as_completed($task_details->id);
	}

	private static function delete_online_meeting_for_booking($task_name, $task_details) {
		if ($task_name !== 'delete_online_meeting_for_booking') {
			return false;
		}
		$booking_id = $task_details->main_arg_value;
		$booking_obj = wpcal_get_booking($booking_id);
		wpcal_delete_online_meeting_for_booking($booking_obj);

		self::update_task_as_completed($task_details->id);
	}

	private static function get_and_set_meeting_url_from_google_calendar($task_name, $task_details) {
		if ($task_name !== 'get_and_set_meeting_url_from_google_calendar') {
			return false;
		}
		$booking_id = $task_details->main_arg_value;
		$booking_obj = wpcal_get_booking($booking_id);
		wpcal_get_and_set_meeting_url_from_google_calendar($booking_obj);

		self::update_task_as_completed($task_details->id);
	}

	private static function booking_mail_task($task_name, $task_details) {
		static $task_mail_method_mapping = [
			'send_invitee_booking_confirmation_mail' => 'send_invitee_booking_confirmation',
			'send_invitee_booking_reminder_mail' => 'schedule_invitee_booking_reminder', //deprecated slug 'send_invitee_booking_reminder_mail'
			'schedule_invitee_booking_reminder_mail' => 'schedule_invitee_booking_reminder',
			'send_invitee_reschedule_booking_confirmation_mail' => 'send_invitee_reschedule_booking_confirmation',
			'send_invitee_booking_cancellation_mail' => 'send_invitee_booking_cancellation',
			'send_admin_new_booking_info_mail' => 'send_admin_new_booking_info',
			'send_admin_reschedule_booking_info_mail' => 'send_admin_reschedule_booking_info',
			'send_admin_booking_cancellation_mail' => 'send_admin_booking_cancellation',
			'delete_invitee_booking_reminder_mail' => 'delete_scheduled_invitee_booking_reminder', //deprecated slug delete_invitee_booking_reminder_mail
			'delete_scheduled_invitee_booking_reminder_mail' => 'delete_scheduled_invitee_booking_reminder',
		];
		if (!isset($task_mail_method_mapping[$task_name])) {
			throw new WPCal_Exception('unknown_mail_task');
		}

		$mail_task = $task_mail_method_mapping[$task_name];
		$booking_id = $task_details->main_arg_value;
		$booking_obj = wpcal_get_booking($booking_id);
		call_user_func('WPCal_Mail::' . $mail_task, $booking_obj);
		self::update_task_as_completed($task_details->id);
	}

	private static function send_scheduled_mail($task_name, $task_details) { //only mail sending via wp_mail
		if (empty($task_details->task_args) || !is_array($task_details->task_args)) {
			throw new WPCal_Exception('mail_task_args_missing');
		}
		$task_args = $task_details->task_args;
		if (
			empty($task_args['to_email']) ||
			empty($task_args['subject']) ||
			empty($task_args['body']) ||
			empty($task_args['mail_options'])
		) {
			throw new WPCal_Exception('task_args_important_arg_missing');
		}
		$from_email = !empty($task_args['from_email']);
		$to_email = $task_args['to_email'];
		$subject = $task_args['subject'];
		$body = $task_args['body'];
		$options = $task_args['mail_options'];

		$is_sent = WPCal_Mail::send_mail_using_wp_mail($from_email, $to_email, $subject, $body, $options);

		if (!$is_sent) {
			throw new WPCal_Exception('mail_sending_via_wp_mail_failed');
		}
		self::update_task_as_completed($task_details->id);
	}

	private static function send_mail($task_name, $task_details) {
		if (empty($task_details->task_args) || !is_array($task_details->task_args)) {
			throw new WPCal_Exception('mail_task_args_missing');
		}
		$task_args = $task_details->task_args;
		if (
			empty($task_args['to_email']) ||
			empty($task_args['subject']) ||
			empty($task_args['body']) ||
			empty($task_args['mail_options'])
		) {
			throw new WPCal_Exception('task_args_important_arg_missing');
		}
		$from_email = !empty($task_args['from_email']);
		$to_email = $task_args['to_email'];
		$subject = $task_args['subject'];
		$body = $task_args['body'];
		$options = $task_args['mail_options'];

		WPCal_Mail::send_mail($from_email, $to_email, $subject, $body, $options);

		self::update_task_as_completed($task_details->id);
	}

	private static function generate_missing_thumnails($task_name, $task_details) {

		$attachment_id = $task_details->main_arg_value;

		wpcal_may_generate_avatar_attachment($attachment_id);

		self::update_task_as_completed($task_details->id);
	}
}
