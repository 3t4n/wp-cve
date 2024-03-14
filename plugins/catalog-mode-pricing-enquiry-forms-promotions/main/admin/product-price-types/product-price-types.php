<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

require_once (dirname( __FILE__ ) . '/product-price-types-sale.php');

if ( !class_exists( 'WModes_Admin_Product_Prices_Types' ) ) {

    class WModes_Admin_Product_Prices_Types {

        public static function get_modes( $args ) {

            return apply_filters( 'wmodes-admin/product-pricing/get-mode-types', array(), $args );
        }

        public static function get_adjustment_types( $args ) {

            return apply_filters( 'wmodes-admin/product-pricing/get-adjustment-types', array(), $args );
        }

        public static function get_adjustment_based_on_types( $args ) {

            return apply_filters( 'wmodes-admin/product-pricing/get-adjustment-based-on-types', array(), $args );
        }

        public static function get_default_adjustment_types( $args ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return 'fixed_price';
            }

            return 'per_discount';
        }

        public static function get_adjustment_based_on_visibility( $args ) {

            return apply_filters( 'wmodes-admin/product-pricing/get-adjustment-based-on-visibility', array( 'per_discount', 'per_fee' ), $args );
        }

        public static function get_calculate_from_visibility( $args ) {

            return apply_filters( 'wmodes-admin/product-pricing/get-calculate-from-visibility', array( 'per_discount' ), $args );
        }

    }

}
    