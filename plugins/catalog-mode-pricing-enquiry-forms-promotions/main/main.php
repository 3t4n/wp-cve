<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Main' ) ) {

    class WModes_Main {

        public function __construct() {

            if ( !defined( 'WMODES_ASSETS_URL' ) ) {
                
                define( 'WMODES_ASSETS_URL', plugins_url( 'assets/', __FILE__ ) );
            }

            if ( is_admin() ) {

                add_action( 'reon/init', array( $this, 'load_admin_page' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 20 );
            } else {

                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ), 9999 );
            }

            add_action( 'woocommerce_init', array( $this, 'init_public' ) );
        }

        public function load_admin_page() {

            require_once (dirname( __FILE__ ) . '/admin/admin.php');
        }

        public function init_public() {

            require_once (dirname( __FILE__ ) . '/compatibility/compatibility.php');
            require_once dirname( __FILE__ ) . '/public/views/views.php';
            require_once dirname( __FILE__ ) . '/public/views/view-css/view-css.php';

            if ( !is_admin() || (defined( 'DOING_AJAX' ) && DOING_AJAX) ) {

                require_once dirname( __FILE__ ) . '/public/wmodes.php';
                require_once dirname( __FILE__ ) . '/public/pipelines/pipeline.php';
            }
        }

        public function enqueue_admin_scripts() {

            wp_enqueue_style( 'wmodes-admin-styles', WMODES_ASSETS_URL . 'admin-styles.css', array(), '1.0', 'all' );
            
            WModes_Admin_Notices::get_instance()->enqueue_scripts();
        }

        public function enqueue_public_scripts() {

            wp_enqueue_style( 'wmodes-public-styles', WMODES_ASSETS_URL . 'public-styles.min.css', array(), '1.0', 'all' );

            //Custom CSS
            if ( WModes_Views_CSS::is_using_external_css() ) {

                $custom_css_url = WModes_Views_CSS::get_custom_css_url();
                $custom_css_ver = WModes_Views_CSS::get_custom_css_ver();
                $custom_css_dep = defined( 'WMODES_PREMIUM_ADDON' ) ? array( 'wmodes-pro-public-styles' ) : array( 'wmodes-public-styles' );

                wp_enqueue_style( 'wmodes-custom-styles', $custom_css_url, $custom_css_dep, $custom_css_ver, 'all' );
            } else {

                $custom_css = WModes_Views_CSS::get_css();

                if ( $custom_css != '' ) {

                    wp_add_inline_style( 'wmodes-public-styles', $custom_css );
                }
            }
        }

        public static function get_allow_html() {

            $allowed_html = array(
                'span' => array(
                    'id' => true,
                    'title' => true,
                    'class' => true,
                ),
                'a' => array(
                    'id' => true,
                    'href' => true,
                    'title' => true,
                    'class' => true,
                    'target' => true,
                ),
                'strong' => array(
                    'id' => true,
                    'title' => true,
                    'class' => true,
                ),
                'b' => array(
                    'id' => true,
                    'title' => true,
                    'class' => true,
                ),
                'i' => array(
                    'id' => true,
                    'title' => true,
                    'class' => true,
                ),
            );

            $allowed_html;
        }

    }

}