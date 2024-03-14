<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Mail {
	private static $site_token;
	private static $site_url;
	private static $license_email;
	public static $dev_preview = false;

	private static function load_site_details() {
		if (!empty(self::$site_token)) {
			return;
		}
		$license_info = WPCal_License::get_site_token_and_account_email();
		if (empty($license_info['site_token'])) {
			throw new WPCal_Exception('invalid_license_info');
		}
		self::$site_token = $license_info['site_token'];
		self::$license_email = $license_info['email'];
		self::$site_url = site_url();
	}

	public static function send_mail($from_email, $to_email, $subject, $body, $options = []) {
		if (self::$dev_preview) {
			echo '<div style="text-align: center;}">Subject: ' . $subject . '</div> ' . $body;
			return;
		}

		$use_wp_mail = WPCal_General_Settings::get('use_wp_mail');
		if ($use_wp_mail == '1') {
			if (!empty($options['schedule_time'])) {
				self::schedule_mail_using_wp_mail($from_email, $to_email, $subject, $body, $options);
			} else {
				self::send_mail_using_wp_mail($from_email, $to_email, $subject, $body, $options);
			}
		} else {
			self::send_mail_via_mail_server($from_email, $to_email, $subject, $body, $options);
		}
	}

	private static function send_mail_later($from_email, $to_email, $subject, $body, $options = [], $background_task_options = []) {
		$schedule_task_args = [
			'from_email' => $from_email,
			'to_email' => $to_email,
			'subject' => $subject,
			'body' => $body,
			'mail_options' => $options,
		];

		$task_details = [];
		$task_details['task_name'] = 'send_mail';
		$task_details['scheduled_time_ts'] = $options['schedule_time'] ?? 0; //0 means send asap
		$task_details['task_args'] = $schedule_task_args;

		if (!empty($background_task_options['main_arg_name'])) {
			$task_details['main_arg_name'] = $background_task_options['main_arg_name'];
		}
		if (!empty($background_task_options['main_arg_value'])) {
			$task_details['main_arg_value'] = $background_task_options['main_arg_value'];
		}
		if (!empty($background_task_options['expiry_ts'])) {
			$task_details['expiry_ts'] = $background_task_options['expiry_ts'];
		}

		WPCal_Background_Tasks::add_task($task_details);
	}

	private static function schedule_mail_using_wp_mail($from_email, $to_email, $subject, $body, $options) {

		if (empty($options['schedule_time'])) {
			throw new WPCal_Exception('schedule_mail_invalid_schedule_time');
		}
		$schedule_time = $options['schedule_time'];

		$background_task_options = isset($options['background_task_options']) ? $options['background_task_options'] : '';
		unset($options['background_task_options']);

		if (empty($background_task_options['task_name'])) {
			throw new WPCal_Exception('schedule_mail_task_name_missing');
		}

		$schedule_task_args = [
			'from_email' => $from_email,
			'to_email' => $to_email,
			'subject' => $subject,
			'body' => $body,
			'mail_options' => $options,
		];

		$task_details = [];
		$task_details['task_name'] = $background_task_options['task_name'];
		$task_details['scheduled_time_ts'] = $schedule_time;
		$task_details['task_args'] = $schedule_task_args;

		if (!empty($background_task_options['main_arg_name'])) {
			$task_details['main_arg_name'] = $background_task_options['main_arg_name'];
		}
		if (!empty($background_task_options['main_arg_value'])) {
			$task_details['main_arg_value'] = $background_task_options['main_arg_value'];
		}
		if (!empty($background_task_options['expiry_ts'])) {
			$task_details['expiry_ts'] = $background_task_options['expiry_ts'];
		}

		WPCal_Background_Tasks::add_task($task_details);
	}

	private static function send_mail_via_mail_server($from_email, $to_email, $subject, $body, $options = []) {

		self::load_site_details();

		//var_dump($to_email, $subject, $options);

		$schedule_time = time() - 1;
		if (!empty($options['schedule_time'])) {
			$schedule_time = $options['schedule_time'];
		}

		if (empty($options['event_id'])) {
			throw new WPCal_Exception('event_id_missing');
		}

		$reply_to = '';
		if (!empty($options['reply_to'])) {
			$reply_to = $options['reply_to'];
		}

		$request_data = [
			"app_id" => self::$site_token,
			"site_url" => self::$site_url,
			"plugin_slug" => WPCAL_PLUGIN_SLUG,
			"plugin_verion" => WPCAL_VERSION,
			"event_id" => $options['event_id'],
			"account_email" => self::$license_email,
			"to_emails" => $to_email,
			"reply_to" => $reply_to,
			"email_subject" => $subject,
			"email_body" => $body,
			"schedule_time" => $schedule_time,
			//"ip_address" => "127.0.0.1",
		];

		$request_body = json_encode($request_data);

		$http_args = array(
			'method' => "POST",
			'headers' => ['Content-Type' => 'application/json'],
			'timeout' => 10,
			'body' => $request_body,
		);

		$url = WPCAL_CRON_URL . 'cron-event';

		try {
			//$__start_time = microtime(1);
			$response = wp_remote_request($url, $http_args);
			//var_dump(['total_time' => round( microtime(1) - $__start_time, 5)]);
			$response_data = wpcal_check_and_get_data_from_rest_api_response_json($response);
			//var_dump($response_data);
			if (isset($response_data['status'])) {
				if ($response_data['status'] === 'success') {
					return true;
				} elseif ($response_data['status'] === 'error') {
					$error_desc = isset($response_data['res_desc']) ? $response_data['res_desc'] : '';
					throw new WPCal_Exception('cron_server_error', $error_desc);
				}
			} else {
				wpcal_check_http_error($response);
			}
			throw new WPCal_Exception('cron_server_invalid_response');
		} catch (WPCal_Exception $e) {
			throw $e;
		} catch (Exception $e) {
			throw new WPCal_Exception('unknown_error', $e->getMessage());
		}
	}

	public static function send_mail_using_wp_mail($from_email, $to_email, $subject, $body, $options = []) {

		$headers = [];
		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		if (!empty($options['reply_to'])) {
			$headers[] = 'Reply-To: ' . $options['reply_to'];
		}

		$is_mail_sent = wp_mail($to_email, $subject, $body, $headers);
		return $is_mail_sent;
	}

	private static function delete_scheduled_mail($options) {

		if (empty($options['background_task_options'])) {
			throw new WPCal_Exception('delete_scheduled_mail_invalid_background_task_options');
		}
		$background_task_options = $options['background_task_options'];

		if (empty($background_task_options['task_name']) || empty($background_task_options['main_arg_name']) || empty($background_task_options['main_arg_value'])) {
			throw new WPCal_Exception('delete_scheduled_mail_invalid_background_task_option_missing');
		}

		$is_task_exists = WPCal_Background_Tasks::is_task_exists_by_main_args($background_task_options['task_name'], $background_task_options['main_arg_name'], $background_task_options['main_arg_value']);

		if ($is_task_exists) {
			self::delete_scheduled_mail_using_wp_mail($background_task_options);
		} else {
			if (empty($options['event_id'])) {
				throw new WPCal_Exception('delete_scheduled_mail_event_id_missing');
			}
			self::delete_scheduled_mail_via_mail_server($options['event_id']);
		}
	}

	private static function delete_scheduled_mail_via_mail_server($event_id) {
		self::load_site_details();

		$request_data = [
			"app_id" => self::$site_token,
			"plugin_slug" => WPCAL_PLUGIN_SLUG,
			"plugin_verion" => WPCAL_VERSION,
			"event_id" => $event_id,
		];

		$request_body = json_encode($request_data);

		$http_args = array(
			'method' => "POST",
			'headers' => ['Content-Type' => 'application/json'],
			'timeout' => 10,
			'body' => $request_body,
		);

		$url = WPCAL_CRON_URL . 'delete-event';

		try {
			//$__start_time = microtime(1);
			$response = wp_remote_request($url, $http_args);
			//var_dump(['total_time' => round( microtime(1) - $__start_time, 5)]);
			$response_data = wpcal_check_and_get_data_from_rest_api_response_json($response);
			//var_dump($response_data);
			if (isset($response_data['status'])) {
				if ($response_data['status'] === 'success') {
					return true;
				} elseif ($response_data['status'] === 'error') {
					$error_desc = isset($response_data['res_desc']) ? $response_data['res_desc'] : '';
					throw new WPCal_Exception('cron_server_error', $error_desc);
				}
			} else {
				wpcal_check_http_error($response);
			}
			throw new WPCal_Exception('cron_server_invalid_response');
		} catch (WPCal_Exception $e) {
			throw $e;
		} catch (Exception $e) {
			throw new WPCal_Exception('unknown_error', $e->getMessage());
		}
	}

	private static function delete_scheduled_mail_using_wp_mail($background_task_options) {
		global $wpdb;
		$updated_ts = time();

		$table_background_tasks = $wpdb->prefix . 'wpcal_background_tasks';

		$query = "UPDATE `$table_background_tasks` SET `status` = 'cancelled', `updated_ts` = %s WHERE `task_name` = %s AND `main_arg_name` = %s AND `main_arg_value` = %s AND `status` IN ('pending', 'retry')";
		$query = $wpdb->prepare($query, $updated_ts, $background_task_options['task_name'], $background_task_options['main_arg_name'], $background_task_options['main_arg_value']);
		$is_updated = $wpdb->query($query);

		if ($is_updated === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return true;
	}

	//======================================================================>

	public static function send_admin_new_booking_info(WPCal_Booking $booking_obj) {

		if ($booking_obj->is_booking_mail_sent_by_type('send_admin_new_booking_info_mail')) {
			//already sent
			return;
		}

		//prepare mail data
		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());

		$location_html = self::get_booking_location_html($booking_obj, 'admin');
		$question_answers_html = self::get_booking_invitee_question_answers_html($booking_obj);

		$admin_view_booking_url = $booking_obj->get_admin_view_booking_url();

		$old_admin_name = '';
		if ($booking_obj->is_old_rescheduled_and_new_booking_having_different_admins()) {
			$old_admin_user_id = $booking_obj->get_old_resceduled_booking_admin_user_id();
			$old_admin_details = wpcal_get_admin_details($old_admin_user_id);
			$old_admin_name = $old_admin_details['name'];
		}

		$mail_data = [
			'service_id' => $booking_obj->service_obj->get_id(),
			'booking_id' => $booking_obj->get_id(),
			'hi_name' => $admin_details['first_name'] ? ' ' . $admin_details['first_name'] : '',
			'booking_admin_first_name' => $admin_details['first_name'],
			'service_name' => $booking_obj->service_obj->get_name(),
			'invitee_name' => $booking_obj->get_invitee_name(),
			'invitee_email' => $booking_obj->get_invitee_email(),
			'invitee_tz' => wpcal_get_timezone_name($booking_obj->get_invitee_tz()),
			'invitee_question_answers_html' => $question_answers_html,
			'location_html' => $location_html,
			'booking_from_to_time_str_with_tz' => WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($booking_obj->get_booking_from_time(), $booking_obj->get_booking_to_time()),
			'admin_view_booking_url' => $admin_view_booking_url,
			'is_old_and_new_booking_having_different_admins' => $booking_obj->is_old_rescheduled_and_new_booking_having_different_admins(),
			'old_admin_name' => $old_admin_name,
		];

		$mail_whole_html = wpcal_get_template_html('emails/admin_booking_new.php', ['mail_data' => $mail_data], $booking_obj->service_obj->get_id());
		list($mail_subject, $mail_body) = self::get_mail_parts_from_html($mail_whole_html);

		if (!isset($mail_subject) || !isset($mail_body)) {
			throw new WPCal_Exception('mail_template_output_error');
		}

		$from_email = '';
		$to_email = $admin_details['email'];
		$subject = $mail_subject;
		$body = $mail_body;
		$options = ['event_id' => $booking_obj->get_unique_link() . '-admin_new_booking_info'];
		return self::send_mail($from_email, $to_email, $subject, $body, $options);
	}

	public static function send_admin_reschedule_booking_info(WPCal_Booking $booking_obj) {

		if ($booking_obj->is_booking_mail_sent_by_type('send_admin_reschedule_booking_info_mail')) {
			//already sent
			return;
		}

		//prepare mail data
		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());

		$old_booking_obj = wpcal_get_old_booking_if_rescheduled($booking_obj->get_id());
		$old_booking_from_to_time_str_with_tz = '';
		if ($old_booking_obj) {
			$old_booking_from_time = $old_booking_obj->get_booking_from_time();
			$old_booking_to_time = $old_booking_obj->get_booking_to_time();

			$old_booking_from_to_time_str_with_tz = WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($old_booking_from_time, $old_booking_to_time);
		}

		$location_html = self::get_booking_location_html($booking_obj, 'admin');
		$question_answers_html = self::get_booking_invitee_question_answers_html($booking_obj);

		$admin_view_booking_url = $booking_obj->get_admin_view_booking_url();

		$reschedule_cancel_user_id = $booking_obj->get_old_resceduled_booking_user_id();
		$reschedule_cancel_by = wpcal_related_get_user_full_name('reschedule_cancel_person_name', $reschedule_cancel_user_id, $whos_view = 'admin', $booking_obj, $admin_details);

		$mail_data = [
			'service_id' => $booking_obj->service_obj->get_id(),
			'booking_id' => $booking_obj->get_id(),
			'hi_name' => $admin_details['first_name'] ? ' ' . $admin_details['first_name'] : '',
			'booking_admin_first_name' => $admin_details['first_name'],
			'service_name' => $booking_obj->service_obj->get_name(),
			'invitee_name' => $booking_obj->get_invitee_name(),
			'invitee_email' => $booking_obj->get_invitee_email(),
			'invitee_tz' => wpcal_get_timezone_name($booking_obj->get_invitee_tz()),
			'invitee_question_answers_html' => $question_answers_html,
			'rescheduled_reason' => $booking_obj->get_old_resceduled_booking_reason() ? $booking_obj->get_old_resceduled_booking_reason() : '-',
			'rescheduled_by' => $reschedule_cancel_by,
			'location_html' => $location_html,
			'booking_from_to_time_str_with_tz' => WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($booking_obj->get_booking_from_time(), $booking_obj->get_booking_to_time()),
			'old_booking_from_to_time_str_with_tz' => $old_booking_from_to_time_str_with_tz,
			'admin_view_booking_url' => $admin_view_booking_url,
		];

		$mail_whole_html = wpcal_get_template_html('emails/admin_booking_reschedule.php', ['mail_data' => $mail_data], $booking_obj->service_obj->get_id());
		list($mail_subject, $mail_body) = self::get_mail_parts_from_html($mail_whole_html);

		if (!isset($mail_subject) || !isset($mail_body)) {
			throw new WPCal_Exception('mail_template_output_error');
		}

		$from_email = '';
		$to_email = $admin_details['email'];
		$subject = $mail_subject;
		$body = $mail_body;
		$options = ['event_id' => $booking_obj->get_unique_link() . '-admin_reschedule_booking_info'];
		return self::send_mail($from_email, $to_email, $subject, $body, $options);
	}

	public static function send_admin_booking_cancellation(WPCal_Booking $booking_obj) {

		if ($booking_obj->is_booking_mail_sent_by_type('send_admin_booking_cancellation_mail')) {
			//already sent
			return;
		}

		//prepare mail data
		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());

		$location_html = self::get_booking_location_html($booking_obj, 'admin');

		$reschedule_cancel_user_id = $booking_obj->get_reschedule_cancel_user_id();
		$reschedule_cancel_by = wpcal_related_get_user_full_name('reschedule_cancel_person_name', $reschedule_cancel_user_id, $whos_view = 'admin', $booking_obj, $admin_details);

		$is_old_and_new_booking_having_different_admins = false;
		$new_admin_name = '';
		if ($booking_obj->get_status() == '-5' && $booking_obj->get_rescheduled_booking_id()) { //reschedule - when host changes we will sent it as cancel mail
			$new_booking_obj = wpcal_get_booking($booking_obj->get_rescheduled_booking_id());
			$is_old_and_new_booking_having_different_admins = $new_booking_obj->is_old_rescheduled_and_new_booking_having_different_admins();
			if ($is_old_and_new_booking_having_different_admins) {
				$new_admin_details = wpcal_get_admin_details($new_booking_obj->get_admin_user_id());
				$new_admin_name = $new_admin_details['name'];
			}
		}

		$mail_data = [
			'service_id' => $booking_obj->service_obj->get_id(),
			'booking_id' => $booking_obj->get_id(),
			'hi_name' => $admin_details['first_name'] ? ' ' . $admin_details['first_name'] : '',
			'booking_admin_first_name' => $admin_details['first_name'],
			'service_name' => $booking_obj->service_obj->get_name(),
			'invitee_name' => $booking_obj->get_invitee_name(),
			'invitee_email' => $booking_obj->get_invitee_email(),
			//'invitee_tz' => $booking_obj->get_invitee_tz(),
			'reschedule_cancel_reason' => $booking_obj->get_reschedule_cancel_reason() ? $booking_obj->get_reschedule_cancel_reason() : '-',
			'reschedule_cancel_by' => $reschedule_cancel_by,
			'location_html' => $location_html,
			'booking_from_to_time_str_with_tz' => WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($booking_obj->get_booking_from_time(), $booking_obj->get_booking_to_time()),
			'is_old_and_new_booking_having_different_admins' => $is_old_and_new_booking_having_different_admins,
			'new_admin_name' => $new_admin_name,
		];

		$mail_whole_html = wpcal_get_template_html('emails/admin_booking_cancellation.php', ['mail_data' => $mail_data], $booking_obj->service_obj->get_id());
		list($mail_subject, $mail_body) = self::get_mail_parts_from_html($mail_whole_html);

		if (!isset($mail_subject) || !isset($mail_body)) {
			throw new WPCal_Exception('mail_template_output_error');
		}

		$from_email = '';
		$to_email = $admin_details['email'];
		$subject = $mail_subject;
		$body = $mail_body;
		$options = ['event_id' => $booking_obj->get_unique_link() . '-admin_booking_cancellation'];
		return self::send_mail($from_email, $to_email, $subject, $body, $options);
	}

	public static function send_invitee_booking_confirmation(WPCal_Booking $booking_obj) {

		if ($booking_obj->is_booking_mail_sent_by_type('send_invitee_booking_confirmation_mail')) {
			//already sent
			return;
		}

		//prepare mail data
		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());
		$invitee_name = $booking_obj->get_invitee_name();
		$invitee_name_parts = explode(' ', $invitee_name, 2);
		$invitee_first_name = $invitee_name_parts[0];

		$timezone_name = $booking_obj->service_obj->get_tz();
		$booking_from_time = $booking_obj->get_booking_from_time();
		$booking_to_time = $booking_obj->get_booking_to_time();

		if ($booking_obj->get_invitee_tz()) {
			$timezone_name = $booking_obj->get_invitee_tz();
			$booking_from_time->setTimezone(new DateTimeZone($timezone_name));
			$booking_to_time->setTimezone(new DateTimeZone($timezone_name));
		}
		$booking_from_to_time_str_with_tz = WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($booking_from_time, $booking_to_time);

		$location_html = self::get_booking_location_html($booking_obj, 'user');
		$question_answers_html = self::get_booking_invitee_question_answers_html($booking_obj);

		$mail_data = [
			'service_id' => $booking_obj->service_obj->get_id(),
			'booking_id' => $booking_obj->get_id(),
			'booking_admin_display_name' => $admin_details['display_name'],
			'service_name' => $booking_obj->service_obj->get_name(),
			'hi_name' => $invitee_first_name ? ' ' . $invitee_first_name : '',
			'location_html' => $location_html,
			'invitee_question_answers_html' => $question_answers_html,
			'add_event_to_google_calendar_url' => $booking_obj->get_add_event_to_google_calendar_url(),
			'download_ics_url' => $booking_obj->get_download_ics_url(),
			'reschedule_url' => $booking_obj->get_redirect_reschedule_url(),
			'cancel_url' => $booking_obj->get_redirect_cancel_url(),
			'booking_from_to_time_str_with_tz' => $booking_from_to_time_str_with_tz,
		];

		$mail_whole_html = wpcal_get_template_html('emails/invitee_booking_new.php', ['mail_data' => $mail_data], $booking_obj->service_obj->get_id());
		list($mail_subject, $mail_body) = self::get_mail_parts_from_html($mail_whole_html);

		if (!isset($mail_subject) || !isset($mail_body)) {
			throw new WPCal_Exception('mail_template_output_error');
		}

		$from_email = '';
		//$to_email = $booking_obj->get_invitee_name().' <'.$booking_obj->get_invitee_email().'>';
		$to_email = $booking_obj->get_invitee_email();
		$subject = $mail_subject;
		$body = $mail_body;
		$options = [
			'event_id' => $booking_obj->get_unique_link() . '-invitee_booking_confirmation',
			'reply_to' => $admin_details['email'],
			//'reply_to' => $admin_details['display_name'].' <'.$admin_details['email'].'>'
		];

		return self::send_mail($from_email, $to_email, $subject, $body, $options);
	}

	public static function send_invitee_reschedule_booking_confirmation(WPCal_Booking $booking_obj) {

		if ($booking_obj->is_booking_mail_sent_by_type('send_invitee_reschedule_booking_confirmation_mail')) {
			//already sent
			return;
		}

		//prepare mail data
		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());
		$invitee_name = $booking_obj->get_invitee_name();
		$invitee_name_parts = explode(' ', $invitee_name, 2);
		$invitee_first_name = $invitee_name_parts[0];

		$timezone_name = $booking_obj->service_obj->get_tz();
		$booking_from_time = $booking_obj->get_booking_from_time();
		$booking_to_time = $booking_obj->get_booking_to_time();

		$old_booking_obj = wpcal_get_old_booking_if_rescheduled($booking_obj->get_id());
		if ($old_booking_obj) {
			$old_booking_from_time = $old_booking_obj->get_booking_from_time();
			$old_booking_to_time = $old_booking_obj->get_booking_to_time();
		}

		if ($booking_obj->get_invitee_tz()) {
			$timezone_name = $booking_obj->get_invitee_tz();
			$booking_from_time->setTimezone(new DateTimeZone($timezone_name));
			$booking_to_time->setTimezone(new DateTimeZone($timezone_name));
			if ($old_booking_obj) {
				$old_booking_from_time->setTimezone(new DateTimeZone($timezone_name));
				$old_booking_to_time->setTimezone(new DateTimeZone($timezone_name));
			}

		}
		$booking_from_to_time_str_with_tz = WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($booking_from_time, $booking_to_time);

		$reschedule_from_to_time_str_with_tz = '';
		if ($old_booking_obj) {
			$reschedule_from_to_time_str_with_tz = WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($old_booking_from_time, $old_booking_to_time);
		}

		$location_html = self::get_booking_location_html($booking_obj, 'user');
		$question_answers_html = self::get_booking_invitee_question_answers_html($booking_obj);

		$reschedule_cancel_user_id = $booking_obj->get_old_resceduled_booking_user_id();
		$reschedule_cancel_by = wpcal_related_get_user_full_name('reschedule_cancel_person_name', $reschedule_cancel_user_id, $whos_view = 'user', $booking_obj, $admin_details);

		$mail_data = [
			'service_id' => $booking_obj->service_obj->get_id(),
			'booking_id' => $booking_obj->get_id(),
			'booking_admin_display_name' => $admin_details['display_name'],
			'service_name' => $booking_obj->service_obj->get_name(),
			'hi_name' => $invitee_first_name ? ' ' . $invitee_first_name : '',
			'rescheduled_reason' => $booking_obj->get_old_resceduled_booking_reason() ? $booking_obj->get_old_resceduled_booking_reason() : '',
			'rescheduled_by' => $reschedule_cancel_by,
			'location_html' => $location_html,
			'invitee_question_answers_html' => $question_answers_html,
			'add_event_to_google_calendar_url' => $booking_obj->get_add_event_to_google_calendar_url(),
			'download_ics_url' => $booking_obj->get_download_ics_url(),
			'reschedule_url' => $booking_obj->get_redirect_reschedule_url(),
			'cancel_url' => $booking_obj->get_redirect_cancel_url(),
			'booking_from_to_time_str_with_tz' => $booking_from_to_time_str_with_tz,
			'reschedule_booking_from_to_time_str_with_tz' => $reschedule_from_to_time_str_with_tz,
		];

		$mail_whole_html = wpcal_get_template_html('emails/invitee_booking_reschedule.php', ['mail_data' => $mail_data], $booking_obj->service_obj->get_id());
		list($mail_subject, $mail_body) = self::get_mail_parts_from_html($mail_whole_html);

		if (!isset($mail_subject) || !isset($mail_body)) {
			throw new WPCal_Exception('mail_template_output_error');
		}

		$from_email = '';
		//$to_email = $booking_obj->get_invitee_name().' <'.$booking_obj->get_invitee_email().'>';
		$to_email = $booking_obj->get_invitee_email();
		$subject = $mail_subject;
		$body = $mail_body;
		$options = [
			'event_id' => $booking_obj->get_unique_link() . '-invitee_reschedule_booking_confirmation',
			'reply_to' => $admin_details['email'],
			//'reply_to' => $admin_details['display_name'].' <'.$admin_details['email'].'>'
		];

		return self::send_mail($from_email, $to_email, $subject, $body, $options);
	}

	public static function send_invitee_booking_cancellation(WPCal_Booking $booking_obj) {

		if ($booking_obj->is_booking_mail_sent_by_type('send_invitee_booking_cancellation_mail')) {
			//already sent
			return;
		}

		//prepare mail data
		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());
		$invitee_name = $booking_obj->get_invitee_name();
		$invitee_name_parts = explode(' ', $invitee_name, 2);
		$invitee_first_name = $invitee_name_parts[0];

		$timezone_name = $booking_obj->service_obj->get_tz();
		$booking_from_time = $booking_obj->get_booking_from_time();
		$booking_to_time = $booking_obj->get_booking_to_time();

		if ($booking_obj->get_invitee_tz()) {
			$timezone_name = $booking_obj->get_invitee_tz();
			$booking_from_time->setTimezone(new DateTimeZone($timezone_name));
			$booking_to_time->setTimezone(new DateTimeZone($timezone_name));
		}
		$booking_from_to_time_str_with_tz = WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($booking_from_time, $booking_to_time);

		$location_html = self::get_booking_location_html($booking_obj, 'user');

		$reschedule_cancel_user_id = $booking_obj->get_reschedule_cancel_user_id();
		$reschedule_cancel_by = wpcal_related_get_user_full_name('reschedule_cancel_person_name', $reschedule_cancel_user_id, $whos_view = 'user', $booking_obj, $admin_details);

		$mail_data = [
			'service_id' => $booking_obj->service_obj->get_id(),
			'booking_id' => $booking_obj->get_id(),
			'booking_admin_display_name' => $admin_details['display_name'],
			'service_name' => $booking_obj->service_obj->get_name(),
			'hi_name' => $invitee_first_name ? ' ' . $invitee_first_name : '',
			'reschedule_cancel_reason' => $booking_obj->get_reschedule_cancel_reason() ? $booking_obj->get_reschedule_cancel_reason() : '',
			'reschedule_cancel_by' => $reschedule_cancel_by,
			'location_html' => $location_html,
			'add_event_to_google_calendar_url' => $booking_obj->get_add_event_to_google_calendar_url(),
			'download_ics_url' => $booking_obj->get_download_ics_url(),
			'reschedule_url' => $booking_obj->get_redirect_reschedule_url(),
			'cancel_url' => $booking_obj->get_redirect_cancel_url(),
			'booking_from_to_time_str_with_tz' => $booking_from_to_time_str_with_tz,
		];

		$mail_whole_html = wpcal_get_template_html('emails/invitee_booking_cancellation.php', ['mail_data' => $mail_data], $booking_obj->service_obj->get_id());
		list($mail_subject, $mail_body) = self::get_mail_parts_from_html($mail_whole_html);

		if (!isset($mail_subject) || !isset($mail_body)) {
			throw new WPCal_Exception('mail_template_output_error');
		}

		$from_email = '';
		//$to_email = $booking_obj->get_invitee_name().' <'.$booking_obj->get_invitee_email().'>';
		$to_email = $booking_obj->get_invitee_email();
		$subject = $mail_subject;
		$body = $mail_body;
		$options = [
			'event_id' => $booking_obj->get_unique_link() . '-invitee_booking_cancellation',
			'reply_to' => $admin_details['email'],
			//'reply_to' => $admin_details['display_name'].' <'.$admin_details['email'].'>'
		];

		return self::send_mail($from_email, $to_email, $subject, $body, $options);
	}

	public static function schedule_invitee_booking_reminder(WPCal_Booking $booking_obj) {
		if (!self::$dev_preview && ($booking_obj->is_booking_mail_sent_by_type('send_invitee_booking_reminder_mail') ||
			$booking_obj->is_booking_mail_sent_by_type('schedule_invitee_booking_reminder_mail')
		)) {
			//already sent
			return;
		}

		//prepare mail data
		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());
		$invitee_name = $booking_obj->get_invitee_name();
		$invitee_name_parts = explode(' ', $invitee_name, 2);
		$invitee_first_name = $invitee_name_parts[0];

		$timezone_name = $booking_obj->service_obj->get_tz();
		$booking_from_time = $booking_obj->get_booking_from_time();
		$booking_to_time = $booking_obj->get_booking_to_time();

		$schedule_time = WPCal_DateTime_Helper::DateTime_Obj_to_unix($booking_from_time) - (24 * 60 * 60);

		if ($schedule_time < time()) {
			//schedule_time already crossed lets not send the reminder
			return true;
		}

		if ($booking_obj->get_invitee_tz()) {
			$timezone_name = $booking_obj->get_invitee_tz();
			$booking_from_time->setTimezone(new DateTimeZone($timezone_name));
			$booking_to_time->setTimezone(new DateTimeZone($timezone_name));
		}

		$booking_from_to_time_str_with_tz = WPCal_DateTime_Helper::DateTime_Obj_to_from_and_to_full_date_time_with_tz($booking_from_time, $booking_to_time);

		$location_html = self::get_booking_location_html($booking_obj, 'user');

		$mail_data = [
			'service_id' => $booking_obj->service_obj->get_id(),
			'booking_id' => $booking_obj->get_id(),
			'booking_admin_display_name' => $admin_details['display_name'],
			'service_name' => $booking_obj->service_obj->get_name(),
			'hi_name' => $invitee_first_name ? ' ' . $invitee_first_name : '',
			'location_html' => $location_html,
			'add_event_to_google_calendar_url' => $booking_obj->get_add_event_to_google_calendar_url(),
			'download_ics_url' => $booking_obj->get_download_ics_url(),
			'reschedule_url' => $booking_obj->get_redirect_reschedule_url(),
			'cancel_url' => $booking_obj->get_redirect_cancel_url(),
			'booking_from_to_time_str_with_tz' => $booking_from_to_time_str_with_tz,
		];

		$mail_whole_html = wpcal_get_template_html('emails/invitee_booking_reminder.php', ['mail_data' => $mail_data], $booking_obj->service_obj->get_id());
		list($mail_subject, $mail_body) = self::get_mail_parts_from_html($mail_whole_html);

		if (!isset($mail_subject) || !isset($mail_body)) {
			throw new WPCal_Exception('mail_template_output_error');
		}

		$from_email = '';
		//$to_email = $booking_obj->get_invitee_name().' <'.$booking_obj->get_invitee_email().'>';
		$to_email = $booking_obj->get_invitee_email();
		$subject = $mail_subject;
		$body = $mail_body;

		$to_time_obj = $booking_obj->get_booking_to_time();
		$expiry_ts = WPCal_DateTime_Helper::DateTime_Obj_to_unix($to_time_obj) + WPCAL_ADD_BOOKING_BG_TASK_RELATIVE_EXPIRY; // N seconds after booking "to" time

		$options = [
			'event_id' => $booking_obj->get_unique_link() . '-invitee_booking_reminder',
			'reply_to' => $admin_details['email'],
			//'reply_to' => $admin_details['display_name'].' <'.$admin_details['email'].'>',
			'schedule_time' => $schedule_time,
			'background_task_options' => [ //only when using wp_mail these options will be used
				'task_name' => 'send_scheduled_invitee_reminder_mail',
				'main_arg_name' => 'booking_id',
				'main_arg_value' => $booking_obj->get_id(),
				'expiry_ts' => $expiry_ts,
			],
		];

		return self::send_mail($from_email, $to_email, $subject, $body, $options);
	}

	public static function delete_scheduled_invitee_booking_reminder(WPCal_Booking $booking_obj) {

		$event_id = $booking_obj->get_unique_link() . '-invitee_booking_reminder';
		$options = [
			'event_id' => $event_id,
			'background_task_options' => [ //only when using wp_mail these options will be used
				'task_name' => 'send_scheduled_invitee_reminder_mail',
				'main_arg_name' => 'booking_id',
				'main_arg_value' => $booking_obj->get_id(),
			],
		];
		return self::delete_scheduled_mail($options);
	}

	//======================================================================>

	private static function get_booking_location_html($booking_obj, $whos_view) {
		$location = $booking_obj->get_location();
		if (empty($location) || empty($location['type'])) {
			return '';
		}

		$label_of_location_types = [
			'zoom_meeting' => 'Zoom',
			'gotomeeting_meeting' => 'GoToMeeting',
			'googlemeet_meeting' => 'Google Hangout / Meet',
		];

		$location_html = '
		<tr>
		<td style="padding: 10px 0;">
		  <strong style="font-size: 11px; text-transform: uppercase;"
			>' . __('Location', 'wpcal') . '</strong
		  >';

		if (in_array($location['type'], ['zoom_meeting', 'gotomeeting_meeting', 'googlemeet_meeting']) && !empty($location['form']['location'])) {

			$location_html .= '<br />
		  <span style="color: #7c7d9c;">' . $label_of_location_types[$location['type']] . ' ' . __('Web Conference', 'wpcal') . '</span>';
			$location_html .= '<br />
		  <span style="color: #7c7d9c;"><a href="' . $location['form']['location'] . '">' . $location['form']['location'] . '</a></span>';

			if (!empty($location['form']['display_meeting_id'])) {
				$meeting_id_label = $location['type'] === 'googlemeet_meeting' ? __('Meeting code', 'wpcal') : __('Meeting ID', 'wpcal');

				$location_html .= '<br />
			<span style="color: #7c7d9c;">' . $label_of_location_types[$location['type']] . ' ' . $meeting_id_label . ': ' . $location['form']['display_meeting_id'] . '</span>';
			}

			if (!empty($location['form']['password_data']['password'])) {
				$password_label = $location['form']['password_data']['label'] ? __($location['form']['password_data']['label'], 'wpcal') : __('Password', 'wpcal');
				$location_html .= '<br />
			<span style="color: #7c7d9c;">' . $password_label . ': ' . $location['form']['password_data']['password'] . '</span>';
			}

			$location_html .= '<br />
		  <span style="font-size: 11px;">' . __('You can join from any device.', 'wpcal') . '</span>';
		}

		if ($location['type'] == 'phone' && !empty($location['form']['location']) && !empty($location['form']['who_calls'])) {
			//$location_html .= '<span style="color: #7c7d9c;">Phone call</span>';
			$location_str = $booking_obj->get_location_str($whos_view, $html = true);
			$location_html .= '<br />
			<span style="color: #7c7d9c;">' . $location_str . '</span>';
		}

		if (in_array($location['type'], ['physical', 'custom', 'ask_invitee']) && !empty($location['form']['location'])) {
			$location_html .= '<br />
		  <span style="color: #7c7d9c;">' . $location['form']['location'] . '</span>';
		}

		if (in_array($location['type'], ['physical', 'custom']) && !empty($location['form']['location_extra'])) {
			$location_html .= '<br>
		  <span style="color: #7c7d9c; white-space: pre;">' . $location['form']['location_extra'] . '</span>';
		}

		$location_html .= '
			</td>
		</tr>
		';
		return $location_html;
	}

	private static function get_booking_invitee_question_answers_html($booking_obj) {
		$question_answers_html = '';
		$question_answers = $booking_obj->get_invitee_question_answers();
		if (empty($question_answers) || !is_array($question_answers)) {
			return $question_answers_html;
		}

		$first_q_a_style = 'border-top: 1px solid #d8dbe7;';
		$this_q_a_html = '';
		foreach ($question_answers as $key => $question_answer) {
			$this_q_a_html .= '
			<tr>
				<td style="padding: 10px 0;' . $first_q_a_style . '">
					<div style="white-space: pre-wrap;">' . $question_answer['question'] . '</div>
					<div style="color: #7c7d9c; margin-top: 5px; white-space: pre-wrap;' . ($question_answer['answer'] == '' ? ' font-style: italic;' : '') . '">' . ($question_answer['answer'] == '' ? __('(Not entered)', 'wpcal') : $question_answer['answer']) . '</div>
				</td>
			</tr>';

			$question_answers_html .= $this_q_a_html;
			$this_q_a_html = '';
			$first_q_a_style = '';
		}

		return $question_answers_html;
	}

	private static function get_mail_parts_from_html($mail_whole_html) {

		$mail_parts = explode('<!-- WPCal_mail_separator DO_NOT_EDIT_THIS_LINE -->', $mail_whole_html, 2);

		$subject = '';

		if (count($mail_parts) == 2) {
			$other_details = $mail_parts[0];
			$subject = wpcal_get_tag_content($other_details, 'subject');
			$body = $mail_parts[1];
		} else {
			$body = $mail_parts[0];
		}

		return [$subject, $body];
	}

	//=========================================================================>

	public static function send_admin_api_error_need_action($tp_calendar_account = null, $tp_account = null, $error_details = [], $options = []) {

		if (!empty($tp_calendar_account) && ($tp_calendar_account instanceof WPCal_Abstract_TP_Calendar)) {
			$tp_calendar_account_id = $tp_calendar_account->get_cal_account_id();
			$provider = $tp_calendar_account->get_provider();
			$tp_calendar_account_details = $tp_calendar_account->get_cal_account_details();
			$admin_user_id = $tp_calendar_account_details->admin_user_id;
			$account_name = $tp_calendar_account_details->account_email;
			$view_setting_url = get_admin_url() . 'admin.php?page=wpcal_admin#/settings/calendars';

		} elseif (!empty($tp_account) && ($tp_account instanceof WPCal_Abstract_TP)) {
			$tp_account_id = $tp_account->get_tp_account_id();
			$provider = $tp_account->get_provider();
			$tp_account_details = $tp_account->get_account_details();
			$admin_user_id = $tp_account_details->admin_user_id;
			$account_name = $tp_account_details->tp_account_email;
			$view_setting_url = get_admin_url() . 'admin.php?page=wpcal_admin#/settings/integrations';

		} else {
			throw new WPCal_Exception('invalid_input');
		}

		$admin_details = wpcal_get_admin_details($admin_user_id);

		$label_providers = [
			'zoom_meeting' => 'Zoom',
			'gotomeeting_meeting' => 'GoToMeeting',
			'google_calendar' => 'Google Calendar',
		];

		$mail_data = [
			'account_id' => $tp_calendar_account_id ?? $tp_account_id,
			'provider' => $provider,
			'provider_name' => $label_providers[$provider] ?? '',
			'account_name' => $account_name,
			'hi_name' => $admin_details['first_name'] ? ' ' . $admin_details['first_name'] : '',
			'view_settings_url' => $view_setting_url,
		];

		$mail_whole_html = wpcal_get_template_html('emails/admin_api_error_need_action.php', ['mail_data' => $mail_data]);
		list($mail_subject, $mail_body) = self::get_mail_parts_from_html($mail_whole_html);

		if (!isset($mail_subject) || !isset($mail_body)) {
			throw new WPCal_Exception('mail_template_output_error');
		}

		$from_email = '';
		$to_email = $admin_details['email'];
		$subject = $mail_subject;
		$body = $mail_body;
		$_options = ['event_id' => 'api_error_need_action-' . time()];
		$background_task_options = [
			'main_arg_name' => 'api_error_need_action_' . $provider,
			'main_arg_value' => $mail_data['account_id'],
			'expiry_ts' => time() + WPCAL_ADMIN_ACTION_REQUIRED_MAIL_RELATIVE_EXPIRY,
		];
		if (self::$dev_preview) {
			return self::send_mail($from_email, $to_email, $subject, $body, $_options);
		} else {
			return self::send_mail_later($from_email, $to_email, $subject, $body, $_options, $background_task_options); //save in background_tasks table and send
		}
	}
}
