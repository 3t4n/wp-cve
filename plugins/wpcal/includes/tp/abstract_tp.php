<?php
if (!defined('ABSPATH')) {exit;}

abstract class WPCal_Abstract_TP {

	private $allowed_provider_details = [
		'zoom_meeting' => ['provider' => 'zoom_meeting', 'provider_type' => 'meeting'],
		'gotomeeting_meeting' => ['provider' => 'gotomeeting_meeting', 'provider_type' => 'meeting'],
	];

	private $allowed_tp_account_fields = [
		'tp_user_id',
		'tp_account_email',
		'api_token',
		'last_token_fetched_ts',
		'status',
	];

	public function get_provider() {
		return $this->provider;
	}

	public function get_provider_type() {
		return $this->provider_type;
	}

	public function get_tp_account_id() {
		return $this->tp_account_id;
	}

	protected function is_valid_provider_details() {

		$current_provider = $this->get_provider();

		if (isset($this->allowed_provider_details[$current_provider])) {
			return true;
		}
		throw new WPCal_Exception('invalid_tp_provider_details');
	}

	public function is_status_ok() {
		if (empty($this->tp_account_details)) {
			$this->load_account_details();
		}
		if (property_exists($this->tp_account_details, 'status') && $this->tp_account_details->status == 1) {
			return true;
		}
		return false;
	}

	protected function add_or_update_account($details, $only_update = false) {
		global $wpdb;

		if (!is_array($details)) {
			return false;
		}

		$this->is_valid_provider_details();

		$data = wpcal_get_allowed_fields($details, $this->allowed_tp_account_fields);
		$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';
		$current_admin_user_id = get_current_user_id();

		$tp_account_id = $this->tp_account_id;
		if (!$tp_account_id) {
			//check if exists then update
			$query = "SELECT `id` FROM `$table_tp_accounts` WHERE `provider` = %s AND `provider_type` = %s AND `admin_user_id` = %s";
			$query = $wpdb->prepare($query, $this->get_provider(), $this->get_provider_type(), $current_admin_user_id);

			$query_where_addl = $wpdb->prepare(" AND `tp_account_email` = %s", $data['tp_account_email']);
			if (!empty($data['tp_user_id'])) { //this will help if email is different and same user id OR user id is different same email.
				$query_where_addl = $wpdb->prepare(" AND (`tp_user_id` = %s OR `tp_account_email` = %s) ", $data['tp_user_id'], $data['tp_account_email']);
			}
			$query .= $query_where_addl;
			$tp_account_id = $wpdb->get_var($query);
		}

		if (!empty($tp_account_id)) {
			$this->tp_account_id = $tp_account_id;
			$update_data = ['api_token' => $data['api_token']];
			if (isset($data['last_token_fetched_ts'])) {
				$update_data['last_token_fetched_ts'] = $data['last_token_fetched_ts'];
			}
			if (!empty($details['_auth_done'])) { //just now authorized - here reauthorized
				$update_data['status'] = '1';
			}
			$this->update_account_details($update_data);
			return $tp_account_id;
		}

		if ($only_update) { // should have updated using update_account_details(); Need to improve code, mostly used in reauth
			if (!empty($data['api_token'])) {
				echo 'You cannot add a new account using reconnect link. <a href="admin.php?page=wpcal_admin#/settings/integrations">Click here</a>.';
				exit;
			}
			return false;
		}

		isset($data['api_token']) ? $data['api_token'] = wpcal_encode_token($data['api_token']) : '';

		//add it here
		$data['provider'] = $this->get_provider();
		$data['provider_type'] = $this->get_provider_type();
		$data['status'] = '1';
		$data['admin_user_id'] = $current_admin_user_id;

		$data['added_ts'] = $data['updated_ts'] = time();

		$result = $wpdb->insert($table_tp_accounts, $data);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		$tp_account_id = $wpdb->insert_id;
		return $tp_account_id;
	}

	protected function update_account_details($details) {
		global $wpdb;

		$data = wpcal_get_allowed_fields($details, $this->allowed_tp_account_fields);
		isset($data['api_token']) ? $data['api_token'] = wpcal_encode_token($data['api_token']) : '';

		$table = $wpdb->prefix . 'wpcal_tp_accounts';
		$result = $wpdb->update($table, $data, ['id' => $this->get_tp_account_id()]);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		$this->load_account_details();
		return $result;
	}

	protected function update_last_token_fetch_attempt_ts($old_last_token_fetch_attempt_ts) {
		global $wpdb;

		// last_token_fetch_attempt_ts will be updated only during periodic fetch token process only.

		$table = $wpdb->prefix . 'wpcal_tp_accounts';
		$data = ['last_token_fetch_attempt_ts' => time()];
		$where = ['id' => $this->get_tp_account_id(), 'last_token_fetch_attempt_ts' => $old_last_token_fetch_attempt_ts];

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
		if ($this->tp_account_details->last_token_fetch_attempt_ts > $threshold) {
			//called too early
			return false;
		}

		$is_updated_this_instance = $this->update_last_token_fetch_attempt_ts($this->tp_account_details->last_token_fetch_attempt_ts); // update fetch attempt before making the call.
		if (!$is_updated_this_instance) {
			//data already changed possibly by another instance
			return false;
		}

		$this->do_periodic_fetch_and_save_refresh_token();
	}

	protected function load_account_details() {
		global $wpdb;

		$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';
		$query = "SELECT * FROM `$table_tp_accounts` WHERE id = %s AND `provider` = %s AND `provider_type` = %s";
		$query = $wpdb->prepare($query, $this->get_tp_account_id(), $this->get_provider(), $this->get_provider_type());
		$result = $wpdb->get_row($query);
		if (empty($result)) {
			throw new WPCal_Exception('tp_account_id_not_exists');
		}

		$this->api_token = wpcal_decode_token($result->api_token);
		unset($result->api_token);
		$this->tp_account_details = $result;
	}

	public function get_account_details() {
		if (empty($this->tp_account_details)) {
			$this->load_account_details();
		}
		return $this->tp_account_details;
	}

	protected function remove_tp_account_and_its_data() {
		global $wpdb;

		$table_tp_accounts = $wpdb->prefix . 'wpcal_tp_accounts';
		$tp_account_id = $this->get_tp_account_id();

		if (empty($tp_account_id)) {
			return;
		}

		$delete_tp_account = $wpdb->delete($table_tp_accounts, ['id' => $tp_account_id]);
		if ($delete_tp_account === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return true;
	}

}
