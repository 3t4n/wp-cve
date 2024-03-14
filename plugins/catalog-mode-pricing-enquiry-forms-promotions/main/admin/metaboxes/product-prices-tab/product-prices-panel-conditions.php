<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Prices_MetaBox_Panel_Conditions' ) ) {

    class WModes_Admin_Product_Prices_MetaBox_Panel_Conditions {

        public static function init() {

            add_filter( 'wmodes-admin/product-pricing/get-mbx-panels', array( new self(), 'get_Panel' ), 40, 2 );
        }

        public static function get_Panel( $in_fields, $product_id ) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => false,
                'panel_size' => 'smaller',
                'width' => '100%',
                'merge_fields' => false,
                'css_class' => array( 'wmodes_metabox_panel', 'mbx_last_panel' ),
                'last' => true,
                'fields' => self::get_sub_Panel( array(), $product_id ),
            );

            return $in_fields;
        }

        public static function get_sub_Panel( $in_fields, $product_id ) {

            $args = array(
                'module' => 'product-pricing',
                'is_global' => false,
                'product_id' => $product_id,
                'text' => esc_html__( 'product pricing', 'wmodes-tdm' ),
            );

            $in_fields[] = array(
                'id' => 'condition_args',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => true,
                'panel_size' => 'smaller',
                'width' => '100%',
                'merge_fields' => true,
                'last' => true,
                'fields' => apply_filters( 'wmodes-admin/get-panel-conditions-fields', array(), $args ),
            );
            return $in_fields;
        }

    }

    WModes_Admin_Product_Prices_MetaBox_Panel_Conditions::init();
}