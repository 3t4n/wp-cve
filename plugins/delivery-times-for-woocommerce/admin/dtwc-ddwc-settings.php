<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.deviodigital.com/
 * @since      1.2
 *
 * @package    DTWC
 * @subpackage DTWC/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	wp_die();
}

/**
 * Delivery Drivers for WooCommerce
 *
 * Adds delivery date details to the Driver Dashboard order details
 * table below the order date.
 *
 * @since 1.2
 */
function dtwc_driver_dashboard_order_details_table_tbody_bottom() {
    // Order ID.
    $order_id = filter_input( INPUT_GET, 'orderid' );

    // Get the delivery date.
    $delivery_date_meta = get_post_meta( $order_id, 'dtwc_delivery_date', true );

    // Create readable delivery date.
    $delivery_date = date( apply_filters( 'dtwc_date_format', get_option( 'date_format' ) ), strtotime( $delivery_date_meta ) );

    // Get the delivery time.
    $delivery_time_meta = get_post_meta( $order_id, 'dtwc_delivery_time', true );

    // Create readable delivery time.
    $delivery_time = date( apply_filters( 'dtwc_time_format', get_option( 'time_format' ) ), strtotime( $delivery_time_meta ) );

    // Display the delivery details.
    if ( '' != $delivery_date_meta ) {
        // Get delivery driver details.
        $delivery_times = '<tr><td class="dtwc-delivery-date">' . dtwc_delivery_date_label() . '</td><td>' . $delivery_date . ' - ' . $delivery_time . '</td></tr>';

        echo apply_filters( 'dtwc_driver_dashboard_order_details_table_tbody_bottom', $delivery_times );
    }
}
add_action( 'ddwc_driver_dashboard_order_details_table_tbody_bottom', 'dtwc_driver_dashboard_order_details_table_tbody_bottom' );
