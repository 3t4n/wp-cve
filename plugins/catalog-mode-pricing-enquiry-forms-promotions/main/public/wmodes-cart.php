<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( !class_exists( 'WModes_Cart' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    require_once 'wmodes-cart-session.php';

    class WModes_Cart {

        private static $instance = null;

        private static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function get_data( $contex = 'edit' ) {

            return self::get_instance()->get_all_data( $contex );
        }

        private function get_all_data( $contex ) {

            $cart_sesion = WModes_Cart_Session::get_instance();

            $sesion_data = $cart_sesion->get_cart_data();

            return $this->get_cart_items( array( 'contex' => $contex ), $sesion_data, $contex );
        }

        private function get_cart_items( $cart_data, $sesion_data, $contex ) {

            $cart_items = array();

            foreach ( $sesion_data[ 'items' ] as $key => $cart_item ) {

                $raw_cart_item = $cart_item;

                $cart_items[ $key ] = apply_filters( 'wmodes/get-cart-item', $cart_item, $raw_cart_item, $contex );
            }

            $cart_data[ 'items' ] = $cart_items;

            return $this->get_customer( $cart_data, $sesion_data, $contex );
        }

        private function get_customer( $cart_data, $sesion_data, $contex ) {

            $raw_customer = $sesion_data[ 'customer' ];

            $cart_data[ 'customer' ] = apply_filters( 'wmodes/get-cart-customer', $sesion_data[ 'customer' ], $raw_customer );

            return $cart_data;
        }

    }

}