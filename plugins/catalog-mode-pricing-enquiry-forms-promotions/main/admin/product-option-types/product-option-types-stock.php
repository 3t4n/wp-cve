<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Option_Type_Stock' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Product_Option_Type_Stock {

        public static function init() {

            add_filter( 'wmodes-admin/product-options/get-settings-option-types', array( new self(), 'get_types' ), 10, 2 );

            add_filter( 'wmodes-admin/product-options/get-option-type-stock-fields', array( new self(), 'get_fields' ), 10, 2 );

            add_filter( 'wmodes-admin/product-options/get-type-requires-variations', array( new self(), 'get_requires_variations' ), 10, 2 );
        }

        public static function get_types( $in_options, $args = array() ) {

            $in_options[ 'stock' ] = array(
                'title' => esc_html__( 'Stock Settings', 'wmodes-tdm' ),
            );

            return $in_options;
        }

        public static function get_fields( $in_fields, $args ) {

            if ( $args[ 'is_global' ] ) {

                $in_fields[] = array(
                    'id' => 'any_ids',
                    'type' => 'columns-field',
                    'columns' => 5,
                    'merge_fields' => false,
                    'fields' => self::get_panel_fields(),
                );
            } else {

                $in_fields[] = array(
                    'id' => 'any_ids',
                    'type' => 'columns-field',
                    'columns' => 4,
                    'merge_fields' => false,
                    'fields' => self::get_panel_meta_fields(),
                );
            }

            return $in_fields;
        }

        public static function get_requires_variations( $in_options, $args ) {

            $in_options[] = 'stock';

            return $in_options;
        }

        private static function get_panel_fields() {

            $in_fields = array();

            $in_fields[] = array(
                'id' => 'manage_stock',
                'type' => 'select2',
                'column_size' => 1,
                'column_title' => esc_html__( 'Manage Stock', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Enables stock management at product level', 'wmodes-tdm' ),
                'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                'default' => 'no',
                'options' => array(
                    'no' => esc_html__( 'No', 'wmodes-tdm' ),
                ),
                'width' => '100%',
                'fold_id' => 'manage_stock',
            );

            $in_fields[] = array(
                'id' => 'stock_status',
                'type' => 'select2',
                'column_size' => 3,
                'column_title' => esc_html__( 'Stock Status', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend', 'wmodes-tdm' ),
                'default' => 'instock',
                'options' => wc_get_product_stock_status_options(),
                'fold' => array(
                    'target' => 'manage_stock',
                    'attribute' => 'value',
                    'value' => 'no',
                    'oparator' => 'eq',
                    'clear' => false,
                ),
                'width' => '100%',
            );

            return $in_fields;
        }

        private static function get_panel_meta_fields() {

            $in_fields = array();

            $in_fields[] = array(
                'id' => 'manage_stock',
                'type' => 'select2',
                'column_size' => 1,
                'column_title' => esc_html__( 'Manage Stock', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Enables stock management at product level', 'wmodes-tdm' ),
                'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                'default' => 'no',
                'options' => array(
                    'prem_1' => esc_html__( 'Yes (Premium)', 'wmodes-tdm' ),
                    'prem_2' => esc_html__( 'Yes (%) (Premium)', 'wmodes-tdm' ),
                    'no' => esc_html__( 'No', 'wmodes-tdm' ),
                ),
                'width' => '100%',
                'fold_id' => 'manage_stock',
            );

            $in_fields[] = array(
                'id' => 'stock_status',
                'type' => 'select2',
                'column_size' => 2,
                'column_title' => esc_html__( 'Stock Status', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend', 'wmodes-tdm' ),
                'default' => 'instock',
                'options' => wc_get_product_stock_status_options(),
                'fold' => array(
                    'target' => 'manage_stock',
                    'attribute' => 'value',
                    'value' => 'no',
                    'oparator' => 'eq',
                    'clear' => false,
                ),
                'width' => '100%',
            );

            return $in_fields;
        }

    }

    WModes_Admin_Product_Option_Type_Stock::init();
}