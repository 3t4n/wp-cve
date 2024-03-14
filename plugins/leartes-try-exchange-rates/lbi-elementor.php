<?php
/**
 * Name         : Elementor Addons For Leartes.NET
 * Description  : Provides additional Elementor Elements
 * Author : Leartes.NET
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'LBI_Elementor_Extensions' ) ) {
    final class LBI_Elementor_Extensions {
        const LBI_GROUP = 'leartes';
 
        private static $_instance = null;

        public function __construct() {
            add_action( 'elementor/elements/categories_registered', array( $this, 'add_widget_categories' ) );
            add_action( 'elementor/widgets/widgets_registered', array( $this, 'elementor_widgets' ) );
            add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'leartes_enqueue_scripts' ) );
            add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_frontend_styles' ) );

            add_action( 'elementor/dynamic_tags/register_tags', array( $this, 'register_tags' ) );
            add_action( 'elementor/controls/controls_registered', array( $this, 'include_custom_controls' ) );
        }

        public static function instance () {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function add_widget_categories( $elements_manager ) {
            $elements_manager->add_category(
                'leartes-elements',
                [
                    'title' => esc_html__( 'Leartes.NET Elements', 'lbi-exchrates' ),
                    'icon' => 'fa fa-plug',
					'active' => true,
                ]
            );
        }

        public function elementor_widgets() {
            require_once 'elementor/lbi-exchrates-widget.php';
        }

        public function register_tags( $dynamic_tags ) {
            // In our Dynamic Tag we use a group named request-variables so we need
            // To register that group as well before the tag
        }

        public function include_custom_controls(){

        }

        public function leartes_enqueue_scripts() {

        }

        public function enqueue_frontend_styles() {

        }
    }
}

if ( did_action( 'elementor/loaded' ) ) {
    // Finally initialize code
    LBI_Elementor_Extensions::instance();
}
?>