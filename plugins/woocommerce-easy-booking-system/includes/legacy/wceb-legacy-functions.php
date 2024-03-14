<?php

/**
*
* Deprecated functions.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Gets product number of dates to select
*
* @deprecated 2.2.7 use wceb_get_product_number_of_dates_to_select() instead.
* @param WC_Product or WC_Product_Variation - $product
* @return str - $booking_dates
*
**/
function wceb_get_product_booking_dates( $product ) {
	return wceb_get_product_number_of_dates_to_select( $product );
}

/**
*
* Gets the price suffix for bookable products (e.g. "/ day").
*
* @deprecated 2.2.7 use wceb_get_product_price_suffix() instead.
* @param WC_Product or WC_Product_Variation - $product
* @return str - $price_html
*
**/
function wceb_get_price_html( $product ) {
	return wceb_get_product_price_suffix( $product );
}

/**
*
* Get product booking settings.
*
* @deprecated 2.2.7 use wceb_get_product_booking_settings() instead.
* @param WC_Product - $product
* @return array - $booking_settings
*
**/
function wceb_get_product_booking_data( $product ) {
	return wceb_get_product_booking_settings( $product );
}

/**
*
* Get variation booking settings.
*
* @deprecated 2.2.7 use wceb_get_product_booking_settings() instead.
* @param WC_Product_Variation - $variation
* @return array - $booking_settings
*
**/
function wceb_get_variation_booking_data( $variation ) {
	return wceb_get_product_booking_settings( $product );
}

/**
*
* Adjusts a given color
* Credits to someone on Stackoverflow for this function
*
* @deprecated 2.3.0 use wc_hex_darker() and wc_hex_lighter() instead.
* @param str $hex - The color
* @param int $steps
* @return str - New hex color
*
**/
function wceb_adjust_brightness( $hex, $steps ) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max( -255, min( 255, $steps ) );

    // Format the hex color string
    $hex = str_replace( '#', '', $hex );
    if ( strlen( $hex ) == 3) {
        $hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
    }

    // Get decimal values
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );

    // Adjust number of steps and keep it inside 0 to 255
    $r = max( 0, min( 255, $r + $steps ) );
    $g = max( 0, min( 255, $g + $steps ) );  
    $b = max( 0, min( 255, $b + $steps ) );

    $r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
    $g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
    $b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

    return '#' . $r_hex . $g_hex . $b_hex;
    
}

/**
*
* Get product price.
*
* @deprecated 2.3.0 use $product->get_price() and $product->get_regular_price() instead.
* @param WC_Product or WC_Product_Variation $product - The product or variation object
* @param WC_Product - $child - The product child (for grouped or bundled products)
* @param bool - $display - True if displaying the price on the front end
* @param str - $type - 'single' or 'array', 'single' will return the price, 'array' an array of regular and sale price if on sale
* @return str or array - $prices
*
**/
function wceb_get_product_price( $product, $child = false, $display = false, $type = 'single' ) {

    $prices_include_tax = get_option( 'woocommerce_prices_include_tax' );
    $tax_display_mode   = get_option( 'woocommerce_tax_display_shop' );

    $_product = ( $child ) ?  $child : $product;

    if ( $product->is_type( 'bundle' ) && $child ) {

        $id = $child->get_id();

        $bundled_items = $product->get_bundled_items();

        $ids = array();
        foreach ( $bundled_items as $bundled_item ) {

            if ( $bundled_item->get_product_variations() ) {

                foreach ( $bundled_item->get_product_variations() as $variation ) {
                    
                    if ( $variation['variation_id'] == $id ) {

                        if ( $bundled_item->is_priced_individually() ) {
                            $discount = $bundled_item->get_discount();
                        } else {
                            return false;
                        }
                        
                    }

                }

            } else {

                if ( $bundled_item->product_id == $id ) {

                    if ( $bundled_item->is_priced_individually() ) {
                        $discount = $bundled_item->get_discount();
                    } else {
                        return false;
                    }
                    
                }

            }

        }

        $regular_price = $child->get_regular_price();
        $price = $child->get_price();

        if ( isset( $discount ) && ! empty( $discount ) ) {
            $price -= ( $price * $discount / 100 );
        }

    } else {

        $regular_price = $_product->get_regular_price();
        $price = $_product->get_price();

    }

    if ( $type === 'array' ) {

        $prices = array( 'price' => '', 'regular_price' => '' );

        if ( true === $display ) {

            $args = array( 'price' => $price );
            $prices['price'] = ( $tax_display_mode === 'incl' ) ? wc_get_price_including_tax( $_product, $args ) : wc_get_price_excluding_tax( $_product, $args );

            $args = array( 'price' => $regular_price );
            $prices['regular_price'] = ( $tax_display_mode === 'incl' ) ? wc_get_price_including_tax( $_product, $args ) : wc_get_price_excluding_tax( $_product, $args );

        } else {

            $args = array( 'price' => $price );
            $prices['price'] = ( $prices_include_tax === 'yes' ) ? wc_get_price_including_tax( $_product, $args ) : wc_get_price_excluding_tax( $_product, $args );

            $args = array( 'price' => $regular_price );
            $prices['regular_price'] = ( $prices_include_tax === 'yes' ) ? wc_get_price_including_tax( $_product, $args ) : wc_get_price_excluding_tax( $_product, $args );

        }

        return $prices;

    } else {

        if ( true === $display ) {

            $args = array( 'price' => $price );
            return ( $tax_display_mode === 'incl' ) ? wc_get_price_including_tax( $_product, $args ) : wc_get_price_excluding_tax( $_product, $args );

        }

        $args = array( 'price' => $price );
        return ( $prices_include_tax === 'yes' ) ? wc_get_price_including_tax( $_product, $args ) : wc_get_price_excluding_tax( $_product, $args );

    }

}

/**
*
* Get product custom booking duration.
*
* @deprecated 3.0.0 use wceb_get_product_booking_duration() instead.
* @param WC_Product | WC_Product_Variation - $_product
* @return int
*
**/
function wceb_get_product_custom_booking_duration( $_product ) {
    return wceb_get_product_booking_duration( $_product );
}