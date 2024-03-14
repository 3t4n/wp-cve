<?php

namespace EasyBooking;

/**
*
* Reports "Calendar" tab.
* @version 3.1.9
*
**/

defined( 'ABSPATH' ) || exit;

class Reports_Calendar {

	public function __construct() {

		add_action( 'easy_booking_reports_calendar_tab', array( $this, 'display_reports_calendar_page' ) );

	}

	/**
    *
    * Display reports calendar page.
    *
    **/
	public function display_reports_calendar_page() {
		include_once( 'views/html-wceb-reports-calendar-page.php' );
	}

}

new Reports_Calendar();