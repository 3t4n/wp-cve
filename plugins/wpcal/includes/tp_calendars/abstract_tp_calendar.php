<?php
if (!defined('ABSPATH')) {exit;}

abstract class WPCal_Abstract_TP_Calendar {
	//abstract protected function get_list();
	abstract protected function api_refresh_calendars();
	//abstract protected function api_refresh_events();
	//abstract protected function get_events();

	abstract protected function api_add_event($cal_details, WPCal_Booking $booking_obj);
	abstract protected function api_update_event($cal_details, WPCal_Booking $booking_obj);
	abstract protected function api_delete_event($cal_details, WPCal_Booking $booking_obj);

	public function is_status_ok() {
		if (empty($this->cal_account_details)) {
			$this->load_account_details();
		}
		if (property_exists($this->cal_account_details, 'status') && $this->cal_account_details->status == 1) {
			return true;
		}
		return false;
	}

	public function get_calendar_id_by_tp_cal_id($tp_cal_id) {
		global $wpdb;

		$table_calendars = $wpdb->prefix . 'wpcal_calendars';
		$query = "SELECT `id` FROM `$table_calendars` WHERE `calendar_account_id` = %s AND `tp_cal_id` = %s";
		$query = $wpdb->prepare($query, $this->cal_account_id, $tp_cal_id);
		$result = $wpdb->get_var($query);
		if (!empty($result)) {
			return $result;
		}
		return false;
	}

	protected function do_add_or_update_calendar($cal_data) {
		global $wpdb;

		$table_calendars = $wpdb->prefix . 'wpcal_calendars';
		$cal_id = $this->get_calendar_id_by_tp_cal_id($cal_data['tp_cal_id']);

		$cal_data['updated_ts'] = time();
		if (!empty($cal_id)) {
			$result = $wpdb->update($table_calendars, $cal_data, ['id' => $cal_id]);
			if ($result === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			}
		} else {
			if (empty($cal_data['calendar_account_id']) || empty($cal_data['tp_cal_id'])) {
				throw new WPCal_Exception('invalid_input');
			}
			$cal_data['status'] = '1';
			$cal_data['added_ts'] = $cal_data['updated_ts'];
			$result = $wpdb->insert($table_calendars, $cal_data);
			if ($result === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			}
		}
		return $result;
	}

	protected function update_calendar_sync_status($cal_id, $new_status, $old_status) {
		global $wpdb;

		$cal_data = [
			'list_events_sync_status' => $new_status, 'list_events_sync_status_update_ts' => time(),
		];

		if ($new_status == 'completed') {
			$cal_data['list_events_sync_last_update_ts'] = time(); //sprint4 from now on list_events_sync_last_update_ts will be updated on completed status marked. Currently, even completed status is not validated
		}

		$where = ['id' => $cal_id, 'list_events_sync_status' => $old_status];

		$table_calendars = $wpdb->prefix . 'wpcal_calendars';

		$result = $wpdb->update($table_calendars, $cal_data, $where);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return $result;
	}

	protected function do_add_or_update_calendar_event($cal_id, $event_data) {
		global $wpdb;

		// var_dump('====cal_id=event_data===', $cal_id, $event_data);

		$table_calendar_events = $wpdb->prefix . 'wpcal_calendar_events';
		$event_id = $this->get_event_id_by_tp_event_id($cal_id, $event_data['tp_event_id']);

		$event_data['updated_ts'] = time();
		if (!empty($event_id)) {
			$result = $wpdb->update($table_calendar_events, $event_data, ['id' => $event_id]);
			if ($result === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			}
		} else {
			$event_data['added_ts'] = $event_data['updated_ts'];
			$result = $wpdb->insert($table_calendar_events, $event_data);
			if ($result === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			}
		}
		wpcal_service_availability_slots_mark_refresh_cache_by_admin($this->cal_account_details->admin_user_id);
		return $result;
	}

	protected function delete_calendar_event($cal_id, $tp_event_id) {
		// var_dump('====cal_id=tp_event_id===', $cal_id, $tp_event_id);

		global $wpdb;

		$table_calendar_events = $wpdb->prefix . 'wpcal_calendar_events';
		$result = $wpdb->delete($table_calendar_events, ['calendar_id' => $cal_id, 'tp_event_id' => $tp_event_id]);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		wpcal_service_availability_slots_mark_refresh_cache_by_admin($this->cal_account_details->admin_user_id);
		return $result;
	}

	protected function get_event_id_by_tp_event_id($cal_id, $tp_event_id) {
		global $wpdb;

		$table_calendar_events = $wpdb->prefix . 'wpcal_calendar_events';
		$query = "SELECT `id` FROM `$table_calendar_events` WHERE `calendar_id` = %s AND `tp_event_id` = %s";
		$query = $wpdb->prepare($query, $cal_id, $tp_event_id);
		$result = $wpdb->get_var($query);
		if (!empty($result)) {
			return $result;
		}
		return false;
	}

	protected function load_account_details() {
		global $wpdb;

		$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
		$query = "SELECT * FROM `$table_calendar_accounts` WHERE id = %s AND `provider` = %s";
		$query = $wpdb->prepare($query, $this->get_cal_account_id(), $this->get_provider());
		$result = $wpdb->get_row($query);
		if (empty($result)) {
			throw new WPCal_Exception('calendar_account_id_not_exists');
		}

		$this->api_token = wpcal_decode_token($result->api_token);
		unset($result->api_token);
		$this->cal_account_details = $result;
	}

	protected function update_account_details($details) {
		global $wpdb;

		$data = wpcal_get_allowed_fields($details, $this->get_cal_account_details_edit_allowed_keys());
		isset($data['api_token']) ? $data['api_token'] = wpcal_encode_token($data['api_token']) : '';

		$data['updated_ts'] = time();

		$table = $wpdb->prefix . 'wpcal_calendar_accounts';
		$result = $wpdb->update($table, $data, ['id' => $this->get_cal_account_id()]);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		$this->load_account_details();
		return $result;
	}

	protected function update_last_token_fetch_attempt_ts($old_last_token_fetch_attempt_ts) {
		global $wpdb;

		// last_token_fetch_attempt_ts will be updated only during periodic fetch token process only.

		$table = $wpdb->prefix . 'wpcal_calendar_accounts';
		$data = ['last_token_fetch_attempt_ts' => time()];
		$where = ['id' => $this->get_cal_account_id(), 'last_token_fetch_attempt_ts' => $old_last_token_fetch_attempt_ts];

		$result = $wpdb->update($table, $data, $where);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		$this->load_account_details();
		return $result;
	}

	public function perodic_fetch_and_save_refresh_token() {
		if (!method_exists($this, 'do_periodic_fetch_and_save_refresh_token')) {
			return false;
		}

		$threshold = time() - 4 * 60 * 60;
		if ($this->cal_account_details->last_token_fetch_attempt_ts > $threshold) {
			//called too early
			return false;
		}

		$is_updated_this_instance = $this->update_last_token_fetch_attempt_ts($this->cal_account_details->last_token_fetch_attempt_ts); // update fetch attempt before making the call.
		if (!$is_updated_this_instance) {
			//data already changed possibly by another instance
			return false;
		}

		$this->do_periodic_fetch_and_save_refresh_token();
	}

	protected function add_or_update_calendar_account($details, $only_update = false) {
		global $wpdb;

		if (!is_array($details)) {
			return false;
		}

		$data = wpcal_get_allowed_fields($details, ['account_email', 'api_token', 'last_token_fetched_ts']);
		$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
		$current_admin_user_id = get_current_user_id();

		//check if exists then update
		$query = "SELECT `id` FROM `$table_calendar_accounts` WHERE `admin_user_id` = %s AND `account_email` = %s";
		$query = $wpdb->prepare($query, $current_admin_user_id, $data['account_email']);
		$calendar_account_id = $wpdb->get_var($query);

		if (!empty($calendar_account_id)) {
			$this->cal_account_id = $calendar_account_id;
			$update_data = ['api_token' => $data['api_token']];
			if (isset($data['last_token_fetched_ts'])) {
				$update_data['last_token_fetched_ts'] = $data['last_token_fetched_ts'];
			}
			if (!empty($details['_auth_done'])) { //just now authorized - here reauthorized
				$update_data['status'] = '1';
				//For Google Calendar - list_calendars_sync_token - no need to reset sync token or even calendars table events list sync token, old sync tokens works after reauth.
			}
			$this->update_account_details($update_data);
			return $calendar_account_id;
		}

		if ($only_update) { // should have updated using update_account_details(); Need to improve code, mostly used in reauth
			if (!empty($data['api_token'])) {
				echo 'You cannot add a new account using reconnect link. <a href="admin.php?page=wpcal_admin#/settings/calendars">Click here</a>.';
				exit;
			}
			return false;
		}

		if (wpcal_is_calendar_accounts_limit_reached_of_current_admin()) {
			//to do revoke access with API
			throw new WPCal_Exception('max_calendar_account_limit_reached');
		}

		isset($data['api_token']) ? $data['api_token'] = wpcal_encode_token($data['api_token']) : '';

		//add it here
		$data['provider'] = $this->get_provider();
		$data['status'] = '1';
		$data['admin_user_id'] = $current_admin_user_id;

		$data['added_ts'] = $data['updated_ts'] = time();

		$result = $wpdb->insert($table_calendar_accounts, $data);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		$calendar_account_id = $wpdb->insert_id;
		return $calendar_account_id;
	}

	protected function get_all_calendars() {
		global $wpdb;

		$table_calendars = $wpdb->prefix . 'wpcal_calendars';
		$query = "SELECT * FROM `$table_calendars` WHERE `calendar_account_id` = %s AND `status` = '1'";
		$query = $wpdb->prepare($query, $this->get_cal_account_id());
		$result = $wpdb->get_results($query);
		if (!empty($result)) {
			return $result;
		}
		return [];
	}

	protected function get_all_conflict_calendars() {
		global $wpdb;

		$table_calendars = $wpdb->prefix . 'wpcal_calendars';
		$query = "SELECT * FROM `$table_calendars` WHERE `calendar_account_id` = %s AND `status` = '1' AND `is_conflict_calendar` = '1'";
		$query = $wpdb->prepare($query, $this->get_cal_account_id());
		$result = $wpdb->get_results($query);
		if (!empty($result)) {
			return $result;
		}
		return [];
	}

	protected function manage_calendar_events_webhooks($action = 'all') {
		// for calendar events - webhooks(watch) is only required for conflict calendars

		// For each calendar one webhook(according to GCal) for events watch

		// in GCal we gonna set 30 days expiration(max allowed), after 20th day create new one and delete old new

		$do_add = true;
		$do_stop = true;

		if ($action == 'stop_only') {
			$do_add = false;
		}

		$calendars = $this->get_all_calendars();

		foreach ($calendars as $calendar) {

			//STEP 1) may add webhook for conflict calendars
			if (
				$do_add &&
				$this->is_calendar_events_webhook_required($calendar) && !$this->is_calendar_events_webhook_added($calendar)
			) { //webhook needs to be added
				$this->add_calendar_events_webhook($calendar);
				continue;
			}

			//STEP 2) check about to expire or expired webhook and renew
			if (
				$do_add &&
				$this->is_calendar_events_webhook_required($calendar) && $this->is_calendar_events_webhook_added($calendar) && $this->is_calendar_events_webhook_expired_or_about_to_expiry($calendar)
			) { //need to renew
				$this->renew_calendar_events_webhook($calendar);
				continue;
			}

			//STEP 3) Remove unnecessary webhook - probably removed from conflict calendar
			if (
				$do_stop &&
				!$this->is_calendar_events_webhook_required($calendar) &&
				$this->is_calendar_events_webhook_added($calendar)
			) { //webhook needs to be removed
				$this->stop_calendar_events_webhook($calendar);
				continue;
			}
		}
	}

	protected function renew_calendar_events_webhook($calendar) {
		$calendar_old_webhook_data = clone $calendar;
		$this->add_calendar_events_webhook($calendar);
		$this->stop_calendar_events_webhook($calendar_old_webhook_data, $update_db = false);
	}

	protected function is_calendar_events_webhook_active_with_long_expiry($calendar) {

		$now_ts = time();
		$about_to_expiry_threshold_ts = $now_ts + $this->webhook_about_expiry_sec;

		if ($calendar->events_webhook_expiry_ts > $about_to_expiry_threshold_ts) {
			return true;
		}
		return false;
	}

	protected function is_calendar_events_webhook_expired_or_about_to_expiry($calendar) {

		$now_ts = time();
		$about_to_expiry_threshold_ts = $now_ts + $this->webhook_about_expiry_sec;

		if ($calendar->events_webhook_expiry_ts < $about_to_expiry_threshold_ts) {
			return true;
		}
		return false;
	}

	protected function is_calendar_events_webhook_required($calendar) {
		if ($calendar->is_conflict_calendar == '1' && $calendar->events_webhook_not_supported != '1') {
			return true;
		}
		return false;
	}

	protected function is_calendar_events_webhook_added($calendar) {
		if (!empty($calendar->events_webhook_resource_id)) {
			return true;
		}
		return false;
	}

	protected function remove_calendar_account_and_its_data() {
		global $wpdb;

		$table_calendar_accounts = $wpdb->prefix . 'wpcal_calendar_accounts';
		$table_calendars = $wpdb->prefix . 'wpcal_calendars';
		$table_calendar_events = $wpdb->prefix . 'wpcal_calendar_events';

		$cal_account_id = $this->cal_account_id;

		if (empty($cal_account_id)) {
			return;
		}

		$query1 = "SELECT `id` FROM `$table_calendars` WHERE `calendar_account_id` = %s";
		$query1 = $wpdb->prepare($query1, $cal_account_id);
		$calendar_ids = $wpdb->get_col($query1);

		if (!empty($calendar_ids)) {
			$query2 = "DELETE FROM `$table_calendar_events` WHERE `calendar_id` IN(" . implode(', ', $calendar_ids) . ")";
			$delete_calendar_events = $wpdb->query($query2);
			if ($delete_calendar_events === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			}

			$query3 = "DELETE FROM `$table_calendars` WHERE `id` IN(" . implode(', ', $calendar_ids) . ")";
			$delete_calendars = $wpdb->query($query3);
			if ($delete_calendars === false) {
				throw new WPCal_Exception('db_error', '', $wpdb->last_error);
			}
		}

		$delete_calendar_account = $wpdb->delete($table_calendar_accounts, ['id' => $cal_account_id]);
		if ($delete_calendar_account === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}

		wpcal_service_availability_slots_mark_refresh_cache_by_admin($this->cal_account_details->admin_user_id);

		return true;
	}

}
