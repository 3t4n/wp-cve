<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Options' ) ) {

    class WModes_Product_Options {

        private $options_engine;

        public function __construct() {

            $this->options_engine = new WModes_Product_Options_Engine();
            add_filter( 'wmodes/process-product-data', array( $this->options_engine, 'process_product_data' ), 20, 1 );
            add_filter( 'wmodes/process-product-hash', array( $this->options_engine, 'process_product_hash' ), 20, 2 );
        }

        public function get_options( $product_data, $data ) {

            $product_options = array();

            if ( isset( $data[ 'options' ] ) ) {

                foreach ( $data[ 'options' ] as $key => $options ) {

                    $product_options = $this->merge_options( $product_options, $options );
                }
            }

            $product_data[ 'options' ] = $product_options;

            return $product_data;
        }

        private function merge_options( $product_options, $options ) {

            foreach ( $options as $key => $option ) {

                $product_options[] = $this->map_keys( $key, $option );
            }

            return $product_options;
        }

        private function map_keys( $option_id, $options ) {

            $opt = array(
                'id' => $option_id
            );

            foreach ( $options as $key => $value ) {

                $opt[ $key ] = $value;
            }

            return $opt;
        }

    }

}