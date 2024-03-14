<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Prices' ) ) {

    class WModes_Product_Prices {

        private $prices_engine;

        public function __construct() {

            $this->prices_engine = new WModes_Product_Prices_Engine();
            
            add_filter( 'wmodes/process-product-data', array( $this->prices_engine, 'process_product_data' ), 30, 1 );
            add_filter( 'wmodes/process-product-hash', array( $this->prices_engine, 'process_product_hash' ), 30, 2 );
        }

        public function get_prices( $product_data, $data ) {

            $product_prices = array();

            if ( isset( $data[ 'prices' ] ) ) {

                foreach ( $data[ 'prices' ] as $pricing_id => $prices ) {

                    $product_prices = $this->merge_prices( $product_prices, $prices );
                }
            }

            if ( isset( $product_prices[ 'regular_price' ] ) ) {

                $product_prices[ 'price' ] = $product_prices[ 'regular_price' ];
            }

            if ( isset( $product_prices[ 'sale_price' ] ) ) {

                $product_prices[ 'price' ] = $product_prices[ 'sale_price' ];
            }

            if ( count( $product_prices ) ) {

                $product_data[ 'prices' ] = $product_prices;
            }

            return $this->get_source_prices( $product_data, $data );
        }

        private function merge_prices( $product_prices, $prices ) {

            foreach ( $prices as $key => $price ) {

                $product_prices[ $key ] = $price;
            }

            return $product_prices;
        }

        private function get_source_prices( $product_data, $data ) {

            if ( !isset( $data[ 'src_prices'] ) ) {
                
                return $product_data;
            }

            $product_data[ 'source_prices' ] = $data[ 'src_prices'];

            return $product_data;
        }

    }

}
