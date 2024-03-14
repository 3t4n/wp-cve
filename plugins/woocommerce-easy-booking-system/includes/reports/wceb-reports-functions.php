<?php

/**
*
* Reports functions.
* @version 3.1.9
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Get bookings, sorted by date.
*
**/
function wceb_get_bookings_sorted_by_date() {

    // Booking mode (Days or Nights)
    $booking_mode = get_option( 'wceb_booking_mode' );

    /**
    * Get booked items from orders. Imported booking are added via the filter in PRO version.
    * @param bool - Get bookings in the past.
    **/
    $booked_products = apply_filters( 'wceb_reports_booked_products', wceb_get_booked_items_from_orders( false ) );
    $bookings        = array();

    // If no booking, return empty array
    if ( empty( $booked_products ) ) {
        return $bookings;
    }

    // Sort bookings by start date
    $start_dates = array_column( $booked_products, 'start' );
    array_multisort( $start_dates, SORT_ASC, $booked_products );
    
    $i = 1;
    foreach ( $booked_products as $index => $booking ) {

    	$product = wc_get_product( $booking['product_id'] );
    	$start   = $booking['start'];
        $end     = isset( $booking['end'] ) ? $booking['end'] : $start;

        // One date selection in nights mode: set end date to next day
        // Commented because I'm not sure whether to show them overnight or on a single day
        if ( $booking_mode === 'nights' && $start === $end ) {
            // $end = date( 'Y-m-d', strtotime( $end . ' +1 day' ) );
        }

        /**
        * @param str  - start date
        * @param str  - end date
        * @param bool - Get dates in the past.
        **/
        $dates = wceb_get_dates_from_daterange( $start, $end, true );

        foreach ( $dates as $date ) {

            $is_start = $date === $start ? true : false;
            $is_end   = $date === $end ? true : false;

            if ( true === $is_start ) {

                if ( isset( $bookings[$date] ) ) {

                    // If booking starts, fill first empty index of date or add a new index
                    for ( $j = 1; $j <= array_key_last( $bookings[$date] ); $j++ ) {

                        if ( ! array_key_exists( $j, $bookings[$date] ) ) {
                            $i = $j;
                            break;
                        } else {
                            $i = $j+1;
                        }

                    }

                } else {
                    $i = 1;
                }
                
            }

            $bookings[$date][$i] = array( 
                esc_html( wp_strip_all_tags( $booking['qty'] . ' x ' . $product->get_formatted_name() ) ),
                $is_start, $is_end
            );

            // Re-index array
            ksort( $bookings[$date] );

        }

        $i++;

    }

    return $bookings;

}