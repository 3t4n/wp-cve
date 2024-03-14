<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Filter_Type_Products' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Product_Filter_Type_Products {

        public static function init() {

            add_filter( 'wmodes-admin/get-product-filter-groups', array( new self(), 'get_groups' ), 10, 2 );

            add_filter( 'wmodes-admin/get-products-group-product-filters', array( new self(), 'get_filters' ), 10, 2 );

            add_filter( 'wmodes-admin/get-spec_products-product-filter-fields', array( new self(), 'get_specific_products_fields' ), 10, 2 );
            add_filter( 'wmodes-admin/get-product_cats-product-filter-fields', array( new self(), 'get_product_categories_fields' ), 10, 2 );
            add_filter( 'wmodes-admin/get-product_tags-product-filter-fields', array( new self(), 'get_product_tags_fields' ), 10, 2 );
        }

        public static function get_groups( $in_groups, $args ) {

            $in_groups[ 'products' ] = esc_html__( 'Products', 'wmodes-tdm' );

            return $in_groups;
        }

        public static function get_filters( $in_list, $args ) {

            $in_list[ 'spec_products' ] = esc_html__( 'Specific Products', 'wmodes-tdm' );
            $in_list[ 'product_cats' ] = esc_html__( 'Product Categories', 'wmodes-tdm' );
            $in_list[ 'product_tags' ] = esc_html__( 'Product Tags', 'wmodes-tdm' );

            return $in_list;
        }

        public static function get_specific_products_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'compare',
                'type' => 'select2',
                'default' => 'in_list',
                'options' => array(
                    'in_list' => esc_html__( 'Any in the list', 'wmodes-tdm' ),
                    'none' => esc_html__( 'None in the list', 'wmodes-tdm' ),
                ),
                'width' => '98%',
                'box_width' => '25%',
            );

            $in_fields[] = array(
                'id' => 'product_slugs',
                'type' => 'select2',
                'multiple' => true,
                'minimum_input_length' => 2,
                'placeholder' => esc_html__( 'Search products...', 'wmodes-tdm' ),
                'allow_clear' => true,
                'minimum_results_forsearch' => 10,
                'data' => array(
                    'source' => 'wc:products',
                    'ajax' => true,
                    'value_col' => 'slug',
                ),
                'width' => '100%',
                'box_width' => '56%',
            );

            return $in_fields;
        }

        public static function get_product_categories_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'compare',
                'type' => 'select2',
                'default' => 'in_list',
                'options' => array(
                    'in_list' => esc_html__( 'Any in the list', 'wmodes-tdm' ),
                    'in_all_list' => esc_html__( 'All in the list', 'wmodes-tdm' ),
                    'in_list_only' => esc_html__( 'Only in the list', 'wmodes-tdm' ),
                    'in_all_list_only' => esc_html__( 'Only all in the list', 'wmodes-tdm' ),
                    'none' => esc_html__( 'None in the list', 'wmodes-tdm' ),
                ),
                'width' => '98%',
                'box_width' => '25%',
            );

            $in_fields[] = array(
                'id' => 'category_slugs',
                'type' => 'select2',
                'multiple' => true,
                'minimum_input_length' => 1,
                'placeholder' => esc_html__( 'Search categories...', 'wmodes-tdm' ),
                'allow_clear' => true,
                'minimum_results_forsearch' => 10,
                'data' => array(
                    'source' => 'categories:product_cat',
                    'ajax' => true,
                    'value_col' => 'slug',
                ),
                'width' => '100%',
                'box_width' => '56%',
            );

            return $in_fields;
        }

        public static function get_product_tags_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'compare',
                'type' => 'select2',
                'default' => 'in_list',
                'options' => array(
                    'in_list' => esc_html__( 'Any in the list', 'wmodes-tdm' ),
                    'in_all_list' => esc_html__( 'All in the list', 'wmodes-tdm' ),
                    'in_list_only' => esc_html__( 'Only in the list', 'wmodes-tdm' ),
                    'in_all_list_only' => esc_html__( 'Only all in the list', 'wmodes-tdm' ),
                    'none' => esc_html__( 'None in the list', 'wmodes-tdm' ),
                ),
                'width' => '98%',
                'box_width' => '25%',
            );

            $in_fields[] = array(
                'id' => 'tag_slugs',
                'type' => 'select2',
                'multiple' => true,
                'minimum_input_length' => 1,
                'placeholder' => esc_html__( 'Search tags...', 'wmodes-tdm' ),
                'allow_clear' => true,
                'minimum_results_forsearch' => 10,
                'data' => array(
                    'source' => 'categories:product_tag',
                    'ajax' => true,
                    'value_col' => 'slug',
                ),
                'width' => '100%',
                'box_width' => '56%',
            );

            return $in_fields;
        }

    }

    WModes_Admin_Product_Filter_Type_Products::init();
}