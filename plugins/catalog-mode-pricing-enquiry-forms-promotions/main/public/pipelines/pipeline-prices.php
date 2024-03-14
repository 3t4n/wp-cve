<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Prices' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_Prices {

        private static $instance;
        private $prices;
        private $hash_data;

        public function __construct() {

            $this->prices = array();
            $this->hash_data = array();

            add_filter( 'woocommerce_product_get_sale_price', array( $this, 'get_sale_price' ), 999, 2 );
            add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'get_sale_price' ), 99999, 2 );

            add_filter( 'woocommerce_product_get_price', array( $this, 'get_price' ), 99999, 2 );
            add_filter( 'woocommerce_product_variation_get_price', array( $this, 'get_price' ), 99999, 2 );

            add_filter( 'woocommerce_cart_hash', array( $this, 'get_cart_hash' ), 99999, 2 );
        }

        public static function get_instance() {

            if ( !self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_sale_price( $price, $product ) {

            $pipeline_data = $this->get_calculated_prices( $product );

            if ( isset( $pipeline_data[ 'sale_price' ] ) ) {

                return apply_filters( 'wmodes/get-sale-price', $pipeline_data[ 'sale_price' ], $product, null );
            }

            return $price;
        }

        public function get_price( $price, $product ) {

            $pipeline_data = $this->get_calculated_prices( $product );

            if ( isset( $pipeline_data[ 'price' ] ) ) {

                return apply_filters( 'wmodes/get-price', $pipeline_data[ 'price' ], $product, null );
            }

            return $price;
        }

        public function get_variation_sale_price( $price, $variation, $product ) {

            $pipeline_data = $this->get_calculated_prices( $product, $variation );

            if ( isset( $pipeline_data[ 'sale_price' ] ) ) {

                return apply_filters( 'wmodes/get-sale-price', $pipeline_data[ 'sale_price' ], $product, $variation );
            }

            return $price;
        }

        public function get_variation_price( $price, $variation, $product ) {

            $pipeline_data = $this->get_calculated_prices( $product, $variation );

            if ( isset( $pipeline_data[ 'price' ] ) ) {

                return apply_filters( 'wmodes/get-price', $pipeline_data[ 'price' ], $product, $variation );
            }

            return $price;
        }

        public function get_cart_hash( $cart_hash, $cart_session ) {

            if ( !is_array( $cart_session ) ) {

                return $cart_hash;
            }

            foreach ( $cart_session as $key => $item ) {

                $product_id = $item[ 'product_id' ];

                $item_key = $this->get_prices_key_by_id( $product_id );

                if ( !isset( $this->hash_data[ $item_key ] ) ) {

                    $this->hash_data[ $item_key ] = WModes_Pipeline::get_hash_data( $product_id );
                }
            }

            if ( count( $this->hash_data ) ) {

                $hash_data = array(
                    'cart' => $cart_hash,
                    'wmodes' => $this->hash_data
                );

                return md5( wp_json_encode( $hash_data ) );
            }

            return $cart_hash;
        }

        public function get_prices_hash( $product ) {

            $prices_key = $this->get_prices_key( $product, null );

            if ( isset( $this->hash_data[ $prices_key ] ) ) {

                return $this->hash_data[ $prices_key ];
            }

            $product_id = $product->get_id();

            $this->hash_data[ $prices_key ] = WModes_Pipeline::get_hash_data( $product_id );

            return $this->hash_data[ $prices_key ];
        }

        public function get_calculated_prices( $product, $variation = null ) {

            $prices_key = $this->get_prices_key( $product, $variation );

            if ( isset( $this->prices[ $prices_key ][ 'prices' ] ) ) {

                return $this->prices[ $prices_key ][ 'prices' ];
            }

            $pipeline_data = WModes_Pipeline::run_product_data( $product, $variation );

            if ( isset( $pipeline_data[ 'prices' ] ) ) {

                $this->prices[ $prices_key ][ 'prices' ] = $pipeline_data[ 'prices' ];
            } else {

                return array();
            }

            return $this->prices[ $prices_key ][ 'prices' ];
        }

        public function get_source_prices( $product, $variation = null ) {

            $prices_key = $this->get_prices_key( $product, $variation );

            if ( isset( $this->prices[ $prices_key ][ 'source_prices' ] ) ) {

                return $this->prices[ $prices_key ][ 'source_prices' ];
            }

            $pipeline_data = WModes_Pipeline::run_product_data( $product, $variation );

            if ( isset( $pipeline_data[ 'source_prices' ] ) ) {

                $this->prices[ $prices_key ][ 'source_prices' ] = $pipeline_data[ 'source_prices' ];
            } else {

                $this->prices[ $prices_key ][ 'source_prices' ] = array();
            }


            return $this->prices[ $prices_key ][ 'source_prices' ];
        }

        private function get_prices_key( $product, $variation ) {

            $product_id = $product->get_id();

            $variation_id = 0;


            if ( $variation ) {

                $variation_id = $variation->get_id();
            }

            return $this->get_prices_key_by_id( $product_id, $variation_id );
        }

        private function get_prices_key_by_id( $product_id, $variation_id = 0 ) {

            return $product_id . '_' . $variation_id;
        }

    }

    WModes_Pipeline_Prices::get_instance();
}