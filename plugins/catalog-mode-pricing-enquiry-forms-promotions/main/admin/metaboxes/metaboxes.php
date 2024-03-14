<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Metaboxes' ) ) {


    require_once (dirname( __FILE__ ) . '/catalog-mode-tab/catalog-mode-tab.php');
    if ( !defined( 'WMODES_PREMIUM_ADDON' ) ) {
        require_once (dirname( __FILE__ ) . '/catalog-mode-tab/catalog-mode-global.php');
    }
    require_once (dirname( __FILE__ ) . '/catalog-mode-tab/catalog-mode-box.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-tab/catalog-mode-panel-note.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-tab/catalog-mode-panel-modes.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-tab/catalog-mode-panel-conditions.php');


    require_once (dirname( __FILE__ ) . '/product-prices-tab/product-prices-tab.php');
    if ( !defined( 'WMODES_PREMIUM_ADDON' ) ) {
        require_once (dirname( __FILE__ ) . '/product-prices-tab/product-prices-global.php');
    }
    require_once (dirname( __FILE__ ) . '/product-prices-tab/product-prices-box.php');
    require_once (dirname( __FILE__ ) . '/product-prices-tab/product-prices-panel.php');
    require_once (dirname( __FILE__ ) . '/product-prices-tab/product-prices-panel-options.php');
    require_once (dirname( __FILE__ ) . '/product-prices-tab/product-prices-panel-variations.php');
    require_once (dirname( __FILE__ ) . '/product-prices-tab/product-prices-panel-max.php');
    require_once (dirname( __FILE__ ) . '/product-prices-tab/product-prices-panel-conditions.php');


    require_once (dirname( __FILE__ ) . '/product-options-tab/product-options-tab.php');
    if ( !defined( 'WMODES_PREMIUM_ADDON' ) ) {
        require_once (dirname( __FILE__ ) . '/product-options-tab/product-options-global.php');
    }

    require_once (dirname( __FILE__ ) . '/product-options-tab/product-options.php');
    require_once (dirname( __FILE__ ) . '/product-options-tab/product-options-panel-note.php');
    require_once (dirname( __FILE__ ) . '/product-options-tab/product-options-panel-options.php');
    require_once (dirname( __FILE__ ) . '/product-options-tab/product-options-panel-conditions.php');

    class WModes_Admin_Metaboxes {

        public static function init() {

            $settings = self::get_metabox_settings();

            if ( 'yes' == $settings[ 'catalog_mode' ] ) {

                WModes_Admin_Catalog_Mode_MetaBox_Tab::init();
            }

            if ( 'yes' == $settings[ 'product_pricing' ] ) {

                WModes_Admin_Product_Prices_MetaBox_Tab::init();
            }

            if ( 'yes' == $settings[ 'product_options' ] ) {

                WModes_Admin_Product_Options_MetaBox_Tab::init();
            }
        }

        private static function get_metabox_settings() {

            global $wmodes_settings;

            if ( isset( $wmodes_settings[ 'meta_boxes' ] ) ) {

                return $wmodes_settings[ 'meta_boxes' ];
            }

            return array(
                'catalog_mode' => 'yes',
                'product_pricing' => 'yes',
                'product_options' => 'yes',
            );
        }

    }

    WModes_Admin_Metaboxes::init();
}