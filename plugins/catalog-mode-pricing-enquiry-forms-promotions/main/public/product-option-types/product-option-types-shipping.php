<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Option_Type_Shipping' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Product_Option_Type_Shipping {

        public function get_option( $option, $option_args, $data ) {

            $option[ 'shipping' ] = array(
                'is_virtual' => ('yes' == $option_args[ 'is_virtual' ])
            );

            return $option;
        }

    }

}
