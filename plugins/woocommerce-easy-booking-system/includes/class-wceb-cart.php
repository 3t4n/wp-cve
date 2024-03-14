<?php

namespace EasyBooking;

/**
*
* Cart action hooks and filters.
* @version 3.2.0
*
**/

defined( 'ABSPATH' ) || exit;

class Cart {

    public function __construct() {
        
        // Check that dates are set and valid before adding to cart.
        add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 20, 5 );

        // Check that dates are valid in cart.
        add_action( 'woocommerce_check_cart_items', array( $this, 'check_dates_in_cart' ), 10 );

        // Get cart item data from session.
        add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_booking_data_from_session' ), 98, 2 );

        // Add cart item booking data and price.
        add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_booking_data' ), 12, 4 );
        add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item_booking_price' ), 10, 1 );

        // Display formatted dates in cart.
        add_filter( 'woocommerce_get_item_data', array( $this, 'display_booking_dates_in_cart' ), 10, 2 );
        
    }

    /**
    *
    * Check that dates are set and valid before adding to cart.
    *
    * @param bool - $passed
    * @param int - $product_id
    * @param int - $quantity
    * @param (optional) int - $variation_id
    * @param (optional) array - $variations
    * @return bool - $passed
    *
    **/
    public function add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations = array() ) {

        $_product_id = empty( $variation_id ) ? $product_id : $variation_id;
        $_product    = wc_get_product( $_product_id );

        if ( ! $passed || ! $_product ) {
            return false;
        }

        if ( wceb_is_bookable( $_product ) ) {

            // Use $_REQUEST to allow $_POST and $_GET
            $start = isset( $_REQUEST['start_date_submit'] ) ? $_REQUEST['start_date_submit'] : false;
            $end   = isset( $_REQUEST['end_date_submit'] ) ? $_REQUEST['end_date_submit'] : false;

            $valid_dates = Date_Selection::check_selected_dates( $start, $end, $_product );

            if ( is_wp_error( $valid_dates ) ) {

                wc_add_notice( esc_html( $valid_dates->get_error_message() ), 'error' );
                $passed = false;

            }

            $valid_booking_duration = Date_Selection::get_selected_booking_duration( $start, $end, $_product );

            if ( is_wp_error( $valid_booking_duration ) ) {

                wc_add_notice( esc_html( $valid_booking_duration->get_error_message() ), 'error' );
                $passed = false;

            }

        }

        return $passed;

    }

    /**
    *
    * Check that dates are valid in cart.
    *
    * @return bool
    *
    **/
    public function check_dates_in_cart() {

        foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
            
            if ( isset( $values['_booking_start_date'] ) ) {

                $_product = $values['data'];
                $start    = $values['_booking_start_date'];
                $end      = isset( $values['_booking_end_date'] ) ? $values['_booking_end_date'] : false;

                $valid_dates = Date_Selection::check_selected_dates( $start, $end, $_product );

                if ( is_wp_error( $valid_dates ) ) {
                    wc_add_notice( esc_html( $valid_dates->get_error_message() ), 'error' );
                    return false;
                }

                $valid_booking_duration = Date_Selection::get_selected_booking_duration( $start, $end, $_product );

                if ( is_wp_error( $valid_booking_duration ) ) {
                    wc_add_notice( esc_html( $valid_booking_duration->get_error_message() ), 'error' );
                    return false;
                }

            }

        }

        return true;

    }

    /**
    *
    * Get cart item booking data from session.
    *
    * @param array $session_data
    * @param array $values - cart_item_meta
    * @return array $session_data
    *
    **/
    function get_cart_item_booking_data_from_session( $session_data, $values ) {

        if ( isset( $values['_booking_price'] ) ) {
            $session_data['_booking_price'] = $values['_booking_price'];
        }

        if ( isset( $values['_booking_duration'] ) ) {
            $session_data['_booking_duration'] = $values['_booking_duration'];
        }

        // Start date yyyy-mm-dd
        if ( isset( $values['_booking_start_date'] ) ) {
            $session_data['_booking_start_date'] = $values['_booking_start_date'];
        }

        // End date yyyy-mm-dd
        if ( isset( $values['_booking_end_date'] ) ) {
            $session_data['_booking_end_date'] = $values['_booking_end_date'];
        }

        $this->add_cart_item_booking_price( $session_data );
        
        return $session_data;

    }

    /**
    *
    * Add cart item booking data.
    *
    * @param array - $cart_item_meta
    * @param int - $product_id
    * @param int - $variation_id
    * @param int - $quantity
    * @return array $cart_item_meta
    *
    **/
    function add_cart_item_booking_data( $cart_item_meta, $product_id, $variation_id, $quantity ) {

        // Use $_REQUEST to allow $_POST and $_GET
        if ( isset( $_REQUEST ) && ! empty( $product_id ) ) {
            $post_data = $_REQUEST;
        } else {
            return $cart_item_meta;
        }

        $_product_id  = empty( $variation_id ) ? $product_id : $variation_id;
        $product      = wc_get_product( $product_id );
        $_product     = wc_get_product( $_product_id );

        // Return if product is not bookable, or if start date is not set
        if ( ! wceb_is_bookable( $_product ) || ! isset( $post_data['start_date_submit'] ) ) {
            return $cart_item_meta;
        }

        $start = $post_data['start_date_submit'];
        $end   = isset( $post_data['end_date_submit'] ) ? $post_data['end_date_submit'] : false;

        $booking_duration = Date_Selection::get_selected_booking_duration( $start, $end, $_product );

        $data = array(
            'start'    => $start,
            'duration' => $booking_duration,
            'quantity' => $quantity
        );

        if ( isset( $end ) && ! empty( $end ) ) {
            $data['end'] = $end;
        }

        $booking_data = Date_Selection::{'get_' . $product->get_type() . '_product_booking_data'}( $data, $product, $_product );

        $cart_item_meta['_booking_price']      = wc_format_decimal( $booking_data[$_product_id]['new_price'] );
        $cart_item_meta['_booking_start_date'] = sanitize_text_field( $post_data['start_date_submit'] );
        $cart_item_meta['_booking_end_date']   = sanitize_text_field( $post_data['end_date_submit'] );
        $cart_item_meta['_booking_duration']   = absint( $booking_duration );

        return apply_filters( 'easy_booking_add_cart_item_booking_data', $cart_item_meta );

    }

    /**
    *
    * Set cart item booking price.
    *
    * @param array $cart_item
    * @return array $cart_item
    *
    **/
    function add_cart_item_booking_price( $cart_item ) {

        if ( isset( $cart_item['_booking_price'] ) && $cart_item['_booking_price'] >= 0 ) {

            $cart_item['_booking_price'] = apply_filters(
                'easy_booking_set_booking_price',
                $cart_item['_booking_price'],
                $cart_item
            );

            // Filter for third-party plugins.
            $cart_item = apply_filters( 'easy_booking_cart_item', $cart_item );

            // Set product price.
            $cart_item['data']->set_price( (float) $cart_item['_booking_price'] );

        }

        return $cart_item;

    }
 
    /**
    *
    * Display formatted dates in cart.
    *
    * @param array $other_data
    * @param array $cart_item
    * @return array $other_data
    *
    **/
    function display_booking_dates_in_cart( $other_data, $cart_item ) {

        // For bundles, only display dates on parent product.
        if ( isset( $cart_item['bundled_by'] ) ) {
            return $other_data;
        }

        if ( isset( $cart_item['_booking_start_date'] ) && ! empty ( $cart_item['_booking_start_date'] ) ) {

            $other_data[] = array(
                'name'  => esc_html( wceb_get_start_text( $cart_item['data'] ) ),
                'value' => date_i18n( get_option( 'date_format' ), strtotime( $cart_item['_booking_start_date'] ) )
            );

        }

        if ( isset( $cart_item['_booking_end_date'] ) && ! empty ( $cart_item['_booking_end_date'] ) ) {

            $other_data[] = array(
                'name'  => esc_html( wceb_get_end_text( $cart_item['data'] ) ),
                'value' => date_i18n( get_option( 'date_format' ), strtotime( $cart_item['_booking_end_date'] ) )
            );

        }

        return $other_data;

    }

}

new Cart();