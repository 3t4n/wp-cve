<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Options_Panel_Conditions' ) ) {

    class WModes_Admin_Product_Options_Panel_Conditions {

        public static function init() {

            add_filter( 'wmodes-admin/product-options/get-panels', array( new self(), 'get_Panel' ), 40 );
        }

        public static function get_Panel( $in_fields ) {

            $args = array(
                'module' => 'product-options',
                'is_global' => true,
                'text' => esc_html__( 'product settings', 'wmodes-tdm' ),
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

    WModes_Admin_Product_Options_Panel_Conditions::init();
}