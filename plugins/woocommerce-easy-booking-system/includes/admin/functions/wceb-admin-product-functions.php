<?php

/**
*
* Admin product functions.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Sanitize and save product booking options.
* @param int $post_id
* @param array $booking_data
*
**/
function wceb_save_product_booking_options( $id, array $booking_data ) {
    
	$is_bookable  = EasyBooking\Settings::sanitize_checkbox( $booking_data['bookable'] );
	$all_bookable = get_option( 'wceb_all_bookable' );

    if ( $all_bookable === 'yes' ) {
        $is_bookable = 'yes';
    }

    $data = array(
        'booking_min'          => $booking_data['booking_min'],
        'booking_max'          => $booking_data['booking_max'],
        'first_available_date' => $booking_data['first_available_date']
    );

    foreach ( $data as $name => $value ) {
        
        switch ( $value ) {
            case '' :
                ${$name} = '';
            break;

            case 0 :
                ${$name} = '0';
            break;

            default :
                ${$name} = EasyBooking\Settings::sanitize_duration_field( $value );
            break;
        }
        
    }

    if ( $booking_min != 0 && $booking_max != 0 && $booking_min > $booking_max ) {
        \WC_Admin_Meta_Boxes::add_error( __( 'Minimum booking duration must be inferior to maximum booking duration', 'woocommerce-easy-booking-system' ) );
    } else {
        update_post_meta( $id, '_booking_min', $booking_min );
        update_post_meta( $id, '_booking_max', $booking_max );
    }

    if ( ! empty( $booking_data['booking_duration'] ) ) {

        $booking_duration = absint( $booking_data['booking_duration'] );

        if ( $booking_duration <= 0 ) {
            $booking_duration = 1;
        }

    } else {
        $booking_duration = '';
    }

    $dates = 'two';

    if ( ! empty( $booking_data['dates'] )
        && ( $booking_data['dates'] === 'one'
        || $booking_data['dates'] === 'two'
        || $booking_data['dates'] === 'parent'
        || $booking_data['dates'] === 'global' ) ) {

        $dates = sanitize_text_field( $booking_data['dates'] );

    }
    
    update_post_meta( $id, '_number_of_dates', $dates );
    update_post_meta( $id, '_booking_duration', $booking_duration );
    update_post_meta( $id, '_first_available_date', $first_available_date );
    update_post_meta( $id, '_bookable', $is_bookable );

}