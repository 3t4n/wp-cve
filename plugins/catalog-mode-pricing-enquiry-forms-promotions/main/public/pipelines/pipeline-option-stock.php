<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Stock' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_Stock {

        private $options;

        public function __construct() {

            $this->options = array();

            add_filter( 'woocommerce_product_get_manage_stock', array( $this, 'get_manage_stock' ), 99999, 2 );
            add_filter( 'woocommerce_product_variation_get_manage_stock', array( $this, 'get_manage_stock' ), 99999, 2 );

            add_filter( 'woocommerce_product_get_stock_status', array( $this, 'get_stock_status' ), 99999, 2 );
            add_filter( 'woocommerce_product_variation_get_stock_status', array( $this, 'get_stock_status' ), 99999, 2 );
        }

        public function get_manage_stock( $manage_stock, $product ) {


            $options = $this->get_options( $product );

            if ( isset( $options[ 'manage_stock' ] ) ) {

                return $options[ 'manage_stock' ];
            }

            return $manage_stock;
        }

        public function get_stock_status( $stock_status, $product ) {

            $options = $this->get_options( $product );

            if ( isset( $options[ 'stock_status' ] ) ) {

                return $options[ 'stock_status' ];
            }

            return $stock_status;
        }

        private function get_options( $product ) {

            $product_id = $product->get_id();

            if ( isset( $this->options[ $product_id ] ) ) {

                return $this->options[ $product_id ];
            }

            $options = WModes_Pipeline::get_product_option( $product, 'stock', true );

            if ( isset( $options[ 'stock' ] ) ) {

                $this->options[ $product_id ] = $options[ 'stock' ];
            } else {

                return array();
            }

            return $this->options[ $product_id ];
        }

    }

    new WModes_Pipeline_Stock();
}