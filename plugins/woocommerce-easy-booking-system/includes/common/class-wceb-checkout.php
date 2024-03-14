<?php

namespace EasyBooking;

/**
*
* Checkout action hooks and filters.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Checkout {

    public function __construct() {

        // Add booking dates to order item.
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_order_item_booking_data' ), 10, 3 );

        // Display formatted dates in checkout page.
        add_filter( 'woocommerce_display_item_meta', array( $this, 'display_booking_dates_in_checkout' ), 10, 3 );

    }

    /**
    *
    * Add booking dates and status to the order item meta data.
    *
    * @param int - $item_id
    * @param str - $cart_item_key
    * @param array - $values
    *
    **/
    public function add_order_item_booking_data( $item, $cart_item_key, $values ) {

        if ( ! empty( $values['_booking_start_date'] ) ) {

            // Start date format yyyy-mm-dd
            $start = sanitize_text_field( $values['_booking_start_date'] );

            // Store start date. 
            $item->add_meta_data( '_booking_start_date', $start );

            // End date format yyyy-mm-dd
            $end = ! empty( $values['_booking_end_date'] ) ? sanitize_text_field( $values['_booking_end_date'] ) : false;

            // Maybe store end date.
            if ( $end ) {
                $item->add_meta_data( '_booking_end_date', $end );
            }
            
            // Backward compatibility 2.3.0
            do_action_deprecated( 'easy_booking_add_booked_item', array( $item, $start, $end ), '2.3.0', 'easy_booking_add_order_item_booking_data' );

            do_action( 'easy_booking_add_order_item_booking_data', $item, $start, $end );

        }

    }

    /**
    *
    * Display order item localized booking dates in checkout.
    *
    * @param str $output
    * @param WC_Order_Item $order_item
    * @param str $output
    *
    **/
    public function display_booking_dates_in_checkout( $html, $item, $args ) {

        $product   = $item->get_product();
        $strings   = array();
        $meta_data = array();
        
        // Add class for styling
        $args = wp_parse_args(
            array( 'before' => '<ul class="wc-item-meta wceb-item-meta"><li>' ),
            $args
        );

        $start = $item->get_meta( '_booking_start_date' );

        if ( isset( $start ) && ! empty( $start ) ) {

            $meta_data[] = array(
                'display_key'   => wceb_get_start_text( $product ),
                'display_value' => date_i18n( get_option( 'date_format' ), strtotime( $start ) )
            );

        }

        $end = $item->get_meta( '_booking_end_date' );

        if ( isset( $end ) && ! empty( $end ) ) {

            $meta_data[] = array(
                'display_key'   => wceb_get_end_text( $product ),
                'display_value' => date_i18n( get_option( 'date_format' ), strtotime( $end ) )
            );

        }

        foreach ( apply_filters( 'easy_booking_display_item_meta', $meta_data, $item ) as $index => $meta ) {
            $value     = $args['autop'] ? wp_kses_post( $meta['display_value'] ) : wp_kses_post( make_clickable( trim( $meta['display_value'] ) ) );
            $strings[] = $args['label_before'] . wp_kses_post( $meta['display_key'] ) . $args['label_after'] . $value;
        }

        if ( $strings ) {
            $html .= $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
        }

        return $html;

    }

}

new Checkout();