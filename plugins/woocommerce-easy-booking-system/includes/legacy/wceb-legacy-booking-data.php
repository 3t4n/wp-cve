<?php

/**
*
* '_ebs_start_format' was changed to '_booking_start_date'
* '_ebs_end_format' was changed to '_booking_end_date'
* @version 2.3.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Backward compatibility for meta data in cart.
*
*/
add_filter( 'easy_booking_add_cart_item_booking_data', 'wceb_legacy_add_cart_item_booking_data', 10, 1 );

function wceb_legacy_add_cart_item_booking_data( $cart_item_meta ) {

    // Start date yyyy-mm-dd
    if ( isset( $cart_item_meta['_booking_start_date'] ) ) {
        $cart_item_meta['_ebs_start'] = $cart_item_meta['_booking_start_date'];
    }

    // End date yyyy-mm-dd
    if ( isset( $cart_item_meta['_booking_end_date'] ) ) {
        $cart_item_meta['_ebs_end'] = $cart_item_meta['_booking_end_date'];
    }

    return $cart_item_meta;

}

/**
*
* Backward compatibility for meta data in checkout/order.
*
*/
add_action( 'easy_booking_add_order_item_booking_data', 'wceb_legacy_add_order_item_booking_data', 10, 3 );

function wceb_legacy_add_order_item_booking_data( $item, $start, $end ) {

    // Store start date. 
    $item->add_meta_data( '_ebs_start_format', sanitize_text_field( $start ) );

    // Maybe store end date.
    if ( $end ) {
        $item->add_meta_data( '_ebs_end_format', sanitize_text_field( $end ) );
    }

}

/**
*
* Backward compatibility for meta data on order pages.
*
*/
add_filter( 'woocommerce_hidden_order_itemmeta', 'wceb_legacy_hide_order_item_booking_data', 10, 1 );

function wceb_legacy_hide_order_item_booking_data( $item_meta ) {

    $item_meta[] = '_ebs_start_format';
    $item_meta[] = '_ebs_end_format';

    return $item_meta;

}


/**
*
* Backward compatibility for meta data wehn saving order items.
*
*/
add_filter( 'woocommerce_before_save_order_item', 'wceb_legacy_save_order_item', 10, 1 );

function wceb_legacy_save_order_item( $item ) {

    $booking_start_date = $item->get_meta( '_booking_start_date' );

    if ( ! empty( $booking_start_date ) ) {
        $item->add_meta_data( '_ebs_start_format', $booking_start_date );
    }

    $booking_end_date = $item->get_meta( '_booking_end_date' );

    if ( ! empty( $booking_end_date ) ) {
        $item->add_meta_data( '_ebs_end_format', $booking_end_date );
    }

    return $item;

}