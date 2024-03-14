<?php
namespace GSLOGO;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Integration Class
class Integration_Oxygen {

    private static $_instance = null;
    
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );        
        add_action( 'init', array( $this, 'init' ) );        
    }

    public function plugins_loaded() {

        if ( is_preview() ) {

            add_action( 'ct_builder_start', function() {
                remove_action( 'ct_builder_start', 'ct_templates_buffer_start' );
            }, 0);
            
            add_action( 'ct_builder_end', function() {
                remove_action( 'ct_builder_end', 'ct_templates_buffer_end' );
            }, 0);

        }
    }

    public function init() {        
        if ( class_exists('OxygenElement') ) {
            load_oxygen_builder_class();
            new GS_Logo_Slider_Oxygen_Shortcode();
        }
    }

}

function load_oxygen_builder_class() {

    class GS_Logo_Slider_Oxygen_Shortcode extends \OxyEl {
    
        function init() {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        }

        function enqueue_scripts() {

            if ( !empty($_GET['ct_builder']) && !empty($_GET['oxygen_iframe']) ) {
                plugin()->scripts->wp_enqueue_style_all( 'public', ['gs-logo-divi-public'] );
                plugin()->scripts->wp_enqueue_script_all( 'public' );
                gsLogoAssetGenerator()->enqueue_prefs_custom_css();
            }

        }
    
        function name() {
            return 'GS Logo Slider';
        }
        
        function slug() {
            return "gs-logo-slider";
        }
    
        function icon() {
            return GSL_PLUGIN_URI . 'assets/img/icon.svg';
        }
        
        function render($options) {
            echo do_shortcode( sprintf( '[gslogo id="%u"]', esc_attr($options['shortcode_id']) ) );
        }
    
        function controls() {
    
            $this->addOptionControl(
                array(
                    "type" => "dropdown",
                    "name" => __("Select Shortcode"),
                    "slug" => "shortcode_id"
                )
            )->setValue( $this->get_shortcode_list() )->rebuildElementOnChange();
    
        }

        protected function get_shortcode_list() {

            $shortcodes = get_shortcodes();

            if ( !empty($shortcodes) ) {
                return wp_list_pluck( $shortcodes, 'shortcode_name', 'id' );
            }
            
            return [];

        }
        
    }

}