<?php
if (!defined('ABSPATH')) {exit;}

include_once WPCAL_PATH . '/includes/tp_calendars/abstract_tp_calendar.php';
include_once WPCAL_PATH . '/lib/google-api-php-client/vendor/autoload.php';

use WPCal\GoogleAPI\Google_Client;
use WPCal\GoogleAPI\Google_Service_Calendar;
use WPCal\GoogleAPI\Google_Service_Calendar_Event;
use WPCal\GoogleAPI\Google_Service_Exception;

class WPCal_TP_Google_Calendar extends WPCal_Abstract_TP_Calendar {

	private $api = null;
	protected $cal_account_id;
	protected $cal_account_details;
	protected $cal_account_details_edit_allowed_keys = ['status', 'api_token', 'list_calendars_sync_token', 'list_calendars_sync_last_update_ts', 'last_token_fetched_ts'];
	protected $api_token = '';

	private $provider = 'google_calendar';

	private $api_client_keys = '{"web":{"client_id": "828579377188-ctuoaec27lnbf8schfgfldca9fp3ch3t.apps.googleusercontent.com","project_id": "wpcal-plugin-user-sync","auth_uri": "https://accounts.google.com/o/oauth2/auth","token_uri": "https://oauth2.googleapis.com/token","auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs","client_secret": "eyT4GKrC6WEXqGx8HXg1Gito"}}';

	private $api_send_updates = 'all';

	protected $webhook_about_expiry_sec = (86400 * 10);

	public function __construct($cal_account_id) {
		$this->cal_account_id = $cal_account_id;

		if ($this->cal_account_id > 0) {
			$this->load_account_details();
		}
	}

	public function get_provider() {
		return $this->provider;
	}

	public function get_cal_account_id() {
		return $this->cal_account_id;
	}

	public function get_cal_account_details() {
		$details = $this->cal_account_details;
		unset($details->{'api_token'});
		return $details;
	}

	public function get_cal_account_details_edit_allowed_keys() {
		return $this->cal_account_details_edit_allowed_keys;
	}

	public function set_api() {
		if ($this->api === null) {
			$client = $this->get_api_client();
			$service = new Google_Service_Calendar($client);
			$this->api = $service;
		}
	}

	public function may_api_refresh_calendars($attempt = 0) {
		if ($this->cal_account_details->list_calendars_sync_last_update_ts > (time() - (12 * 60 * 60))) {
			//task last ran less than given time
			return false;
		}
		$this->api_refresh_calendars();
	}

	public function api_refresh_calendars($attempt = 0) {
		if ($attempt > 1) {
			return false;
		}

		$this->set_api();
		$calendar_list = $this->api->calendarList;
		$opt_params = array();
		$inital_sync_token = $this->cal_account_details->list_calendars_sync_token;
		if ($inital_sync_token) {
			$opt_params['syncToken'] = $inital_sync_token;
		}
		try {
			$list = $calendar_list->listCalendarList($opt_params);
		} catch (Google_Service_Exception $e) {
			if ($e->getCode() == 410) {
				$this->update_account_details(array('list_calendars_sync_token' => null));
				$this->load_account_details(); //update data for recursive call
				$attempt++;
				return $this->api_refresh_calendars($attempt);
			}
			throw $e;
		}

		while (true) {
			//var_dump($list);
			foreach ($list->getItems() as $list_item) {
				$this->add_or_update_calendar($list_item);
			}
			$page_token = $list->getNextPageToken();
			if ($page_token) {
				$opt_params = array('pageToken' => $page_token);
				$list = $calendar_list->listCalendarList($opt_params);
			} else {
				$next_sync_token = $list->getNextSyncToken();
				if ($inital_sync_token != $next_sync_token) {
					$this->update_account_details(array('list_calendars_sync_token' => $next_sync_token, 'list_calendars_sync_last_update_ts' => time()));
				}
				break;
			}
		}
	}

	private function add_or_update_calendar($cal_item) {
		if ($cal_item->getDeleted()) {
			return true;
		}
		global $wpdb;
		$cal_data = [];
		$cal_data['calendar_account_id'] = $this->cal_account_id;
		$cal_data['name'] = $cal_item->getSummary();
		$cal_data['tp_cal_id'] = $cal_item->getId();
		$cal_data['is_readable'] = (int) $this->is_readable($cal_item->getAccessRole());
		$cal_data['is_writable'] = (int) $this->is_writable($cal_item->getAccessRole());
		$cal_data['is_primary'] = (int) ($cal_item->getPrimary() === true);
		$cal_data['timezone'] = $cal_item->getTimeZone();

		$result = $this->do_add_or_update_calendar($cal_data);
		return $result;
	}

	private function is_readable($access_role) {
		if (in_array($access_role, array('owner', 'writer', 'reader'), true)) {
			return true;
		}
		return false;
	}

	private function is_writable($access_role) {
		if (in_array($access_role, array('owner', 'writer'), true)) {
			return true;
		}
		return false;
	}

	public function refresh_events_for_all_conflict_calendars() {
		$this->may_refresh_events_for_all_conflict_calendars($check_and_do = false);
	}

	public function may_refresh_events_for_all_conflict_calendars($check_and_do = true) {
		$conflict_calendars = $this->get_all_conflict_calendars();
		//get_min_max_dates_based_on_services();
		$min_date = WPCal_DateTime_Helper::DateTime_DB_to_DateTime_obj('now', new DateTimeZone('UTC'));
		$max_date = clone $min_date;
		$max_date->add(new DateInterval('P11Y'));
		foreach ($conflict_calendars as $calendar) {
			if ($check_and_do) {
				$this->may_api_refresh_calendar_events($calendar, $min_date, $max_date);
			} else {
				$this->api_refresh_calendar_events($calendar, $min_date, $max_date);
			}
		}
	}

	private function may_api_refresh_calendar_events($calendar, $min_date, $max_date) {

		if ($calendar->list_events_sync_last_update_ts > (time() - (5 * 60))) {
			//task last ran less than given time
			return false;
		}
		$this->api_refresh_calendar_events($calendar, $min_date, $max_date);
	}

	private function api_refresh_calendar_events($calendar, $min_date, $max_date) {
		if (!($calendar->list_events_sync_status === 'completed' || $calendar->list_events_sync_status == null)) {
			//task running else where
			return false;
		}
		$status_update = $this->update_calendar_sync_status($calendar->id, 'running', $calendar->list_events_sync_status);
		if ($status_update != '1') {
			//status of sync may changed by other instance
			return false;
		}
		$this->do_api_refresh_calendar_events($calendar, $min_date, $max_date);
		$status_update = $this->update_calendar_sync_status($calendar->id, 'completed', 'running');
	}

	private function do_api_refresh_calendar_events($calendar, $min_date, $max_date, $attempt = 0) {
		$cal_id = $calendar->id;
		if ($attempt > 1) {
			return false;
		}

		$this->set_api();

		$tp_cal_id = $calendar->tp_cal_id;
		$opt_params = array(
			//'orderBy' => 'startTime',
			'singleEvents' => true,
			//'showDeleted' => true,
			'timeMin' => $min_date->format('c'), //won't work with sync token
			'timeMax' => $max_date->format('c'), //won't work with sync token
			'timeZone' => 'UTC',
		);

		$inital_sync_token = $calendar->list_events_sync_token;
		$do_fresh_sync = $calendar->do_fresh_sync;
		if (!empty($do_fresh_sync)) {
			/**
			 * It's always better to make sure we have authorised connection
			 * with API before deleting the events. So that for time being
			 * these exisiting events will be used for conflict resolution.
			 * Instead of not having anything when we delete the events first.
			 */
			$inital_sync_token = null;

			//checking still have valid connection
			$_calendar_list = $this->api->calendarList;
			$_primary_calendar_details = $_calendar_list->get('primary');
			$_primary_id = $_primary_calendar_details->getId();
			if (empty($_primary_id)) {
				throw new WPCal_Exception('auth_failed');
			}

			//if all goes well - continue with full sync.
			wpcal_delete_all_calendar_events_recursively_in_blocks($cal_id);
		}

		if ($inital_sync_token) {
			// the following params won't work if request have sync token iCalUID, orderBy, privateExtendedProperty, q, sharedExtendedProperty, timeMin, timeMax, updatedMin
			//Another thing to note is even inital request have timeMin and timeMax, events coming via sync token will be any new events and change in old events without any time limitations etc
			$opt_params = [
				'singleEvents' => true,
				'syncToken' => $inital_sync_token,
				'timeZone' => 'UTC',
			];
		}

		try {
			$events = $this->api->events->listEvents($tp_cal_id, $opt_params);
		} catch (Google_Service_Exception $e) {
			if ($e->getCode() == 410) {
				$calendar->list_events_sync_token = null; //update data for recursive call
				$this->do_add_or_update_calendar(array('list_events_sync_token' => null, 'tp_cal_id' => $tp_cal_id));
				/*
				Whenever events sync token goes out of sync, it means sync breaks - the old calendar events data will have future dated events, which might not present in new sync. So its better to delete all the events before the new sync process starts.
				 */
				wpcal_delete_all_calendar_events_recursively_in_blocks($cal_id);
				$attempt++;
				return $this->do_api_refresh_calendar_events($calendar, $min_date, $max_date, $attempt);
			}
			throw $e;
		}

		while (true) {
			//var_dump($events);
			foreach ($events->getItems() as $event) {
				$this->handle_calendar_event($cal_id, $tp_cal_id, $event);
			}
			$page_token = $events->getNextPageToken();
			if ($page_token) {
				//$opt_params = array('pageToken' => $page_token);
				$opt_params['pageToken'] = $page_token;
				echo '<br>=============================================><br>';
				$events = $this->api->events->listEvents($tp_cal_id, $opt_params);
			} else {
				$next_sync_token = $events->getNextSyncToken();
				//var_dump(' $next_sync_token,',  $next_sync_token);
				if ($inital_sync_token != $next_sync_token) {
					$this->do_add_or_update_calendar(array('list_events_sync_token' => $next_sync_token, 'tp_cal_id' => $tp_cal_id));
				}
				break;
			}
		}
		if (!empty($do_fresh_sync)) {
			// If its here, it means fresh sync is completed.
			$this->do_add_or_update_calendar(['do_fresh_sync' => '0', 'tp_cal_id' => $tp_cal_id]);
			// wpcal_service_availability_slots_mark_refresh_cache_by_admin($this->cal_account_details->admin_user_id); //not required taken care.
		}
	}

	protected function get_booking_id_from_tp_event_id($cal_id, $tp_cal_id, $tp_event_id) {

		$booking_id = $this->get_booking_id_from_tp_event_id_and_cal_id($cal_id, $tp_event_id);
		if (!empty($booking_id)) {
			return $booking_id;
		}

		//say if TP Calendar account is disconnected and re-connected now try to find with tp_cal_id
		$booking_id = $this->get_booking_id_from_tp_event_id_and_tp_cal_id($cal_id, $tp_cal_id, $tp_event_id);
		return $booking_id;
	}

	protected function get_booking_id_from_tp_event_id_and_cal_id($cal_id, $tp_event_id) {
		global $wpdb;

		$table_bookings = $wpdb->prefix . 'wpcal_bookings';
		$query = "SELECT `id` FROM `$table_bookings` WHERE `event_added_calendar_id` = %s AND `event_added_tp_event_id` = %s";
		$query = $wpdb->prepare($query, $cal_id, $tp_event_id);
		$result = $wpdb->get_var($query);
		if (!empty($result)) {
			return $result;
		}
		return false;
	}

	protected function get_booking_id_from_tp_event_id_and_tp_cal_id($cal_id, $tp_cal_id, $tp_event_id) {
		global $wpdb;

		$table_bookings = $wpdb->prefix . 'wpcal_bookings';
		$query = "SELECT `id`, `admin_user_id` FROM `$table_bookings` WHERE `event_added_tp_cal_id` = %s AND `event_added_tp_event_id` = %s";
		$query = $wpdb->prepare($query, $tp_cal_id, $tp_event_id);
		$result = $wpdb->get_row($query);
		if (empty($result)) {
			return false;
		}

		//verify booking's admin_id and cal_id(tp_cal_id is belongs to cal_id) admin are same, tp_cal_id should unique in the whole world atleast for google calendar, in same tp_cal_id connected via another account is also fine
		if (empty($result->admin_user_id) || empty($this->cal_account_details->admin_user_id)) {
			return false;
		}

		if ($result->admin_user_id == $this->cal_account_details->admin_user_id) {
			$booking_id = $result->id;
			return $booking_id;
		}
		return false;
	}

	private function handle_calendar_event($cal_id, $tp_cal_id, $event) {
		$status = $event->getStatus();
		$tp_event_id = $event->getId();
		$summary = $event->getSummary();
		$is_wpcal_event = '0';

		$_booking_id = $this->get_booking_id_from_tp_event_id($cal_id, $tp_cal_id, $tp_event_id);
		if ($_booking_id) {
			// The following commentted, "sync cancellation" will be bringing later
			// $attendees = $event->getAttendees();
			// if(!empty($attendees)){
			//     foreach($attendees as $attendee){
			//         if(!$attendee->getSelf() &&  $attendee->getResponseStatus() == 'declined'){
			//             var_dump('====cal_id=invitee_cancel_via_tp_cal===', $cal_id, $tp_event_id);

			//             wpcal_cancel_booking($_booking_id, $cancel_reason='invitee_cancel_via_tp_cal');
			//             return;
			//         }
			//     }
			// }
			//return;
			$is_wpcal_event = '1'; //if this event is in this particular $cal_id which is private that WPCal Admin host.
		}

		//The following are events not related to WPCal

		$tp_self_attendee_status = '_default'; // _default is WPCal's default
		//check for group event declined
		$attendees = $event->getAttendees();
		if (!empty($attendees)) {
			foreach ($attendees as $attendee) {
				if ($attendee->getSelf()) {
					$tp_self_attendee_status = $attendee->getResponseStatus();
					// if ($tp_self_attendee_status === 'declined') {
					// 	$this->delete_calendar_event($cal_id, $tp_event_id);
					// 	return;
					// }
				}
			}
		}

		$is_consider_confirmed = '1';

		if ($tp_self_attendee_status === 'declined') {
			$is_consider_confirmed = '0';
		}

		if ($status === 'confirmed' || $status === 'tentative') {
			//add or update event

			$from_time = $this->event_time_to_unix($event->start);
			$to_time = $this->event_time_to_unix($event->end);
			$tp_created = $this->_std_string_UTC_time_to_unix($event->getCreated());
			$tp_updated = $this->_std_string_UTC_time_to_unix($event->getUpdated());
			$tp_event_link = $event->getHtmlLink();
			$_transparency = $event->getTransparency(); // API 'transparent' comes, busy comes as NULL(null)
			$tp_is_busy = $_transparency === 'transparent' ? '0' : '1';

			$event_data = [
				'calendar_id' => $cal_id,
				'status' => '1',
				'tp_event_id' => $tp_event_id,
				'is_wpcal_event' => $is_wpcal_event,
				'tp_summary' => $summary,
				'from_time' => $from_time,
				'to_time' => $to_time,
				'tp_created' => $tp_created,
				'tp_updated' => $tp_updated,
				'tp_event_status' => $status,
				'tp_self_attendee_status' => $tp_self_attendee_status,
				'tp_is_busy' => $tp_is_busy,
				'is_consider_confirmed' => $is_consider_confirmed,
				'tp_event_link' => $tp_event_link,
			];

			$this->do_add_or_update_calendar_event($cal_id, $event_data);
		} elseif ($status === 'cancelled') {
			//may be delete event
			$this->delete_calendar_event($cal_id, $tp_event_id);
		}
	}

	private function event_time_to_unix($event_time) {
		$t = $event_time->dateTime;
		if (empty($t)) {
			$t = $event_time->date;
		}

		$unix_time = $this->_std_string_UTC_time_to_unix($t);
		return $unix_time;
	}

	private function _std_string_UTC_time_to_unix($std_str) {
		$time_obj = WPCal_DateTime_Helper::DateTime_DB_to_DateTime_obj($std_str, new DateTimeZone('UTC'));
		$unix_time = WPCal_DateTime_Helper::DateTime_Obj_to_unix($time_obj);
		return $unix_time;
	}

	private function _prepare_booking_event_object_for_api(WPCal_Booking $booking_obj, $add_to_cal_details) {

		$service_obj = $booking_obj->service_obj;

		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());
		$whos_view = 'neutral';

		$location_content_options = [];

		$location = $booking_obj->get_location_str();
		if (empty($location) && $booking_obj->get_location_type() === 'googlemeet_meeting') {
			$location = $booking_obj->get_redirect_meeting_url();
			$location_content_options['override_location_if_empty'] = $location;
		}

		$form_time = WPCal_DateTime_Helper::DateTime_Obj_to_ISO($booking_obj->get_booking_from_time());
		$to_time = WPCal_DateTime_Helper::DateTime_Obj_to_ISO($booking_obj->get_booking_to_time());

		$location_descr = wpcal_get_booking_location_content($booking_obj, $for = 'calendar_event', $whos_view, $this->get_provider(), $location_content_options);

		$admin_attendee = [
			'email' => $add_to_cal_details->tp_cal_id,
			'displayName' => $admin_details['display_name'],
			'responseStatus' => 'accepted',
		];

		$attendee = [
			'email' => $booking_obj->get_invitee_email(),
			'displayName' => $booking_obj->get_invitee_name(),
			'responseStatus' => 'accepted',
		];

		//$description = wpcal_get_booking_descr_for_calendar($service_obj, $booking_obj, $location_descr, $whos_view);

		$event_contents = wpcal_get_booking_contents_for_calendar($service_obj, $booking_obj, $location_descr, $admin_details, $whos_view, $options = ['for' => 'calendar_event_api']);

		$event_data = array(
			'summary' => $event_contents['summary'],
			'location' => $location,
			'description' => $event_contents['descr'],
			'start' => array(
				'dateTime' => $form_time,
			),
			'end' => array(
				'dateTime' => $to_time,
			),
			'attendees' => array(
				$admin_attendee,
				$attendee,
			),
			'conferenceData' => null,
		);

		if ($booking_obj->get_location_type() === 'googlemeet_meeting') {
			$event_data['conferenceData'] = [];
			$event_data['conferenceData']['createRequest'] = [
				'requestId' => sha1($booking_obj->get_id() . '|' . uniqid('', true)),
				//'conferenceSolutionKey' => ['type' => 'hangoutsMeet']
			];
		}

		$event = new Google_Service_Calendar_Event($event_data);

		return $event;
	}

	public function api_add_event($cal_details, WPCal_Booking $booking_obj) {
		$this->set_api();

		$event = $this->_prepare_booking_event_object_for_api($booking_obj, $cal_details);

		$send_updates_to_invitee = $booking_obj->service_obj->is_invitee_notify_by_calendar_invitation() ? 'all' : 'none';

		$response_event = $this->api->events->insert($cal_details->tp_cal_id, $event, ['sendUpdates' => $send_updates_to_invitee, 'conferenceDataVersion' => 1]); //improve code NEED try catch

		$booking_id = $booking_obj->get_id();
		$_calendar_id = $cal_details->calendar_id;
		$_tp_cal_id = $cal_details->tp_cal_id;
		$_tp_event_id = $response_event->getId();

		wpcal_booking_update_tp_calendar_event_details($booking_id, $this->provider, $_calendar_id, $_tp_cal_id, $_tp_event_id);

		$this->may_handle_conference_data_for_meeting($response_event, $cal_details, $booking_obj);
	}

	public function api_update_event($cal_details, WPCal_Booking $booking_obj) {
		$this->set_api();

		$event = $this->_prepare_booking_event_object_for_api($booking_obj, $cal_details);

		$send_updates_to_invitee = $booking_obj->service_obj->is_invitee_notify_by_calendar_invitation() ? 'all' : 'none';

		$response_event = $this->api->events->update($cal_details->tp_cal_id, $booking_obj->get_event_added_tp_event_id(), $event, ['sendUpdates' => $send_updates_to_invitee, 'conferenceDataVersion' => 1]); //improve code NEED try catch //setting 'conferenceDataVersion' => 1 here may generate new meeting url if all ready exists
		// var_dump($response_event);
		// printf('Event updated: %s\n', $response_event->htmlLink);

		$booking_id = $booking_obj->get_id();
		$_calendar_id = $cal_details->calendar_id;
		$_tp_cal_id = $cal_details->tp_cal_id;
		$_tp_event_id = $response_event->getId();

		wpcal_booking_update_tp_calendar_event_details($booking_id, $this->provider, $_calendar_id, $_tp_cal_id, $_tp_event_id);

		$this->may_handle_conference_data_for_meeting($response_event, $cal_details, $booking_obj);
	}

	public function api_update_event_location($cal_details, WPCal_Booking $booking_obj) { //purpose of this method is when google meet meeting is required, first it will send temporary url as location. Now it will be updated with original url.
		$this->set_api();

		$event = $this->_prepare_booking_event_object_for_api($booking_obj, $cal_details);

		$response_event = $this->api->events->update($cal_details->tp_cal_id, $booking_obj->get_event_added_tp_event_id(), $event, ['sendUpdates' => 'none']); //improve code NEED try catch

	}

	public function api_delete_event($cal_details, WPCal_Booking $booking_obj) {
		$this->set_api();
		$send_updates_to_invitee = $booking_obj->service_obj->is_invitee_notify_by_calendar_invitation() ? 'all' : 'none';

		$response_event = $this->api->events->delete($cal_details->tp_cal_id, $booking_obj->get_event_added_tp_event_id(), ['sendUpdates' => $send_updates_to_invitee]); //improve code NEED try catch
		//var_dump($response_event);
	}

	public function api_get_event($cal_details, WPCal_Booking $booking_obj) {
		$this->set_api();

		$response_event = $this->api->events->get($cal_details->tp_cal_id, $booking_obj->get_event_added_tp_event_id()); //improve code NEED try catch

		return $response_event;
	}

	public function get_and_set_meeting_url_from_event($cal_details, WPCal_Booking $booking_obj) {
		$response_event = $this->api_get_event($cal_details, $booking_obj);
		$this->may_handle_conference_data_for_meeting($response_event, $cal_details, $booking_obj);
	}

	public function may_handle_conference_data_for_meeting($response_event, $cal_details, WPCal_Booking $booking_obj) {

		if ($booking_obj->get_location_type() !== 'googlemeet_meeting') {
			return;
		}

		$meeting_link = '';
		$meeting_code = '';
		$conference_data = $response_event->getConferenceData();
		if ($conference_data) {
			$conference_create_request = $conference_data->getCreateRequest();
			if ($conference_create_request) {
				$conference_create_request_status = $conference_create_request->getStatus();
				$conference_create_request_status_code = $conference_create_request_status->getStatusCode();
				if ($conference_create_request_status_code === 'success') {
					$entry_points = $conference_data->getEntryPoints();
					$meeting_code = $conference_data->getConferenceId();
					foreach ($entry_points as $entry_point) {
						if ($entry_point->getEntryPointType() === 'video') {
							$meeting_link = $entry_point->getUri();
							break;
						}
					}
				}
			}
		}
		//what if status_code other than 'success' error handling need to be done. Improve Later

		if (!empty($meeting_link)) {
			$location_type = 'googlemeet_meeting';
			$location_form_data = ['location' => $meeting_link,
				'display_meeting_id' => $meeting_code];
			wpcal_booking_update_online_meeting_details($booking_obj, $location_type, $location_form_data, null);

			$booking_obj = wpcal_get_booking($booking_obj->get_id()); //reload the data from DB. Because it is updated just above.
			$this->api_update_event_location($cal_details, $booking_obj);
		}
	}

	public function manage_calendar_events_webhooks($action = 'all') {
		$feature_details = WPCal_License::get_feature_details('google_calendar_webhook');
		if (!isset($feature_details['enable']) || $feature_details['enable'] != 1) {
			return false;
		}

		parent::manage_calendar_events_webhooks($action);
	}

	public function api_add_calendar_events_webhook($tp_cal_id, $webhook_data) { //watch - webhook

		$this->set_api();

		$watch_data = [
			'id' => $webhook_data['channel_id'],
			'token' => $webhook_data['token'],
			'type' => 'web_hook',
			'address' => WPCAL_CRON_URL . 'webhooks/google-calendar',
			'params' => [
				'ttl' => (86400 * 30), //even sending more then 30 days, it still set expiration to 30 days
			],
		];
		$watch_data_obj = new \WPCal\GoogleAPI\Google_Service_Calendar_Channel($watch_data);

		$response = $this->api->events->watch($tp_cal_id, $watch_data_obj);

		return $response;
	}

	public function api_stop_calendar_events_webhook($tp_cal_id, $webhook_data) { //watch - webhook

		$this->set_api();

		$watch_data = [
			'id' => $webhook_data['channel_id'],
			'resourceId' => $webhook_data['resource_id'],
		];
		$watch_data_obj = new \WPCal\GoogleAPI\Google_Service_Calendar_Channel($watch_data);

		$response = $this->api->channels->stop($watch_data_obj);

		return $response;
	}

	protected function add_calendar_events_webhook($calendar) {

		$license_info = WPCal_License::get_site_token_and_account_email();
		if (empty($license_info['site_token'])) {
			throw new WPCal_Exception('invalid_license_info');
		}

		$unique_id_parts = [$license_info['site_token'], $calendar->tp_cal_id, $calendar->calendar_account_id, microtime(), rand(9999, 999999)];
		$unique_id = sha1(implode('|', $unique_id_parts)); //unique across WPCal

		$token_parts = [
			'app_id' => $license_info['site_token'],
			'calendar_id' => $calendar->id,
		];
		$token = http_build_query($token_parts);

		$webhook_data = [
			'channel_id' => $unique_id,
			'token' => $token,
		];

		try {
			$response = $this->api_add_calendar_events_webhook($calendar->tp_cal_id, $webhook_data);
		} catch (\WPCal\GoogleAPI\Google_Service_Exception $e) {
			$errors = $e->getErrors();
			if ($e->getCode() == 400 && isset($errors[0]['reason']) && $errors[0]['reason'] == 'pushNotSupportedForRequestedResource') {
				//ja.japanese#holiday@group.v.calendar.google.com, en.indian#holiday@group.v.calendar.google.com for these calendars webhook/watch is not allowed by Google Calendar API gives this error pushNotSupportedForRequestedResource, to avoid retry events_webhook_not_supported set to 1
				$update_data = [
					'events_webhook_channel_id' => null,
					'events_webhook_resource_id' => null,
					'events_webhook_expiry_ts' => null,
					'events_webhook_not_supported' => '1',
					'events_webhook_updated_ts' => time(),
					'tp_cal_id' => $calendar->tp_cal_id, //presense of this makes it as update
				];
				$this->do_add_or_update_calendar($update_data);
				return;
			}
			throw $e;
		}
		if ($response->getResourceId()) {
			$update_data = [
				'events_webhook_channel_id' => $response->getId(),
				'events_webhook_resource_id' => $response->getResourceId(),
				'events_webhook_expiry_ts' => $response->getExpiration() / 1000, //ms to s
				'events_webhook_updated_ts' => time(),
				'tp_cal_id' => $calendar->tp_cal_id, //presense of this makes it as update
			];
			$this->do_add_or_update_calendar($update_data);
		}
	}

	protected function stop_calendar_events_webhook($calendar, $update_db = true) {

		if (empty($calendar->events_webhook_channel_id) || empty($calendar->events_webhook_resource_id)) {
			throw new WPCal_Exception('invalid_stop_webhook_input');
		}

		$webhook_data = [
			'channel_id' => $calendar->events_webhook_channel_id,
			'resource_id' => $calendar->events_webhook_resource_id,
		];

		$response = $this->api_stop_calendar_events_webhook($calendar->tp_cal_id, $webhook_data);
		//if no throw above things are ok

		if ($update_db) {
			$update_data = [
				'events_webhook_channel_id' => null,
				'events_webhook_resource_id' => null,
				'events_webhook_expiry_ts' => null,
				'events_webhook_updated_ts' => time(),
				'tp_cal_id' => $calendar->tp_cal_id, //presense of this makes it as update
			];
			$this->do_add_or_update_calendar($update_data);
		}
	}

	public function handle_api_exceptions(\WPCal\GoogleAPI\Google_Service_Exception $e, $calendar_id = '') {
		$code = $e->getCode();
		$errors = $e->getErrors();
		$message = $e->getMessage();

		$calendar_account_id = $this->cal_account_id;

		$error = $error_description = '';

		//var_export(['0001', $code, $message, $errors, $e]);
		if (!empty($errors)) {
			$error = $errors[0]['reason'] ?? '';
		} elseif (!empty($message)) {
			$message_array = json_decode($message, true);
			if (is_array($message_array) && !empty($message_array['error'])) {
				$error = $message_array['error'];
			}
		}

		if (empty($error)) {
			throw $e;
		}

		if (in_array($error, ['authError', 'invalid_grant'], true) && !empty($calendar_account_id)) {
			//reauth required - mark it in DB
			$this->load_account_details(); //this will get latest status, avoid multiple triggers
			if ($this->cal_account_id && $this->cal_account_details->status == -5) {
				return true;
			}
			$update_data = ['status' => '-5'];
			$update_result = $this->update_account_details($update_data);
			if ($update_result) { //$update_result will have affected rows - to ensure the following triggers once per event
				WPCal_Mail::send_admin_api_error_need_action($this);

				$tp_calendar_account_details = $this->get_cal_account_details();
				$admin_user_id = $tp_calendar_account_details->admin_user_id;
				wpcal_calendars_required_reauth_add_notice($admin_user_id);
			}
			return true;
		}

		throw $e;
	}

	//====================================================================>

	private function init_api_base_client() {
		$client = new Google_Client();
		$client->setApplicationName('Google Calendar API PHP Quickstart');
		$client->setScopes(Google_Service_Calendar::CALENDAR);
		$auth_config = json_decode($this->api_client_keys, true);
		$client->setAuthConfig($auth_config);
		$client->setAccessType('offline');
		$client->setPrompt('select_account consent');
		//$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		//$redirect_uri='https://wpcal-01.com/wp-admin/admin.php?page=wpcal_admin&wpcal_action=google_calendar_receive_token';
		$redirect_uri = WPCAL_GOOGLE_OAUTH_REDIRECT_SITE_URL . 'cal-api-receive-it/';
		$client->setRedirectUri($redirect_uri);
		return $client;
	}

	private function get_api_client() {
		$client = $this->init_api_base_client();

		// // Load previously authorized token from a file, if it exists.
		// // The file token.json stores the user's access and refresh tokens, and is
		// // created automatically when the authorization flow completes for the first
		// // time.
		// $tokenPath = WPCAL_PATH . '/lib/token.json';
		// //var_dump($tokenPath, file_exists($tokenPath));
		// if (file_exists($tokenPath)) {
		//     $accessToken = json_decode(file_get_contents($tokenPath), true);
		//     $client->setAccessToken($accessToken);
		// }

		$accessToken = json_decode($this->api_token, true);
		$client->setAccessToken($accessToken);

		// If there is no previous token or it's expired.
		if ($client->isAccessTokenExpired()) {
			// Refresh the token if possible, else fetch a new one.
			if ($client->getRefreshToken()) {
				$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			} else {

				if (isset($_GET['code'])) {

					$authCode = trim($_GET['code']);

					// Exchange authorization code for an access token.
					$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
					$client->setAccessToken($accessToken);

					// Check to see if there was an error.
					if (array_key_exists('error', $accessToken)) {
						throw new Exception(join(', ', $accessToken));
					}
				} else {
					// Request authorization from the user.
					$authUrl = $client->createAuthUrl();
					echo '<h3><a  href="' . $authUrl . '">OAuth 2.0 Google here</a></h3>';

				}

				// printf("Open the following link in your browser:\n%s\n", $authUrl);
				// print 'Enter verification code: ';
				// $authCode = trim(fgets(STDIN));

			}
			// Save the token to a file.
			// if (!file_exists(dirname($tokenPath))) {
			//     mkdir(dirname($tokenPath), 0700, true);
			// }
			// file_put_contents($tokenPath, json_encode($client->getAccessToken()));
			$this->api_token = json_encode($client->getAccessToken());
			$this->update_account_details(['api_token' => $this->api_token, 'last_token_fetched_ts' => time()]);
		}
		return $client;
	}

	private function get_access_token_with_refresh_token_and_set_and_save() {
		$client = $this->init_api_base_client();

		$accessToken = json_decode($this->api_token, true);
		$client->setAccessToken($accessToken);

		if (!$client->getRefreshToken()) {
			return false;
		}
		$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		$access_token = $client->getAccessToken();
		if (!$access_token) {
			return false;}
		$this->api_token = json_encode($access_token);
		$this->update_account_details(['api_token' => $this->api_token, 'last_token_fetched_ts' => time()]);
	}

	protected function do_periodic_fetch_and_save_refresh_token() {
		try {
			$this->get_access_token_with_refresh_token_and_set_and_save();
		} catch (\WPCal\GoogleAPI\Google_Service_Exception $e) {
			$this->handle_api_exceptions($e);
			throw $e;
		}
	}

	public function get_add_account_url($action = 'add') {
		$client = $this->init_api_base_client();

		$site_redirect_url = trailingslashit(admin_url()) . 'admin.php?page=wpcal_admin&wpcal_action=google_calendar_receive_token';
		if ($action == 'reauth') {
			$site_redirect_url .= '&wpcal_reauth=1';
		}
		$state_array = ['site_redirect_url' => $site_redirect_url, 'state_token' => 'dshkjfhksdhfkjhsdkjfhskhfskjdhfkjhsdfkjhsdfkjh'];
		$state = wpcal_base64_url_encode(json_encode($state_array));

		$client->setState($state);

		$authUrl = $client->createAuthUrl();
		return $authUrl;
	}

	public function add_account_after_auth($action = 'add') {
		if (!isset($_GET['code']) || empty($_GET['code'])) {
			return false;
		}

		$client = $this->init_api_base_client();
		$authCode = trim($_GET['code']);

		// Exchange authorization code for an access token.
		$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
		//var_dump($accessToken);
		$client->setAccessToken($accessToken);

		// Check to see if there was an error.
		if (array_key_exists('error', $accessToken)) {
			throw new Exception(join(', ', $accessToken));
		}

		$this->api_token = json_encode($client->getAccessToken());

		$this->set_api();

		$calendar_list = $this->api->calendarList;
		//if all goes well
		$primary_calendar_details = $calendar_list->get('primary');
		$new_calendar_account = [];
		$new_calendar_account['account_email'] = $primary_calendar_details->getId();
		$new_calendar_account['api_token'] = $this->api_token;
		$new_calendar_account['last_token_fetched_ts'] = time();
		$new_calendar_account['_auth_done'] = true; //just now authorized

		$only_update = $action == 'reauth' ? true : false;
		$calendar_account_id = $this->add_or_update_calendar_account($new_calendar_account, $only_update);

		if ($calendar_account_id) {
			$tp_calendar_obj = new WPCal_TP_Google_Calendar($calendar_account_id);
			$tp_calendar_obj->api_refresh_calendars();
			$is_default_calendars_added = wpcal_check_and_add_default_calendars_for_current_admin($calendar_account_id);
		}
		return ['calendar_account_id' => $calendar_account_id, 'is_default_calendars_added' => $is_default_calendars_added];
	}

	public function revoke_access_and_delete_its_data($force = false, $revoke = true) {
		$is_revoked = false;

		if ($revoke) {
			$client = $this->get_api_client();
			try {
				$is_revoked = $client->revokeToken();
			} catch (Exception $e) {
				if (!$force) {
					throw $e;
				}
				$is_revoked = false;
			}
		}

		if ($is_revoked || $force) {
			$this->remove_calendar_account_and_its_data();
			return true;
		}
		//need to improve as data removing is not confirmed
		return false;
	}
}

// /**
//  * Returns an authorized API client.
//  * @return Google_Client the authorized client object
//  */
// function getClient()
// {
//     $client = new Google_Client();
//     $client->setApplicationName('Google Calendar API PHP Quickstart');
//     $client->setScopes(Google_Service_Calendar::CALENDAR);
//     $client->setAuthConfig( WPCAL_PATH . '/lib/Google_Cal_Api_client_id.json');
//     $client->setAccessType('offline');
// 	$client->setPrompt('select_account consent');
// 	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
// 	$client->setRedirectUri($redirect_uri='https://wpcal-01.com/wp-admin/admin.php?page=wpcal_admin_test');

//     // Load previously authorized token from a file, if it exists.
//     // The file token.json stores the user's access and refresh tokens, and is
//     // created automatically when the authorization flow completes for the first
//     // time.
//     $tokenPath = WPCAL_PATH . '/lib/token.json';
//     //var_dump($tokenPath, file_exists($tokenPath));
//     if (file_exists($tokenPath)) {
//         $accessToken = json_decode(file_get_contents($tokenPath), true);
//         $client->setAccessToken($accessToken);
//     }

//     // If there is no previous token or it's expired.
//     if ($client->isAccessTokenExpired()) {
//         // Refresh the token if possible, else fetch a new one.
//         if ($client->getRefreshToken()) {
//             $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
//         } else {

//             if( isset($_GET['code'])){

//                 $authCode = trim($_GET['code']);

//                 // Exchange authorization code for an access token.
//                 $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//                 var_dump($accessToken);
//                 $client->setAccessToken($accessToken);

//                 // Check to see if there was an error.
//                 if (array_key_exists('error', $accessToken)) {
//                     throw new Exception(join(', ', $accessToken));
//                 }
//             }
//             else{
//                 // Request authorization from the user.
//                 $authUrl = $client->createAuthUrl();
//                 echo '<h3><a  href="'.$authUrl.'">OAuth 2.0 Google here</a></h3>';

//             }

//             // printf("Open the following link in your browser:\n%s\n", $authUrl);
//             // print 'Enter verification code: ';
//             // $authCode = trim(fgets(STDIN));

//         }
//         // Save the token to a file.
//         if (!file_exists(dirname($tokenPath))) {
//             mkdir(dirname($tokenPath), 0700, true);
//         }
//         file_put_contents($tokenPath, json_encode($client->getAccessToken()));
//     }
//     return $client;
// }

// function sample_google_cal_call(){

//     // Get the API client and construct the service object.
//     $client = getClient();
//     $service = new Google_Service_Calendar($client);

//     // Print the next 10 events on the user's calendar.
//     $calendarId = 'primary';
//     $optParams = array(
//     'maxResults' => 10,
//     'orderBy' => 'startTime',
//     'singleEvents' => true,
//     'timeMin' => date('c'),
//     );
//     $results = $service->events->listEvents($calendarId, $optParams);
//     $events = $results->getItems();

//     if (empty($events)) {
//         print "No upcoming events found.\n";
//     } else {
//         print "Upcoming events:\n";
//         foreach ($events as $event) {
//             $start = $event->start->dateTime;
//             if (empty($start)) {
//                 $start = $event->start->date;
//             }
//             printf("%s (%s)\n", $event->getSummary(), $start);
//         }
//     }

// }
