<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Prices_Panel_Products' ) ) {

    class WModes_Admin_Product_Prices_Panel_Products {

        public static function init() {

            add_filter( 'wmodes-admin/product-pricing/get-panel-option-fields', array( new self(), 'get_fields' ), 10 );
        }

        public static function get_fields( $in_fields ) {

            $args = array(
                'module' => 'product-pricing',
                'is_global' => true,
            );

            return WModes_Admin_Logic_Types_Product_Filters::get_fields( $in_fields, $args );
        }

    }

    WModes_Admin_Product_Prices_Panel_Products::init();
}
