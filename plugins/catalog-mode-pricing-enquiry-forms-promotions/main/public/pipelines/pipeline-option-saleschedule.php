<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Sale_Schedule' ) ) {

    class WModes_Pipeline_Sale_Schedule {

        private $options = array();

        public function __construct() {

            $this->options = array();

            add_filter( 'woocommerce_product_get_date_on_sale_from', array( $this, 'get_date_on_sale_from' ), 99999, 2 );
            add_filter( 'woocommerce_product_variation_get_date_on_sale_from', array( $this, 'get_date_on_sale_from' ), 99999, 2 );
            add_filter( 'woocommerce_product_get_date_on_sale_to', array( $this, 'get_date_on_sale_to' ), 99999, 2 );
            add_filter( 'woocommerce_product_variation_get_date_on_sale_to', array( $this, 'get_date_on_sale_to' ), 99999, 2 );
        }

        public function get_date_on_sale_from( $date_on_sale_from, $product ) {

            $options = $this->get_options( $product );

            if ( isset( $options[ 'from' ] ) ) {

                return $this->process_date( $options[ 'from' ], $date_on_sale_from );
            }

            return $date_on_sale_from;
        }

        public function get_date_on_sale_to( $date_on_sale_to, $product ) {

            $options = $this->get_options( $product );

            if ( isset( $options[ 'to' ] ) ) {

                return $this->process_date( $options[ 'to' ], $date_on_sale_to );
            }

            return $date_on_sale_to;
        }

        private function process_date( $date_str, $default ) {

            try {
                if ( false !== $date_str ) {
                    $date_time = new WC_DateTime( $date_str, wp_timezone() );

                    return $date_time;
                }

                return null;
            } catch ( Exception $exc ) {
                
            }

            return $default;
        }

        private function get_options( $product ) {

            $product_id = $product->get_id();

            if ( isset( $this->options[ $product_id ] ) ) {

                return $this->options[ $product_id ];
            }

            $options = WModes_Pipeline::get_product_option( $product, 'sale_schedule', true );

            if ( isset( $options[ 'sale_schedule' ] ) ) {

                $this->options[ $product_id ] = $options[ 'sale_schedule' ];
            } else {

                return array();
            }

            return $this->options[ $product_id ];
        }

    }

    new WModes_Pipeline_Sale_Schedule();
}