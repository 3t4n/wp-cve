<?php
if (!defined('ABSPATH')) {exit;}

include_once WPCAL_PATH . '/lib/gotomeeting/GoToMeeting.php';
include_once WPCAL_PATH . '/includes/tp/abstract_tp.php';
include_once WPCAL_PATH . '/includes/tp/abstract_tp_meeting.php';

use WPCal\ComposerPackages\League\OAuth2\Client\Provider\GoToMeeting;

class WPCal_TP_GoToMeeting_Meeting extends WPCal_Abstract_TP_Meeting {
	private $api = null;
	protected $provider = 'gotomeeting_meeting';
	protected $provider_type = 'meeting';
	protected $tp_account_id;
	protected $api_token = '';
	protected $tp_account_details;

	private $api_client_keys = [
		'clientId' => 'hO3Wli59SATofJCKGPzKXtml8HuMrhZV',
		'clientSecret' => 'QAtqrdSwGCPiPnkw',
		'redirectUri' => '',
	];

	public function __construct($tp_account_id) {

		$this->api_client_keys['redirectUri'] = WPCAL_GOTOMEETING_OAUTH_REDIRECT_SITE_URL . 'cal-api-receive-it/';

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
		$client = new GoToMeeting($this->api_client_keys);
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

		try {
			$access_token = $client->fetchAccessTokenWithRefreshToken();
		} catch (Exception $e) {
			$message = trim($e->getMessage());
			$code = trim($e->getCode());
			if ($code == 400 && $message === 'Bad Request') {
				// for goto API if fetchAccessTokenWithRefreshToken call, gives 400 and Bad Request then it means, invalid_grant. if refresh token is revoked then invalid_request error code comes, if app grant is removed from GoTo then invalid_token error comes. We don't know if any other case comes. So lets use this 'Bad Request' as of now.
				throw new Exception('get_refresh_token_bad_request', 400, $e);
			}
			throw $e;
		}

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

		$site_redirect_url = trailingslashit(admin_url()) . 'admin.php?page=wpcal_admin&wpcal_action=tp_account_receive_token&provider=gotomeeting_meeting';
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

		$meeting_type = 'scheduled';
		$_start_time = $booking_obj->get_booking_from_time();
		$start_time = WPCal_DateTime_Helper::DateTime_Obj_to_UTC_and_ISO_Z($_start_time);

		$_end_time = $booking_obj->get_booking_to_time();
		$end_time = WPCal_DateTime_Helper::DateTime_Obj_to_UTC_and_ISO_Z($_end_time);

		$meeting_data = [
			'subject' => $subject,
			'starttime' => $start_time,
			'endtime' => $end_time,
			'passwordrequired' => false,
			'conferencecallinfo' => 'VoIP',
			'timezonekey' => '', //DEPRECATED
			'meetingtype' => $meeting_type,
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
		if (isset($response_meeting_data[0])) {
			$response_meeting_data = $response_meeting_data[0];
		}

		$tp_resource_data = [
			'for_type' => 'booking',
			'for_id' => $booking_obj->get_id(),
			'type' => 'meeting',
			'status' => 'active',
			'provider' => $this->provider,
			'tp_account_id' => $this->tp_account_id,
			//'tp_user_id' => $this->tp_account_details['tp_user_id'],
			'tp_account_email' => $this->tp_account_details->tp_account_email,
			'tp_id' => $response_meeting_data['meetingid'],
			'tp_data' => $response_meeting_data,
		];
		$meeting_tp_resource_id = WPCal_TP_Resource::create_resource($tp_resource_data);
		$location_type = $this->provider;
		$location_data = [
			'location' => $response_meeting_data['joinURL'],
			'display_meeting_id' => $response_meeting_data['meetingid'], //here in Get meetingid "id" i is small
			// 'password_data' => [
			// 	'label' => 'Password',
			// 	'password' => $response_meeting_data['password']
			// ]
		];

		wpcal_booking_update_online_meeting_details($booking_obj, $location_type, $location_data, $meeting_tp_resource_id);

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
		if (isset($response_meeting_data[0])) {
			$response_meeting_data = $response_meeting_data[0];
		}

		//joinURL not coming in getMeeting, only coming in createMeeting
		$old_tp_data = $tp_resource_obj->get_tp_data();
		$response_meeting_data['joinURL'] = $old_tp_data['joinURL']; //this is safe as per current API of get meeting and create meeting

		$tp_resource_data = [
			'status' => 'active',
			'tp_data' => $response_meeting_data,
		];
		WPCal_TP_Resource::update_resource($tp_resource_data, $meeting_tp_resource_id);
		$location_type = $this->provider;
		$location_form_data = [
			'location' => $response_meeting_data['joinURL'],
			'display_meeting_id' => $response_meeting_data['meetingId'], //here in Get meetingId "Id" I is caps
			// 'password_data' => [
			// 	'label' => 'Password',
			// 	'password' => $response_meeting_data['password']
			// ]
		];

		wpcal_booking_update_online_meeting_details($booking_obj, $location_type, $location_form_data, $meeting_tp_resource_id); //mostly this won't be required, but for a safety
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
			$client = $this->get_api_base_client_with_token();
			try {
				$is_revoked = $client->revokeToken(); // access token
				// revoking refresh token, still allows access token to work till expires
				$is_revoked = $client->revokeToken('refresh'); // refresh token
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

			if (empty($this->tp_account_details->tp_user_id)) { // before v0.9.5.4 tp_user_id not saved for GoToMeeting, hence updating via this. This doesn't update for all users.
				$update_data = ['tp_user_id' => $user->getId()];
				$update_result = $this->update_account_details($update_data);
			}

			return true;
		} catch (Exception $e) {

			$message = trim($e->getMessage());
			$code = trim($e->getCode());

			if (
				$code == 401 && ($message === 'Unauthorized')
				||
				$code == 400 && ($message === 'Bad Request' || $message === 'get_refresh_token_bad_request')
			) {
				try {
					//let try getting access token
					$this->get_access_token_with_refresh_token_and_set_and_save();
					return true;
				} catch (Exception $e2) {

					$message = $e2->getMessage();
					$code = $e2->getCode();

					if (
						$code == 401 && ($message === 'Unauthorized')
						||
						$code == 400 && $message === 'get_refresh_token_bad_request'
					) {
						/// for goto API if fetchAccessTokenWithRefreshToken call, gives 400 and Bad Request then it means, invalid_grant
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
			$code == 401 && ($message === 'Unauthorized')
			||
			$code == 400 && $message === 'Bad Request'
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
