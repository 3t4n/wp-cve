<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Prices_Panel_Max' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Product_Prices_Panel_Max {

        public static function init() {

            add_filter( 'wmodes-admin/product-pricing/get-panels', array( new self(), 'get_panel' ), 30 );
        }

        public static function get_panel( $in_fields ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => false,
                'panel_size' => 'smaller',
                'width' => '100%',
                'merge_fields' => false,
                'last' => true,
                'css_class' => array( 'wmodes_option_list', 'wmodes_last_panel' ),
                'field_css_class' => array( 'wmodes_option_list_field' ),
                'fields' => self::get_sub_panel( array() ),
            );

            return $in_fields;
        }

        public static function get_sub_panel( $in_fields ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => true,
                'panel_size' => 'smaller',
                'width' => '100%',
                'merge_fields' => false,
                'last' => true,
                'fields' => self::get_limit_box( array() ),
            );

            return $in_fields;
        }

        private static function get_limit_box( $in_fields ) {

            $in_fields[] = array(
                'id' => 'limit',
                'type' => 'columns-field',
                'columns' => 8,
                'merge_fields' => true,
                'fields' => self::get_limit_fields( array() ),
            );

            return $in_fields;
        }

        private static function get_limit_fields( $in_fields ) {

            $in_fields[] = array(
                'id' => 'limit_type',
                'type' => 'select2',
                'column_size' => 3,
                'column_title' => esc_html__( 'Pricing Limit (Per Product)', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls pricing limits per product', 'wmodes-tdm' ),
                'default' => 'no',
                'disabled_list_filter' => 'wmodes-admin/get-disabled-list',
                'options' => array(
                    'no' => esc_html__( 'No limit', 'wmodes-tdm' ),
                ),
                'width' => '100%',
            );

            return $in_fields;
        }

    }

    WModes_Admin_Product_Prices_Panel_Max::init();
}