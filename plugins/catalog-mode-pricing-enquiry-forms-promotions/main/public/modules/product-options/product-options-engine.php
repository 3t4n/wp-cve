<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Options_Engine' ) ) {

    class WModes_Product_Options_Engine {

        private $valid_options;
        private $product_list;
        private $product_hash_list;
        private $engine_meta;

        public function __construct() {

            $this->valid_options = array();
            $this->product_list = array();
            $this->product_hash_list = array();

            $this->engine_meta = new WModes_Product_Options_Engine_Meta();
        }

        public function process_product_hash( $hash_keys, $product_id ) {

            //return in memory options hash
            if ( isset( $this->product_hash_list[ $product_id ] ) ) {

                return $this->product_hash_list[ $product_id ];
            }

            //process options hash
            $this->product_hash_list[ $product_id ] = $this->get_option_hash( $hash_keys, $product_id );

            //return in processed options hash
            return $this->product_hash_list[ $product_id ];
        }

        public function process_product_data( $data ) {

            $cache_key = $this->get_cache_key( $data );

            //can't process empty product
            if ( empty( $cache_key ) ) {

                return $data;
            }

            //return in memory options
            if ( isset( $this->product_list[ $cache_key ] ) ) {

                $data[ 'options' ] = $this->product_list[ $cache_key ];

                return $data;
            }

            //process options
            $data = $this->process_options( $data );

            //store options into memory for re-use
            if ( isset( $data[ 'options' ] ) ) {

                $this->product_list[ $cache_key ] = $data[ 'options' ];
            } 

            return $data;
        }

        private function get_option_hash( $hash_keys, $product_id ) {

            $settings = $this->get_settings();
            
            //check if options should apply
            if ( 'no' == $settings[ 'mode' ] ) {

                //process meta hash and return result
                return $this->engine_meta->process_product_hash( $hash_keys, $product_id );
            }
            
            // check of meta settings
            if ( !$this->engine_meta->use_global( $product_id ) ) {

                // process meta hash only and return result
                return $this->engine_meta->process_product_hash( $hash_keys, $product_id );
            }

            // go through the options and apply them as keys
            foreach ( $this->get_valid_opr_options() as $opr_option ) {

                if ( !isset( $hash_keys[ 'options' ] ) ) {

                    $hash_keys[ 'options' ] = array();
                }

                $hash_key = md5( wp_json_encode( $opr_option ) );

                $hash_keys[ 'options' ][ $opr_option[ 'option_id' ] ] = $hash_key;
            }

            return $this->engine_meta->process_product_hash( $hash_keys, $product_id );
        }

        private function process_options( $data ) {

            $settings = $this->get_settings();

            //check if options should apply
            if ( 'no' == $settings[ 'mode' ] ) {

                //process meta data and return result
                return $this->engine_meta->process_product_data( $data );
            }

            // check of meta settings
            if ( !$this->engine_meta->use_global( $this->get_product_id_from_data( $data ) ) ) {

                // process meta data only and return result
                return $this->engine_meta->process_product_data( $data );
            }

            // go through the options and apply them
            foreach ( $this->get_valid_opr_options() as $opr_options ) {

                $opr_option_head = array(
                    'apply_mode' => $opr_options[ 'apply_mode' ],
                    'option_id' => $opr_options[ 'option_id' ],
                    'settings' => $settings,
                    'module' => 'product-options',
                    'is_global' => $opr_options[ 'is_global' ],
                );

                if ( !$this->can_apply_option( $opr_option_head, $data ) ) {

                    continue;
                }

                $options = $this->get_options( $opr_options, $data );

                if ( has_filter( 'wmodes/product-options/gotten-options' ) ) {

                    $options = apply_filters( 'wmodes/product-options/gotten-options', $option, $opr_options[ 'option_id' ], $opr_options[ 'is_global' ], $data );
                }

                $data = $this->add_opr_option( $data, $options, $opr_option_head );
            }

            //process meta data and return result
            return $this->engine_meta->process_product_data( $data );
        }

        private function get_options( $opr_options, $data ) {

            $options_args = $this->get_args_from_opr_options( $opr_options );

            return WModes_Product_Option_Types::get_options( $options_args, $data );
        }

        private function get_args_from_opr_options( $opr_options ) {

            $options_args = array();

            if ( !isset( $opr_options[ 'options' ] ) ) {

                return $options_args;
            }

            foreach ( $opr_options[ 'options' ] as $key => $opr_option_arg ) {

                $options_args[ $key ] = $opr_option_arg;

                $options_args[ $key ][ 'option_id' ] = $opr_options[ 'option_id' ];

                $options_args[ $key ][ 'module' ] = 'product-options';

                $options_args[ $key ][ 'is_global' ] = $opr_options[ 'is_global' ];
            }

            return $options_args;
        }

        private function can_apply_option( $opr_option_head, $data ) {


            $bool_val = true;

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $bool_val = WModes_Premium_Product_Options_Engine::can_apply_option( $opr_option_head, $data );
            }

            // allows other plugins to check if the option should apply
            if ( has_filter( 'wmodes/product-options/can-apply-option' ) ) {

                $bool_val = apply_filters( 'wmodes/product-options/can-apply-option', $bool_val, $opr_option_head, $data );
            }

            return $bool_val;
        }

        private function add_opr_option( $data, $option, $opr_option_head ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $data = WModes_Premium_Product_Options_Engine::add_opr_option( $data, $option, $opr_option_head );
            } else {
                $data[ 'options' ][ $opr_option_head[ 'option_id' ] ] = $option;
            }

            // allows other plugins to modify prices data once added
            if ( has_filter( 'wmodes/product-options/added-option' ) ) {
                if ( isset( $data[ 'options' ][ $opr_option_head[ 'option_id' ] ] ) ) {
                    $data[ 'options' ][ $opr_option_head[ 'option_id' ] ] = apply_filters( 'wmodes/product-options/added-options', $data[ 'options' ][ $opr_option_head[ 'option_id' ] ], $opr_option_head );
                }
            }

            return $data;
        }

        private function get_valid_opr_options() {

            // check for already validated options
            if ( count( $this->valid_options ) ) {

                return $this->valid_options;
            }

            // go through each options and validate them
            foreach ( $this->get_opr_options() as $opr_options ) {

                // allows active rules only
                if ( 'no' == $opr_options[ 'enable' ] ) {

                    continue;
                }

                $opr_options[ 'is_global' ] = true;

                // check for required validations
                if ( !isset( $opr_options[ 'condition_args' ][ 'conditions' ] ) ) {

                    $this->valid_options[] = $opr_options;
                    continue;
                }

                // get conditions to validate
                $option_conditions = $opr_options[ 'condition_args' ];
                $option_conditions[ 'id' ] = $opr_options[ 'option_id' ];
                $option_conditions[ 'module' ] = 'product-options';
                $option_conditions[ 'is_global' ] = true;
                $option_conditions[ 'context' ] = 'edit';

                // get data to validate
                $data = WModes_Cart::get_data( $option_conditions[ 'context' ] );

                // validate option conditions
                $bool_val = WModes_Condition_Types::validate( $option_conditions, $data );

                if ( !$bool_val ) {

                    continue;
                }

                $this->valid_options[] = $opr_options;
            }

            return $this->valid_options;
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

        private function get_opr_options() {

            $rules = array();

            foreach ( WModes::get_option( 'product_options', array() ) as $rule ) {

                $rules[] = $rule;
            }

            return $rules;
        }

        private function get_settings() {

            $default = array(
                'mode' => 'no',
            );

            return WModes::get_option( 'product_option_settings', $default );
        }

    }

}