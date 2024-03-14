<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Catalog_Mode_Engine_Meta' ) ) {

    class WModes_Catalog_Mode_Engine_Meta {

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

        public function process_product_modes( $data ) {

            if ( !$data[ 'wc' ][ 'is_product' ] ) {

                return $data;
            }

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

            if ( $product_id == 0 ) {

                return true;
            }

            if ( 'no' == $this->get_is_meta_enabled() ) {

                return true;
            }

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return WModes_Premium_Catalog_Mode_Engine_Meta::use_global( $product_id );
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

            if ( isset( $data[ 'modes' ] ) ) {

                $global_options = $data[ 'modes' ];

                unset( $data[ 'modes' ] );
            }

            $data = $this->process_options( $data, $product_id );

            if ( isset( $data[ 'modes' ] ) ) {

                $data[ 'modes' ] = $this->merge_options( $global_options, $data[ 'modes' ] );
            } else {
                $data[ 'modes' ] = $global_options;
            }

            return $data;
        }

        private function get_options_hash( $hash_keys, $product_id ) {

            // go through the options and apply them as keys
            foreach ( $this->get_valid_mode_options( $product_id ) as $mode_option ) {

                if ( !isset( $hash_keys[ 'modes' ] ) ) {

                    $hash_keys[ 'modes' ] = array();
                }

                $hash_key = md5( wp_json_encode( $mode_option ) );

                $hash_keys[ 'modes' ][ $mode_option[ 'mode_id' ] ] = $hash_key;
            }

            return $hash_keys;
        }

        private function process_options( $data, $product_id ) {

            // go through the mode options and apply them
            foreach ( $this->get_valid_mode_options( $product_id ) as $mode_option ) {

                $mode_option_head = array(
                    'mode_id' => $mode_option[ 'mode_id' ],
                    'module' => 'catalog-modes',
                    'is_global' => $mode_option[ 'is_global' ],
                    'product_id' => $mode_option[ 'product_id' ],
                );

                if ( !$this->can_apply_option( $mode_option_head, $data ) ) {

                    continue;
                }

                $modes = $this->get_modes( $mode_option, $data );

                if ( has_filter( 'wmodes/catalog-modes/gotten-modes' ) ) {

                    $modes = apply_filters( 'wmodes/catalog-modes/gotten-modes', $modes, $mode_options[ 'mode_id' ], $mode_options[ 'is_global' ], $data );
                }

                $data = $this->add_mode_option( $data, $modes, $mode_option_head );
            }

            return $data;
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

        private function can_apply_option( $mode_option_head, $data ) {

            $bool_val = true;

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $bool_val = WModes_Premium_Catalog_Mode_Engine_Meta::can_apply_option( $mode_option_head, $data );
            }

            // allows other plugins to check if the option should apply
            if ( has_filter( 'wmodes/catalog-modes/can-apply-option' ) ) {

                $bool_val = apply_filters( 'wmodes/catalog-modes/can-apply-option', $bool_val, $mode_option_head, $data );
            }

            return $bool_val;
        }

        private function add_mode_option( $data, $option_modes, $mode_options_head ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {
                $data = WModes_Premium_Catalog_Mode_Engine_Meta::add_mode_option( $data, $option_modes, $mode_options_head );
            } else {
                $data[ 'modes' ][ $mode_options_head[ 'mode_id' ] ] = $option_modes;
            }

            // allows other plugins to modify options data once added
            if ( has_filter( 'wmodes/catalog-modes/added-option' ) ) {
                if ( isset( $data[ 'modes' ][ $mode_options_head[ 'mode_id' ] ] ) ) {
                    $data[ 'modes' ][ $mode_options_head[ 'mode_id' ] ] = apply_filters( 'wmodes/catalog-modes/added-options', $data[ 'modes' ][ $mode_options_head[ 'mode_id' ] ], $mode_options_head );
                }
            }

            return $data;
        }

        private function get_valid_mode_options( $product_id ) {

            // check for already validated options
            if ( isset( $this->valid_options[ $product_id ] ) ) {

                return $this->valid_options[ $product_id ];
            }

            $this->valid_options[ $product_id ] = array();

            // go through each options and validate them
            foreach ( $this->get_mode_options( $product_id ) as $mode_option ) {

                $mode_option[ 'is_global' ] = false;
                $mode_option[ 'product_id' ] = $product_id;

                // check for required validations
                if ( !isset( $mode_option[ 'condition_args' ][ 'conditions' ] ) ) {
                    $this->valid_options[ $product_id ][] = $mode_option;
                    continue;
                }

                // get conditions to validate
                $option_conditions = $mode_option[ 'condition_args' ];
                $option_conditions[ 'id' ] = $mode_option[ 'mode_id' ];
                $option_conditions[ 'module' ] = 'catalog-modes';
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

                $this->valid_options[ $product_id ][] = $mode_option;
            }

            return $this->valid_options[ $product_id ];
        }

        private function get_mode_options( $product_id ) {

            $rules = array();

            foreach ( WModes::get_meta_option( $product_id, 'wmodes_catalog_modes', array() ) as $rule ) {

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

                return WModes_Premium_Catalog_Mode_Engine_Meta::use_meta( $product_id );
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

            return WModes::get_meta_option( $product_id, 'wmodes_catalog_mode_settings', $default );
        }

        private function get_is_meta_enabled() {

            $dafault = array(
                'catalog_mode' => 'yes',
            );

            $opt = WModes::get_option( 'meta_boxes', $dafault );

            return ($opt[ 'catalog_mode' ]);
        }

    }

}