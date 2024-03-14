<?php

/**
 * The WooCommerce checkout fields
 *
 * @link       https://www.deviodigital.com/
 * @since      1.0
 *
 * @package    DTWC
 * @subpackage DTWC/admin
 */

/**
 * Add Delivery Date & Time checkout fields
 *
 * @since 1.0
 */
function dtwc_delivery_info_checkout_fields( $checkout ) {

    // Set variables.
    $open_time  = strtotime( dtwc_business_opening_time() );
    $close_time = strtotime( dtwc_business_closing_time() );

    // Create delivery time.
    $delivery_time = $open_time;

    // Round to next 30 minutes (30 * 60 seconds)
    $delivery_time = ceil( $delivery_time / ( 30 * 60 ) ) * ( 30 * 60 );

    // Create times array with default option.
    $times = array( '' => apply_filters( 'dtwc_checkout_delivery_times_select_default_text', __( 'Select a desired time for delivery', 'delivery-times-for-woocommerce' ) ) );

    // Loop through and add delivery times based on open/close times.
    while( $delivery_time <= $close_time && $delivery_time >= $open_time ) {

        // Delivery prep time.
        $delivery_prep = dtwc_delivery_prep_time();

        // Set the delivery prep time for the strtotime.
        if ( '1' == $delivery_prep ) {
            $strtotime = '+' . $delivery_prep . 'hour';
        } elseif ( $delivery_prep > 1 ) {
            $strtotime = '+' . $delivery_prep . 'hours';
        } else {
            $strtotime = 'now';
        }

        // Get the prep time based on the settings in delivery prep.
        //$prep_time = date( 'H:i', strtotime( $strtotime, strtotime( current_time( 'H:i' ) ) ) );

        // Add delivery time to array of times.
        $times[date( 'H:i', $delivery_time )] = date( apply_filters( 'dtwc_time_format', get_option( 'time_format' ) ), $delivery_time );

        // Update delivery time variable.
        $delivery_time = strtotime( '+30 minutes', $delivery_time );
    }

    // Set default.
    $require_date = FALSE;

    // Require date?
    if ( 'on' == dtwc_require_delivery_date() ) {
        $require_date = TRUE;
    }

    // Create Delivery date field.
    woocommerce_form_field( 'dtwc_delivery_date', array(
        'type'     => 'text',
        'class'    => array( 'dtwc_delivery_date form-row-wide' ),
        'label'    => dtwc_delivery_date_label(),
        'required' => $require_date,
    ), $checkout->get_value( 'dtwc_delivery_date' ) );

    // Set default.
    $require_time = FALSE;

    // Require time?
    if ( 'on' == dtwc_require_delivery_time() ) {
        $require_time = TRUE;
    }

    // Create Delivery time field.
    woocommerce_form_field( 'dtwc_delivery_time', array(
        'type'     => 'select',
        'class'    => array( 'dtwc_delivery_time form-row-wide' ),
        'label'    => dtwc_delivery_time_label(),
        'required' => $require_time,
        'options'  => $times
    ), $checkout->get_value( 'dtwc_delivery_time' ) );

}

/**
 * Process the Delivery Date & Time checkout fields
 *
 * @since 1.0
 */
function dtwc_delivery_date_checkout_field_process() {

    // Create error message.
    $message = esc_attr__( 'Please select a delivery date.', 'delivery-times-for-woocommerce' );

    // Check if set, if its not set add an error.
    if ( ! filter_input( INPUT_POST, 'dtwc_delivery_date' ) && 'on' == dtwc_require_delivery_date() ) {
        wc_add_notice( apply_filters( 'dtwc_delivery_date_error_notice', $message ), 'error' );
    }

    // Create error message.
    $message = esc_attr__( 'Please select a delivery time.', 'delivery-times-for-woocommerce' );

    // Check if set, if its not set add an error.
    if ( ! filter_input( INPUT_POST, 'dtwc_delivery_time' ) && 'on' == dtwc_require_delivery_time() ) {
        wc_add_notice( apply_filters( 'dtwc_delivery_time_error_notice', $message ), 'error' );
    }
}
add_action( 'woocommerce_checkout_process', 'dtwc_delivery_date_checkout_field_process' );

/**
 * Save Delivery Date & Time checkout fields
 *
 * @since 1.0
 */
function dtwc_add_order_delivery_info_to_order ( $order_id ) {
	if ( null !== filter_input( INPUT_POST, 'dtwc_delivery_date' ) && '' != filter_input( INPUT_POST, 'dtwc_delivery_date' ) ) {
		add_post_meta( $order_id, 'dtwc_delivery_date',  sanitize_text_field( filter_input( INPUT_POST, 'dtwc_delivery_date' ) ) );
	}
	if ( null !== filter_input( INPUT_POST, 'dtwc_delivery_time' ) && '' != filter_input( INPUT_POST, 'dtwc_delivery_time' ) ) {
		add_post_meta( $order_id, 'dtwc_delivery_time',  sanitize_text_field( filter_input( INPUT_POST, 'dtwc_delivery_time' ) ) );
	}
}
add_action( 'woocommerce_checkout_update_order_meta', 'dtwc_add_order_delivery_info_to_order' , 10, 1 );

/**
 * Add Delivery Date & Time checkout fields to WooCommerce emails.
 *
 * @since 1.0
 */
function dtwc_add_delivery_info_to_emails( $fields, $sent_to_admin, $order ) {
    // Get the Order ID.
    if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {
        $order_id = $order->get_id();
    } else {
        $order_id = $order->id;
    }

    // Remove delivery time from emails.
    $remove = dtwc_remove_delivery_time_from_emails();

    // Bail early?
    if ( 'on' == $remove ) {     
        return $fields;
    }

    // Get the delivery date.
    $delivery_date_meta = get_post_meta( $order_id, 'dtwc_delivery_date', true );

    // Create readable delivery date.
    $delivery_date = date( apply_filters( 'dtwc_date_format', get_option( 'date_format' ) ), strtotime( $delivery_date_meta ) );

    // Display delivery date.
    if ( '' != $delivery_date_meta ) {
        $fields[ dtwc_delivery_date_label() ] = array(
            'label' => dtwc_delivery_date_label(),
            'value' => $delivery_date,
        );
    }

    // Get the delivery time.
    $delivery_time_meta = get_post_meta( $order_id, 'dtwc_delivery_time', true );

    // Create readable delivery time.
    $delivery_time = date( apply_filters( 'dtwc_time_format', get_option( 'time_format' ) ), strtotime( $delivery_time_meta ) );

    // Display delivery time.
    if ( '' != $delivery_time_meta ) {
        $fields[ dtwc_delivery_time_label() ] = array(
            'label' => dtwc_delivery_time_label(),
            'value' => $delivery_time,
        );
    }

    return $fields;
}
add_filter( 'woocommerce_email_order_meta_fields', 'dtwc_add_delivery_info_to_emails' , 10, 3 );

/**
 * Add Delivery Date & Time checkout fields to WooCommerce thank you page.
 *
 * @since 1.0
 */
function dtwc_add_delivery_info_to_order_received_page( $order ) {
    // Get the Order ID.
	if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {
        $order_id = $order->get_id();
    } else {
        $order_id = $order->id;
    }

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
        echo do_action( 'dtwc_order_received_delivery_details_before' );

        // Get delivery driver details.
        $delivery_details = '<p class="dtwc-delivery-date"><strong>' . dtwc_delivery_date_label() . ':</strong> ' . $delivery_date . ' @ ' . $delivery_time . '</p>';

        echo apply_filters( 'dtwc_order_received_delivery_details', $delivery_details );

        echo do_action( 'dtwc_order_received_delivery_details_after' );
	}
}
add_action( 'woocommerce_order_details_after_order_table_items', 'dtwc_add_delivery_info_to_order_received_page', 10 , 1 );
