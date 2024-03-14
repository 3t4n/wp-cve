<?php

namespace src\fortnox;

if ( !defined( 'ABSPATH' ) ) die();

class WF_Sku_Generator{

    /**
     * Generate and save a simple / parent product SKU from the product slug or ID.
     *
     * @param \WC_Product $product WC_Product object
     * @return string
     */
    protected static function generate_product_sku( $product ) {
        return preg_replace( '/[^\w-]/', '', get_post( $product->get_id() )->post_name );
    }


    /**
     * Generate and save a product variation SKU using the product slug or ID.
     *
     * @param array $variation product variation data
     * @return string
     */
    protected static function generate_variation_sku( $variation ) {

        $variation['attributes'] = str_replace( ' ', '_', $variation['attributes'] );
        $variation_sku = implode('_',  $variation['attributes']);
        $variation_sku = str_replace( 'attribute_', '', $variation_sku );

        return $variation_sku;
    }


    /**
     * Update the product with the generated SKU.
     *
     * @param \WC_Product|int $product WC_Product object or product ID
     * @return string
     */
    public static function set_new_sku( $product ) {

        if ( is_numeric( $product ) ) {
            $product = wc_get_product( absint( $product ) );
        }
        $product_sku = self::generate_product_sku( $product );

        if ( $product->is_type( 'variable'  ) ) {

            $variations = self::get_all_variations( $product->get_id() );

            foreach ( $variations as $variation_id ) {

                $product_variation = wc_get_product( $variation_id );

                if( ! $product_variation->get_sku() ){
                    $variation = $product->get_available_variation( $product_variation );

                    $variation_sku = self::generate_variation_sku( $variation );
                    $sku = $product_sku . '_' . $variation_sku;
                    $sku = substr( $sku, 0, 50 );

                    update_post_meta( $variation_id, '_sku', $sku );
                }
            }
        }
        $product_sku = substr( $product_sku, 0, 50 );
        update_post_meta( $product->get_id(), '_sku', $product_sku );

        return $product_sku;
    }


    /**
     * Return all variations for a product
     *
     * @param int $product_id
     * @return array
     */
    protected static function get_all_variations( $product_id ) {

        $args = array(
            'post_parent' => $product_id,
            'post_type'   => 'product_variation',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
            'fields'      => 'ids',
            'post_status' => array( 'publish', 'private'  ),
            'numberposts' => -1,
        );

        return get_posts( $args );
    }

}
