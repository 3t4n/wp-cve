<?php

namespace EasyBooking;

/**
*
* All functions related to WooCommerce Product Bundles.
* @version 2.2.8
*
**/

defined( 'ABSPATH' ) || exit;

class Pb_Functions {

    /**
    *
    * Gets the bundle item corresponding to the bundle product and child product.
    * @param WC_Product_Bundle - $product
    * @param WC_Product | WC_Product_Variation - $child
    * @return WC_Bundled_Item
    *
    **/
    public static function get_corresponding_bundled_item( $product, $child ) {

        if ( ! $product->is_type( 'bundle' ) ) {
            return false;
        }

        $child_id = $child->get_id();
        $bundled_items = $product->get_bundled_items();

        if ( $bundled_items ) foreach ( $bundled_items as $bundled_item ) {

            if ( $child->is_type( 'variation' ) && $bundled_item->get_product_variations() ) {

                foreach ( $bundled_item->get_product_variations() as $variation ) {
                    
                    if ( $variation['variation_id'] == $child_id ) {
                        return $bundled_item;                   
                    }

                }

            } else {

                if ( $bundled_item->get_product_id() == $child_id ) {
                    return $bundled_item;  
                }

            }

        }

        return false;

    }

}