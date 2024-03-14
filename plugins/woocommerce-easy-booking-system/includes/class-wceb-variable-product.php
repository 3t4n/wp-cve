<?php

namespace EasyBooking;

/**
*
* Variations on frontend.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Variable_Product {

	public function __construct() {
        
        add_filter( 'woocommerce_available_variation', array( $this, 'bookable_variation_data' ), 10, 3 );
        add_filter( 'woocommerce_show_variation_price', array( $this, 'hide_bookable_variation_price' ), 10, 3 );
        
	}

	/**
    *
    * Add data to bookable variations.
    *
    * @param array - $available_variations
    * @param WC_Product - $product
    * @param WC_Product_Variation - $variation
    * @return array - $available_variations
    *
    **/
    public function bookable_variation_data( $available_variations, $product, $variation ) {

        $available_variations['is_bookable']     = (bool) wceb_is_bookable( $variation );
        $available_variations['number_of_dates'] = esc_html( wceb_get_product_number_of_dates_to_select( $variation ) );
        
        return $available_variations;

    }

    /**
    *
    * Hide bookable variation price on single product pages. It will be displayed with Javascript.
    *
    * @param bool - $display
    * @param WC_Product - $product
    * @param WC_Product_Variation - $variation
    * @return bool - $display
    *
    **/
    public function hide_bookable_variation_price( $display, $product, $variation ) {

        if ( wceb_is_bookable( $variation ) ) {
            $display = false;
        }

        return $display;

    }

}

new Variable_Product();