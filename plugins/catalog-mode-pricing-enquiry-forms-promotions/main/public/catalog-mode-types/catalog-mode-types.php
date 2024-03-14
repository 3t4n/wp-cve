<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Catalog_Mode_Types' ) ) {

    require_once (dirname( __FILE__ ) . '/catalog-mode-types-shop.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-types-product.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-types-cart.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-types-checkout.php');

    class WModes_Catalog_Mode_Types {

        private static $instance;

        private static function get_instance() {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function get_modes( $modes_args, $data ) {

            $this_obj = self::get_instance();

            $modes = array();

            foreach ( $modes_args as $mode_args ) {

                if ( !$this_obj->validate_product( $mode_args, $data ) ) {

                    continue;
                }

                $mode_id = $mode_args[ 'id' ];

                $modes[ $mode_id ] = $this_obj->get_mode( $mode_args, $data );
            }

            return $modes;
        }

        private function get_mode( $mode_args, $data ) {

            $mode_type = '';

            if ( isset( $mode_args[ 'mode_type' ] ) ) {

                $mode_type = $mode_args[ 'mode_type' ];
            }

            $mode = array();

            if ( '' == $mode_type ) {

                return $mode;
            }

            switch ( $mode_type ) {

                case 'shop':

                    $shop_mode = new WModes_Catalog_Mode_Types_Shop();

                    return $shop_mode->get_mode( $mode, $mode_args, $data );

                case 'product':

                    $product_mode = new WModes_Catalog_Mode_Types_Product();

                    return $product_mode->get_mode( $mode, $mode_args, $data );

                case 'cart':

                    $cart_mode = new WModes_Catalog_Mode_Types_Cart();

                    return $cart_mode->get_mode( $mode, $mode_args, $data );

                case 'checkout':

                    $checkout_mode = new WModes_Catalog_Mode_Types_Checkout();

                    return $checkout_mode->get_mode( $mode, $mode_args, $data );

                default:

                    if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                        return WModes_Premium_Catalog_Mode_Types::get_mode( $mode, $mode_args, $data );
                    } else {

                        return apply_filters( 'wmodes/catalog-modes/process-' . $mode_type . '-mode', $mode, $mode_args, $data );
                    }
            }
        }

        public static function prepare_mode( $mode, $mode_args ) {

            $mode[ 'mode_type' ] = $mode_args[ 'mode_type' ];

            $mode[ 'is_product' ] = $mode_args[ 'is_product' ];

            return $mode;
        }

        private function validate_product( $mode_args, $data ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Catalog_Mode_Types::validate_product( $mode_args, $data );
            }

            return true;
        }

    }

}