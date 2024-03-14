<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Catalog_Mode_Types_Groups' ) ) {

    class WModes_Admin_Catalog_Mode_Types_Groups {

        public static function init() {

            add_filter( 'wmodes-admin/catalog-modes/get-mode-type-groups', array( new self(), 'get_groups' ), 10, 2 );
        }

        public static function get_groups( $in_groups, $args = array() ) {

            $in_groups[ 'shop' ] = esc_html__( 'Shop &amp; Product Page', 'wmodes-tdm' );

            if ( $args[ 'is_global' ] ) {

                $in_groups[ 'cart' ] = esc_html__( 'Cart &amp; Checkout', 'wmodes-tdm' );
            }

            return $in_groups;
        }

    }

    WModes_Admin_Catalog_Mode_Types_Groups::init();
}