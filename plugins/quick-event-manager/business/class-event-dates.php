<?php
/**
 * @copyright (c) 2020.
 * @author            Alan Fuller (support@fullworks)
 * @licence           GPL V3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link                  https://fullworks.net
 *
 * This file is part of  a Fullworks plugin.
 *
 *   This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with  this plugin.  https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace Quick_Event_Manager\Plugin\Business;


class Event_Dates {
	public $start;
	public $end;
	protected $post_id;

	/**
	 * @param $post_id
	 *
	 * usage     $dates = new Event_Dates($post_id);
	 *
	 *           $dates->start;
	 *           $dates->end;
	 */
	public function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->convert_dates();
	}

	private function convert_dates() {
		$event_local_start_date_time_epoch = get_post_meta( $this->post_id, 'event_date', true );
		// this is local start date  and time reflected in seconds
		$event_local_end_date_time_epoch = get_post_meta( $this->post_id, 'event_end_date', true );
		// this is local end date  and time reflected in seconds  but may be blank  if blank  the use start date
		$event_local_start_time_string = get_post_meta( $this->post_id, 'event_start', true );
		// this is local start time reflected in string of local date this can be blank - if so it is an all day event from 0 to 23:59
		$event_local_start_time_epoch = qem_time( $event_local_start_time_string );
		// this is local start time reflected in seconds from midnight of local date, invalid string is 0
		$event_local_finish_time_string = get_post_meta( $this->post_id, 'event_finish', true );
		// this is local end time string of local date can be blank, invalid string is 0

		$event_local_finish_time_epoch = qem_time( $event_local_finish_time_string );

		// this is local start time reflected in seconds from midnight of local date - need to subtract to reverse engineer correct day
		$event_local_start_date_epoch = $event_local_start_date_time_epoch - $event_local_start_time_epoch;

		if ( empty( $event_local_end_date_time_epoch ) ) {
			// blank end date / time use
			$event_local_finish_date_epoch = $event_local_start_date_epoch;
		} else {
			// this is local finish time reflected in seconds from midnight of local date - need to subtract to reverse engineer correct day
			$event_local_finish_date_epoch = $event_local_end_date_time_epoch - $event_local_finish_time_epoch;
		}


		if ( 0 === $event_local_finish_time_epoch ) {
			if ( 0 === $event_local_start_time_epoch ) {
				// all day event
				$event_local_start_time_epoch  = MINUTE_IN_SECONDS;
				$event_local_finish_time_epoch = DAY_IN_SECONDS - 1;
			} else {
				//  no finish set to start + 3 hours same as default Eventbrite
				$event_local_finish_time_epoch = $event_local_start_time_epoch + HOUR_IN_SECONDS * 3;
			}
		}


		// by now we should have all the date pieces so reassemble into a ISO date local time
		$event_local_start_date_fmt_string  = date( 'Y-m-d', $event_local_start_date_epoch );
		$event_local_start_time_fmt_string  = date( 'H:i:s', $event_local_start_time_epoch );
		$event_local_finish_date_fmt_string = date( 'Y-m-d', $event_local_finish_date_epoch );
		$event_local_finish_time_fmt_string = date( 'H:i:s', $event_local_finish_time_epoch );

		$event_local_start_date_time_fmt_string  = $event_local_start_date_fmt_string . ' ' . $event_local_start_time_fmt_string;
		$event_local_finish_date_time_fmt_string = $event_local_finish_date_fmt_string . ' ' . $event_local_finish_time_fmt_string;

		// build date object based on local timezone

		$this->start = date_create( $event_local_start_date_time_fmt_string, wp_timezone() );
		$this->end   = date_create( $event_local_finish_date_time_fmt_string, wp_timezone() );
	}
}
