<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Shop_Catalog' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_Shop_Catalog {

        private static $instance;
        private $mode_list;

        private function __construct() {

            $this->mode_list = array();

            add_filter( 'woocommerce_loop_add_to_cart_args', array( $this, 'get_loop_add_to_cart_args' ), 9999, 2 );
            add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'get_loop_add_to_cart_url' ), 9999, 2 );
            add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'get_loop_add_to_cart_text' ), 9999, 2 );

            add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'get_single_add_to_cart_text' ), 9999, 2 );

            add_filter( 'body_class', array( $this, 'get_body_classes' ), 9999, 2 );
        }

        public static function get_instance(): self {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_loop_add_to_cart_args( $args, $product ) {

            $mode = $this->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !$mode ) {

                return $args;
            }

            if ( !isset( $mode[ 'add_to_cart' ] ) ) {

                return $args;
            }

            $add_to_cart = $mode[ 'add_to_cart' ];

            if ( !$add_to_cart[ 'enable' ] ) {

                return $args;
            }

            if ( !isset( $add_to_cart[ 'customize' ] ) || !$add_to_cart[ 'customize' ] ) {

                return $args;
            }
            
            if ( !isset( $add_to_cart[ 'customize' ][ 'url' ] ) ) {

                return $args;
            }

            if ( $add_to_cart[ 'customize' ][ 'url' ] ) {

                return $this->disable_add_to_cart( $args );
            }


            return $args;
        }

        public function get_loop_add_to_cart_url( $url, $product ) {

            $mode = $this->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !$mode ) {

                return $url;
            }

            if ( !isset( $mode[ 'add_to_cart' ] ) ) {

                return $url;
            }

            $add_to_cart = $mode[ 'add_to_cart' ];

            if ( !$add_to_cart[ 'enable' ] ) {

                return $url;
            }

            if ( !isset( $add_to_cart[ 'customize' ] ) || !$add_to_cart[ 'customize' ] ) {

                return $url;
            }
            
            if ( !isset( $add_to_cart[ 'customize' ][ 'url' ] ) ) {

                return $url;
            }

            if (  $add_to_cart[ 'customize' ][ 'url' ] ) {

                return $add_to_cart[ 'customize' ][ 'url' ];
            }

            return $url;
        }

        public function get_loop_add_to_cart_text( $text, $product ) {

            $mode = $this->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !$mode ) {

                return $text;
            }

            if ( !isset( $mode[ 'add_to_cart' ] ) ) {

                return $text;
            }

            $add_to_cart = $mode[ 'add_to_cart' ];

            if ( !$add_to_cart[ 'enable' ] ) {

                return $text;
            }

            if ( !isset( $add_to_cart[ 'customize' ] ) || !$add_to_cart[ 'customize' ] ) {

                return $text;
            }

            $custom_text = '';

            if ( isset( $add_to_cart[ 'customize' ][ 'text' ] ) ) {

                $custom_text = $add_to_cart[ 'customize' ][ 'text' ];
            }

            if ( !empty( $custom_text ) ) {

                return $custom_text;
            }

            return $text;
        }

        public function get_render_loop_add_to_cart( $product ) {

            $mode = $this->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !$mode ) {

                return;
            }

            if ( !isset( $mode[ 'add_to_cart' ][ 'enable' ] ) ) {

                return;
            }

            if ( $mode[ 'add_to_cart' ][ 'enable' ] ) {

                return;
            }

            if ( !$mode[ 'add_to_cart' ][ 'replace' ] ) {

                return;
            }

            if ( $mode[ 'add_to_cart' ][ 'textblock' ] ) {

                WModes_Views::render_view( $mode[ 'add_to_cart' ][ 'textblock' ] );
            }
        }

        public function get_render_loop_price( $product ) {

            $mode = $this->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !$mode ) {

                return;
            }

            if ( !isset( $mode[ 'price' ][ 'enable' ] ) ) {

                return;
            }

            if ( $mode[ 'price' ][ 'enable' ] ) {

                return;
            }

            if ( !$mode[ 'price' ][ 'replace' ] ) {

                return;
            }

            if ( $mode[ 'price' ][ 'textblock' ] ) {

                WModes_Views::render_view( $mode[ 'price' ][ 'textblock' ] );
            }
        }

        public function get_loop_css_classes( $css_classes, $product ) {

            $mode = $this->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !$mode ) {

                return $css_classes;
            }

            if ( isset( $mode[ 'add_to_cart' ][ 'enable' ] ) && !$mode[ 'add_to_cart' ][ 'enable' ] ) {

                $css_classes[] = 'wmd-loop-hide-add-to-cart';
            }

            if ( isset( $mode[ 'price' ][ 'enable' ] ) && !$mode[ 'price' ][ 'enable' ] ) {

                $css_classes[] = 'wmd-loop-hide-price';
            }

            return $css_classes;
        }

        public function get_single_add_to_cart_text( $text, $product ) {

            $mode = $this->get_modes( 'product', $product, $this->get_default_modes( 'product' ) );

            if ( !$mode ) {

                return $text;
            }

            if ( !isset( $mode[ 'add_to_cart' ] ) ) {

                return $text;
            }

            $add_to_cart = $mode[ 'add_to_cart' ];

            if ( !$add_to_cart[ 'enable' ] ) {

                return $text;
            }

            if ( !isset( $add_to_cart[ 'customize' ] ) || !$add_to_cart[ 'customize' ] ) {

                return $text;
            }
            $custom_text = '';

            if ( isset( $add_to_cart[ 'customize' ][ 'text' ] ) ) {

                $custom_text = $add_to_cart[ 'customize' ][ 'text' ];
            }

            if ( !empty( $custom_text ) ) {

                return $custom_text;
            }

            return $text;
        }

        public function get_render_single_add_to_cart( $product ) {

            $mode = $this->get_modes( 'product', $product, $this->get_default_modes( 'product' ) );

            if ( !$mode ) {

                return;
            }

            if ( !isset( $mode[ 'add_to_cart' ][ 'enable' ] ) ) {

                return;
            }

            if ( $mode[ 'add_to_cart' ][ 'enable' ] ) {

                return;
            }

            if ( !$mode[ 'add_to_cart' ][ 'replace' ] ) {

                return;
            }

            if ( $mode[ 'add_to_cart' ][ 'textblock' ] ) {

                WModes_Views::render_view( $mode[ 'add_to_cart' ][ 'textblock' ] );
            }
        }

        public function get_render_single_price( $product ) {

            $mode = $this->get_modes( 'product', $product, $this->get_default_modes( 'product' ) );

            if ( !$mode ) {

                return;
            }

            if ( !isset( $mode[ 'price' ][ 'enable' ] ) ) {

                return;
            }

            if ( $mode[ 'price' ][ 'enable' ] ) {

                return;
            }

            if ( !$mode[ 'price' ][ 'replace' ] ) {

                return;
            }

            if ( $mode[ 'price' ][ 'textblock' ] ) {

                WModes_Views::render_view( $mode[ 'price' ][ 'textblock' ] );
            }
        }

        public function get_single_css_classes( $css_classes, $product ) {

            $mode = $this->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !$mode ) {

                return $css_classes;
            }

            if ( isset( $mode[ 'add_to_cart' ][ 'enable' ] ) && !$mode[ 'add_to_cart' ][ 'enable' ] ) {

                $css_classes[] = 'wmd-single-hide-add-to-cart';
            }

            if ( isset( $mode[ 'price' ][ 'enable' ] ) && !$mode[ 'price' ][ 'enable' ] ) {

                $css_classes[] = 'wmd-single-hide-price';
            }

            if ( isset( $mode[ 'variations' ] ) && !$mode[ 'variations' ] ) {

                $css_classes[] = 'wmd-single-hide-price';
            }

            return $css_classes;
        }

        public function get_body_classes( $classes, $class ) {

            $cart_catalog = WModes_Pipeline_Cart_Catalog::get_instance();

            if ( !is_product() ) {

                return $classes;
            }

            $product = WModes_Product::get_product( get_the_ID() );

            if ( !$product ) {

                return $classes;
            }

            $mode = $this->get_modes( 'product', $product, $this->get_default_modes( 'product' ) );

            if ( !$mode ) {

                return $classes;
            }

            if ( isset( $mode[ 'add_to_cart' ][ 'enable' ] ) && !$mode[ 'add_to_cart' ][ 'enable' ] ) {

                $classes[] = 'wmd-hide-add-to-cart';
            }

            if ( isset( $mode[ 'price' ][ 'enable' ] ) && !$mode[ 'price' ][ 'enable' ] ) {

                $classes[] = 'wmd-hide-price';
            }


            return $classes;
        }

        public function get_hide_price( $product, $in_summary ) {

            $mode_type = 'shop';

            if ( $in_summary ) {

                $mode_type = 'product';
            }

            $mode = $this->get_modes( $mode_type, $product, $this->get_default_modes( $mode_type ) );

            if ( !$mode ) {

                return false;
            }

            if ( !isset( $mode[ 'price' ][ 'enable' ] ) ) {

                return false;
            }

            if ( $mode[ 'price' ][ 'enable' ] ) {

                return false;
            }

            return true;
        }

        public function get_modes( $mode_name, $product, $default ) {

            $product_id = $product->get_id();

            if ( !isset( $this->mode_list[ $product_id ] ) ) {

                $mode_list = WModes_Pipeline::get_product_modes( $product );

                if ( $mode_list ) {

                    $this->mode_list[ $product_id ] = $mode_list;
                }
            }

            if ( isset( $this->mode_list[ $product_id ][ $mode_name ] ) ) {

                return $this->mode_list[ $product_id ][ $mode_name ];
            }

            return $default;
        }

        private function get_default_modes( $mode_name ) {

            $defualt_modes = array();

            $defualt_modes[ 'shop' ] = array(
                'add_to_cart' => array(
                    'enable' => true,
                    'customize' => false,
                ),
                'price' => array(
                    'enable' => true,
                ),
            );

            $defualt_modes[ 'product' ] = array(
                'add_to_cart' => array(
                    'enable' => true,
                    'customize' => false,
                ),
                'price' => array(
                    'enable' => true,
                ),
                'variations' => true,
            );

            if ( isset( $defualt_modes[ $mode_name ] ) ) {

                return $defualt_modes[ $mode_name ];
            }

            return false;
        }

        private function disable_add_to_cart( $args ) {

            if ( isset( $args[ 'quantity' ] ) ) {

                unset( $args[ 'quantity' ] );
            }

            if ( isset( $args[ 'class' ] ) ) {

                $args_css_classes = explode( ' ', $args[ 'class' ] );

                $css_classes = array();

                foreach ( $args_css_classes as $css_class ) {

                    if ( 'add_to_cart_button' == $css_class || 'ajax_add_to_cart' == $css_class ) {

                        continue;
                    }

                    $css_classes[] = $css_class;
                }

                $args[ 'class' ] = implode( ' ', $css_classes );
            }

            if ( isset( $args[ 'attributes' ][ 'rel' ] ) ) {

                unset( $args[ 'attributes' ][ 'rel' ] );
            }

            return $args;
        }

    }

    WModes_Pipeline_Shop_Catalog::get_instance();
}
