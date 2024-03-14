<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Prices_Engine' ) ) {

    class WModes_Product_Prices_Engine {

        private $valid_options;
        private $product_list;
        private $product_hash_list;
        private $engine_meta;

        public function __construct() {

            $this->valid_options = array();
            $this->product_list = array();
            $this->product_hash_list = array();

            $this->engine_meta = new WModes_Product_Prices_Engine_Meta();
        }

        public function process_product_hash( $hash_keys, $product_id ) {

            //return in memory prices hash
            if ( isset( $this->product_hash_list[ $product_id ] ) ) {

                return $this->product_hash_list[ $product_id ];
            }

            //process prices hash
            $this->product_hash_list[ $product_id ] = $this->get_pricing_hash( $hash_keys, $product_id );

            //return in processed prices hash
            return $this->product_hash_list[ $product_id ];
        }

        public function process_product_data( $data ) {

            $cache_key = $this->get_cache_key( $data );

            //can't process empty product
            if ( empty( $cache_key ) ) {

                return $data;
            }
            
            // get source prices
            $data[ 'src_prices' ] = $this->get_source_prices( $data );

            //return in memory prices
            if ( isset( $this->product_list[ $cache_key ] ) ) {

                $data[ 'prices' ] = $this->product_list[ $cache_key ];

                return $data;
            }

            //process prices
            $data = $this->calculate_prices( $data );

            //store prices into memory for re-use
            if ( isset( $data[ 'prices' ] ) ) {

                $this->product_list[ $cache_key ] = $data[ 'prices' ];
            } 

            return $data;
        }

        private function get_pricing_hash( $hash_keys, $product_id ) {

            $settings = $this->get_settings();

            //check if prices should apply
            if ( 'no' == $settings[ 'mode' ] ) {

                //process meta hash and return result
                return $this->engine_meta->process_product_hash( $hash_keys, $product_id );
            }

            // check of meta settings
            if ( !$this->engine_meta->use_global( $product_id ) ) {

                // process meta hash only and return result
                return $this->engine_meta->process_product_hash( $hash_keys, $product_id );
            }

            // go through the prices options and apply them as keys
            foreach ( $this->get_valid_prices_options() as $price_option ) {

                if ( !isset( $hash_keys[ 'prices' ] ) ) {

                    $hash_keys[ 'prices' ] = array();
                }

                $hash_key = md5( wp_json_encode( $price_option ) );

                $hash_keys[ 'prices' ][ $price_option[ 'pricing_id' ] ] = $hash_key;
            }

            return $this->engine_meta->process_product_hash( $hash_keys, $product_id );
        }

        private function calculate_prices( $data ) {

            //check for valid variation id
            if ( !$this->is_valid_variation_id( $data ) ) {

                return $data;
            }

            $settings = $this->get_settings();

            //check if prices should apply
            if ( 'no' == $settings[ 'mode' ] ) {

                //process meta data and return result
                return $this->engine_meta->process_product_data( $data );
            }

            // check of meta settings
            if ( !$this->engine_meta->use_global( $this->get_product_id_from_data( $data ) ) ) {

                // process meta data only and return result
                return $this->engine_meta->process_product_data( $data );
            }

            $data = $this->set_prev_prices( $data );

            // go through the prices options and apply them
            foreach ( $this->get_valid_prices_options() as $price_option ) {

                $option_head = array(
                    'apply_mode' => $price_option[ 'apply_mode' ],
                    'pricing_id' => $price_option[ 'pricing_id' ],
                    'settings' => $settings,
                    'module' => 'product-pricing',
                    'is_global' => $price_option[ 'is_global' ],
                );

                $product_args = array();

                if ( isset( $price_option[ 'product_args' ] ) ) {

                    $product_args = $price_option[ 'product_args' ];
                }

                if ( !$this->can_apply_option( $option_head, $product_args, $data ) ) {

                    continue;
                }

                $prices = $this->get_prices( $price_option, $settings, $data );

                if ( has_filter( 'wmodes/product-pricing/calculated-prices' ) ) {

                    $prices = apply_filters( 'wmodes/product-pricing/calculated-prices', $prices, $price_option[ 'pricing_id' ], $price_option[ 'is_global' ], $data );
                }

                $data = $this->set_prev_prices( $data, $prices );

                $data = $this->add_option_prices( $data, $prices, $option_head );
            }

            //process meta data and return result
            return $this->engine_meta->process_product_data( $data );
        }

        private function get_prices( $price_option, $settings, $data ) {

            $prices = array();

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $prices = WModes_Premium_Product_Prices_Engine::get_prices( $price_option, $settings, $data );
            } else {

                $prices_args = $this->get_args_from_options( $price_option );

                $prices = WModes_Product_Prices_Types::get_prices( $prices_args, $data );
            }

            return $prices;
        }

        private function get_args_from_options( $price_option ) {

            $ex_keys = array(
                'admin_note',
                'limit',
                'product_args',
                'conditions',
                'apply_mode',
                'enable'
            );

            $price_args = array();

            foreach ( $price_option as $key => $price_option_arg ) {

                if ( in_array( $key, $ex_keys ) ) {

                    continue;
                }

                $price_args[ $key ] = $price_option_arg;
            }

            return $price_args;
        }

        private function can_apply_option( $option_head, $product_args, $data ) {

            //validate product

            $data[ 'wc' ][ 'context' ] = 'edit';

            $bool_val = WModes_Product_Filter_Types::validate_product( $data, $product_args );

            if ( !$bool_val ) {

                return false;
            }

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $bool_val = WModes_Premium_Product_Prices_Engine::can_apply_option( $option_head, $data );
            }

            // allows other plugins to check if the option should apply
            if ( has_filter( 'wmodes/product-pricing/can-apply-option' ) ) {

                $bool_val = apply_filters( 'wmodes/product-pricing/can-apply-option', $bool_val, $option_head, $data );
            }

            return $bool_val;
        }

        private function add_option_prices( $data, $prices, $option_head ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $data = WModes_Premium_Product_Prices_Engine::add_option_prices( $data, $prices, $option_head );
            } else {
                $data[ 'prices' ][ $option_head[ 'pricing_id' ] ] = $prices;
            }

            // allows other plugins to modify prices data once added
            if ( has_filter( 'wmodes/product-pricing/added-option-prices' ) ) {
                if ( isset( $data[ 'prices' ][ $option_head[ 'pricing_id' ] ] ) ) {
                    $data[ 'prices' ][ $option_head[ 'pricing_id' ] ] = apply_filters( 'wmodes/product-pricing/added-option-prices', $data[ 'prices' ][ $option_head[ 'pricing_id' ] ], $option_head );
                }
            }

            return $data;
        }

        private function set_prev_prices( $data, $prices = array() ) {

            if ( !isset( $data[ 'wc' ][ 'prev_prices' ] ) ) {

                $reg_price = isset( $data[ 'wc' ][ 'product' ][ 'regular_price' ] ) ? $data[ 'wc' ][ 'product' ][ 'regular_price' ] : 0;
                $sale_price = isset( $data[ 'wc' ][ 'product' ][ 'sale_price' ] ) ? $data[ 'wc' ][ 'product' ][ 'sale_price' ] : 0;

                if ( !$sale_price ) {
                    $sale_price = $reg_price;
                }

                $data[ 'wc' ][ 'prev_prices' ] = array(
                    'regular_price' => is_numeric( $reg_price ) ? $reg_price : 0,
                    'sale_price' => is_numeric( $sale_price ) ? $sale_price : 0
                );
            }

            if ( isset( $prices[ 'regular_price' ] ) ) {

                $data[ 'wc' ][ 'prev_prices' ][ 'regular_price' ] = $prices[ 'regular_price' ];
            }

            if ( isset( $prices[ 'sale_price' ] ) ) {

                $data[ 'wc' ][ 'prev_prices' ][ 'sale_price' ] = $prices[ 'sale_price' ];
            }

            return $data;
        }

        private function is_valid_variation_id( $data ) {

            $product_type = '';

            $variation_id = 0;

            if ( isset( $data[ 'wc' ][ 'product' ][ 'type' ] ) ) {

                $product_type = $data[ 'wc' ][ 'product' ][ 'type' ];
            }

            if ( isset( $data[ 'wc' ][ 'product' ][ 'variation_id' ] ) ) {

                $variation_id = $data[ 'wc' ][ 'product' ][ 'variation_id' ];
            }

            if ( 'variable' == $product_type && $variation_id <= 0 ) {

                return false;
            }

            return true;
        }

        private function get_valid_prices_options() {

            // check for already validated options
            if ( count( $this->valid_options ) ) {

                return $this->valid_options;
            }

            // go through each options and validate them
            foreach ( $this->get_prices_options() as $price_option ) {

                // allows active rules only
                if ( 'no' == $price_option[ 'enable' ] ) {

                    continue;
                }

                $price_option[ 'is_global' ] = true;

                // check for required validations
                if ( !isset( $price_option[ 'condition_args' ][ 'conditions' ] ) ) {

                    $this->valid_options[] = $price_option;
                    continue;
                }

                // get conditions to validate
                $option_conditions = $price_option[ 'condition_args' ];
                $option_conditions[ 'id' ] = $price_option[ 'pricing_id' ];
                $option_conditions[ 'module' ] = 'product-pricing';
                $option_conditions[ 'is_global' ] = true;
                $option_conditions[ 'context' ] = 'edit';

                // get data to validate
                $data = WModes_Cart::get_data( $option_conditions[ 'context' ] );

                // validate option conditions
                $bool_val = WModes_Condition_Types::validate( $option_conditions, $data );

                if ( !$bool_val ) {

                    continue;
                }

                $this->valid_options[] = $price_option;
            }

            return $this->valid_options;
        }

        private function get_source_prices( $data ) {

            if ( !isset( $data[ 'wc' ][ 'product' ] ) ) {

                return array();
            }

            $product = $data[ 'wc' ][ 'product' ];

            $prices = array(
                'regular_price' => $product[ 'regular_price' ],
                'sale_price' => $product[ 'sale_price' ],
                'price' => $product[ 'price' ],
            );

            return $prices;
        }
        
        private function get_cache_key( $data ) {

            $key = '';

            if ( isset( $data[ 'wc' ][ 'product' ][ 'product_id' ] ) ) {
                $key = $data[ 'wc' ][ 'product' ][ 'product_id' ];
            }

            if ( isset( $data[ 'wc' ][ 'product' ][ 'variation_id' ] ) ) {
                $key = $key . '_' . $data[ 'wc' ][ 'product' ][ 'variation_id' ];
            }

            return $key;
        }

        private function get_product_id_from_data( $data ) {

            if ( isset( $data[ 'wc' ][ 'product' ][ 'product_id' ] ) ) {

                return $data[ 'wc' ][ 'product' ][ 'product_id' ];
            }

            return 0;
        }

        private function get_prices_options() {

            $rules = array();

            foreach ( WModes::get_option( 'product_pricings', array() ) as $rule ) {

                $rules[] = $rule;
            }

            return $rules;
        }

        private function get_settings() {

            $default = array(
                'mode' => 'no',
            );

            return WModes::get_option( 'product_pricing_settings', $default );
        }

    }

}