<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Product_Prices_MetaBox_Panel_Variations' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Admin_Product_Prices_MetaBox_Panel_Variations {

        public static function init() {
            add_filter( 'wmodes-admin/product-pricing/get-mbx-panels', array( new self(), 'get_Panel' ), 39, 2 );
        }

        public static function get_Panel( $in_fields, $product_id ) {

            $args = array(
                'module' => 'product-pricing',
                'is_global' => false,
                'product_id' => $product_id,
                'text'=>'product pricing',
            );
            
            
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
                'fields' => WModes_Admin_Logic_Types_Product_Variations::get_fields( array(), $args ),
                'fold' => array(
                    'target' => 'product-type',
                    'attribute' => 'value',
                    'value' => 'variable',
                    'oparator' => 'eq',
                    'clear' => false,
                ),
            );

            return $in_fields;
        }

    }

    WModes_Admin_Product_Prices_MetaBox_Panel_Variations::init();
}