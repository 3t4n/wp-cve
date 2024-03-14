<?php

/**
*
* Date functions.
* @version 3.0.5
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Check if a date is valid (yyyy-mm-dd).
* @param str - $date
* @return bool
*
**/
function wceb_is_valid_date( $date ) {

    if ( ! $date ) {
        return false;
    }

	if ( ! preg_match( '/^([0-9]{4}\-[0-9]{2}\-[0-9]{2})$/', $date ) ) {
        return false;
    }

    return true;

}

/**
*
* Check if a day is valid (between 1 and 7).
* @param int - $day
* @return bool
*
**/
function wceb_is_valid_day( $day ) {
    return ( $day >= 1 && $day <= 7 );
}

/**
*
* Sort dates in ascending order.
* @param str $a - First date
* @param str $b - Second date
* @return bool
*
**/
function wceb_sort_dates( $a, $b ) {
    return strtotime( $a ) - strtotime( $b );
}

/**
*
* Get all dates between start date and end date.
* @param string - $start
* @param string - $end
* @param bool - $past - Get passed dates
* @param bool - $offset - Whether to apply "Nights" mode one-day offset or not
* @return array - $dates
*
**/
function wceb_get_dates_from_daterange( $start, $end, $past = false, $offset = false ) {

	// Calculation mode (Days or Nights)
	$booking_mode = get_option( 'wceb_booking_mode' );

    // If calculation mode is set to "Nights" and offset to true, add one day to start day (as you book the night)
    if ( $offset && $booking_mode === 'nights' ) {
       $start = date( 'Y-m-d', strtotime( $start . ' +1 day' ) );
    }

    // Get an array of all dates inside daterange
    $dates = array( $start );

    while ( end( $dates ) < $end ) {
        $dates[] = date( 'Y-m-d', strtotime( end( $dates ) . ' +1 day' ) );
    }

     // Get current date
    $current_date = date( 'Y-m-d' );

    foreach ( $dates as $index => $date ) {

    	// Maybe remove date if it is in the past
        if ( ! $past && $date < $current_date ) {
            unset( $dates[$index] );
        }

    }

    return $dates;

}

/**
*
* Add or remove x days to a given date.
* @param string (yyyy-mm-dd) - $date
* @param string - $offset
* @param string (plus/minus) - $action
* @return string - $new_date
*
**/
function wceb_shift_date( $date, $offset, $action = 'plus' ) {

    if ( wceb_is_valid_date( $date ) && $offset >= 1 ) {
        $date = $action = 'plus' ? date( 'Y-m-d', strtotime( $date . ' + ' . absint( $offset ) . ' days' ) ) : date( 'Y-m-d', strtotime( $date . ' - ' . absint( $offset ) . ' days' ) );
    }
    
    return $date;

}