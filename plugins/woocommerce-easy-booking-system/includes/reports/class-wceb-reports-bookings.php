<?php

namespace EasyBooking;

/**
*
* Admin: "Bookings" reports page content.
* @version 3.0.6
*
**/

defined( 'ABSPATH' ) || exit;

class Reports_Bookings {

	public function __construct() {

		add_action( 'easy_booking_reports_bookings_tab', array( $this, 'list_bookings_tab' ) );

	}

	/**
	*
	* Display bookings in "Reports" tab.
	*
	**/
	public function list_bookings_tab() {

		echo '<p>' . esc_html__( 'To see "Completed" bookings, use the "Booking status" filter below.', 'woocommerce-easy-booking-system' ) . '</p>';

		$list_bookings = new List_Bookings();
		$list_bookings->prepare_items();
		$list_bookings->display();

	}
	
}

new Reports_Bookings();