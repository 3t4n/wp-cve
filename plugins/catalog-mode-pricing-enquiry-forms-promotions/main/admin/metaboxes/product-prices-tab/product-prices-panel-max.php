<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Prices_MetaBox_Panel_Max' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Product_Prices_MetaBox_Panel_Max {

        public static function init() {
            add_filter( 'wmodes-admin/product-pricing/get-mbx-panels', array( new self(), 'get_panel' ), 39, 2 );
        }

        public static function get_panel( $in_fields, $product_id ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => true,
                'panel_size' => 'smaller',
                'width' => '100%',
                'merge_fields' => false,
                'css_class' => array( 'wmodes-padded-panel' ),
                'last' => true,
                'fields' => self::get_limit_box(),
            );

            return $in_fields;
        }

        private static function get_limit_box() {

            $in_fields = array();

            $in_fields[] = array(
                'id' => 'limit',
                'type' => 'columns-field',
                'columns' => 8,
                'merge_fields' => true,
                'fields' => self::get_limit_fields(),
            );

            return $in_fields;
        }

        private static function get_limit_fields() {

            $in_fields = array();

            $in_fields[] = array(
                'id' => 'limit_type',
                'type' => 'select2',
                'column_size' => 3,
                'column_title' => esc_html__( 'Pricing Limit', 'wmodes-tdm' ),
                'tooltip' => esc_html__( 'Controls pricing limits', 'wmodes-tdm' ),
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

    WModes_Admin_Product_Prices_MetaBox_Panel_Max::init();
}