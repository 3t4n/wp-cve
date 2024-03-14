<?php

if (!class_exists('Reon')) {
    return;
}

if (!class_exists('WModes_Admin_Product_Prices_MetaBox_Panel')) {

    class WModes_Admin_Product_Prices_MetaBox_Panel {

        public static function init() {
            
            add_filter('wmodes-admin/product-pricing/get-mbx-panels', array(new self(), 'get_panel'), 1, 2);
            add_filter('wmodes-admin/product-pricing/get-mbx-panel-fields', array(new self(), 'get_panel_fields'), 10, 2);
        }

        public static function get_panel($in_fields, $product_id) {

            $in_fields[] = array(
                'id' => 'any_id',
                'type' => 'panel',
                'full_width' => true,
                'center_head' => true,
                'white_panel' => true,
                'panel_size' => 'smaller',
                'width' => '100%',
                'last' => true,
                'css_class' => array('wmodes-padded-panel'),
                'merge_fields' => false,
                'fields' => apply_filters('wmodes-admin/product-pricing/get-mbx-panel-fields', array(), $product_id),
            );

            return $in_fields;
        }

        public static function get_panel_fields($in_fields, $product_id) {
            
            return apply_filters('wmodes-admin/product-pricing/get-mbx-panel-option-fields', $in_fields, $product_id);
        }

    }

    WModes_Admin_Product_Prices_MetaBox_Panel::init();
}
