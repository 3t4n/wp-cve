<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Shipping' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_Shipping {

        private $options;

        public function __construct() {

            $this->options = array();

            add_filter( 'woocommerce_product_get_virtual', array( $this, 'get_virtual' ), 99999, 2 );
            add_filter( 'woocommerce_product_variation_get_virtual', array( $this, 'get_virtual' ), 99999, 2 );
            add_filter( 'woocommerce_is_virtual', array( $this, 'get_virtual' ), 99999, 2 );
        }

        public function get_virtual( $virtual, $product ) {

            $options = $this->get_options( $product );
           
            if ( isset( $options[ 'is_virtual' ] ) ) {

                return $options[ 'is_virtual' ];
            }

            return $virtual;
        }

        private function get_options( $product ) {

            $product_id = $product->get_id();

            if ( isset( $this->options[ $product_id ] ) ) {

                return $this->options[ $product_id ];
            }

            $options = WModes_Pipeline::get_product_option( $product, 'shipping', true );

            if ( isset( $options[ 'shipping' ] ) ) {

                $this->options[ $product_id ] = $options[ 'shipping' ];
            } else {

                return array();
            }

            return $this->options[ $product_id ];
        }

    }

    new WModes_Pipeline_Shipping();
}