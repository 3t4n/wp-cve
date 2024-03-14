<?php

/**
*
* Bookable product functions.
* @version 3.1.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Returns whether the product is bookable or not.
* @param mixed int | WC_Product or WC_Product_Variation - $product
* @return bool
*
**/
function wceb_is_bookable( $_product ) {

    // If product ID was passed, get the product
    if ( is_numeric( $_product ) ) {
        $_product = wc_get_product( $_product );
    }

    if ( ! $_product ) {
        return false;
    }

    $product_type = $_product->get_type();

    // Check the product type
    $allowed_product_types   = wceb_get_allowed_product_types();
    $allowed_product_types[] = 'variation';

    $is_bookable = $_product->get_meta( '_bookable', true );

    // Check that variable products have at least one bookable variation.
    if ( $product_type === 'variable' ) {

        $has_bookable_variation = false;
        foreach ( $_product->get_children() as $variation_id ) {

            $variation = wc_get_product( $variation_id );

            if ( wceb_is_bookable( $variation ) ) {
                $has_bookable_variation = true;
                break;
            }

        }

        if ( ! $has_bookable_variation ) {
            return false;
        }

    }

    // If $_product is a variation, make sure the parent product is bookable aswell.
    if ( $product_type === 'variation' ) {

        $parent_product_id = $_product->get_parent_id();
        $parent_product    = wc_get_product( $parent_product_id );

        if ( ! is_a( $parent_product, 'WC_Product' ) ) {
            return false;
        }

        if ( 'yes' !== $parent_product->get_meta( '_bookable', true ) ) {
            return false;
        }

    }

    $is_bookable = ( $is_bookable === 'yes' && in_array( $product_type, $allowed_product_types ) ) ? true : false;

    return apply_filters( 'easy_booking_product_is_bookable', $is_bookable, $_product );

}

/**
*
* Get product booking settings.
*
* @param WC_Product or WC_Product_Variation - $_product
* @return array - $booking_settings
*
**/
function wceb_get_product_booking_settings( $_product ) {

    $booking_settings = array(
        'booking_dates'           => wceb_get_product_number_of_dates_to_select( $_product ),
        'booking_duration'        => wceb_get_product_booking_duration( $_product ),
        'booking_min'             => wceb_get_product_minimum_booking_duration( $_product ),
        'booking_max'             => wceb_get_product_maximum_booking_duration( $_product ),
        'first_available_date'    => wceb_get_product_first_available_date( $_product )
    );

    return $booking_settings;

}

/**
*
* Get product minimum booking duration.
* @param WC_Product or WC_Product_Variation - $_product
* @return int - $booking_min
*
**/
function wceb_get_product_minimum_booking_duration( $_product ) {

    // If product setting is empty or not defined, get global setting
    $global_booking_min  = get_option( 'wceb_booking_min' );
    $product_booking_min = $_product->get_meta( '_booking_min', true );

    if ( $_product->is_type( 'variation' ) && ( ! isset( $product_booking_min ) || $product_booking_min === '' ) ) {

        $parent_product_id = $_product->get_parent_id();
        $parent_product = wc_get_product( $parent_product_id );

        $product_booking_min = $parent_product->get_meta( '_booking_min', true );

    }

    $booking_min = isset( $product_booking_min ) && $product_booking_min !== '' ? $product_booking_min : $global_booking_min;

    // Get booking mode - Days or Nights
    $booking_mode = get_option( 'wceb_booking_mode' );

    // Get booking duration
    $booking_duration = wceb_get_product_booking_duration( $_product );

    // Multiply by booking duration (remove 1 day in "Days" mode)
    $booking_min = $booking_mode === 'days' ? $booking_min * $booking_duration - 1 : $booking_min * $booking_duration;

    // Force 1 in Nights mode and 0 in Days mode.
    if ( $booking_mode === 'nights' && $booking_min <= 0 ) {
        $booking_min = 1;
    } else if ( $booking_mode === 'days' && $booking_min < 0 ) {
        $booking_min = 0;
    }

    return apply_filters( 'easy_booking_product_minimum_booking_duration', $booking_min, $_product );

}

/**
*
* Get product maximum booking duration.
* @param WC_Product or WC_Product_Variation - $_product
* @return int - $booking_max
*
**/
function wceb_get_product_maximum_booking_duration( $_product ) {

    // If product setting is empty or not defined, get global setting
    $global_booking_max  = get_option( 'wceb_booking_max' );
    $product_booking_max = $_product->get_meta( '_booking_max', true );

    if ( $_product->is_type( 'variation' ) && ( ! isset( $product_booking_max ) || $product_booking_max === '' ) ) {

        $parent_product_id = $_product->get_parent_id();
        $parent_product = wc_get_product( $parent_product_id );

        $product_booking_max = $parent_product->get_meta( '_booking_max', true );

    }
    
    $booking_max = isset( $product_booking_max ) && $product_booking_max !== '' ? $product_booking_max : $global_booking_max;

    // Get booking mode - Days or Nights
    $booking_mode = get_option( 'wceb_booking_mode' );

    // Get booking duration
    $booking_duration = wceb_get_product_booking_duration( $_product );

    // Multiply by booking duration (remove 1 day in "Days" mode)
    $booking_max = $booking_mode === 'days' ? $booking_max * $booking_duration - 1 : $booking_max * $booking_duration;

    if ( $booking_max <= 0 ) {
        $booking_max = false;
    }

    return apply_filters( 'easy_booking_product_maximum_booking_duration', $booking_max, $_product );

}

/**
*
* Get product first available date.
* @param WC_Product or WC_Product_Variation - $_product
* @return int - $first_available_date
*
**/
function wceb_get_product_first_available_date( $_product ) {

    // If product setting is empty or not defined, get global setting
    $global_first_available_date  = get_option( 'wceb_first_available_date' );
    $product_first_available_date = $_product->get_meta( '_first_available_date', true );

    if ( $_product->is_type( 'variation' ) && ( ! isset( $product_first_available_date ) || $product_first_available_date === '' ) ) {

        $parent_product_id = $_product->get_parent_id();
        $parent_product = wc_get_product( $parent_product_id );

        $product_first_available_date = $parent_product->get_meta( '_first_available_date', true );

    }
    
    $first_available_date = isset( $product_first_available_date ) && $product_first_available_date !== '' ? $product_first_available_date : $global_first_available_date;

    return apply_filters( 'easy_booking_product_first_available_date', $first_available_date, $_product );

}

/**
*
* Get product booking duration.
*
* @param WC_Product or WC_Product_Variation - $_product
* @return str - $booking_duration
*
**/
function wceb_get_product_booking_duration( $_product ) {
    
    $booking_duration = $_product->get_meta( '_booking_duration', true );

    // For grouped products and bundles single product pages, force the parent product value.
    if ( is_product() ) {

        // Get queried product ID. For grouped and bundled products, it will return the parent product ID.
        $current_id      = get_queried_object_id();
        $queried_product = wc_get_product( $current_id );
        
        // If it is a children or bundled product on the parent product page, return the parent data.
        if ( ( $queried_product->is_type( 'grouped' ) || $queried_product->is_type( 'bundle' ) ) && $current_id !== $_product->get_id() ) {
            $booking_duration = $queried_product->get_meta( '_booking_duration', true );
        }

    }

    // If it is a variation with the parent product settings.
    if ( $_product->is_type( 'variation' ) && empty( $booking_duration ) ) {
        
        // Get parent product
        $parent_product   = wc_get_product( $_product->get_parent_id() );
        $booking_duration = $parent_product->get_meta( '_booking_duration', true );

    }

    if ( empty( $booking_duration ) ) {
        $booking_duration = get_option( 'wceb_booking_duration' );
    }

    // Backward compatibilty to avoid errors
    if ( ! is_numeric( $booking_duration ) || $booking_duration <= 0 ) {
        return 1;
    }

    return apply_filters( 'easy_booking_product_booking_duration', $booking_duration, $_product );

}

/**
*
* Gets number of dates to select for the product.
*
* @param WC_Product or WC_Product_Variation - $_product
* @return str - $number_of_dates
*
**/
function wceb_get_product_number_of_dates_to_select( $_product ) {

    $number_of_dates = $_product->get_meta( '_number_of_dates', true );

    // For grouped products and bundles single product pages, force the parent product value.
    if ( is_product() ) {

    	// Get queried product ID. For grouped and bundled products, it will return the parent product ID.
    	$queried_id = get_queried_object_id();
    	$queried_product = wc_get_product( $queried_id );
        
    	// If it is a children or bundled product on the parent product page, return the parent data.
	    if ( ( $queried_product->is_type( 'grouped' ) || $queried_product->is_type( 'bundle' ) ) && $queried_id !== $_product->get_id() ) {
            $number_of_dates = $queried_product->get_meta( '_number_of_dates', true );
	    }
	    
	}

    // If it is a variation with the parent product settings.
    if ( $_product->is_type( 'variation' ) && ( $number_of_dates === 'parent' || empty( $number_of_dates ) ) ) {

        // Get parent product
        $parent_product_id = $_product->get_parent_id();
        $parent_product = wc_get_product( $parent_product_id );

        if ( $parent_product ) {
            $number_of_dates = $parent_product->get_meta( '_number_of_dates', true );
        }

    }

    if ( empty( $number_of_dates ) || $number_of_dates === 'global' ) {
    	$number_of_dates = get_option( 'wceb_number_of_dates' );
    }

    return apply_filters( 'easy_booking_product_number_of_dates_to_select', $number_of_dates, $_product );

}

/**
*
* Gets the price suffix for bookable products (e.g. "/ day").
*
* @param WC_Product or WC_Product_Variation - $_product
* @return str
*
**/
function wceb_get_product_price_suffix( $_product ) {

    $suffix = '';

    $number_of_dates  = wceb_get_product_number_of_dates_to_select( $_product );
    $booking_duration = wceb_get_product_booking_duration( $_product );
    
    // For variable products, price html will be displayed in Javascript for each variation
    if ( $number_of_dates === 'two' && ! $_product->is_type( 'variable' ) ) {

        $booking_mode = get_option( 'wceb_booking_mode' );
        $suffix = $booking_mode === 'nights' ? sprintf( _n( ' / night', ' / %s nights', $booking_duration, 'woocommerce-easy-booking-system' ), $booking_duration ) : sprintf( _n( ' / day', ' / %s days', $booking_duration, 'woocommerce-easy-booking-system' ), $booking_duration );

    }

    // Backward compatibility
    $suffix = apply_filters_deprecated( 'easy_booking_get_price_html', array( $suffix, $_product, $booking_duration, $booking_duration ), '2.2.7', 'easy_booking_get_price_suffix' );

    // Last argument = $custom_booking_duration for backward compatibility
    return apply_filters( 'easy_booking_get_price_suffix', $suffix, $_product, $booking_duration, $booking_duration );

}

/**
*
* Get children IDs of grouped and bundle products.
*
* @param WC_Product or WC_Product_Variation - $_product
* @return array
*
**/
function wceb_get_product_children_ids( $product ) {

    $product_children = array();

    if ( $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) ) {

        $product_children = $product->get_children();  
        
    } else if ( $product->is_type( 'bundle' ) ) {

        $bundled = $product->get_bundled_item_ids();
        $product_children = array();

        if ( $bundled ) foreach ( $bundled as $bundled_item_id ) {

            $bundled_item = $product->get_bundled_item( $bundled_item_id );
            $_product = $bundled_item->product;
            
            if ( $_product->is_type( 'variable' ) ) {
                $variations = $_product->get_children();

                foreach ( $variations as $variation_id ) {
                    $product_children[] = $variation_id;
                }
            }

            $product_children[] = $_product->get_id();

        }

        // Add main bundle product
        $product_children[] = $product->get_id();

    }

    return $product_children;

}