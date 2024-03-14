<?php

/**
*
* Template hooks for product pages.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Display datepickers on single product pages.
*
**/
function wceb_single_product_html() {
    global $product;
    
    // Product is bookable
    if ( wceb_is_bookable( $product ) ) {
        
        $start_date_text = wceb_get_start_text( $product );
        $end_date_text   = wceb_get_end_text( $product );
        $number_of_dates = wceb_get_product_number_of_dates_to_select( $product );

        include_once( wceb_load_template( 'includes/views', 'html-wceb-single-product.php' ) );
       
    }

}

add_action( 'woocommerce_before_add_to_cart_button', 'wceb_single_product_html', 20 );

/**
*
* Use a different hook to display datepickers on variable product pages.
*
**/
function wceb_variable_product_html() {
    remove_action( 'woocommerce_before_add_to_cart_button', 'wceb_single_product_html', 20 );
    add_action( 'woocommerce_single_variation', 'wceb_single_product_html', 18 );
}

add_action( 'woocommerce_before_variations_form', 'wceb_variable_product_html', 10 );

/**
*
* Display a custom link on shop page for bookable products.
*
* @param str | $content
* @param WC_Product | $product
* @return str
*
**/
function wceb_loop_add_to_cart_link( $content, $product ) {

    if ( wceb_is_bookable( $product ) ) {

        $text = apply_filters( 'easy_booking_select_dates_text', __( 'Select date(s)', 'woocommerce-easy-booking-system' ), $product );

        return apply_filters(
            'easy_booking_loop_add_to_cart_link',
            sprintf(
                '<a href="%s" rel="nofollow" class="button">%s</a>',
                esc_url( get_permalink( $product->get_id() ) ),
                esc_html( $text )
            ),
            $product, $text
        );
        
    }
    
    return $content;
    
}

add_filter( 'woocommerce_loop_add_to_cart_link', 'wceb_loop_add_to_cart_link', 10, 2 );