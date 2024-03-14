<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Catalog_Mode_Engine' ) ) {

    class WModes_Catalog_Mode_Engine {

        private $valid_options;
        private $shop_modes;
        private $product_list;
        private $product_hash_list;
        private $engine_meta;

        public function __construct() {

            $this->valid_options = array();
            $this->shop_modes = array();
            $this->product_list = array();
            $this->product_hash_list = array();

            $this->engine_meta = new WModes_Catalog_Mode_Engine_Meta();
        }

        public function process_data( $data, $context ) {

            //return in memory options
            if ( count( $this->shop_modes ) ) {

                $data[ 'modes' ] = $this->shop_modes;

                return $data;
            }

            //process options
            $data[ 'wc' ][ 'is_product' ] = false;
            $data = $this->process_modes( $data );

            //store options into memory for re-use
            if ( isset( $data[ 'modes' ] ) ) {

                $this->shop_modes = $data[ 'modes' ];
            }

            return $data;
        }

        public function process_product_hash( $hash_keys, $product_id ) {

            //return in memory options hash
            if ( isset( $this->product_hash_list[ $product_id ] ) ) {

                return $this->product_hash_list[ $product_id ];
            }

            //process options hash
            $this->product_hash_list[ $product_id ] = $this->get_mode_hash( $hash_keys, $product_id );

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

                $data[ 'modes' ] = $this->product_list[ $cache_key ];

                return $data;
            }

            //process options
            $data[ 'wc' ][ 'is_product' ] = true;
            $data = $this->process_modes( $data );

            //store options into memory for re-use
            if ( isset( $data[ 'modes' ] ) ) {

                $this->product_list[ $cache_key ] = $data[ 'modes' ];
            } 

            return $data;
        }

        private function get_mode_hash( $hash_keys, $product_id ) {

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
            foreach ( $this->get_valid_options() as $mode_options ) {

                if ( !isset( $hash_keys[ 'modes' ] ) ) {

                    $hash_keys[ 'modes' ] = array();
                }

                $hash_key = md5( wp_json_encode( $mode_options ) );

                $hash_keys[ 'modes' ][ $mode_options[ 'mode_id' ] ] = $hash_key;
            }

            return $this->engine_meta->process_product_hash( $hash_keys, $product_id );
        }

        private function process_modes( $data ) {

            $settings = $this->get_settings();

            //check if options should apply
            if ( 'no' == $settings[ 'mode' ] ) {

                //process meta data and return result
                return $this->engine_meta->process_product_modes( $data );
            }

            // check for meta settings
            if ( !$this->engine_meta->use_global( $this->get_product_id_from_data( $data ) ) ) {

                // process meta data only and return result
                return $this->engine_meta->process_product_modes( $data );
            }

            // go through the options and apply them
            foreach ( $this->get_valid_options() as $mode_options ) {

                $mode_options_head = array(
                    'apply_mode' => $mode_options[ 'apply_mode' ],
                    'mode_id' => $mode_options[ 'mode_id' ],
                    'settings' => $settings,
                    'module' => 'catalog-modes',
                    'is_global' => $mode_options[ 'is_global' ],
                );

                if ( !$this->can_apply_option( $mode_options_head, $data ) ) {

                    continue;
                }

                $modes = $this->get_modes( $mode_options, $data );

                if ( has_filter( 'wmodes/catalog-modes/gotten-modes' ) ) {

                    $modes = apply_filters( 'wmodes/catalog-modes/gotten-modes', $modes, $mode_options[ 'mode_id' ], $mode_options[ 'is_global' ], $data );
                }

                $data = $this->add_mode_option( $data, $modes, $mode_options_head );
            }

            //process meta data and return result
            return $this->engine_meta->process_product_modes( $data );
        }

        private function get_modes( $mode_options, $data ) {

            $modes_args = $this->get_args_from_mode_options( $mode_options, $data );

            return WModes_Catalog_Mode_Types::get_modes( $modes_args, $data );
        }

        private function get_args_from_mode_options( $mode_options, $data ) {

            $modes_args = array();

            if ( !isset( $mode_options[ 'options' ] ) ) {

                return $modes_args;
            }

            foreach ( $mode_options[ 'options' ] as $key => $mode_option_arg ) {

                $modes_args[ $key ] = $mode_option_arg;

                $modes_args[ $key ][ 'mode_id' ] = $mode_options[ 'mode_id' ];

                $modes_args[ $key ][ 'module' ] = 'catalog-modes';

                $modes_args[ $key ][ 'is_global' ] = $mode_options[ 'is_global' ];
                $modes_args[ $key ][ 'is_product' ] = $data[ 'wc' ][ 'is_product' ];
            }

            return $modes_args;
        }

        private function can_apply_option( $mode_options_head, $data ) {

            $bool_val = true;

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $bool_val = WModes_Premium_Catalog_Mode_Engine::can_apply_option( $mode_options_head, $data );
            }

            // allows other plugins to check if the option should apply
            if ( has_filter( 'wmodes/catalog-modes/can-apply-option' ) ) {

                $bool_val = apply_filters( 'wmodes/catalog-modes/can-apply-option', $bool_val, $mode_options_head, $data );
            }

            return $bool_val;
        }

        private function add_mode_option( $data, $option_modes, $mode_options_head ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $data = WModes_Premium_Catalog_Mode_Engine::add_mode_option( $data, $option_modes, $mode_options_head );
            } else {
                $data[ 'modes' ][ $mode_options_head[ 'mode_id' ] ] = $option_modes;
            }

            // allows other plugins to modify prices data once added
            if ( has_filter( 'wmodes/catalog-modes/added-option-modes' ) ) {
                if ( isset( $data[ 'modes' ][ $mode_options_head[ 'mode_id' ] ] ) ) {
                    $data[ 'modes' ][ $mode_options_head[ 'mode_id' ] ] = apply_filters( 'wmodes/catalog-modes/added-option-modes', $data[ 'modes' ][ $mode_options_head[ 'mode_id' ] ], $mode_options_head );
                }
            }

            return $data;
        }

        private function get_valid_options() {

            // check for already validated options
            if ( count( $this->valid_options ) ) {

                return $this->valid_options;
            }

            // go through each options and validate them
            foreach ( $this->get_options() as $mode_options ) {

                // allows active options only
                if ( 'no' == $mode_options[ 'enable' ] ) {

                    continue;
                }

                $mode_options[ 'is_global' ] = true;

                // check for required validations
                if ( !isset( $mode_options[ 'condition_args' ][ 'conditions' ] ) ) {

                    $this->valid_options[] = $mode_options;
                    continue;
                }

                // get conditions to validate
                $option_conditions = $mode_options[ 'condition_args' ];
                $option_conditions[ 'id' ] = $mode_options[ 'mode_id' ];
                $option_conditions[ 'module' ] = 'catalog-modes';
                $option_conditions[ 'is_global' ] = true;
                $option_conditions[ 'context' ] = 'edit';

                // get data to validate
                $data = WModes_Cart::get_data( $option_conditions[ 'context' ] );

                // validate option conditions
                $bool_val = WModes_Condition_Types::validate( $option_conditions, $data );

                if ( !$bool_val ) {

                    continue;
                }

                $this->valid_options[] = $mode_options;
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

        private function get_options() {

            $rules = array();

            foreach ( WModes::get_option( 'catalog_modes', array() ) as $rule ) {

                $rules[] = $rule;
            }

            return $rules;
        }

        private function get_settings() {

            $default = array(
                'mode' => 'no',
            );

            return WModes::get_option( 'catalog_mode_settings', $default );
        }

    }

}