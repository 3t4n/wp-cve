<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Option_Type_Groups' ) ) {

    class WModes_Admin_Product_Option_Type_Groups {

        public static function init() {

            add_filter( 'wmodes-admin/product-options/get-option-type-groups', array( new self(), 'get_groups' ), 10, 2 );
        }


        public static function get_groups( $in_groups, $args ) {

            $in_groups[ 'settings' ] = esc_html__( 'Product Settings', 'wmodes-tdm' );
            $in_groups[ 'promotions' ] = esc_html__( 'Promotion Settings', 'wmodes-tdm' );

            return $in_groups;
        }

    }

    WModes_Admin_Product_Option_Type_Groups::init();
}