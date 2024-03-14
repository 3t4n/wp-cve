<?php

namespace EasyBooking;

/**
*
* Frontend AJAX.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Ajax {

	public function __construct() {
        
        add_action( 'init', array( $this, 'define_ajax' ), 0 );
        add_action( 'template_redirect', array( $this, 'do_wceb_ajax' ), 0 );

		add_action( 'wp_ajax_set_booking_session', array( $this, 'set_booking_session' ) );
        add_action( 'wp_ajax_nopriv_set_booking_session', array( $this, 'set_booking_session' ) );
        add_action( 'wceb_ajax_set_booking_session', array( $this, 'set_booking_session' ) );

	}


    /**
    *
    * Set WCEB AJAX constant and headers.
    * @author Credits to WooCommerce
    *
    **/
    public static function define_ajax() {

        if ( isset( $_GET['wceb-ajax'] ) && ! empty( $_GET['wceb-ajax'] ) ) {

            if ( ! defined( 'DOING_AJAX' ) ) {
                define( 'DOING_AJAX', true );
            }

            if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
                @ini_set( 'display_errors', 0 ); // Turn off display_errors during AJAX events to prevent malformed JSON
            }

            $GLOBALS['wpdb']->hide_errors();
        }

    }

    /**
    *
    * Check for WCEB Ajax request and fire action.
    * @author Credits to WooCommerce
    *
    **/
    public static function do_wceb_ajax() {
        global $wp_query;

        if ( isset( $_GET['wceb-ajax'] ) && ! empty( $_GET['wceb-ajax'] ) ) {
            $wp_query->set( 'wceb-ajax', sanitize_text_field( wp_unslash( $_GET['wceb-ajax'] ) ) );
        }

        $action = $wp_query->get( 'wceb-ajax' );

        if ( $action ) {

            self::ajax_headers();
            $action = sanitize_text_field( $action );
            do_action( 'wceb_ajax_' . $action );
            wp_die();

        }

    }

    /**
    *
    * Send headers for WCEB Ajax Requests.
    * @author Credits to WooCommerce
    *
    **/
    private static function ajax_headers() {

        if ( ! headers_sent() ) {

            send_origin_headers();
            send_nosniff_header();
            wc_nocache_headers();
            header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
            header( 'X-Robots-Tag: noindex' );
            status_header( 200 );

        } elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

            headers_sent( $file, $line );
            trigger_error( "wceb_ajax_headers cannot set headers - headers already sent by {$file} on line {$line}", E_USER_NOTICE );
            
        }

    }

    /**
    *
    * Calculate booking price, set booking session and update fragments.
    *
    **/
    public static function set_booking_session() {

        /**
        *
        * Security check.
        * This can cause issues with cache plugins. Disable caching on product pages to avoid it.
        *
        **/

        if ( ! check_ajax_referer( 'set-dates', 'security', false ) ) {
            self::get_error_fragments( __( 'Sorry there was a problem. Please try again.', 'woocommerce-easy-booking-system' ) );
        }

        /**
        *
        * Get data.
        *
        **/

        $product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : ''; // Product ID
        $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : ''; // Variation ID
        $children     = isset( $_POST['children'] ) ? array_map( 'absint', $_POST['children'] ) : array(); // Product children for grouped and variable products

        $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1; // Product quantity
        
        $id = ! empty( $variation_id ) ? $variation_id : $product_id; // Product or variation id

        $start = isset( $_POST['start_format'] ) ? sanitize_text_field( $_POST['start_format'] ) : ''; // Start date 'yyyy-mm-dd'
        $end   = isset( $_POST['end_format'] ) ? sanitize_text_field( $_POST['end_format'] ) : ''; // End date 'yyyy-mm-dd'

        $product  = wc_get_product( $product_id ); // Product object
        $_product = ( $product_id !== $id ) ? wc_get_product( $id ) : $product; // Product or variation object

        /**
        *
        * Check data.
        *
        **/

        if ( ! $product || ! $_product || ! wceb_is_bookable( $_product ) ) {
            self::get_error_fragments( __( 'Please select a product.', 'woocommerce-easy-booking-system' ) );
        }

        // If product is variable and no variation was selected
        if ( $product->is_type( 'variable' ) && empty( $variation_id ) ) {
            self::get_error_fragments( __( 'Please select product option.', 'woocommerce-easy-booking-system' ) );
        }

        // If product is grouped and no quantity was selected for grouped products
        if ( $product->is_type( 'grouped' ) && empty( $children ) ) {
            self::get_error_fragments( __( 'Please choose the quantity of items you wish to add to your cart&hellip;', 'woocommerce' ) );
        }

        /**
        *
        * Check dates.
        *
        **/

        $valid_dates = Date_Selection::check_selected_dates( $start, $end, $_product );

        // Handle errors
        if ( is_wp_error( $valid_dates ) ) {
            self::get_error_fragments( $valid_dates );
        }

        /**
        *
        * Get booking duration.
        *
        **/

        $duration = Date_Selection::get_selected_booking_duration( $start, $end, $_product );

        // Handle errors
        if ( is_wp_error( $duration ) ) {
            self::get_error_fragments( $duration );
        }

        /**
        *
        * Calculate booking price.
        *
        **/

        // Store data in array
        $data = array(
            'start'    => $start,
            'duration' => $duration,
            'quantity' => $quantity
        );

        // Maybe store end date
        if ( isset( $end ) && ! empty( $end ) ) {
            $data['end'] = $end;
        }

        // Get booking data for each product type (new price, new regular price)
        $booking_data = Date_Selection::{'get_' . $product->get_type() . '_product_booking_data'}( $data, $product, $_product, $children );

        /**
        *
        * Return price to frontend.
        *
        **/

        self::get_success_fragments( $id, $booking_data );

        die();

    }

    /**
    *
    * Return error fragments and clear booking session.
    * @param WP_Error | str - $error_code
    *
    **/
    private static function get_error_fragments( $error ) {
        
        $error_message = is_wp_error( $error ) ? $error->get_error_message() : $error;

        $fragments = array(
            'error' => esc_html( $error_message )
        );

        wp_send_json( $fragments );
        die();

    }

    /**
    *
    * Return fragments after booking session is successfully set.
    * @param int - $id - Product or variation ID
    * @param array - $booking_data
    *
    **/
    private static function get_success_fragments( $id, $booking_data ) {
        
        $product = wc_get_product( $id );

        // Check that booking data is set for the main product.
        if ( ! isset( $booking_data[$id] ) ) {
            self::get_error_fragments( __( 'Sorry there was a problem. Please try again.', 'woocommerce-easy-booking-system' ) );
        }
        
        $new_price         = 0;
        $new_regular_price = 0;

        /* 
        * Get total booking price and regular price.
        * Include children prices for grouped and bundle products.
        * Multiply by quantity.
        */
        foreach ( $booking_data as $_product_id => $_product_booking_data ) {

            $_product = $_product_id === $id ? $product : wc_get_product( $_product_id );

            // Tweak for bundles, because quantity field is for the whole bundle.
            $bundle_qty = $product->is_type( 'bundle' ) && ! $_product->is_type( 'bundle' ) ? $booking_data[$id]['quantity'] : 1;

            $qty = apply_filters( 'easy_booking_selected_quantity', $_product_booking_data['quantity'] * $bundle_qty, $_product_id, $_product_booking_data );

            $args = array(
                'price' => apply_filters( 'easy_booking_new_price_to_display', $_product_booking_data['new_price'], $_product_id, $_product_booking_data ),
                'qty'   => $qty
            );

            $price_to_display = wc_get_price_to_display( $product, $args );

            $new_price += $price_to_display;

            if ( isset( $_product_booking_data['new_regular_price'] ) ) {

                $args = array(
                    'price' => apply_filters( 'easy_booking_new_regular_price_to_display', $_product_booking_data['new_regular_price'], $_product_id, $_product_booking_data ),
                    'qty'   => $qty
                );
                $regular_price_to_display = wc_get_price_to_display( $product, $args );

            } else {
                $regular_price_to_display = $price_to_display;
            }

            $new_regular_price += $regular_price_to_display;

        }

        // Get booking details.
        $details = Date_Selection::get_booking_price_details( $product, $booking_data[$id], $new_price );

        $fragments = array(
            'fragments' => apply_filters(
                'easy_booking_fragments',
                array(
                    'session'               => true,
                    'booking_price'         => esc_attr( $new_price ),
                    'booking_regular_price' => ( $new_regular_price != $new_price ) ? esc_attr( $new_regular_price ) : '',
                    'p.booking_details'     => '<p class="booking_details">' . wp_kses_post( $details ) . '</p>',
                    'input.wceb_nonce'      => '<input type="hidden" name="_wceb_nonce" class="wceb_nonce" value="' . wp_create_nonce( 'set-dates' ) . '">'
                ),
                $booking_data,
                $product
            )
        );

        wp_send_json( $fragments );
        die();

    }
    
}

new Ajax();