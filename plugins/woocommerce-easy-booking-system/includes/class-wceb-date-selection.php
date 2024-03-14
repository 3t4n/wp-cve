<?php

namespace EasyBooking;

/**
*
* Date selection.
* @version 3.1.8
*
**/

defined( 'ABSPATH' ) || exit;

class Date_Selection {

    /**
    *
    * Check selected dates.
    * @param str - $start
    * @param str - $end
    * @param WC_Product | WC_Product_Variation - $_product
    * @return bool | WP_Error
    *
    **/
    public static function check_selected_dates( $start, $end, $_product ) {

        // Make sure all datetimes are in the same timezone.
        date_default_timezone_set( 'UTC' );

        $current_date = date( 'Y-m-d' ); // Get current date to check for valid dates.

        // Add first available date parameter to current date.
        $first_available_date = wceb_get_product_first_available_date( $_product );
        $first_date           = wceb_shift_date( $current_date, $first_available_date );

        $booking_mode = get_option( 'wceb_booking_mode' ); // Booking mode (Days or Nights)
        $dates_format = wceb_get_product_number_of_dates_to_select( $_product );

        if ( $dates_format === 'one' ) {

            // If start is not set, return false.
            if ( ! wceb_is_valid_date( $start ) || $start < $first_date ) {
                return new \WP_Error( 'easy_booking_invalid_dates', __( 'Please select a valid date.', 'woocommerce-easy-booking-system' ), 'error' );
            }

        } else if ( $dates_format === 'two' ) {
    
            // If start and/or end are not set, return false.
            if ( ! wceb_is_valid_date( $start ) || ! wceb_is_valid_date( $end ) || $start < $first_date || $end < $start ) {
                return new \WP_Error( 'easy_booking_invalid_dates', __( 'Please select valid dates.', 'woocommerce-easy-booking-system' ), 'error' );
            }

            if ( $booking_mode === 'nights' && $start === $end ) {
                return new \WP_Error( 'easy_booking_invalid_dates', __( 'Please select valid dates.', 'woocommerce-easy-booking-system' ), 'error' );
            }

        }

        return true;

    }

    /**
    *
    * Get and check selected booking duration after selecting dates.
    * @param str - $start
    * @param str - $end
    * @param WC_Product | WC_Product_Variation - $_product
    * @return int | WP_Error
    *
    **/
    public static function get_selected_booking_duration( $start, $end, $_product ) {

        $booking_mode     = get_option( 'wceb_booking_mode' ); // Booking mode (Days or Nights)
        $booking_duration = wceb_get_product_booking_duration( $_product );

        // One-date selection: booking duration is always 1
        if ( empty( $end ) || ! $end ) {
            return 1;
        }

        // Get booking duration in days
        $duration = intval( ( strtotime( $end ) - strtotime( $start ) ) / 86400 );

        // If booking mode is set to "Days", add one day
        if ( $booking_mode === 'days' ) {
            $duration += 1 ;
        }

        // Make sure booking duration is correct
        if ( $duration % $booking_duration !== 0 || $duration <= 0 ) {
            return new \WP_Error( 'easy_booking_invalid_dates', __( 'Please choose valid dates', 'woocommerce-easy-booking-system' ) );
        }

        return apply_filters( 'easy_booking_selected_booking_duration', $duration / $booking_duration, $start, $end, $_product );

    }

    /**
    *
    * Get simple product booking price.
    * @param array - $data
    * @param WC_Product - $product
    * @param WC_Product - $_product
    * @return array - $booking_data
    *
    **/
    public static function get_simple_product_booking_data( $data, $product, $_product ) {

        $booking_data = array();
        $_product_id  = $_product->get_id();

        // Get product price and (if on sale) regular price
        foreach ( array( 'price', 'regular_price' ) as $price_type ) {

            $price = $_product->{'get_' . $price_type}();

            if ( $price === '' ) {
                continue;
            }

            ${'new_' . $price_type} = self::calculate_booking_price( $price, $data, $price_type, $product, $_product );

        }

        $data['new_price'] = $new_price;

        if ( isset( $new_regular_price ) && ! empty( $new_regular_price ) && ( $new_regular_price !== $new_price ) ) {
            $data['new_regular_price'] = $new_regular_price;
        }

        $booking_data[$_product_id] = $data;

        return apply_filters( 'easy_booking_simple_product_booking_data', $booking_data, $product );

    }

    /**
    *
    * Get variable product booking price.
    * @param array - $data
    * @param WC_Product - $product
    * @param WC_Product_Variation - $_product
    * @return array - $booking_data
    *
    **/
    public static function get_variable_product_booking_data( $data, $product, $_product ) {

        $booking_data = self::get_simple_product_booking_data( $data, $product, $_product );
        return apply_filters( 'easy_booking_variable_product_booking_data', $booking_data, $product, $_product );

    }

    /**
    *
    * Get grouped product booking price.
    * @param array - $data
    * @param WC_Product - $product
    * @param WC_Product | WC_Product_Variation - $_product
    * @param array - $children
    * @return array - $booking_data
    *
    **/
    public static function get_grouped_product_booking_data( $data, $product, $_product, $children = array() ) {

        $booking_data = array();
        $new_price = 0;
        $new_regular_price = 0;

        $_product_id = $_product->get_id();

        foreach ( $children as $child_id => $quantity ) {

            if ( $quantity <= 0 || ( $child_id === $_product_id ) ) {
                continue;
            }

            $child = wc_get_product( $child_id );

             foreach ( array( 'price', 'regular_price' ) as $price_type ) {

                $price = $child->{'get_' . $price_type}();

                if ( $price === '' ) {
                    continue;
                }

                // Multiply price by duration only if children is bookable
                ${'child_new_' . $price_type} = self::calculate_booking_price( $price, $data, $price_type, $product, $child );

            }

            $data['new_price'] = $child_new_price;

            if ( isset( $child_new_regular_price ) && ! empty( $child_new_regular_price ) && ( $child_new_regular_price !== $child_new_price ) ) {
                $data['new_regular_price'] = $child_new_regular_price;
            }

            // Store child booking data
            $booking_data[$child_id] = $data;

            $booking_data[$child_id]['quantity'] = $quantity;

            // Make sure to set parent product price to 0 and remove regular price (parent product has no price).
            $data['new_price'] = 0;
            unset( $data['new_regular_price'] );

        }

        // Store parent product data
        $booking_data[$_product_id] = $data;
        
        return apply_filters( 'easy_booking_grouped_product_booking_data', $booking_data, $product, $children );

    }
  
  	/**
    *
    * Get bundle product booking price.
    * @param array - $data
    * @param WC_Product - $product
    * @param WC_Product | WC_Product_Variation - $_product
    * @param array - $children
    * @return array - $booking_data
    *
    **/
    public static function get_bundle_product_booking_data( $data, $product, $_product, $children = array() ) {

        $booking_data = array();

        $_product_id = $_product->get_id();

        if ( ! empty( $children ) ) foreach ( $children as $child_id => $quantity ) {

            // Parent ID is in $children array for technical reasons, but is not a child.
            if ( $child_id === $_product_id ) {
                continue;
            }

            $child = wc_get_product( $child_id );
            $bundled_item = class_exists( 'EasyBooking\Pb_Functions' ) ? Pb_Functions::get_corresponding_bundled_item( $product, $child ) : false;

            // Return if no bundled item or if quantity is 0
            if ( ! $bundled_item || $quantity <= 0 ) {
                continue;
            }

            if ( $bundled_item->is_priced_individually() ) {

                $child = wc_get_product( $child_id );

                foreach ( array( 'price', 'regular_price' ) as $price_type ) {

                    $price = $child->{'get_' . $price_type}();

                    if ( empty( $price ) ) {
                        continue;
                    }

                    // Maybe apply bundle discount.
                    $discount = $bundled_item->get_discount();

                    if ( isset( $discount ) && ! empty( $discount ) ) {
                        $price -= ( $price * $discount / 100 );
                    }

                    // Multiply price by duration only if product is bookable
                    ${'child_new_' . $price_type} = self::calculate_booking_price( $price, $data, $price_type, $product, $child );

                }

            } else { // Tweak for not individually priced bundled products
                
                $child_new_price = 0;
                $child_new_regular_price = 0;

            }

            $data['new_price'] = $child_new_price;
            $data['new_regular_price'] = isset( $child_new_regular_price ) ? $child_new_regular_price : 0;

            $booking_data[$child_id] = $data;

            // Store parent product
            $booking_data[$child_id]['grouped_by'] = $_product_id;

            // Store child quantity
            $booking_data[$child_id]['quantity'] = $quantity;

        }

        // Get parent product price and (if on sale) regular price
        foreach ( array( 'price', 'regular_price' ) as $price_type ) {

            $price = $product->{'get_' . $price_type}();

            if ( empty( $price ) ) {
                continue;
            }

            ${'new_' . $price_type} = self::calculate_booking_price( $price, $data, $price_type, $product, $_product );

        }

        $data['new_price'] = isset( $new_price ) ? $new_price : 0;

        if ( isset( $new_regular_price ) && ! empty( $new_regular_price ) && ( $new_regular_price !== $new_price ) ) {
            $data['new_regular_price'] = $new_regular_price;
        } else {
            unset( $data['new_regular_price'] ); // Unset value in case it was set for a child product
        }

        $booking_data[$_product_id] = $data;

        return apply_filters( 'easy_booking_bundle_product_booking_data', $booking_data, $product, $children );

    }

    /**
    *
    * Calculate product booking price.
    * @param str - $price
    * @param array - $data
    * @param str - $price_type
    * @param WC_Product - $product
    * @param WC_Product | WC_Product_Variation - $_product
    * @return str - $price
    *
    **/
	public static function calculate_booking_price( $price, $data, $price_type, $product, $_product ) {

        if ( true === wceb_is_bookable( $_product ) && apply_filters( 'easy_booking_calculate_booking_price', true, $_product ) ) {
                
            $number_of_dates = wceb_get_product_number_of_dates_to_select( $_product );
            $dates = $number_of_dates === 'one' ? 'one_date' : 'two_dates';

            $price = apply_filters(
                'easy_booking_' . $dates . '_price',
                $price * $data['duration'],
                $product, $_product, $data, $price_type
            );

        }

	    return apply_filters( 'easy_booking_new_' . $price_type, wc_format_decimal( $price ), $data, $product, $_product );

	}

	/**
    *
    * Get booking price details.
    * @param WC_Product - $product
    * @param array - $booking_data
    * @param str - $price_type
    * @return str - $details
    *
    **/
	public static function get_booking_price_details( $product, $booking_data, $new_price ) {

		$details = '';

        if ( wceb_get_product_booking_dates( $product ) === 'two' ) {

            $duration = $booking_data['duration'];
            $average_price = floatval( $new_price / $duration );

            $booking_mode = get_option( 'wceb_booking_mode' );

            // Get total booking duration
            $booking_duration = wceb_get_product_booking_duration( $product );
            $duration *= $booking_duration;
            
            $unit = $booking_mode === 'nights' ? _n( 'night', 'nights', $duration, 'woocommerce-easy-booking-system' ) : _n( 'day', 'days', $duration, 'woocommerce-easy-booking-system' );

            $details .= apply_filters(
                'easy_booking_total_booking_duration_text',
                sprintf(
                    __( 'Total booking duration: %s %s', 'woocommerce-easy-booking-system' ),
                    absint( $duration ),
                    esc_html( $unit )
                ),
                $duration, $unit
            );

            // Maybe display average price (if there are price variations. E.g Duration discounts or custom pricing)
            if ( true === apply_filters( 'easy_booking_display_average_price', false, $product->get_id() ) ) {
                $details .= '<br />';
                $details .= apply_filters(
                    'easy_booking_average_price_text',
                    sprintf(
                        __( 'Average price %s: %s', 'woocommerce-easy-booking-system' ),
                        wceb_get_product_price_suffix( $product ),
                        wc_price( $average_price )
                    ),
                    $product, $average_price
                );
            }
            
        }

        return apply_filters( 'easy_booking_booking_price_details', $details, $product, $booking_data );

	}

}