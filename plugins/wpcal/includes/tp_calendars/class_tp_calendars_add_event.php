<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

/**
 * Add Even to calendar manually by user via browser
 */
class WPCal_TP_Calendars_Add_Event {

	public static function redirect_to_add_link_or_download($tp, $booking) {

		if ($tp === 'google_calendar') {
			$redirect_url = self::get_google_calendar_add_event_link($booking);
			//wp_redirect($redirect_url); //wp_redirect creating issues google_calendar description line break missing
			header("Location: $redirect_url", true, 302);
			exit;
		} elseif ($tp === 'ics') {
			self::download_ics_file_for_booking($booking);
		}
	}

	private static function prepare_event_data($booking) {
		$booking_obj = wpcal_get_booking($booking);
		$service_obj = $booking_obj->service_obj;

		$admin_details = wpcal_get_admin_details($booking_obj->get_admin_user_id());
		$invitee_name = $booking_obj->get_invitee_name();

		$service_name = $service_obj->get_name();

		$location = $booking_obj->get_location_str();
		$location_descr = wpcal_get_booking_location_content($booking_obj, $for = 'calendar_event', $whos_view = 'user', $provider = '');

		$form_time = $booking_obj->get_booking_from_time();
		$to_time = $booking_obj->get_booking_to_time();
		$_form_time = clone $form_time;
		$_to_time = clone $to_time;
		$_form_time->setTimezone(new DateTimeZone('UTC'));
		$_to_time->setTimezone(new DateTimeZone('UTC'));
		$_from_time_str = $_form_time->format('Ymd\THis\Z');
		$_to_time_str = $_to_time->format('Ymd\THis\Z');

		$unique_link = $booking_obj->get_unique_link();

		//$description = wpcal_get_booking_descr_for_calendar($service_obj, $booking_obj, $location_descr, $whos_view = 'user');
		$event_contents = wpcal_get_booking_contents_for_calendar($service_obj, $booking_obj, $location_descr, $admin_details, $whos_view = 'user', $options = ['for' => 'calendar_event_manual']);

		$return = [
			'name' => $event_contents['summary'],
			'description' => $event_contents['descr'],
			'from_time_T_Z' => $_from_time_str,
			'to_time_T_Z' => $_to_time_str,
			'location' => $location,
			'unique_link' => $unique_link,
			'service_name' => $service_name,
		];

		return $return;
	}

	/**
	 * $booking -> can be booking_id or booking_obj
	 */
	public static function get_google_calendar_add_event_link($booking) {
		$event_data = self::prepare_event_data($booking);

		$query_params = [
			'action' => 'TEMPLATE',
			'text' => $event_data['name'],
			'dates' => $event_data['from_time_T_Z'] . '/' . $event_data['to_time_T_Z'],
			'details' => $event_data['description'],
		];

		if (!empty($event_data['location'])) {
			$query_params['location'] = $event_data['location'];
		}

		$url_params = http_build_query($query_params, "", '&');

		$add_event_link = 'http://www.google.com/calendar/render?' . $url_params;

		return $add_event_link;

	}

	public static function download_ics_file_for_booking($booking) {
		$event_data = self::prepare_event_data($booking);

		$name = $event_data['name'];
		$start = $event_data['from_time_T_Z'];
		$end = $event_data['to_time_T_Z'];
		$description = str_replace(["\r\n", "\n"], "\\n", $event_data['description']);
		$location = $event_data['location'];
		$unique_str = $event_data['unique_link'];

		$slug = sanitize_title($event_data['service_name'] . '-' . substr($unique_str, 0, 6));

		$ics = "BEGIN:VCALENDAR\n";
		$ics .= "VERSION:2.0\n";
		$ics .= "PRODID:-//WPCal.io//NONSGML {$name}//EN\n";
		$ics .= "METHOD:REQUEST\n"; // requied by Outlook
		$ics .= "BEGIN:VEVENT\n";
		$ics .= "UID:" . date('Ymd') . 'T' . date('His') . "-" . $unique_str . "-wpcal.io\n"; // required by Outlook
		$ics .= "DTSTAMP:" . date('Ymd') . 'T' . date('His') . "\n"; // required by Outlook
		$ics .= "DTSTART:{$start}\n";
		$ics .= "DTEND:{$end}\n";
		$ics .= "LOCATION:{$location}\n";
		$ics .= "SUMMARY:{$name}\n";
		$ics .= "DESCRIPTION: {$description}\n";
		$ics .= "END:VEVENT\n";
		$ics .= "END:VCALENDAR\n";

		header("Content-Type: text/Calendar; charset=utf-8");
		header("Content-Disposition: inline; filename={$slug}.ics");
		echo $ics;
		exit;
	}
}
