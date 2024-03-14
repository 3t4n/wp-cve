<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Shop_View' ) ) {

    class WModes_Pipeline_Shop_View {

        private $shop_views;

        public function __construct() {

            $this->shop_views = array();

            $before_title = $this->get_locations( 'before_title' );

            if ( $before_title ) {
                add_action( $before_title[ 'hook' ], array( $this, 'render_before_title' ), $before_title[ 'priority' ] );
            }

            $after_title = $this->get_locations( 'after_title' );

            if ( $after_title ) {
                add_action( $after_title[ 'hook' ], array( $this, 'render_after_title' ), $after_title[ 'priority' ] );
            }

            $after_rating = $this->get_locations( 'after_rating' );

            if ( $after_rating ) {
                add_action( $after_rating[ 'hook' ], array( $this, 'render_after_rating' ), $after_rating[ 'priority' ] );
            }
            
            $after_price = $this->get_locations( 'after_price' );

            if ( $after_price ) {
                add_action( $after_price[ 'hook' ], array( $this, 'render_after_price' ), $after_price[ 'priority' ] );
            }
            
            $after_add_to_cart = $this->get_locations( 'after_add_to_cart' );

            if ( $after_add_to_cart ) {
                add_action( $after_add_to_cart[ 'hook' ], array( $this, 'render_after_add_to_cart' ), $after_add_to_cart[ 'priority' ] );
            }
        }

        public function render_before_title() {

            global $product;

            $views_data = $this->get_views_by_location( $product, 'before_title' );

            foreach ( $views_data as $view_data ) {

                WModes_Views::render_view( $view_data );
            }
        }

        public function render_after_title() {

            global $product;

            $views_data = $this->get_views_by_location( $product, 'after_title' );

            foreach ( $views_data as $view_data ) {

                WModes_Views::render_view( $view_data );
            }
        }

        public function render_after_rating() {

            global $product;

            $views_data = $this->get_views_by_location( $product, 'after_rating' );

            foreach ( $views_data as $view_data ) {

                WModes_Views::render_view( $view_data );
            }
        }
        
        public function render_after_price() {

            global $product;

            $views_data = $this->get_views_by_location( $product, 'after_price' );

            foreach ( $views_data as $view_data ) {

                WModes_Views::render_view( $view_data );
            }
        }
        
        public function render_after_add_to_cart() {

            global $product;

            $views_data = $this->get_views_by_location( $product, 'after_add_to_cart' );

            foreach ( $views_data as $view_data ) {

                WModes_Views::render_view( $view_data );
            }
        }

        private function get_views_by_location( $product, $location ) {

            $location_views = array();

            $views = $this->get_views( $product );

            foreach ( $views as $view ) {

                if ( $location != $view[ 'location' ] ) {

                    continue;
                }

                $location_views[] = $view;
            }

            return $location_views;
        }

        private function get_views( $product ) {

            $product_id = $product->get_id();

            if ( isset( $this->shop_views[ $product_id ] ) ) {

                return $this->shop_views[ $product_id ];
            }

            $product_views = WModes_Pipeline::get_product_views( $product, 'shop' );

            $shop_views = array();

            if ( !$product_views ) {

                return $shop_views;
            }

            foreach ( $product_views as $product_view ) {

                $option_type = $product_view[ 'option_type' ];

                if ( !isset( $product_view[ $option_type ][ 'views' ][ 'shop' ] ) ) {

                    continue;
                }

                $shop_views[] = $product_view[ $option_type ][ 'views' ][ 'shop' ];
            }

            $this->shop_views[ $product_id ] = $shop_views;

            return $this->shop_views[ $product_id ];
        }

        private function get_locations( $location_key ) {

            $locations = WModes_Views::get_view_locations();

            if ( isset( $locations[ 'shop' ][ $location_key ] ) ) {

                return $locations[ 'shop' ][ $location_key ];
            }

            return false;
        }

    }

    new WModes_Pipeline_Shop_View();
}