<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Options_Engine_Meta' ) ) {

    class WModes_Product_Options_Engine_Meta {

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

            return $this->get_options_hash( $hash_keys, $product_id );
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

        public function use_global( $product_id ) {

            if ( 'no' == $this->get_is_meta_enabled() ) {

                return true;
            }

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Product_Options_Engine_Meta::use_global( $product_id );
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

        private function process_meta_data( $data, $product_id ) {

            // check if meta is enabled
            if ( !$this->use_meta( $product_id ) ) {

                return $data;
            }

            $global_options = array();

            if ( isset( $data[ 'options' ] ) ) {

                $global_options = $data[ 'options' ];

                unset( $data[ 'options' ] );
            }

            $data = $this->process_options( $data, $product_id );

            if ( isset( $data[ 'options' ] ) ) {

                $data[ 'options' ] = $this->merge_options( $global_options, $data[ 'options' ] );
            } else {
                $data[ 'options' ] = $global_options;
            }

            return $data;
        }

        private function get_options_hash( $hash_keys, $product_id ) {

            // go through the options and apply them as keys
            foreach ( $this->get_valid_opr_options( $product_id ) as $opr_option ) {

                if ( !isset( $hash_keys[ 'options' ] ) ) {

                    $hash_keys[ 'options' ] = array();
                }

                $hash_key = md5( wp_json_encode( $opr_option ) );

                $hash_keys[ 'options' ][ $opr_option[ 'option_id' ] ] = $hash_key;
            }

            return $hash_keys;
        }

        private function process_options( $data, $product_id ) {

            // go through the opr options and apply them
            foreach ( $this->get_valid_opr_options( $product_id ) as $opr_option ) {

                $opr_option_head = array(
                    'option_id' => $opr_option[ 'option_id' ],
                    'module' => 'product-options',
                    'is_global' => $opr_option[ 'is_global' ],
                    'product_id' => $opr_option[ 'product_id' ],
                );


                if ( !$this->can_apply_option( $opr_option_head, $data ) ) {

                    continue;
                }

                $options = $this->get_options( $opr_option, $data );

                if ( has_filter( 'wmodes/product-options/gotten-options' ) ) {

                    $options = apply_filters( 'wmodes/product-options/gotten-options', $option, $opr_option[ 'option_id' ], $opr_option[ 'is_global' ], $data );
                }

                $data = $this->add_opr_option( $data, $options, $opr_option_head );
            }

            return $data;
        }

        private function get_options( $opr_options, $data ) {

            $options = array();

            $options_args = $this->get_args_from_opr_options( $opr_options );

            $options = WModes_Product_Option_Types::get_options( $options_args, $data );

            return $options;
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

                $bool_val = WModes_Premium_Product_Options_Engine_Meta::can_apply_option( $opr_option_head, $data );
            }

            // allows other plugins to check if the option should apply
            if ( has_filter( 'wmodes/product-options/can-apply-option' ) ) {

                $bool_val = apply_filters( 'wmodes/product-options/can-apply-option', $bool_val, $opr_option_head, $data );
            }

            return $bool_val;
        }

        private function add_opr_option( $data, $option, $opr_option_head ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $data = WModes_Premium_Product_Options_Engine_Meta::add_opr_option( $data, $option, $opr_option_head );
            } else {
                $data[ 'options' ][ $opr_option_head[ 'option_id' ] ] = $option;
            }

            // allows other plugins to modify options data once added
            if ( has_filter( 'wmodes/product-options/added-option' ) ) {
                if ( isset( $data[ 'options' ][ $opr_option_head[ 'option_id' ] ] ) ) {
                    $data[ 'options' ][ $opr_option_head[ 'option_id' ] ] = apply_filters( 'wmodes/product-options/added-options', $data[ 'options' ][ $opr_option_head[ 'option_id' ] ], $opr_option_head );
                }
            }

            return $data;
        }

        private function get_valid_opr_options( $product_id ) {

            // check for already validated options
            if ( isset( $this->valid_options[ $product_id ] ) ) {

                return $this->valid_options[ $product_id ];
            }

            $this->valid_options[ $product_id ] = array();

            // go through each options and validate them
            foreach ( $this->get_opr_options( $product_id ) as $opr_option ) {

                $opr_option[ 'is_global' ] = false;
                $opr_option[ 'product_id' ] = $product_id;

                // check for required validations
                if ( !isset( $opr_option[ 'condition_args' ][ 'conditions' ] ) ) {
                    $this->valid_options[ $product_id ][] = $opr_option;
                    continue;
                }

                // get conditions to validate
                $option_conditions = $opr_option[ 'condition_args' ];
                $option_conditions[ 'id' ] = $opr_option[ 'option_id' ];
                $option_conditions[ 'module' ] = 'product-options';
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

                $this->valid_options[ $product_id ][] = $opr_option;
            }

            return $this->valid_options[ $product_id ];
        }

        private function get_opr_options( $product_id ) {

            $rules = array();

            foreach ( WModes::get_meta_option( $product_id, 'wmodes_product_options', array() ) as $rule ) {

                $rules[] = $rule;
            }

            return $rules;
        }

        private function merge_options( $global_options, $meta_options ) {

            $options = $global_options;

            foreach ( $meta_options as $key => $meta_option ) {
                $options[ $key ] = $meta_option;
            }

            return $options;
        }

        private function use_meta( $product_id ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Product_Options_Engine_Meta::use_meta( $product_id );
            }

            $settings = $this->get_settings( $product_id );

            if ( 'global' == $settings[ 'enable' ] ) {

                return false;
            }

            return true;
        }

        private function get_settings( $product_id ) {

            $default = array(
                'enable' => 'global',
            );

            return WModes::get_meta_option( $product_id, 'wmodes_product_options_settings', $default );
        }

        private function get_is_meta_enabled() {

            $dafault = array(
                'product_options' => 'yes',
            );

            $opt = WModes::get_option( 'meta_boxes', $dafault );

            return ($opt[ 'product_options' ]);
        }

    }

}
