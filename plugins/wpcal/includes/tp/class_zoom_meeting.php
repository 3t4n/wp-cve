<?php
if (!defined('ABSPATH')) {exit;}

include_once WPCAL_PATH . '/lib/zoom/Zoom.php';
include_once WPCAL_PATH . '/includes/tp/abstract_tp.php';
include_once WPCAL_PATH . '/includes/tp/abstract_tp_meeting.php';

use WPCal\ComposerPackages\League\OAuth2\Client\Provider\Zoom;

class WPCal_TP_Zoom_Meeting extends WPCal_Abstract_TP_Meeting {
	private $api = null;
	protected $provider = 'zoom_meeting';
	protected $provider_type = 'meeting';
	protected $tp_account_id;
	protected $api_token = '';
	protected $tp_account_details;

	private $api_client_keys = [
		'clientId' => 'wUhJIYpcQ0aT7eqUiH4I9Q',
		'clientSecret' => 'S3EKz1JRYk02G1NNxzJGx4g7DJ91qIN6',
		'redirectUri' => '',
	];

	public function __construct($tp_account_id) {

		$this->api_client_keys['redirectUri'] = WPCAL_ZOOM_OAUTH_REDIRECT_SITE_URL . 'cal-api-receive-it/';

		$this->tp_account_id = $tp_account_id;

		if ($this->tp_account_id > 0) {
			$this->load_account_details();
		}
	}

	private function set_api() {
		$client = $this->get_api_base_client_with_token();

		if ($client->isAccessTokenExpired()) {
			$this->get_access_token_with_refresh_token_and_set_and_save();
			$client = $this->get_api_base_client_with_token(); //to set new access token to client
		}
		$this->api = $client;
	}

	private function get_api_base_client() {
		$client = new Zoom($this->api_client_keys);
		return $client;
	}

	private function get_api_base_client_with_token() {
		$client = $this->get_api_base_client();
		$api_token = json_decode($this->api_token, true);
		$client->setAPIToken($api_token);
		return $client;
	}

	private function get_access_token_with_refresh_token_and_set_and_save() {
		$client = $this->get_api_base_client_with_token();

		$access_token = $client->fetchAccessTokenWithRefreshToken();

		$access_token_array = $access_token->jsonSerialize();
		$this->api_token = json_encode($access_token_array);

		$this->update_account_details(['api_token' => $this->api_token, 'last_token_fetched_ts' => time()]);
	}

	protected function do_periodic_fetch_and_save_refresh_token() {
		try {
			$this->get_access_token_with_refresh_token_and_set_and_save();
		} catch (Exception $e) {
			$this->handle_api_exceptions($e);
			throw $e;
		}
	}

	public function get_add_account_url($action = 'add') {
		$client = $this->get_api_base_client();

		$site_redirect_url = trailingslashit(admin_url()) . 'admin.php?page=wpcal_admin&wpcal_action=tp_account_receive_token&provider=zoom_meeting';
		if ($action == 'reauth') {
			$site_redirect_url .= '&wpcal_reauth=1';
		}

		$state_array = ['site_redirect_url' => $site_redirect_url, 'state_token' => 'khkhkdsyf89545jhkfrjkjfkjfg'];
		$state = wpcal_base64_url_encode(json_encode($state_array));

		$url = $client->getAuthorizationUrl(['state' => $state]);
		return $url;
	}

	public function add_account_after_auth($action = 'add') {
		if (!isset($_GET['code']) || empty($_GET['code'])) {
			return false;
		}
		$client = $this->get_api_base_client();
		$auth_code = trim($_GET['code']);

		$access_token = $client->getAccessToken('authorization_code', [
			'code' => $auth_code,
		]);

		$access_token_array = $access_token->jsonSerialize();

		$this->api_token = json_encode($access_token_array);
		$this->set_api();

		$user = $this->api->getResourceOwnerDetails();

		$tp_account_details = [
			'api_token' => $this->api_token,
			'last_token_fetched_ts' => time(),
			'tp_user_id' => $user->getId(),
			'tp_account_email' => $user->getEmail(),
			'_auth_done' => true,
		];
		$only_update = $action == 'reauth' ? true : false;
		return $this->add_or_update_account($tp_account_details, $only_update);
	}

	public function __print_resource_owner_details() {
		$this->set_api();
		$user = $this->api->getResourceOwnerDetails();
	}

	private function _prepare_booking_meeting_data_for_api(WPCal_Booking $booking_obj) {

		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());
		$invitee_name = $booking_obj->get_invitee_name();

		if (!empty($admin_details['display_name']) && !empty($invitee_name)) {
			$subject = $admin_details['display_name'] . ' and ' . $invitee_name;
		} else {
			$subject = $booking_obj->service_obj->get_name();
		}

		$duration = $booking_obj->get_duration();
		$type = 2; //2- schedule meeting
		$_start_time = $booking_obj->get_booking_from_time();
		$start_time = WPCal_DateTime_Helper::DateTime_Obj_to_UTC_and_ISO_Z($_start_time);

		$meeting_data = [
			'topic' => $subject,
			'type' => $type,
			'duration' => $duration,
			'start_time' => $start_time,
		];

		return $meeting_data;
	}

	public function create_meeting(WPCal_Booking $booking_obj) {
		try {
			$this->set_api();

			$meeting_data = $this->_prepare_booking_meeting_data_for_api($booking_obj);

			$response_meeting_data = $this->api->createMeeting($meeting_data);
		} catch (Exception $e) {
			$this->handle_api_exceptions($e);
			throw $e;
		}

		$tp_resource_data = [
			'for_type' => 'booking',
			'for_id' => $booking_obj->get_id(),
			'type' => 'meeting',
			'status' => 'active',
			'provider' => $this->provider,
			'tp_account_id' => $this->tp_account_id,
			'tp_user_id' => $this->tp_account_details->tp_user_id,
			'tp_account_email' => $this->tp_account_details->tp_account_email,
			'tp_id' => $response_meeting_data['id'],
			'tp_data' => $response_meeting_data,
		];

		$display_meeting_id = (isset($response_meeting_data['pmi']) && !empty($response_meeting_data['pmi'])) ? $response_meeting_data['pmi'] : $response_meeting_data['id']; //when Zoom setting is set to use Personal meeting ID for schedule meetings, to get usable meeting ID i.e PMI. Unique meeting ID is not working in this case.

		$meeting_tp_resource_id = WPCal_TP_Resource::create_resource($tp_resource_data);
		$location_type = $this->provider;
		$location_form_data = [
			'location' => $response_meeting_data['join_url'],
			'password_data' => [
				'label' => 'Passcode',
				'password' => $response_meeting_data['password'],
			],
			'display_meeting_id' => $display_meeting_id,
		];

		wpcal_booking_update_online_meeting_details($booking_obj, $location_type, $location_form_data, $meeting_tp_resource_id);

	}

	public function update_meeting(WPCal_Booking $booking_obj) {
		try {
			$this->set_api();

			$meeting_tp_resource_id = $booking_obj->get_meeting_tp_resource_id();
			if (empty($meeting_tp_resource_id)) {
				throw new WPCal_Exception('invalid_meeting_tp_resource_id');
			}
			$tp_resource_obj = new WPCal_TP_Resource($meeting_tp_resource_id);

			$meeting_id = $tp_resource_obj->get_tp_id();
			if (empty($meeting_id)) {
				throw new WPCal_Exception('invalid_tp_meeting_id');
			}

			$meeting_data = $this->_prepare_booking_meeting_data_for_api($booking_obj);

			$result = $this->api->updateMeeting($meeting_data, $meeting_id);
			$response_meeting_data = $this->api->getMeeting($meeting_id);
		} catch (Exception $e) {
			$this->handle_api_exceptions($e);
			throw $e;
		}
		$tp_resource_data = [
			'status' => 'active',
			'tp_data' => $response_meeting_data,
		];

		$display_meeting_id = (isset($response_meeting_data['pmi']) && !empty($response_meeting_data['pmi'])) ? $response_meeting_data['pmi'] : $response_meeting_data['id']; //when Zoom setting is set to use Personal meeting ID for schedule meetings, to get usable meeting ID i.e PMI. Unique meeting ID is not working in this case.

		WPCal_TP_Resource::update_resource($tp_resource_data, $meeting_tp_resource_id);
		$location_type = $this->provider;
		$location = [
			'location' => $response_meeting_data['join_url'],
			'password_data' => [
				'label' => 'Passcode',
				'password' => $response_meeting_data['password'],
			],
			'display_meeting_id' => $display_meeting_id,
		];

		wpcal_booking_update_online_meeting_details($booking_obj, $location_type, $location, $meeting_tp_resource_id); //mostly this won't be required, but for a safety
	}

	public function delete_meeting(WPCal_Booking $booking_obj) {
		try {
			$this->set_api();

			$meeting_tp_resource_id = $booking_obj->get_meeting_tp_resource_id();
			if (empty($meeting_tp_resource_id)) {
				throw new WPCal_Exception('invalid_meeting_tp_resource_id');
			}
			$tp_resource_obj = new WPCal_TP_Resource($meeting_tp_resource_id);

			$meeting_id = $tp_resource_obj->get_tp_id();
			if (empty($meeting_id)) {
				throw new WPCal_Exception('invalid_tp_meeting_id');
			}

			$result = $this->api->deleteMeeting($meeting_id);
		} catch (Exception $e) {
			$this->handle_api_exceptions($e);
			throw $e;
		}
		$tp_resource_data = [
			'status' => 'deleted',
		];
		WPCal_TP_Resource::update_resource($tp_resource_data, $meeting_tp_resource_id);
	}

	public function revoke_access_and_delete_its_data($force = false, $revoke = true) {
		$is_revoked = false;

		if ($revoke) {
			try {
				$this->set_api(); //to get latest access code if expired

				$client = $this->get_api_base_client_with_token();
				$is_revoked = $client->revokeToken();
			} catch (Exception $e) {
				if (!$force) {
					throw $e;
				}
				$is_revoked = false;
			}
		}

		if ($is_revoked || $force) {
			$this->remove_tp_account_and_its_data();
			return true;
		}
		return false;
	}

	public function check_auth_if_fails_remove_account() {
		$result = $this->check_auth_ok();
		if ($result === true) {
			return true;
		} elseif ($result === 'invalid_grant') {
			$this->remove_tp_account_and_its_data();
			throw new WPCal_Exception('zoom_disconnected_account_auto_removed', 'Your Zoom account (' . $this->tp_account_details->tp_account_email . ') has been disconnected. This may be because you removed WPCal.io from the Zoom website.');
		}
		return $result; //shouldn't be the case
	}

	public function check_auth_and_process() {
		$result = $this->check_auth_ok();
		if ($result === 'invalid_grant') {
			$this->process_invalid_grant($result);
		}
		return $result;
	}

	private function check_auth_ok() {
		try {
			$this->set_api();

			$user = $this->api->getResourceOwnerDetails();
			// var_dump($user);

			return true;
		} catch (Exception $e) {
			// var_dump($e->getMessage());
			// var_dump($e->getCode());
			$message = trim($e->getMessage());
			$code = trim($e->getCode());

			//Check comments in /lib/zoom/Zoom.php checkResponse()
			if (
				$code == 401 && ($message === 'Unauthorized' || $message === 'Invalid access token.')
				||
				$code == 400 && $message === 'invalid_grant'
			) {
				try {
					//let try getting access token
					$this->get_access_token_with_refresh_token_and_set_and_save();
					return true;
				} catch (Exception $e2) {
					// var_dump($e2->getMessage());
					// var_dump($e2->getCode());
					$message = $e2->getMessage();
					$code = $e2->getCode();

					//Check comments in /lib/zoom/Zoom.php checkResponse()
					if (
						$code == 401 && ($message === 'Unauthorized' || $message === 'Invalid access token.')
						||
						$code == 400 && $message === 'invalid_grant'
					) {
						return 'invalid_grant';
					}
				}
			}
		}
		return false;
	}

	/**
	 * handle_api_exceptions() handles auth issues
	 */
	public function handle_api_exceptions(\Exception $e) {
		$code = $e->getCode();
		$message = $e->getMessage();

		if (!$this->tp_account_id) {
			throw $e;
		}

		if (
			$code == 401 && ($message === 'Unauthorized' || $message === 'Invalid access token.')
			||
			$code == 400 && $message === 'invalid_grant'
		) {
			$check_auth_result = $this->check_auth_ok();
			if ($check_auth_result === 'invalid_grant') {
				$this->process_invalid_grant($check_auth_result);
				return true;
			}
			throw $e;
		}
		throw $e;
	}

	private function process_invalid_grant($auth_result) {
		if ($auth_result != 'invalid_grant') {
			return false;
		}

		//reauth required - mark it in DB
		$this->load_account_details(); //this will get latest status, avoid multiple triggers
		if ($this->tp_account_details->status == -5) {
			//already marked in DB, notice and alert email also sent
			return true;
		}

		$update_data = ['status' => '-5'];
		$update_result = $this->update_account_details($update_data);
		if ($update_result) { //$update_result will have affected rows - to ensure the following triggers once per event
			WPCal_Mail::send_admin_api_error_need_action(null, $this);

			$admin_user_id = $this->tp_account_details->admin_user_id;
			wpcal_tp_accounts_required_reauth_add_notice($admin_user_id);
		}

		return true;
	}
}
