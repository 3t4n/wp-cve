<?php

/**
*
* Update functions.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Function to update database.
* Check if current db version is inferior to plugin db version, and run update functions if necessary.
* @param bool - $full_update - Set true to run all updates.
*
**/
function wceb_db_update( $full_update = false ) {

    $current_db_version = get_option( 'easy_booking_db_version' );

    // First time init or full update (db updates started in version 2.2.4)
    if ( ! $current_db_version || empty( $current_db_version ) || true === $full_update ) {
        $current_db_version = '2.2.3';
    }

	if ( version_compare( $current_db_version, wceb_get_db_version(), '<' ) ) {

        foreach ( wceb_get_db_updates() as $index => $update_version ) {

            if ( version_compare( $current_db_version, $update_version, '<' ) ) {

                $update = str_replace( '.', '', $update_version );

                if ( function_exists( 'wceb_update_db_version_' . $update ) ) {
                    call_user_func( 'wceb_update_db_version_' . $update );
                }

            }

        }
         
    }

}

/**
*
* Get available database updates
* @return array - $updates
*
**/
function wceb_get_db_updates() {
    $updates = array( '2.2.4', '2.2.5', '2.3.0', '3.0.0' );
    return $updates;
}

/**
*
* In version 2.2.4 "_booking_option" post meta becomes "_bookable".
*
**/
function wceb_update_db_version_224() {

    // Query Products
    $args = array(
        'post_type'      => array( 'product', 'product_variation' ),
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'meta_key'       => '_booking_option',
        'fields'         => 'ids'
    );

    $query_products = new WP_Query( $args );

    if ( $query_products->posts ) foreach ( $query_products->posts as $product_id ) :

        $is_bookable = get_post_meta( $product_id, '_booking_option', true );

        if ( $is_bookable === 'yes' ) {
            update_post_meta( $product_id, '_bookable', 'yes' );
        }

        delete_post_meta( $product_id, '_booking_option' );

    endforeach;

    update_option( 'easy_booking_db_version', '2.2.4' );

}

/**
*
* In version 2.2.5 "_booking_dates" post meta becomes "_number_of_dates".
*
**/
function wceb_update_db_version_225() {

    // Query Products
    $args = array(
        'post_type'      => array( 'product', 'product_variation' ),
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'meta_key'       => '_booking_dates',
        'fields'         => 'ids'
    );

    $query_products = new WP_Query( $args );

    if ( $query_products->posts ) foreach ( $query_products->posts as $product_id ) :

        $number_of_dates = get_post_meta( $product_id, '_booking_dates', true );

        if ( ! is_null( $number_of_dates ) || ! is_empty( $number_of_dates ) ) {
            update_post_meta( $product_id, '_number_of_dates', sanitize_text_field( $number_of_dates ) );
            delete_post_meta( $product_id, '_booking_dates' );
        }

    endforeach;

    update_option( 'easy_booking_db_version', '2.2.5' );

}

/**
*
* In version 2.3.0 "_ebs_start_format" order item meta becomes "_booking_start_date" and "_ebs_end_format" becomes "_booking_end_date".
*
**/
function wceb_update_db_version_230() {

    // Query orders
    $orders = wceb_get_orders( false );

    $products = array();
    foreach ( $orders as $index => $order_id ) :

        $order = wc_get_order( $order_id );
        $items = $order->get_items();

        $data = array();
        if ( $items ) foreach ( $items as $item_id => $item ) {

            if ( isset( $item['_ebs_start_format'] ) && ! isset( $item['_booking_start_date'] ) ) {
                wc_add_order_item_meta( $item_id, '_booking_start_date', $item['_ebs_start_format'] );
            }

            if ( isset( $item['_ebs_end_format'] ) && ! isset( $item['_booking_end_date'] ) ) {
                wc_add_order_item_meta( $item_id, '_booking_end_date', $item['_ebs_end_format'] );
            }

        }

    endforeach;

    update_option( 'easy_booking_db_version', '2.3.0' );

}

/**
*
* In version 3.0.0 Booking duration and Custom booking duration were merged into one option.
*
**/
function wceb_update_db_version_300() {

    $booking_duration = get_option( 'wceb_booking_duration' );

    if ( $booking_duration === 'days' ) {

        update_option( 'wceb_booking_duration', 1 );

    } else if ( $booking_duration === 'weeks' ) {

        update_option( 'wceb_booking_duration', 7 );

    }  else if ( $booking_duration === 'custom' ) {

        $custom_booking_duration = get_option( 'wceb_custom_booking_duration' );

        if ( $custom_booking_duration ) {
            update_option( 'wceb_booking_duration', $custom_booking_duration );
        } else {
            update_option( 'wceb_booking_duration', 1 );
        }

    }

    delete_option( 'wceb_custom_booking_duration' );

    // Query Products
    $args = array(
        'post_type'      => array( 'product', 'product_variation' ),
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'meta_key'       => '_booking_duration',
        'fields'         => 'ids'
    );

    $query_products = new WP_Query( $args );

    if ( $query_products->posts ) foreach ( $query_products->posts as $product_id ) :

        $booking_duration = get_post_meta( $product_id, '_booking_duration', true );

        if ( $booking_duration === 'days' ) {

            update_post_meta( $product_id, '_booking_duration', 1 );

        } else if ( $booking_duration === 'weeks' ) {

            update_post_meta( $product_id, '_booking_duration', 7 );

        }  else if ( $booking_duration === 'custom' ) {

            $custom_booking_duration = get_post_meta( $product_id, '_custom_booking_duration', true );

            if ( ! is_null( $custom_booking_duration ) || ! is_empty( $custom_booking_duration ) ) {
                update_post_meta( $product_id, '_booking_duration', $custom_booking_duration );
            } else {
                delete_post_meta( $product_id, '_booking_duration' );
            }

        } else if ( $booking_duration === 'global' || $booking_duration === 'parent' ) {
            delete_post_meta( $product_id, '_booking_duration' );
        }

        delete_post_meta( $product_id, '_custom_booking_duration' );

    endforeach;

    update_option( 'easy_booking_db_version', '3.0.0' );

}