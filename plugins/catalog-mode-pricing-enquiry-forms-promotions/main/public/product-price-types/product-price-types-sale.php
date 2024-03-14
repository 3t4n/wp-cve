<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Prices_Type_Sale' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Product_Prices_Type_Sale {

        public function get_prices( $prices, $prices_args, $data ) {

            if ( 'fixed_price' == $prices_args[ 'sale_adj' ][ 'adj_type' ] ) {

                return $this->get_fixed_price( $prices, $prices_args );
            }

            if ( 'per_discount' == $prices_args[ 'sale_adj' ][ 'adj_type' ] ) {

                return $this->get_per_discount_price( $prices, $prices_args, $data );
            }

            return $prices;
        }

        private function get_fixed_price( $prices, $prices_args ) {

            $amount = $prices_args[ 'sale_adj' ][ 'amount' ];

            if ( !is_numeric( $amount ) ) {

                $prices[ 'sale_price' ] = 0;

                return $prices;
            }

            $prices[ 'sale_price' ] = $amount;

            return $prices;
        }

        private function get_per_discount_price( $prices, $prices_args, $data ) {

            $amount = $prices_args[ 'sale_adj' ][ 'amount' ];

            if ( !is_numeric( $amount ) ) {
                $amount = 0;
            }

            $per_arg = $this->get_per_arg( $prices_args );

            $per_amount = WModes_Product_Prices_Types::get_based_on_amount( $per_arg, $data );

            if ( !$per_amount ) {

                $prices[ 'sale_price' ] = 0;

                return $prices;
            }

            $discount_amount = ($amount / 100) * $per_amount;

            $from_arg = $this->get_from_arg( $prices_args );

            $from_amount = WModes_Product_Prices_Types::get_based_on_amount( $from_arg, $data );

            if ( !$from_amount ) {

                $prices[ 'sale_price' ] = 0;

                return $prices;
            }

            $prices[ 'sale_price' ] = $from_amount - $discount_amount;

            return $prices;
        }

        private function get_per_arg( $prices_args ) {

            $based_on = 'sale_price';

            if ( isset( $prices_args[ 'sale_adj' ][ 'based_on' ] ) && '' != $prices_args[ 'sale_adj' ][ 'based_on' ] ) {

                $based_on = $prices_args[ 'sale_adj' ][ 'based_on' ];
            }

            return $based_on;
        }

        private function get_from_arg( $prices_args ) {

            $based_on = 'sale_price';

            if ( isset( $prices_args[ 'sale_adj' ][ 'cal_from' ] ) && '' != $prices_args[ 'sale_adj' ][ 'cal_from' ] ) {

                $based_on = $prices_args[ 'sale_adj' ][ 'cal_from' ];
            }

            return $based_on;
        }

    }

}