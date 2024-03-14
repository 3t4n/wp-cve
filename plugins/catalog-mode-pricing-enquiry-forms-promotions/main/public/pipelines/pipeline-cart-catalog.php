<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Cart_Catalog' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_Cart_Catalog {

        private static $instance;
        private $mode_list;
        private $totals_calculated;

        private function __construct() {

            $this->mode_list = array();
            $this->totals_calculated = false;

            add_filter( 'woocommerce_get_cart_contents', array( $this, 'get_cart_contents' ), 99999 );

            add_filter( 'woocommerce_cart_contents_count', array( $this, 'get_cart_contents_count' ), 99999 );

            add_action( 'woocommerce_before_calculate_totals', array( $this, 'do_before_calculating' ) );
            add_action( 'woocommerce_after_calculate_totals', array( $this, 'do_after_calculating' ) );

            add_action( 'wp', array( $this, 'process_authorization' ), 99999 );
        }

        public static function get_instance(): self {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_cart_contents( $cart_contents ) {

            if ( !$this->totals_calculated ) {

                return $cart_contents;
            }

            $mode = $this->get_modes( 'cart', $this->get_default_modes( 'cart' ) );

            if ( !$mode ) {

                return $cart_contents;
            }

            if ( !$mode[ 'restrict' ] ) {

                return $cart_contents;
            }

            return array();
        }

        public function get_cart_contents_count( $contents_count ) {

            if ( !$this->totals_calculated ) {

                return $contents_count;
            }

            $mode = $this->get_modes( 'cart', $this->get_default_modes( 'cart' ) );

            if ( !$mode ) {

                return $contents_count;
            }

            if ( $mode[ 'restrict' ] ) {

                return 0;
            }

            return $contents_count;
        }

        public function process_authorization() {

            if ( $this->restrict_cart() ) {

                return;
            }

            $this->restrict_checkout();
        }

        public function do_before_calculating( $cart ) {

            WModes_Cart_Session::get_instance()->before_calculate_totals( $cart );

            $this->totals_calculated = false;
        }

        public function do_after_calculating() {

            $this->totals_calculated = true;
        }

        private function restrict_cart() {

            if ( !is_cart() ) {

                return false;
            }

            $mode = $this->get_modes( 'cart', $this->get_default_modes( 'cart' ) );

            if ( !$mode ) {

                return false;
            }

            if ( !$mode[ 'restrict' ] ) {

                return false;
            }


            if ( $mode[ 'redirect' ] ) {

                $this->process_http_redirect( $mode[ 'redirect' ] );

                return true;
            }

            return false;
        }

        private function restrict_checkout() {

            if ( !is_cart() ) {

                return false;
            }

            $mode = $this->get_modes( 'checkout', $this->get_default_modes( 'checkout' ) );

            if ( !$mode ) {

                return false;
            }

            if ( !$mode[ 'restrict' ] ) {

                return false;
            }


            if ( $mode[ 'redirect' ] ) {

                $this->process_http_redirect( $mode[ 'redirect' ] );

                return true;
            }

            return false;
        }

        private function process_http_redirect( $http_options ) {

            wp_redirect( $http_options[ 'url' ], 307 );
        }

        public function get_modes( $mode_name, $default ) {

            if ( !count( $this->mode_list ) ) {

                $mode_list = WModes_Pipeline::get_site_modes();

                if ( $mode_list ) {

                    $this->mode_list = $mode_list;
                }
            }

            if ( isset( $this->mode_list[ $mode_name ] ) ) {

                return $this->mode_list[ $mode_name ];
            }

            return $default;
        }

        private function get_default_modes( $mode_name ) {

            $defualt_modes = array();

            $defualt_modes[ 'cart' ] = array(
                'restrict' => false,
                'redirect' => false,
            );

            $defualt_modes[ 'checkout' ] = array(
                'restrict' => false,
                'redirect' => false,
            );

            if ( isset( $defualt_modes[ $mode_name ] ) ) {

                return $defualt_modes[ $mode_name ];
            }

            return false;
        }

    }

    WModes_Pipeline_Cart_Catalog::get_instance();
}
        
