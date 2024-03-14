<?php

/**
*
* Core functions.
* @version 3.1.8
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Query orders with the right statuses.
* @param bool - $past - False to get only "processing" orders, true to get all orders.
* @return array
*
**/
function wceb_get_orders( $past = true ) {

    $args = array(
        'status' => apply_filters( 
                    'easy_booking_get_order_statuses',
                    array(
                        'wc-pending',
                        'wc-processing',
                        'wc-on-hold',
                        'wc-completed',
                        'wc-refunded'
                    ) ),
        'limit'  => -1,
        'return' => 'ids'
    );

    // If $past is false, get only processing orders
    if ( ! $past ) {

        $args['meta_query'] = array(
            'key'   => 'order_booking_status',
            'value' => 'processing'
        );

    }

    $query_orders = wc_get_orders( $args );

    return $query_orders;

}

/**
*
* Get all booked products from orders.
* @param bool - $past - False to get only "processing" orders, true to get all orders.
* @return array - $booked
*
**/
function wceb_get_booked_items_from_orders( $past = true ) {

    // Query orders
    $orders = wceb_get_orders( $past );

    $products = array();
    foreach ( $orders as $index => $order_id ) :

        $order = wc_get_order( $order_id );
        $items = $order->get_items();

        $data = array();
        if ( $items ) foreach ( $items as $item_id => $item ) {

            $product_id   = $item['product_id'];
            $variation_id = $item['variation_id'];

            $product = is_a( $item, 'WC_Order_Item_Product' ) ? $item->get_product() : false;

            if ( ! $product ) {
                continue;
            }

            if ( wceb_is_bookable( $product ) ) {

                // If start date is set
                if ( isset( $item['_booking_start_date'] ) ) {

                    $id    = empty( $variation_id ) || $variation_id === '0' ? $product_id : $variation_id;
                    $start = $item['_booking_start_date'];

                    // Check date format to avoid errors (yyyy-mm-dd) and check if product or variation still exists
                    if ( ! wceb_is_valid_date( $start ) || ! wc_get_product( $id ) ) {
                        continue;
                    }

                    $quantity = intval( $item['qty'] );

                    // If a refund of the product has been made, get the refunded quantity
                    $refunded_qty = $order->get_qty_refunded_for_item( $item_id );

                    // Removed refunded items
                    if ( $refunded_qty > 0 ) {
                        $quantity = $quantity - $refunded_qty;
                    }

                    // If 0 items are left, return
                    if ( $quantity <= 0 ) {
                        continue;
                    }

                    $status = isset( $item['_booking_status'] ) ? esc_html( $item['_booking_status'] ) : 'wceb-pending';

                    $data = array(
                        'product_id' => $id,
                        'order_id'   => $order_id,
                        'start'      => $start,
                        'qty'        => $quantity,
                        'status'     => $status
                    );

                }

                // If end date is set
                if ( ( isset( $item['_booking_end_date'] ) && ! empty( $item['_booking_end_date'] ) ) ) {
                    
                    $end = $item['_booking_end_date'];

                    // Check date format to avoid errors (yyyy-mm-dd)
                    if ( ! wceb_is_valid_date( $end ) ) {
                        continue;
                    }

                    $data['end'] = $end;

                }

                if ( ! empty( $data ) && isset( $data['product_id'] ) ) {
                    $products[] = apply_filters( 'easy_booking_booked_reports', $data );
                }

            }
        
        }

    endforeach;
    
    // Sort array by product IDs
    usort( $products, 'wceb_sort_by_product_id' );

    return $products;

}

/**
*
* Filter bookings.
* @param array - $filters
* @return array
*
**/
function wceb_get_filtered_bookings( $filters ) {

    // Filter booking status.
    $filter_status = isset( $filters['status'] ) ? stripslashes( $filters['status'] ) : '';

    // If "Completed" status has not been specifically filtered, don't display completed bookings (to avoir too much data).
    $past = $filter_status !== 'completed' ? false : true;

    // Get all booked items from orders.
    $bookings = apply_filters( 'wceb_reports_booked_products', wceb_get_booked_items_from_orders( $past ) );

    // Filter product.
    $filter_id = isset( $filters['product_ids'] ) ? stripslashes( $filters['product_ids'] ) : '';

    // Filter start date.
    $filter_start_date = isset( $filters['start_date'] ) ? stripslashes( $filters['start_date'] ) : '';

    // Filter end date.
    $filter_end_date   = isset( $filters['end_date'] ) ? stripslashes( $filters['end_date'] ) : '';

    foreach ( $bookings as $index => $booking ) {

        // Filter booking status.
        if ( ! empty( $filter_status ) && $booking['status'] != 'wceb-' . $filter_status ) {

            unset( $bookings[$index] ); // Remove unfiltered booking statuses.
            continue;

        } elseif ( empty( $filter_status ) && $booking['status'] === 'wceb-completed' ) {

            unset( $bookings[$index] ); // Remove completed bookings when not filtered.
            continue;

        }

        // Filter product
        if ( ! empty( $filter_id ) && $booking['product_id'] != $filter_id ) {

            unset( $bookings[$index] ); // Remove unfiltered products.
            continue;

        }

        // Filter dates.
        if ( ! empty( $filter_start_date ) && ! empty( $filter_end_date ) ) {

            $start = strtotime( $booking['start'] );
            $end   = isset( $booking['end'] ) ? strtotime( $booking['end'] ) : $start;

            $start_filter = strtotime( $filter_start_date );
            $end_filter   = strtotime( $filter_end_date );

            if ( $start < $start_filter || $end > $end_filter ) {
                unset( $bookings[$index] );
                continue;
            }

        } elseif ( ! empty( $filter_start_date ) && $booking['start'] != $filter_start_date ) {

            unset( $bookings[$index] );
            continue;

        } elseif ( ! empty( $filter_end_date ) ) {

            $end = isset( $booking['end'] ) ? $booking['end'] : $booking['start'];

            if ( $end != $filter_end_date ) {

                unset( $bookings[$index] );
                continue;

            }

        }

    }

    return apply_filters( 'easy_booking_filter_reports', $bookings );

}