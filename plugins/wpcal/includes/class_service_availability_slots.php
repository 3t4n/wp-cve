<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Service_Availability_Slots {

	private $service_obj;
	private $availability_details_obj;
	private $default_availability_obj;
	private $cache_created_time;
	private $exclude_booking_id;
	private $cache_conflict_tp_calendar_ids = null;
	private $cache_absolute_min_schedule_notice = null;
	private $cache_cal_conflict_free_as_busy = null;

	public function __construct(WPCal_Service $service_obj, $exclude_booking_id = null) {
		$this->service_obj = $service_obj;
		$this->exclude_booking_id = $exclude_booking_id;

		$this->availability_details_obj = new WPCal_Service_Availability_Details($this->service_obj);
		$this->default_availability_obj = $this->availability_details_obj->get_default_availability();

	}

	public function is_cached_slots_are_still_valid(DateTime $from_date, DateTime $to_date) {

		if (!empty($this->exclude_booking_id) && is_numeric($this->exclude_booking_id)) {
			return false;
		}

		//check service parameters for cache validity
		$refresh_cache = $this->service_obj->get_refresh_cache();
		if (!($refresh_cache === '0' || $refresh_cache === 0)) { //if $refresh_cache == 1 means generate new cache some time it can empty or null
			$this->check_refresh_cache_and_may_clear_full_cache(); //need to improve may not be the best place to put because of performance. We can put this additional else where.
			return false;
		}

		// $service_updated_time = $this->service_obj->get_updated_ts();
		// if( $last_cached_slots_generated < $service_updated_time ){
		// 	return false;
		// }

		$cache_details = $this->_get_cached_data_details($from_date, $to_date);
		if (empty($cache_details) || !isset($cache_details['num_of_days'])) {
			return false;
		}

		if (!$this->is_required_cached_slots_are_present($from_date, $to_date, $cache_details['num_of_days'])) {
			return false;
		}

		if ($cache_details['min_cache_created_ts'] < (time() - (60 * 60 * 12))) {
			//cache created more than (60 * 60 * 12) - 12 hrs ago
			return false;
		}

		//CODE NEEDS IMPROVEMENT

		return true;
	}

	private function is_required_cached_slots_are_present(DateTime $from_date, DateTime $to_date, $cached_num_of_days) {
		//check slots are present for given from and to dates
		//validate by no of days check

		$date_diff_obj = $from_date->diff($to_date);
		$date_diff = (int) $date_diff_obj->format('%r%a');
		$num_of_days = $date_diff + 1;

		if ($num_of_days < 1) {
			//something wrong
			return false;
		}

		if ($num_of_days == $cached_num_of_days) {
			return true;
		}

		return false;
	}

	private function _get_cached_data_details(DateTime $from_date, DateTime $to_date) {
		global $wpdb;
		$table_service_availability_slots_cache = $wpdb->prefix . 'wpcal_service_availability_slots_cache';
		$formated_from_date = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB($from_date);
		$formated_to_date = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB($to_date);

		$service_id = $this->service_obj->get_id();

		$query = "SELECT count(*) as num_of_days, min(`cache_created_ts`) as min_cache_created_ts FROM `$table_service_availability_slots_cache` WHERE `service_id` = %s AND `availability_date` >= %s AND `availability_date` <= %s";
		$query = $wpdb->prepare($query, $service_id, $formated_from_date, $formated_to_date);

		$cached_details = $wpdb->get_row($query, ARRAY_A);
		if (empty($cached_details)) {
			//handle error
			return [];
		}
		return $cached_details;
	}

	public function get_slots(DateTime $from_date, DateTime $to_date, $cache = true) {
		$cache = true; //currently $cache=true is forced

		// $from_date = new DateTime(date('Y-m-d'));
		// $to_date = clone $from_date;
		// $to_date->modify( '+21 days' );

		if (!$this->service_obj->is_new_booking_allowed() && !$this->service_obj->is_reschedule_booking_allowed()) {
			return false;
		}

		$service_min_date = $this->default_availability_obj->get_min_date();
		$service_max_date = $this->default_availability_obj->get_max_date();

		if ($from_date > $to_date) {
			return new WPCal_Exception('required_from_date_greater_than_required_to_date');
		}

		if ($service_min_date > $service_max_date) {
			return new WPCal_Exception('service_min_date_greater_than_service_max_date');
		}

		$is_there_common_period = WPCal_DateTime_Helper::is_two_slots_collide($from_date->format('U'), $to_date->format('U'), $service_min_date->format('U'), $service_max_date->format('U'));

		if (!$is_there_common_period) {
			return [];
		}

		list($available_from_date, $available_to_date) = WPCal_Service_Availability_Details::get_final_from_and_to_dates($from_date, $to_date, $service_min_date, $service_max_date);

		if ($available_from_date > $available_to_date) {
			return new WPCal_Exception('available_from_date_greater_than_available_to_date');
		}

		if (!$this->is_cached_slots_are_still_valid($available_from_date, $available_to_date)) {
			$this->generate_slots($available_from_date, $available_to_date, $cache);
		}

		return $this->get_cached_slots($available_from_date, $available_to_date);
	}

	private function generate_slots(DateTime $available_from_date, DateTime $available_to_date, $cache = true) {

		//add one day to cover hours of the day say Jan 01 00:00:00 to Jan 02 00:00:00 to cover full Jan 01
		//$available_to_date->add( new DateInterval('P1D') );

		// echo '<br>';
		// echo $available_from_date->format('c');
		// echo '<br>';
		// echo $__t1 = $available_to_date->format('c');
		// $t1_obj = new DateTime($__t1);
		// echo '<br>';
		// echo '||-> '.$t1_obj->format('c');

		$num_of_days_obj = $available_from_date->diff($available_to_date);
		//var_dump($num_of_days_obj);

		//echo '<br>';
		$num_of_days = $num_of_days_obj->format('%a');
		//var_dump($num_of_days);
		$num_of_days = $num_of_days + 1;

		$_from_date = clone $available_from_date;

		$one_day_interval = new DateInterval('P1D');

		$this->cache_created_time = time();

		$i = 0;
		while ($i < $num_of_days) {
			// echo '<br>';
			// echo $_from_date->format( 'c' );

			$day_is_available = 1;
			$day_is_all_booked = 0;

			$slots_for_a_day = $this->generate_slots_for_a_day_and_remove_conflicts($_from_date);

			if (is_array($slots_for_a_day) && empty($slots_for_a_day)) {

				$day_availability = $this->availability_details_obj->get_availability_by_date($_from_date);

				if (!$day_availability->is_available_by_date($_from_date)) {
					$day_is_available = 0;
				} else {
					$day_is_all_booked = 1;
				}
			}

			//var_dump($slots_for_a_day);

			if ($cache) {
				$this->save_for_cache($_from_date, $slots_for_a_day, $day_is_available, $day_is_all_booked);
			}

			$_from_date->add($one_day_interval);
			$i++;
		}
	}

	private function generate_slots_for_a_day_and_remove_conflicts(DateTime $date_obj) {

		if ($this->is_max_booking_per_day_reached(clone $date_obj)) {
			return [];
		}

		$slots_for_a_day = $this->get_all_possible_slots_for_a_day($date_obj);
		// echo '<br>==============day=======down====count: '.count($slots_for_a_day).'==========#<br>';
		// foreach( $slots_for_a_day as $slot_obj ){
		// 	echo '<br>'.$slot_obj->get_from_time()->format('c D')."\n";
		// }
		// echo '<br>==============day=======up==============#<br>';

		$this->removed_booked_slots_this_service_for_a_day($slots_for_a_day, $date_obj);
		// echo '<br>==============day=======down===after_remove_booked=====count: '.count($slots_for_a_day).'======#<br>';
		// foreach( $slots_for_a_day as $slot_obj ){
		// 	echo '<br>'.$slot_obj->get_from_time()->format('c D')."\n";
		// }
		// echo '<br>==============day=======up====after_remove_booked==========#<br>';

		$this->remove_other_services_booked_slots_for_a_day_by_admin($slots_for_a_day, $date_obj);
		// echo '<br>==============day=======down===after_remove_booked_others=====count: '.count($slots_for_a_day).'======#<br>';
		// foreach( $slots_for_a_day as $slot_obj ){
		// 	echo '<br>'.$slot_obj->get_from_time()->format('c D')."\n";
		// }
		// echo '<br>==============day=======up====after_remove_booked_others==========#<br>';

		$this->remove_conflict_tp_calendar_events_collide_slots_for_a_day_by_admin($slots_for_a_day, $date_obj);

		return $slots_for_a_day;

	}

	private function get_final_display_start_time_every() {
		$display_start_time_every = $this->service_obj->get_display_start_time_every();
		return $display_start_time_every;
	}

	private function get_period_last_meeting_start_time($period_to_time) {
		$period_last_meeting_start_time = clone $period_to_time;
		$total_meeting_time = $this->get_total_meeting_time();
		$period_last_meeting_start_time->sub($total_meeting_time);
		return $period_last_meeting_start_time;
	}

	private function get_total_meeting_time() {
		// The following commented because to not to strictly include the buffer time within the working hours. Already starting slots of day follows it, Now ending slots also follows it.
		// $total_meeting_time =
		// WPCal_DateTime_Helper::add_DateIntervals($this->service_obj->get_duration(), $this->service_obj->get_event_buffer_before(), $this->service_obj->get_event_buffer_after() );
		$total_meeting_time = $this->service_obj->get_duration();
		return $total_meeting_time;
	}

	private function get_all_possible_slots_for_a_day(DateTime $date_obj) {

		$day_availability = $this->availability_details_obj->get_availability_by_date($date_obj);
		//var_dump($day_availability);

		if (empty($day_availability)) {
			return array(); //probably error later check
		}

		if (!$day_availability->is_available_by_date($date_obj)) {
			//echo '<br> not available';
			return array();
		}

		$final_display_start_time_every = $this->get_final_display_start_time_every();

		$all_slots_for_a_day = array();

		$from_time = clone $date_obj;
		$to_time = clone $date_obj;

		foreach ($day_availability->get_periods() as $period) {
			$_from_time = $period->get_from_time();
			$_to_time = $period->get_to_time();

			$period_from_time = clone $from_time;
			$period_to_time = clone $to_time;

			$period_from_time->setTime($_from_time->format('H'), $_from_time->format('i'));
			$period_to_time->setTime($_to_time->format('H'), $_to_time->format('i'));

			$period_last_meeting_start_time = $this->get_period_last_meeting_start_time($period_to_time);

			$all_slots_in_period = new DatePeriod($period_from_time, $final_display_start_time_every, $period_to_time);

			//var_dump($all_slots_in_period);

			foreach ($all_slots_in_period as $slot_start_time) {
				if ($slot_start_time > $period_last_meeting_start_time) { //to remove slot times after period_last_meeting_start_time
					continue;
				}
				$all_slots_for_a_day[] = new WPCal_Slot($this->service_obj, $slot_start_time);
			}
		}

		return $all_slots_for_a_day;

	}

	private function remove_booked_or_conflicting_event_slots_for_a_day(&$slots_for_a_day, $booked_slots) {

		foreach ($slots_for_a_day as $slots_for_a_day_key => $slot_obj) {

			foreach ($booked_slots as $booked_slots_key => $booked_slot) {
				$booked_slot_obj = $booked_slot->slot_obj;

				$is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide($slot_obj->get_total_from_time(), $slot_obj->get_total_to_time(), $booked_slot_obj->get_total_from_time(), $booked_slot_obj->get_total_to_time());

				//var_dump($is_two_slots_collide);

				if ($is_two_slots_collide) {
					unset($slots_for_a_day[$slots_for_a_day_key]);
					continue;
				}
			}
		}
	}

	private function removed_booked_slots_this_service_for_a_day(&$slots_for_a_day, DateTime $date_obj) {
		if (empty($slots_for_a_day)) {
			return;
		}
		$booked_slots = WPCal_Bookings_Query::get_bookings_for_day_by_service($this->service_obj, $date_obj, $this->exclude_booking_id);
		$this->remove_booked_or_conflicting_event_slots_for_a_day($slots_for_a_day, $booked_slots);
	}

	private function remove_other_services_booked_slots_for_a_day_by_admin(&$slots_for_a_day, DateTime $date_obj) {
		if (empty($slots_for_a_day)) {
			return;
		}
		$exclude_service_obj = clone $this->service_obj;
		$admin_id = $this->service_obj->get_owner_admin_id();
		$booked_slots = WPCal_Bookings_Query::get_bookings_for_day_by_admin_and_exclude_service($exclude_service_obj, $date_obj, $admin_id);
		$this->remove_booked_or_conflicting_event_slots_for_a_day($slots_for_a_day, $booked_slots);
	}

	private function remove_conflict_tp_calendar_events_collide_slots_for_a_day_by_admin(&$slots_for_a_day, DateTime $date_obj) {
		if (empty($slots_for_a_day)) {
			return;
		}

		$events_for_a_day = $this->get_conflict_tp_calendar_events_of_admin_which_covers_this_date($date_obj);
		if (empty($events_for_a_day)) {
			return;
		}

		foreach ($slots_for_a_day as $slots_for_a_day_key => $slot_obj) {

			foreach ($events_for_a_day as $event) {

				if (empty($event->from_time) || empty($event->to_time)) {
					continue;
				}

				$event_from_time = WPCal_DateTime_Helper::unix_to_DateTime_obj($event->from_time, $this->service_obj->get_tz());
				$event_to_time = WPCal_DateTime_Helper::unix_to_DateTime_obj($event->to_time, $this->service_obj->get_tz());

				$is_two_slots_collide = WPCal_DateTime_Helper::is_two_slots_collide($slot_obj->get_total_from_time(), $slot_obj->get_total_to_time(), $event_from_time, $event_to_time);

				//var_dump($is_two_slots_collide);

				if ($is_two_slots_collide) {
					unset($slots_for_a_day[$slots_for_a_day_key]);
					continue;
				}

			}
		}
	}

	public function is_max_booking_per_day_reached(DateTime $date) {
		global $wpdb;

		$max_booking_per_day = $this->service_obj->get_max_booking_per_day();

		if (empty($max_booking_per_day) || !is_numeric($max_booking_per_day) || $max_booking_per_day < 1) {
			return false;
		}

		//check number of bookings for the day
		$date->setTime(0, 0);
		$_from_time = WPCal_DateTime_Helper::DateTime_Obj_to_unix($date);
		$one_day_interval = new DateInterval('P1D');
		$_to_date = clone $date;
		$_to_date->add($one_day_interval);
		$_to_time = WPCal_DateTime_Helper::DateTime_Obj_to_unix($_to_date);

		$service_id = $this->service_obj->get_id();

		$table_bookings = $wpdb->prefix . 'wpcal_bookings';
		$query = "SELECT count(*) FROM `$table_bookings` WHERE `booking_from_time` >= %s AND `booking_to_time` < %s AND `status` = '1' AND `service_id` = %s";
		$query = $wpdb->prepare($query, $_from_time, $_to_time, $service_id);

		if (!empty($this->exclude_booking_id)) { //if rescheduling, exclude the old reschedule booking id
			$query .= $wpdb->prepare(" AND `id` != %s", $this->exclude_booking_id);
		}

		$num_of_bookings = $wpdb->get_var($query);
		if ($num_of_bookings >= $max_booking_per_day) {
			return true;
		}

		return false;
	}

	private function is_slot_starts_within_min_schedule_notice(DateTime $slot_start_time) {
		//is_slot_starts_within_min_schedule_notice say 4 hrs, if a slot starts in less than 4 hrs then true

		if ($this->cache_absolute_min_schedule_notice === null) {

			$min_schedule_notice = $this->service_obj->get_min_schedule_notice();

			if (!isset($min_schedule_notice['type']) ||
				!isset($min_schedule_notice['time_units']) ||
				!isset($min_schedule_notice['time_units_in']) ||
				!in_array($min_schedule_notice['time_units_in'], array('mins', 'hrs', 'days')) ||
				!isset($min_schedule_notice['days_before_time']) ||
				!isset($min_schedule_notice['days_before']) ||
				!in_array($min_schedule_notice['type'], array('none', 'units', 'time_days_before'))) {
				$min_schedule_notice['type'] = 'none';
			}

			$absolute_min_schedule_notice = WPCal_DateTime_Helper::now_DateTime_obj($this->service_obj->get_tz());

			if ($min_schedule_notice['type'] === 'none') {
				$min_schedule_notice_interval = WPCal_DateTime_Helper::get_DateInterval_obj(1); //minimum 1 min
				$absolute_min_schedule_notice->add($min_schedule_notice_interval);
			} elseif ($min_schedule_notice['type'] === 'time_days_before') {

				$days_before = $min_schedule_notice['days_before'];
				$time_parts = explode(':', $min_schedule_notice['days_before_time']);
				$interval_in_hrs_mins_sec = new DateInterval('PT' . $time_parts[0] . 'H' . $time_parts[1] . 'M' . $time_parts[2] . 'S');

				//var_dump($min_schedule_notice_interval->format('%H:%i:%s'));

				$absolute_min_schedule_notice->setTime(0, 0, 0);
				$target_time = clone $absolute_min_schedule_notice;
				$target_time->add($interval_in_hrs_mins_sec);
				$current_time = WPCal_DateTime_Helper::now_DateTime_obj($this->service_obj->get_tz());
				//var_dump($target_time , $current_time);
				if ($target_time < $current_time) { //if current time crossed target time then only next day booking
					$absolute_min_schedule_notice->add(new DateInterval('P' . ($days_before + 1) . 'D'));
				} else {
					$absolute_min_schedule_notice->add(new DateInterval('P' . $days_before . 'D'));
					$absolute_min_schedule_notice = $absolute_min_schedule_notice < $current_time ? $current_time : $absolute_min_schedule_notice;
				}
			} elseif ($min_schedule_notice['type'] === 'units') {

				if ($min_schedule_notice['time_units_in'] == 'hrs') {
					$mins = $min_schedule_notice['time_units'] * 60;
				} elseif ($min_schedule_notice['time_units_in'] == 'days') {
					$mins = $min_schedule_notice['time_units'] * 1440;
				} else { //should be mins
					$mins = (int) $min_schedule_notice['time_units'];
				}

				if (!$mins || $mins < 1) {
					$mins = 1;
				}
				$min_schedule_notice_interval = WPCal_DateTime_Helper::get_DateInterval_obj($mins);
				$absolute_min_schedule_notice->add($min_schedule_notice_interval);
			}

			//var_dump($absolute_min_schedule_notice->format('Y-m-d H:i:s'));
			$this->cache_absolute_min_schedule_notice = $absolute_min_schedule_notice;
		}

		if ($slot_start_time < $this->cache_absolute_min_schedule_notice) {
			return true;
		}

		return false;
	}

	public function is_slot_still_available($booking_slot) {

		if (!isset($booking_slot['from_time']) || !isset($booking_slot['to_time']) || !is_numeric($booking_slot['from_time']) || !is_numeric($booking_slot['to_time'])) {
			throw new WPCal_Exception('invaild_slot_details');
		}

		$booking_date = WPCal_DateTime_Helper::unix_to_DateTime_obj($booking_slot['from_time'], $this->service_obj->get_tz());
		$booking_date_str = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB($booking_date);

		$booking_date->setTime(0, 0, 0);
		$booking_date_end = clone $booking_date;
		$booking_date_start = clone $booking_date;
		$booking_date_start->sub(new DateInterval('P1D')); //temporary hack fix, service max date having time of 00:00:00 of same date which needs to be fixed.
		$booking_date_end->add(new DateInterval('P1D')); //temporary hack fix, not able to book today's slots.

		$service_id = $this->service_obj->get_id();
		wpcal_service_availability_slots_mark_refresh_cache($service_id); //performance degradation? how about deleting particular date.

		$slots = $this->get_slots($from_date = $booking_date_start, $to_date = $booking_date_end);

		if (
			empty($slots) ||
			!isset($slots[$booking_date_str]) ||
			!isset($slots[$booking_date_str]['slots']) ||
			!is_array($slots[$booking_date_str]['slots'])
		) {
			return false;
		}

		foreach ($slots[$booking_date_str]['slots'] as $slot) {
			if ($booking_slot['from_time'] == $slot['from_time'] && $booking_slot['to_time'] == $slot['to_time']) {
				return true;
			}
		}
		return false;
	}

	public function check_refresh_cache_and_may_clear_full_cache() {
		if ($this->service_obj->get_refresh_cache() === 1 || $this->service_obj->get_refresh_cache() === '1') {
			$this->clear_full_cache();
		}
	}

	private function clear_full_cache() {
		global $wpdb;
		$table_service_availability_slots_cache = $wpdb->prefix . 'wpcal_service_availability_slots_cache';
		$service_id = $this->service_obj->get_id();

		$wpdb->delete($table_service_availability_slots_cache, array('service_id' => $service_id)); //need to soft delete IMPROVE LATER
		wpcal_service_availability_slots_mark_refresh_cache($service_id, 'off');
	}

	private function save_for_cache(DateTime $date, $slots, $is_available, $is_all_booked) {
		global $wpdb;

		$table_service_availability_slots_cache = $wpdb->prefix . 'wpcal_service_availability_slots_cache';
		$service_id = $this->service_obj->get_id();

		$formatted_slots = [];
		foreach ($slots as $key => $slot) {
			$formatted_slots[] = $slot->get_plain_data(); //resetting keys using [] is important, this decide it is object or array in client side.
		}

		$data = array();
		$data['availability_date'] = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB($date);
		$data['service_id'] = $service_id;
		$data['is_available'] = $is_available;
		$data['is_all_booked'] = $is_all_booked;
		$data['cache_created_ts'] = $this->cache_created_time;
		$data['slots'] = json_encode($formatted_slots);

		$result = $wpdb->replace($table_service_availability_slots_cache, $data);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return $result;
	}

	private function get_cached_slots(DateTime $from_date, DateTime $to_date) {
		global $wpdb;
		$table_service_availability_slots_cache = $wpdb->prefix . 'wpcal_service_availability_slots_cache';
		$service_id = $this->service_obj->get_id();

		$from_date_formatted = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB($from_date);
		$to_date_formatted = WPCal_DateTime_Helper::DateTime_Obj_to_Date_DB($to_date);

		$query = "SELECT * FROM `$table_service_availability_slots_cache` WHERE service_id = %s AND `availability_date` >= %s AND `availability_date` <= %s ORDER BY `availability_date`";
		$query = $wpdb->prepare($query, $service_id, $from_date_formatted, $to_date_formatted);

		$results = $wpdb->get_results($query, ARRAY_A);

		if (empty($results)) {
			//handle error
			return;
		}

		$new_results = array();

		foreach ($results as $result) {
			$final_slots = [];
			$result['slots'] = json_decode($result['slots'], true);
			foreach ($result['slots'] as $current_slot) {

				if ($this->is_slot_starts_within_min_schedule_notice(WPCal_DateTime_Helper::unix_to_DateTime_obj($current_slot['from_time'], $this->service_obj->get_tz()))) {
					continue;
				}
				$final_slots[] = $current_slot;
			}

			if (empty($final_slots)) {
				$result['is_all_booked'] = "1";
			}

			$new_results[$result['availability_date']] = $result;
			$new_results[$result['availability_date']]['slots'] = $final_slots;
		}
		return $new_results;
	}

	private function get_conflict_tp_calendar_ids_by_admin() {
		if ($this->cache_conflict_tp_calendar_ids !== null) {
			return $this->cache_conflict_tp_calendar_ids;
		}

		$admin_user_id = $this->service_obj->get_owner_admin_id();

		$conflict_tp_calendar_ids = wpcal_get_conflict_calendar_ids_by_admin($admin_user_id);

		$this->cache_conflict_tp_calendar_ids = $conflict_tp_calendar_ids;
		return $this->cache_conflict_tp_calendar_ids;
	}

	public function get_conflict_tp_calendar_events_of_admin_which_covers_this_date(DateTime $date_obj) {
		$conflict_tp_calendar_ids = $this->get_conflict_tp_calendar_ids_by_admin();
		if (empty($conflict_tp_calendar_ids)) {
			return [];
		}

		$from_time = clone $date_obj;
		$to_time = clone $date_obj;
		$one_day_interval = new DateInterval('P1D');
		$to_time->add($one_day_interval);

		$from_time_ts = WPCal_DateTime_Helper::DateTime_Obj_to_unix($from_time);
		$to_time_ts = WPCal_DateTime_Helper::DateTime_Obj_to_unix($to_time);

		$cal_conflict_free_as_busy = $this->get_cal_conflict_free_as_busy_by_admin();

		global $wpdb;
		$table_calendar_events = $wpdb->prefix . 'wpcal_calendar_events';

		$query = "SELECT * FROM `$table_calendar_events` WHERE `calendar_id` IN(" . implode(', ', $conflict_tp_calendar_ids) . ") AND `status` = '1' AND
		(
		(`from_time` >= %s AND `from_time` < %s)
		OR
		(%s >= `from_time` AND %s < `to_time`)
		)
		AND `is_wpcal_event` = '0'
		AND `is_consider_confirmed` = '1'
		"; //query condition is based on WPCal_DateTime_Helper::is_two_slots_collide()
		//$conflict_tp_calendar_ids is numbers no need of esc_sql

		if ($cal_conflict_free_as_busy === '0' || $cal_conflict_free_as_busy === 0) {
			$query .= " AND `tp_is_busy` = '1'";
		}

		$query = $wpdb->prepare($query, $from_time_ts, $to_time_ts, $from_time_ts, $from_time_ts);

		$result = $wpdb->get_results($query);
		if (empty($result)) {
			return [];
		}
		return $result;
	}

	private function get_cal_conflict_free_as_busy_by_admin() {
		if ($this->cache_cal_conflict_free_as_busy !== null) {
			return $this->cache_cal_conflict_free_as_busy;
		}

		$admin_user_id = $this->service_obj->get_owner_admin_id();

		$admin_settings_obj = new WPCal_Admin_Settings($admin_user_id);
		$cal_conflict_free_as_busy = $admin_settings_obj->get('cal_conflict_free_as_busy');

		$this->cache_cal_conflict_free_as_busy = $cal_conflict_free_as_busy;
		return $this->cache_cal_conflict_free_as_busy;
	}
}

class WPCal_Slot {
	private $from_time;
	private $to_time;
	private $total_from_time;
	private $total_to_time;
	private $service_obj;

	public function __construct(WPCal_Service $service_obj, DateTime $from_time, DateTime $to_time = null) {
		$this->service_obj = $service_obj;
		$this->from_time = $from_time;

		if ($to_time) {
			$this->to_time = $to_time;
		} elseif (is_null($to_time)) {
			$this->to_time = clone $this->from_time;
			$this->to_time->add($this->service_obj->get_duration());

		} else {
			throw new WPCal_Exception('slot_unexpected_to_time_value');
		}

		$this->total_from_time = clone $this->from_time;
		$this->total_to_time = clone $this->to_time;

		$this->total_from_time = $this->total_from_time->sub($this->service_obj->get_event_buffer_before());

		$this->total_to_time = $this->total_to_time->add($this->service_obj->get_event_buffer_after());

		//var_dump($this->from_time->format('c'), $this->to_time->format('c'));
		//var_dump($this->total_from_time->format('c'), $this->total_to_time->format('c'));

	}

	public function get_from_time() {
		return $this->from_time;
	}

	public function get_to_time() {
		return $this->to_time;
	}

	public function get_total_from_time() {
		return $this->total_from_time;
	}

	public function get_total_to_time() {
		return $this->total_to_time;
	}

	public function get_plain_data() {
		$data = [];

		$data['from_time'] = WPCal_DateTime_Helper::DateTime_Obj_to_unix($this->from_time);
		$data['to_time'] = WPCal_DateTime_Helper::DateTime_Obj_to_unix($this->to_time);
		$data['total_from_time'] = WPCal_DateTime_Helper::DateTime_Obj_to_unix($this->total_from_time);
		$data['total_to_time'] = WPCal_DateTime_Helper::DateTime_Obj_to_unix($this->total_to_time);

		return $data;
	}
}
