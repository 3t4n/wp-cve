<?php

namespace EasyBooking;

/**
*
* Booking statuses.
* @version 3.1.8
*
**/

defined( 'ABSPATH' ) || exit;

class Booking_Statuses {

    public function __construct() {

        // Add booking status to order item on checkout.
        add_action( 'easy_booking_add_order_item_booking_data', array( $this, 'add_order_item_booking_status' ), 10, 3 );

        // Add order booking status on checkout.
        add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'add_order_booking_status' ), 10, 2 );

        // Update booking statuses when modifying order items
        add_action( 'woocommerce_saved_order_items', array( $this, 'update_order_items_booking_statuses' ), 20, 1 );

        // Schedule CRON event to automatically update booking statuses every day.
        if ( ! wp_next_scheduled( 'wceb_update_booking_statuses_event' ) ) {

            $ve = get_option( 'gmt_offset' ) > 0 ? '-' : '+';
            wp_schedule_event(  strtotime( '00:00 tomorrow ' . $ve . absint( get_option( 'gmt_offset' ) ) . ' HOURS' ), 'daily', 'wceb_update_booking_statuses_event' );

        }

        // Hook function to update booking statuses to CRON event.
        add_action( 'wceb_update_booking_statuses_event', array( $this, 'update_booking_statuses' ) );

        // Update booking statuses when saving Easy Booking settings.
        add_action( 'easy_booking_save_settings', array( $this, 'update_booking_statuses' ) ); 

    }

    /**
    *
    * Add order item booking status on checkout.
    *
    * @param WC_Order_Item $item
    * @param str - $start
    * @param str | bool - $end
    *
    **/
    public function add_order_item_booking_status( $item, $start, $end ) {

        $item_status = '';

        $current_date = strtotime( date( 'Y-m-d' ) );
        $start_time   = strtotime( $start );

        // Start date in the future = Pending status
        if ( $current_date < $start_time ) {
            $item_status = 'wceb-pending';
        }
        
        // Set Start status x days before start date (defined in the plugin settings)
        $change_start_day    = get_option( 'wceb_keep_start_status_for' );
        $change_start_status = strtotime( $start . ' -' . $change_start_day . ' days' );

        if ( $current_date === $change_start_status ) {
            $item_status = 'wceb-start';
        }

        if ( $end ) { // Two dates booking

            $end_time = strtotime( $end );

            // Set Processing status between start and end dates
            if ( $current_date > $start_time && $current_date < $end_time ) {
                $item_status = 'wceb-processing';
            }

        } else {

            if ( $current_date === $start_time ) {
                $item_status = 'wceb-processing';
            }

        }

        if ( ! empty( $item_status ) ) {
            $item->add_meta_data( '_booking_status', sanitize_text_field( $item_status ) );
        }

    }

    /**
    *
    * Set order booking status to "Processing" if there is a bookable item in it.
    *
    * @param int $order_id
    * @param array $data
    *
    **/
    public function add_order_booking_status( $order_id, $data ) {

        $order = wc_get_order( $order_id );
        $items = $order->get_items();

        if ( $items ) foreach ( $items as $item_id => $item ) {

            $start = wc_get_order_item_meta( $item_id, '_booking_start_date' );

            if ( ! empty( $start ) ) {

                $order->add_meta_data( 'order_booking_status', 'processing' );
                $order->save();

            }

        }

    }

    /**
    *
    * Update order items booking statuses and order booking status with CRON and when saving Easy Booking settings.
    *
    **/
    public function update_booking_statuses() {

        $orders = wceb_get_orders( false );

        foreach ( $orders as $index => $order_id ) :

            $item_statuses = $this->update_order_items_booking_statuses( $order_id, true );

        endforeach;

        do_action( 'wceb_update_order_items_booking_statuses' );

    }

    /**
    *
    * Update order items booking statuses and order booking status with CRON, when saving Easy Booking settings and when saving order items.
    *
    * @param array $items
    * @param bool $auto | true to update automatically (false when saving order items)
    * @return array $item_statuses
    *
    **/
    public function update_order_items_booking_statuses( $order_id, $auto = false ) {

        // Get current date
        $current_date = strtotime( date( 'Y-m-d' ) );
        
        $change_start     = get_option( 'wceb_set_start_booking_status' );
        $change_start_day = get_option( 'wceb_keep_start_status_for' );

        $change_processing = get_option( 'wceb_set_processing_booking_status' );

        $change_completed     = get_option( 'wceb_set_completed_booking_status' );
        $change_completed_day = get_option( 'wceb_keep_end_status_for' );

        $order = wc_get_order( $order_id );
        $items = $order->get_items();

        $item_statuses = array();
        if ( $items ) foreach ( $items as $item_id => $item ) {

            $start = wc_get_order_item_meta( $item_id, '_booking_start_date' );
            $end   = wc_get_order_item_meta( $item_id, '_booking_end_date' );

            if ( ! empty( $start ) ) {

                $start_time     = strtotime( $start );
                $booking_status = wc_get_order_item_meta( $item_id, '_booking_status' );

                if ( ! empty( $end ) ) {

                    $end_time = strtotime( $end );

                    // No status and start date in the future = Pending status
                    if ( $current_date < $start_time ) {
                        $item_status = 'wceb-pending';
                    }
                    
                    // Automatically change to Start status x days before start date (defined in the plugin settings)
                    $change_start_status = strtotime( $start . ' -' . $change_start_day . ' days' );
                    if ( $change_start === 'automatic' && $current_date >= $change_start_status ) {
                        $item_status = 'wceb-start';
                    }

                    // Automatically change to Processing status between start and end dates
                    if ( $change_processing === 'automatic' && $current_date > $start_time && $current_date < $end_time ) {
                        $item_status = 'wceb-processing';
                    }

                    // Automatically change to End status on end date
                    if ( $current_date >= $end_time ) {
                        $item_status = 'wceb-end';
                    }

                    // If start and end dates are the same day, set Processing instead of Start/End
                    if ( $change_processing === 'automatic' && $start_time === $end_time && $current_date === $start_time && $current_date === $end_time ) {
                        $item_status = 'wceb-processing';
                    }

                    // Automatically change to Completed status after end date
                    $change_completed_status = strtotime( $end . ' +' . $change_completed_day . ' days' );
                    if ( $change_completed === 'automatic' && $current_date > $change_completed_status ) {
                        $item_status = 'wceb-completed';
                    }

                } else {

                    // No status and start date in the future = Pending status
                    if ( $current_date < $start_time ) {
                        $item_status = 'wceb-pending';
                    }

                    // Automatically change to Start status x days before start date (defined in the plugin settings)
                    $change_start_status = strtotime( $start . ' -' . $change_start_day . ' days' );
                    if ( $change_start === 'automatic' && $current_date >= $change_start_status ) {
                        $item_status = 'wceb-start';
                    }

                    if ( $change_processing === 'automatic' && $current_date >= $start_time ) {
                        $item_status = 'wceb-processing';
                    }

                    // Automatically change to End status after date
                    if ( $current_date > $start_time ) {
                        $item_status = 'wceb-end';
                    }

                    $change_completed_status = strtotime( $start . ' +' . $change_completed_day . ' days' );
                    if ( $change_completed === 'automatic' && $current_date > $change_completed_status ) {
                        $item_status = 'wceb-completed';
                    }

                }

                if ( isset( $item_status ) ) {

                    // Update order item status once it is defined (only when using the CRON function)
                    if ( true === $auto && $booking_status !== $item_status ) {
                        wc_update_order_item_meta( $item_id, '_booking_status', sanitize_text_field( $item_status ) );

                        $old_status = str_replace( 'wceb-', '', $booking_status );
                        $new_status = str_replace( 'wceb-', '', $item_status );

                        do_action( 'wceb_order_item_status_' . $new_status, $item_id );
                        do_action( 'wceb_order_item_status_changed_from_' . $old_status . '_to_' . $new_status, $item_id );
                    }

                    // Store item status in an array in order to get all order item statuses later
                    $item_statuses[] = $item_status;

                }

            }

        }

        // Add order booking status to prevent getting too many orders when updating availabilities
        if ( ! empty( $item_statuses ) ) {

            // If all items in the order have the "wceb-completed" status, mark order booking status as complete
            $order_status = array_unique( $item_statuses ) === array( 'wceb-completed' ) ?  'completed' : 'processing';
            $order->update_meta_data( 'order_booking_status', $order_status );
            $order->save();
            
            do_action( 'wceb_order_booking_status_changed', $order_id, $item_statuses );

        } else if ( ! empty( $items ) ) {

            $order->update_meta_data( 'order_booking_status', 'completed' );
            $order->save();

        }

        return $item_statuses;
        
    }

}

new Booking_Statuses();