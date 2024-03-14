<?php

if (!class_exists('Reon')) {
    return;
}

if (!class_exists('WModes_Admin_Catalog_Mode_MetaBox_Tab')) {

    class WModes_Admin_Catalog_Mode_MetaBox_Tab {

        private static $metabox_id = 'wmodes_catalog_mode_tab';

        public static function init() {
            self::init_metabox();
            add_filter('reon/get-metabox-wc-' . self::$metabox_id . '-fields', array(new self(), 'get_metabox_fields'), 20, 3);
        }

        public static function get_metabox_id() {
            return self::$metabox_id;
        }

        public static function init_metabox() {
            Reon::set_wc_product_meta_box(array(
                'title' => esc_html__('Catalog Modes', 'wmodes-tdm'),
                'id' => self::$metabox_id,
                'sanitize_mode' => 'recursive',
                'save_metabox' => true,
                'priority' => 100,
            ));
        }

        public static function get_metabox_fields($in_fields, $metabox_id, $product_id) {

            return apply_filters('wmodes-admin/catalog-modes/get-metabox-fields', array(), $product_id);
        }

        
    }

}