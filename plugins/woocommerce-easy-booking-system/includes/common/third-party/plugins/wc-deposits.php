<?php

/**
*
* Action hooks and filters for WooCommerce Deposits.
* @version 3.0.3
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Add WooCommerce Deposits order statuses to Easy Booking.
* @param array - $statuses
* @return array
*
**/
function wceb_deposits_order_statuses( $statuses ) {

	$statuses = array_merge( $statuses, array( 'wc-partial-payment', 'wc-scheduled-payment', 'wc-pending-deposit' ) );
	return $statuses;

}

add_filter( 'easy_booking_get_order_statuses', 'wceb_deposits_order_statuses', 10, 1 );