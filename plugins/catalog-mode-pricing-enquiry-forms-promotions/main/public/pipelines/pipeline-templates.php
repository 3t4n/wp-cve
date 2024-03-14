<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Templates' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_Templates {

        private $shop_catalog_mode;

        public function __construct() {

            $this->shop_catalog_mode = WModes_Pipeline_Shop_Catalog::get_instance();

            add_filter( 'wc_get_template', array( $this, 'get_template' ), 99999, 5 );
        }

        public function get_template( $template, $template_name, $args, $template_path, $default_path ) {

            if ( 'loop/add-to-cart.php' == $template_name ) {

                return $this->get_loop_add_to_cart( $template, $template_name );
            }

            if ( 'loop/price.php' == $template_name ) {

                return $this->get_loop_price( $template, $template_name );
            }

            if ( $this->is_single_add_to_cart( $template_name ) ) {

                return $this->get_single_add_to_cart( $template, $template_name );
            }

            if ( 'single-product/price.php' == $template_name ) {

                return $this->get_single_price( $template, $template_name );
            }

            if ( 'single-product/add-to-cart/variable.php' == $template_name ) {

                return $this->get_single_variations( $template, $template_name );
            }
            

            return $template;
        }

        private function get_loop_add_to_cart( $template, $template_name ) {

            global $product;

            if ( !$product ) {

                return $template;
            }

            $mode = $this->shop_catalog_mode->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !isset( $mode[ 'add_to_cart' ][ 'enable' ] ) ) {

                return $template;
            }

            if ( !$mode[ 'add_to_cart' ][ 'enable' ] ) {

                return WModes_Views::get_wc_template_path( $template_name );
            }

            return $template;
        }

        private function get_loop_price( $template, $template_name ) {

            global $product;

            if ( !$product ) {

                return $template;
            }

            $mode = $this->shop_catalog_mode->get_modes( 'shop', $product, $this->get_default_modes( 'shop' ) );

            if ( !isset( $mode[ 'price' ][ 'enable' ] ) ) {

                return $template;
            }

            if ( !$mode[ 'price' ][ 'enable' ] ) {

                return WModes_Views::get_wc_template_path( $template_name );
            }

            return $template;
        }

        private function get_single_add_to_cart( $template, $template_name ) {

            global $product;

            if ( !$product ) {

                return $template;
            }

            $mode = $this->shop_catalog_mode->get_modes( 'product', $product, $this->get_default_modes( 'product' ) );

            if ( !isset( $mode[ 'add_to_cart' ][ 'enable' ] ) ) {

                return $template;
            }

            if ( !$mode[ 'add_to_cart' ][ 'enable' ] ) {

                return WModes_Views::get_wc_template_path( 'single-product/add-to-cart.php' );
            }

            return $template;
        }

        private function get_single_price( $template, $template_name ) {

            global $product;

            if ( !$product ) {

                return $template;
            }

            $mode = $this->shop_catalog_mode->get_modes( 'product', $product, $this->get_default_modes( 'product' ) );

            if ( !isset( $mode[ 'price' ][ 'enable' ] ) ) {

                return $template;
            }

            if ( !$mode[ 'price' ][ 'enable' ] ) {

                return WModes_Views::get_wc_template_path( $template_name );
            }

            return $template;
        }

        private function get_single_variations( $template, $template_name ) {

            global $product;

            if ( !$product ) {

                return $template;
            }

            $mode = $this->shop_catalog_mode->get_modes( 'product', $product, $this->get_default_modes( 'product' ) );

            if ( !isset( $mode[ 'variations' ] ) ) {

                return $template;
            }

            if ( !$mode[ 'variations' ] ) {

                return WModes_Views::get_wc_template_path( 'single-product/variations.php' );
            }

            return $template;
        }

        private function is_single_add_to_cart( $template_name ) {

            if ( 'single-product/add-to-cart/external.php' == $template_name ) {

                return true;
            }

            if ( 'single-product/add-to-cart/grouped.php' == $template_name ) {

                return true;
            }

            if ( 'single-product/add-to-cart/simple.php' == $template_name ) {

                return true;
            }

            if ( 'single-product/add-to-cart/variation-add-to-cart-button.php' == $template_name ) {

                return true;
            }

            return false;
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
                'star_rating' => true,
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
                'star_rating' => true,
            );

            if ( isset( $defualt_modes[ $mode_name ] ) ) {

                return $defualt_modes[ $mode_name ];
            }

            return false;
        }

    }

    new WModes_Pipeline_Templates();
}