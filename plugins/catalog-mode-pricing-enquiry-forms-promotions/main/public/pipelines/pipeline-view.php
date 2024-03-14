<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_View' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_View {

        private static $instance;
        private $in_summary;

        private function __construct() {

            $this->init_summary_locations();

            add_filter( 'woocommerce_get_price_html', array( $this, 'get_price_html' ), 99999, 2 );
        }

        public static function get_instance(): self {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_price_html( $price_html, $product ) {

            $shop_catalog = WModes_Pipeline_Shop_Catalog::get_instance();

            if ( $shop_catalog->get_hide_price( $product, $this->get_in_summary() ) ) {

                return '';
            }

            return $price_html;
        }

        public function do_before_summary() {

            $this->in_summary = true;
        }

        public function do_after_summary() {

            $this->in_summary = false;
        }

        public function get_in_summary() {

            return apply_filters( 'wmodes/get-in-summary', $this->in_summary );
        }

        private function init_summary_locations() {

            $this->in_summary = false;

            $before_summary = $this->get_summary_locations( 'before_summary' );

            if ( $before_summary ) {
                add_action( $before_summary[ 'hook' ], array( $this, 'do_before_summary' ), $before_summary[ 'priority' ] );
            }

            $after_summary = $this->get_summary_locations( 'after_summary' );

            if ( $after_summary ) {
                add_action( $after_summary[ 'hook' ], array( $this, 'do_after_summary' ), $after_summary[ 'priority' ] );
            }
        }

        private function get_summary_locations( $location_key ) {

            $locations = WModes_Views::get_view_locations();

            if ( isset( $locations[ 'single-product' ][ $location_key ] ) ) {

                return $locations[ 'single-product' ][ $location_key ];
            }

            return false;
        }

    }

}
