<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

if ( ! function_exists('is_plugin_active')) { include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }

if ( !class_exists( 'WPBForWPbakery_Addons_Init' ) ) {

    class WPBForWPbakery_Addons_Init{

        private static $_instance = null;
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){

            if ( class_exists( 'WooCommerce' ) ) {
                add_action( 'after_setup_theme', array( $this, 'wpbforwpbakery_woocommerce_setup' ) );
                // add_action( 'init', array( $this, 'wpbforwpbakery_plugins_loaded' ) );
                $this->wpbforwpbakery_plugins_loaded();
            }
        }

        // Support WooCommerce
        public function wpbforwpbakery_woocommerce_setup() {
            add_theme_support( 'woocommerce' );
        }

       	public function wpbforwpbakery_plugins_loaded(){
       		$this->wpbforwpbakery_file_includes();
       		$this->wpbforwpbakery_includes_widgets();
    	}

        // Include Widgets File
        public function wpbforwpbakery_includes_widgets(){

            // load addons
            $wpbforwpbakery_addons  = array(
                'archive_product',
                'product_title',
                'product_description',
                'product_short_description',
                'product_image',
                'product_price',
                'product_add_to_cart',
                'product_meta',
                'product_stock',
                'product_rating',
                'product_data_tab',
                'product_related',
                'product_reviews',
                'product_upsell',
                'product_additional_information',
            );

            foreach ( $wpbforwpbakery_addons as $addon ){
                if (  ( wpbforwpbakery_get_option( 'wpb_'. $addon, 'wpbforwpbakery_elements_tabs', 'on' ) === 'on' ) && file_exists( WPBFORWPBAKERY_ADDONS_PL_PATH.'/includes/addons/'.$addon.'.php' ) ){
                    require_once ( WPBFORWPBAKERY_ADDONS_PL_PATH.'/includes/addons/'.$addon.'.php' );
                }
            }

        }

        // Include File
        Public function wpbforwpbakery_file_includes(){
            if( wpbforwpbakery_get_option( 'enablecustomlayout', 'wpbforwpbakery_woo_template_tabs', 'on' ) == 'on' ){
                include_once ( WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/woo_shop.php' );
                include_once ( WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/archive-product-render.php' );

                // rename label
                if( !is_admin() && !is_plugin_active('wc-builder-pro/wc-builder-pro.php') && wpbforwpbakery_get_option( 'enablerenamelabel', 'wpbforwpbakery_rename_label_tabs', 'off' ) == 'on' ){
                    require( WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/rename_label.php' );
                }
            }
        }
    }
    
    WPBForWPbakery_Addons_Init::instance();

}