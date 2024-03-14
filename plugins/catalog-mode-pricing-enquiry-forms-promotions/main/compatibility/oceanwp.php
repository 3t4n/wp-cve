<?php

if ( !class_exists( 'WModes_OceanWP' ) && class_exists( 'OCEANWP_Theme_Class' ) ) {

    class WModes_OceanWP {

        public function __construct() {

            add_filter( 'wmodes/register-view-locations', array( $this, 'register_view_locations' ), 999 );
        }

        public function register_view_locations( $view_locations ) {


            $view_locations[ 'shop' ] = $this->register_loop_view_locations( $view_locations[ 'shop' ] );

            $view_locations[ 'single-product' ] = $this->register_summary_view_locations( $view_locations[ 'single-product' ] );

            return $view_locations;
        }

        private function register_loop_view_locations( $view_locations ) {

            $view_locations[ 'before_title' ][ 'hook' ] = 'ocean_before_archive_product_title';

            $view_locations[ 'after_title' ][ 'hook' ] = 'ocean_after_archive_product_title';
            
            $view_locations[ 'after_rating' ][ 'hook' ] = 'ocean_after_archive_product_rating';
            
            $view_locations[ 'after_price' ][ 'hook' ] = 'ocean_after_archive_product_inner';
            
            $view_locations[ 'after_add_to_cart' ][ 'hook' ] = 'ocean_after_archive_product_add_to_cart';

            return $view_locations;
        }

        private function register_summary_view_locations( $view_locations ) {

            $view_locations[ 'before_title' ][ 'hook' ] = 'ocean_before_single_product_title';

            $view_locations[ 'after_title' ][ 'hook' ] = 'ocean_after_single_product_title';

            $view_locations[ 'after_rating' ][ 'hook' ] = 'ocean_after_single_product_price';

            $view_locations[ 'after_excerpt' ][ 'hook' ] = 'ocean_after_single_product_excerpt';

            $view_locations[ 'after_add_to_cart' ][ 'hook' ] = 'ocean_after_single_product_quantity-button';

            $view_locations[ 'after_meta' ][ 'hook' ] = 'ocean_after_single_product_meta';

            return $view_locations;
        }

    }

    new WModes_OceanWP();
}

