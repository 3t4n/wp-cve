<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Option_Type_TextBlock' ) ) {

    class WModes_Product_Option_Type_TextBlock {

        private $ui_type = 'textblock';

        public function get_option( $option, $option_args, $data ) {

            $option[ 'textblock' ] = $this->get_textblock( $option_args, $data );

            return $option;
        }

        private function get_textblock( $option_args, $data ) {

            if ( !$this->can_process_view( $data ) ) {
                return array();
            }

            return array(
                'views' => $this->get_single_product_view( $this->get_shop_view( array(), $option_args, $data ), $option_args, $data )
            );
        }

        private function get_shop_view( $textblock_options, $option_args, $data ) {

            $show_in_shop = ('no' != $option_args[ 'shop' ][ 'enable' ]);

            if ( !$show_in_shop ) {

                return $textblock_options;
            }

            $textblock_options[ 'shop' ][ 'shop_loop_args' ] = $this->get_shop_loop_args( $option_args );

            $textblock_options[ 'shop' ][ 'view_id' ] = 'shop-textblock';

            return $this->process_ui_options( $textblock_options, $option_args, 'shop', 'shop' );
        }

        private function get_single_product_view( $textblock_options, $option_args, $data ) {

            $show_in_single_product = ('yes' == $option_args[ 'single_product' ][ 'enable' ]);

            if ( !$show_in_single_product ) {

                return $textblock_options;
            }

            $textblock_options[ 'single-product' ][ 'view_id' ] = 'single-textblock';

            return $this->process_ui_options( $textblock_options, $option_args, 'single-product', 'single_product' );
        }

        private function process_ui_options( $textblock_options, $option_args, $page_id, $fields_group ) {

            $option_ui_id = $this->get_ui_id( $option_args[ $fields_group ][ 'ui_id' ] );

            $textblock_options[ $page_id ][ 'contents' ] = apply_filters( 'wmodes/get-textblock-contents', $option_args[ 'contents' ], $option_ui_id );

            $textblock_options[ $page_id ][ 'location' ] = $option_args[ $fields_group ][ 'location' ];

            $textblock_options[ $page_id ][ 'ui_css_class' ] = WModes_Views_CSS::get_css_class( $option_ui_id, $this->ui_type );

            return $textblock_options;
        }

        private function get_ui_id( $option_ui_id ) {

            if ( empty( $option_ui_id ) ) {

                return '2234343';
            }

            return $option_ui_id;
        }

        private static function can_process_view( $data ) {

            if ( !isset( $data[ 'wc' ][ 'product' ] ) ) {

                return false;
            }

            $product_data = $data[ 'wc' ][ 'product' ];

            if ( 'variable' == $product_data[ 'type' ] && $product_data[ 'variation_id' ] > 0 ) {

                return false;
            }

            return true;
        }

        private function get_shop_loop_args( $option_args ) {

            $shop_loop_args = array(
                'loops' => array()
            );

            if ( isset( $option_args[ 'shop_loop_args' ][ 'loops' ] ) && is_array( $option_args[ 'shop_loop_args' ][ 'loops' ] ) ) {

                $shop_loop_args[ 'loops' ] = $option_args[ 'shop_loop_args' ][ 'loops' ];
            }

            $shop_loop_args[ 'compare' ] = $option_args[ 'shop_loop_args' ][ 'compare' ];

            return $shop_loop_args;
        }

    }

}

