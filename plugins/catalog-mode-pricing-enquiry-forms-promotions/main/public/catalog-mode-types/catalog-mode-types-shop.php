<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Catalog_Mode_Types_Shop' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Catalog_Mode_Types_Shop {

        private $text_block_ui_type = 'textblock';

        public function get_mode( $mode, $mode_args, $data ) {

            $mode[ 'shop' ] = $this->get_shop_mode( array(), $mode_args, $data );

            return WModes_Catalog_Mode_Types::prepare_mode( $mode, $mode_args );
        }

        private function get_shop_mode( $mode_options, $mode_args, $data ) {

            if ( !self::can_process_mode( $data ) ) {

                return $mode_options;
            }

            $mode_options = array(
                'add_to_cart' => $this->get_add_to_cart( $mode_args, $data ),
                'price' => $this->get_price( $mode_args, $data ),
            );

            return $mode_options;
        }

        private function get_add_to_cart( $mode_args, $data ) {

            $enabled = ('show' == $mode_args[ 'enable_add_to_cart' ]);

            $options = array(
                'enable' => $enabled,
            );

            if ( $enabled ) {

                return $this->get_add_to_cart_customize( $options, $mode_args );
            }

            return $this->get_add_to_cart_replace( $options, $mode_args, $data );
        }

        private function get_price( $mode_args, $data ) {

            $enabled = ('show' == $mode_args[ 'enable_price' ]);

            $options = array(
                'enable' => $enabled,
            );

            if ( $enabled ) {

                return $options;
            }

            return $this->get_price_replace( $options, $mode_args, $data );
        }

        private function get_add_to_cart_customize( $options, $mode_args ) {

            $customize = ('yes' == $mode_args[ 'add_to_cart_customize' ]);

            if ( !$customize ) {

                $options[ 'customize' ] = $customize;

                return $options;
            }

            $options[ 'customize' ] = array(
                'text' => apply_filters( 'wmodes/get-shop-add-to-cart-text', $mode_args[ 'add_to_cart_text' ], $mode_args[ 'id' ], true ),
                'url' => false,
            );

            $url = '';

            if ( 'url' == $mode_args[ 'add_to_cart_link_type' ] ) {

                $url = $mode_args[ 'add_to_cart_url' ];
            }

            if ( empty( $url ) ) {

                return $options;
            }

            $options[ 'customize' ][ 'url' ] = $url;

            return $options;
        }

        private function get_add_to_cart_replace( $options, $mode_args, $data ) {

            $replace = ('no' != $mode_args[ 'add_to_cart_replace' ]);

            $options[ 'replace' ] = $replace;

            if ( !$replace ) {

                return $options;
            }

            $options[ 'textblock' ] = $this->get_add_to_cart_replace_textblock( $mode_args, $data );

            return $options;
        }

        private function get_add_to_cart_replace_textblock( $mode_args, $data ) {

            if ( 'replace_textblock' != $mode_args[ 'add_to_cart_replace' ] ) {

                return false;
            }

            $textblock_options = array();

            $textblock_options[ 'view_id' ] = 'shop-textblock';

            $option_ui_id = $this->get_ui_id( $mode_args[ 'add_to_cart_textblock_ui_id' ] );

            $textblock_options[ 'contents' ] = apply_filters( 'wmodes/get-shop-add-to-cart-textblock-contents', $mode_args[ 'add_to_cart_textblock' ], $mode_args[ 'id' ], $mode_args[ 'is_global' ] );

            $textblock_options[ 'ui_css_class' ] = WModes_Views_CSS::get_css_class( $option_ui_id, $this->text_block_ui_type );

            return $textblock_options;
        }

        private function get_price_replace( $options, $mode_args, $data ) {

            $replace = ('no' != $mode_args[ 'price_replace' ]);

            $options[ 'replace' ] = $replace;

            if ( !$replace ) {

                return $options;
            }

            $options[ 'textblock' ] = $this->get_price_replace_textblock( $mode_args, $data );

            return $options;
        }

        private function get_price_replace_textblock( $mode_args, $data ) {

            if ( 'replace_textblock' != $mode_args[ 'price_replace' ] ) {

                return false;
            }

            $textblock_options = array();

            $textblock_options[ 'view_id' ] = 'shop-textblock';

            $option_ui_id = $this->get_ui_id( $mode_args[ 'prices_textblock_ui_id' ] );

            $textblock_options[ 'contents' ] = apply_filters( 'wmodes/get-shop-price-textblock-contents', $mode_args[ 'price_textblock' ], $mode_args[ 'id' ], $mode_args[ 'is_global' ] );

            $textblock_options[ 'ui_css_class' ] = WModes_Views_CSS::get_css_class( $option_ui_id, $this->text_block_ui_type );

            return $textblock_options;
        }

        private function get_ui_id( $option_ui_id ) {

            if ( empty( $option_ui_id ) ) {

                return '2234343';
            }

            return $option_ui_id;
        }

        private static function can_process_mode( $data ) {

            if ( !$data[ 'wc' ][ 'is_product' ] ) {

                return false;
            }

            if ( !isset( $data[ 'wc' ][ 'product' ] ) ) {

                return false;
            }

            $product_data = $data[ 'wc' ][ 'product' ];

            if ( 'variable' == $product_data[ 'type' ] && $product_data[ 'variation_id' ] > 0 ) {

                return false;
            }

            return true;
        }

    }

}