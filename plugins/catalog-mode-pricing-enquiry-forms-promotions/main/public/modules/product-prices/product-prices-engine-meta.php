<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Prices_Engine_Meta' ) ) {

    class WModes_Product_Prices_Engine_Meta {

        private $valid_options;

        public function __construct() {

            $this->valid_options = array();
        }

        public function process_product_hash( $hash_keys, $product_id ) {

            if ( !$product_id ) {

                return $hash_keys;
            }

            if ( 'no' == $this->get_is_meta_enabled() ) {

                return $hash_keys;
            }

            if ( !$this->use_meta( $product_id ) ) {

                return $hash_keys;
            }

            return $this->get_pricing_hash( $hash_keys, $product_id );
        }

        public function process_product_data( $data ) {

            if ( 'no' == $this->get_is_meta_enabled() ) {

                return $data;
            }

            if ( !isset( $data[ 'wc' ][ 'product' ][ 'product_id' ] ) ) {

                return $data;
            }

            $product_id = $data[ 'wc' ][ 'product' ][ 'product_id' ];

            return $this->process_meta_data( $data, $product_id );
        }

        private function process_meta_data( $data, $product_id ) {

            // check if meta is enabled
            if ( !$this->use_meta( $product_id ) ) {

                return $data;
            }

            $global_prices = array();

            if ( isset( $data[ 'prices' ] ) ) {

                $global_prices = $data[ 'prices' ];

                unset( $data[ 'prices' ] );
            }

            $data = $this->calculate_prices( $data, $product_id );

            if ( isset( $data[ 'prices' ] ) ) {

                $data[ 'prices' ] = $this->merge_prices( $global_prices, $data[ 'prices' ] );
            } else {
                $data[ 'prices' ] = $global_prices;
            }

            return $data;
        }

        public function use_global( $product_id ) {

            if ( 'no' == $this->get_is_meta_enabled() ) {

                return true;
            }

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Product_Prices_Engine_Meta::use_global( $product_id );
            }

            $settings = $this->get_settings( $product_id );

            if ( !$settings ) {

                return true;
            }

            if ( 'global' != $settings[ 'enable' ] ) {

                return false;
            }

            return true;
        }

        private function get_pricing_hash( $hash_keys, $product_id ) {

            // go through the prices options and apply them as keys
            foreach ( $this->get_valid_prices_options( $product_id ) as $price_option ) {

                if ( !isset( $hash_keys[ 'prices' ] ) ) {

                    $hash_keys[ 'prices' ] = array();
                }
                
                $hash_key = md5( wp_json_encode( $price_option ) );

                $hash_keys[ 'prices' ][$price_option[ 'pricing_id' ]] = $hash_key;
            }

            return $hash_keys;
        }

        private function calculate_prices( $data, $product_id ) {

            $data = $this->set_prev_prices( $data );

            // go through the prices options and apply them
            foreach ( $this->get_valid_prices_options( $product_id ) as $price_option ) {

                $option_head = array(
                    'pricing_id' => $price_option[ 'pricing_id' ],
                    'module' => 'product-pricing',
                    'is_global' => $price_option[ 'is_global' ],
                    'product_id' => $price_option[ 'product_id' ],
                );

                $variation_args = array();

                if ( isset( $price_option[ 'variation_args' ] ) ) {

                    $variation_args = $price_option[ 'variation_args' ];
                }

                if ( !$this->can_apply_option( $option_head, $variation_args, $data ) ) {

                    continue;
                }

                $prices = $this->get_prices( $price_option, $data );

                if ( has_filter( 'wmodes/product-pricing/calculated-prices' ) ) {

                    $prices = apply_filters( 'wmodes/product-pricing/calculated-prices', $prices, $price_option[ 'pricing_id' ], $price_option[ 'is_global' ], $data );
                }

                $data = $this->set_prev_prices( $data, $prices );

                $data = $this->add_option_prices( $data, $prices, $option_head );
            }

            return $data;
        }

        private function get_prices( $price_option, $data ) {

            $prices = array();

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $prices = WModes_Premium_Product_Prices_Engine_Meta::get_prices( $price_option, $data );
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
                'variation_args',
                'conditions',
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

        private function can_apply_option( $option_head, $variation_args, $data ) {

            $bool_val = true;

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $bool_val = WModes_Premium_Product_Prices_Engine_Meta::can_apply_option( $option_head, $variation_args, $data );
            }

            $option_head[ 'variation_args' ] = $variation_args;

            // allows other plugins to check if the option should apply
            if ( has_filter( 'wmodes/product-pricing/can-apply-option' ) ) {

                $bool_val = apply_filters( 'wmodes/product-pricing/can-apply-option', $bool_val, $option_head, $data );
            }

            return $bool_val;
        }

        private function add_option_prices( $data, $prices, $option_head ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $data = WModes_Premium_Product_Prices_Engine_Meta::add_option_prices( $data, $prices, $option_head );
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

        private function get_valid_prices_options( $product_id ) {

            // check for already validated options
            if ( isset( $this->valid_options[ $product_id ] ) ) {

                return $this->valid_options[ $product_id ];
            }

            $this->valid_options[ $product_id ] = array();

            // go through each options and validate them
            foreach ( $this->get_prices_options( $product_id ) as $price_option ) {

                $price_option[ 'is_global' ] = false;
                $price_option[ 'product_id' ] = $product_id;

                // check for required validations
                if ( !isset( $price_option[ 'condition_args' ][ 'conditions' ] ) ) {
                    $this->valid_options[ $product_id ][] = $price_option;
                    continue;
                }

                // get conditions to validate
                $option_conditions = $price_option[ 'condition_args' ];
                $option_conditions[ 'id' ] = $price_option[ 'pricing_id' ];
                $option_conditions[ 'module' ] = 'product-pricing';
                $option_conditions[ 'product_id' ] = $product_id;
                $option_conditions[ 'is_global' ] = false;
                $option_conditions[ 'context' ] = 'edit';

                // get data to validate
                $data = WModes_Cart::get_data( $option_conditions[ 'context' ] );

                // validate option conditions
                $bool_val = WModes_Condition_Types::validate( $option_conditions, $data );

                if ( !$bool_val ) {

                    continue;
                }

                $this->valid_options[ $product_id ][] = $price_option;
            }

            return $this->valid_options[ $product_id ];
        }

        private function get_prices_options( $product_id ) {

            $rules = array();

            foreach ( WModes::get_meta_option( $product_id, 'wmodes_product_pricings', array() ) as $rule ) {

                $rules[] = $rule;
            }

            return $rules;
        }

        private function use_meta( $product_id ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Product_Prices_Engine_Meta::use_meta( $product_id );
            }

            $settings = $this->get_settings( $product_id );

            if ( 'global' == $settings[ 'enable' ] ) {

                return false;
            }

            return true;
        }

        private function merge_prices( $global_prices, $meta_prices ) {

            $prices = $global_prices;

            foreach ( $meta_prices as $key => $meta_price ) {
                $prices[ $key ] = $meta_price;
            }

            return $prices;
        }

        private function get_settings( $product_id ) {

            $default = array(
                'enable' => 'global',
            );

            return WModes::get_meta_option( $product_id, 'wmodes_product_prices_settings', $default );
        }

        private function get_is_meta_enabled() {

            $dafault = array(
                'product_pricing' => 'yes',
            );

            $opt = WModes::get_option( 'meta_boxes', $dafault );

            return ($opt[ 'product_pricing' ]);
        }

    }

}
