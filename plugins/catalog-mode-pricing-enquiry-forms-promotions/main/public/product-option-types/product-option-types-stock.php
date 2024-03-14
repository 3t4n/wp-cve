<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Option_Type_Stock' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Product_Option_Type_Stock {

        public function get_option( $option, $option_args, $data ) {

            $option[ 'stock' ] = $this->get_stock( $option_args );

            return $option;
        }

        private function get_stock( $option_args ) {

            $stock_options = array();

            $stock_options[ 'manage_stock' ] = false;

            if ( !empty( $option_args[ 'stock_status' ] ) ) {
                $stock_options[ 'stock_status' ] = $option_args[ 'stock_status' ];
            }

            return $stock_options;
        }

    }

}