<?php

/**
 * The WooCommerce settings
 *
 * @link       https://www.deviodigital.com/
 * @since      1.2
 *
 * @package    DTWC
 * @subpackage DTWC/admin
 */

/**
 * Add delivery time to admin order data
 */
function dtwc_woocommerce_admin_order_data_after_address( $order ) {
    // Order ID.
    $order_id = $order->get_id();

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
        $delivery_details = '<p class="dtwc-delivery-date"><strong>' . dtwc_delivery_date_label() . ':</strong><br />' . $delivery_date . ' @ ' . $delivery_time . '</p>';

        echo apply_filters( 'dtwc_admin_order_received_delivery_details', $delivery_details );
    }
}

/**
 * Run specific codes on WooCommerce init
 *
 * @since 1.2
 * @return void
 */
function dtwc_woocommerce_init() {
    // Edit Order display setting.
    $after_address = dtwc_delivery_time_edit_order_display();

    // Edit Order screen display placement.
    if ( 'billing' == $after_address ) {
        add_action( 'woocommerce_admin_order_data_after_billing_address', 'dtwc_woocommerce_admin_order_data_after_address', 10, 1 );
    } else {
        add_action( 'woocommerce_admin_order_data_after_shipping_address', 'dtwc_woocommerce_admin_order_data_after_address', 10, 1 );
    }

    // Checkout display setting.
    $checkout_display = dtwc_delivery_time_checkout_display();

    // Display after billing.
    if ( 'after_billing' == $checkout_display ) {
        add_action( 'woocommerce_after_checkout_billing_form', 'dtwc_delivery_info_checkout_fields' , 10, 1 );
    }
    // Display after shipping.
    if ( 'after_shipping' == $checkout_display ) {
        add_action( 'woocommerce_after_checkout_shipping_form', 'dtwc_delivery_info_checkout_fields' , 10, 1 );
    }
    // Display after notes.
    if ( 'after_notes' == $checkout_display ) {
        add_action( 'woocommerce_after_order_notes', 'dtwc_delivery_info_checkout_fields' , 10, 1 );
    }
}
add_action( 'woocommerce_init', 'dtwc_woocommerce_init' );
