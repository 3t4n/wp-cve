<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Prices_Types' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    require_once dirname( __FILE__ ) . '/product-price-types-sale.php';

    class WModes_Product_Prices_Types {

        public static function get_prices( $prices_args, $data ) {

            $prices = array();

            if ( 'sale' == $prices_args[ 'mode' ] ) {

                $sale_prices = new WModes_Product_Prices_Type_Sale();

                $prices = $sale_prices->get_prices( $prices, $prices_args, $data );
            }

            $filter_key = 'wmodes/product-pricing/calculate-product-' . $prices_args[ 'mode' ] . '-prices';

            if ( has_filter( $filter_key ) ) {

                $prices = apply_filters( $filter_key, $prices, $prices_args, $data );
            }

            return $prices;
        }

        public static function get_based_on_amount( $based_on, $data ) {

            $per_amount = 0;

            if ( 'sale_price' == $based_on ) {

                if ( isset( $data[ 'wc' ][ 'product' ][ 'sale_price' ] ) ) {
                    $per_amount = $data[ 'wc' ][ 'product' ][ 'sale_price' ];
                }

                if ( is_numeric( $per_amount ) ) {

                    return $per_amount;
                }

                if ( isset( $data[ 'wc' ][ 'product' ][ 'regular_price' ] ) ) {

                    $per_amount = $data[ 'wc' ][ 'product' ][ 'regular_price' ];
                }

                if ( is_numeric( $per_amount ) ) {

                    return $per_amount;
                }
            } 
            
            
            $filter_key = 'wmodes/product-pricing/get-' . $based_on . '-based-on-amount';

           
            if ( has_filter( $filter_key ) ) {

                return apply_filters( $filter_key, $per_amount, $data );
            }

            return 0;
        }

    }

}