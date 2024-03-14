<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Option_Type_Label' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Product_Option_Type_Label {

        private $ui_type = 'label';

        public function get_option( $option, $option_args, $data ) {

            $option[ 'label' ] = $this->get_label( $option_args );

            $option[ 'override' ] = $this->get_replace_prev( $option_args );

            return $option;
        }

        private function get_label( $option_args ) {

            return array(
                'views' => $this->get_single_product_view( $this->get_shop_view( array(), $option_args ), $option_args )
            );
        }

        private function get_shop_view( $label_options, $option_args ) {

            $show_in_shop = ('no' != $option_args[ 'shop' ][ 'enable' ]);

            if ( !$show_in_shop ) {

                return $label_options;
            }

            if ( empty( $option_args[ 'text' ] ) ) {

                return $label_options;
            }

            $label_options[ 'shop' ][ 'shop_loop_args' ] = $this->get_shop_loop_args( $option_args );

            $label_options[ 'shop' ][ 'view_id' ] = 'shop-label';
            $label_options[ 'shop' ][ 'text' ] = $option_args[ 'text' ];
            $label_options[ 'shop' ][ 'location' ] = $option_args[ 'shop' ][ 'location' ];
            $label_options[ 'shop' ][ 'ui_id' ] = $this->get_ui_id( $option_args[ 'shop' ][ 'ui_id' ] );
            $label_options[ 'shop' ][ 'ui_css_class' ] = WModes_Views_CSS::get_css_class( $option_args[ 'shop' ][ 'ui_id' ], $this->ui_type );

            return $label_options;
        }

        private function get_single_product_view( $label_options, $option_args ) {

            $show_in_single_product = ('yes' == $option_args[ 'single_product' ][ 'enable' ]);

            if ( !$show_in_single_product ) {

                return $label_options;
            }

            if ( empty( $option_args[ 'text' ] ) ) {

                return $label_options;
            }

            $label_options[ 'single-product' ][ 'view_id' ] = 'single-label';
            $label_options[ 'single-product' ][ 'text' ] = $option_args[ 'text' ];
            $label_options[ 'single-product' ][ 'location' ] = $option_args[ 'single_product' ][ 'location' ];
            $label_options[ 'single-product' ][ 'ui_id' ] = $this->get_ui_id( $option_args[ 'single_product' ][ 'ui_id' ] );
            $label_options[ 'single-product' ][ 'ui_css_class' ] = WModes_Views_CSS::get_css_class( $option_args[ 'single_product' ][ 'ui_id' ], $this->ui_type );

            return $label_options;
        }

        private function get_ui_id( $option_ui_id ) {

            if ( empty( $option_ui_id ) ) {

                return '2234343';
            }

            return $option_ui_id;
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

        private function get_replace_prev( $option_args ) {

            return ('yes' == $option_args[ 'remove_prev' ]);
        }

    }

}