<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Product_Filter_Type_Products' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Product_Filter_Type_Products {

        public function can_validate( $filter_type ) {

            $filter_types = array(
                'spec_products',
                'product_cats',
                'product_tags'
            );

            return in_array( $filter_type, $filter_types );
        }

        public function validate( $filter_args, $data ) {

            if ( !isset( $data[ 'wc' ][ 'product' ] ) ) {

                return false;
            }

            $product = $data[ 'wc' ][ 'product' ];

            $filter_type = $filter_args[ 'filter_type' ];

            if ( 'spec_products' == $filter_type ) {

                return $this->validate_specific_products( $filter_args, $product );
            }

            if ( 'product_cats' == $filter_type ) {

                return $this->validate_product_categories( $filter_args, $product );
            }

            if ( 'product_tags' == $filter_type ) {

                return $this->validate_product_tags( $filter_args, $product );
            }

            return false;
        }

        private function validate_specific_products( $filter_args, $product ) {

            if ( !isset( $filter_args[ 'product_slugs' ] ) ) {

                return false;
            }

            $filter_product_ids = WModes_Util::get_product_ids_by_slugs( $filter_args[ 'product_slugs' ] );

            if ( !count( $filter_product_ids ) ) {

                return false;
            }

            $filter_compare = $filter_args[ 'compare' ];

            $product_id = $product[ 'product_id' ];

            return WModes_Validation_Util::validate_value_list( $product_id, $filter_product_ids, $filter_compare );
        }

        private function validate_product_categories( $filter_args, $product ) {

            if ( !isset( $filter_args[ 'category_slugs' ] ) ) {
                return false;
            }

            $filter_category_ids = WModes_Util::get_product_term_ids_by_slugs( $filter_args[ 'category_slugs' ], 'product_cat' );

            if ( !count( $filter_category_ids ) ) {
                return false;
            }

            $filter_compare = $filter_args[ 'compare' ];

            $category_ids = $product[ 'category_ids' ];


            return WModes_Validation_Util::validate_list_list( $category_ids, $filter_category_ids, $filter_compare );
        }

        private function validate_product_tags( $filter_args, $product ) {

            if ( !isset( $filter_args[ 'tag_slugs' ] ) ) {
                return false;
            }

            $filter_tag_ids = WModes_Util::get_product_term_ids_by_slugs( $filter_args[ 'tag_slugs' ], 'product_tag' );

            if ( !count( $filter_tag_ids ) ) {
                return false;
            }

            $filter_compare = $filter_args[ 'compare' ];

            $tag_ids = $product[ 'tag_ids' ];


            return WModes_Validation_Util::validate_list_list( $tag_ids, $filter_tag_ids, $filter_compare );
        }

    }

}