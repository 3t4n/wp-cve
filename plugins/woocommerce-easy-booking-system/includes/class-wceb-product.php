<?php

namespace EasyBooking;

/**
*
* Products on frontend.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Product {

	public function __construct() {
        
        add_filter( 'woocommerce_get_price_html', array( $this, 'bookable_product_price_html' ), 10, 2 );
        
	}

    /**
    *
    * Displays a custom price if the product is bookable on the product page.
    *
    * @param str - $price
    * @param WC_Product - $product
    * @return str - $price
    *
    **/
    public function bookable_product_price_html( $price, $product ) {

        if ( wceb_is_bookable( $product ) ) {
            
            // Get price suffix (/ day, / 2 days, etc.)
            $suffix = wceb_get_product_price_suffix( $product );

            if ( empty( $suffix ) || $price === '' ) {
                return $price;
            }
            
            return apply_filters(
                'easy_booking_price_html',
                $price . '<span class="wceb-price-format">' . esc_html( $suffix ) . '</span>',
                $product,
                $price
            );

        }
        
        return $price;

    }
    
}

new Product();