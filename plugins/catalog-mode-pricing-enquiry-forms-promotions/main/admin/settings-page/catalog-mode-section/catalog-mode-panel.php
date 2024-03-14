<?php

if (!class_exists('Reon')) {
    return;
}

if (!class_exists('WModes_Admin_Catalog_Mode_Panel')) {

    class WModes_Admin_Catalog_Mode_Panel {

        public static function init() {
            add_filter('wmodes-admin/catalog-modes/get-panels', array(new self(), 'get_panel'), 10);
            add_filter('wmodes-admin/catalog-modes/get-panel-fields', array(new self(), 'get_panel_fields'), 10);
        }

        public static function get_panel($in_fields) {

            $in_fields[] = array(
                'id' => 'mode_id',
                'type' => 'autoid',
                'autoid' => 'wmodes',
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
                'last' => true,
                'fields' => apply_filters('wmodes-admin/catalog-modes/get-panel-fields', array()),
            );

            return $in_fields;
        }

        public static function get_panel_fields($in_fields) {
            
            return apply_filters('wmodes-admin/catalog-modes/get-panel-option-fields', $in_fields);
        }

    }

    WModes_Admin_Catalog_Mode_Panel::init();
}