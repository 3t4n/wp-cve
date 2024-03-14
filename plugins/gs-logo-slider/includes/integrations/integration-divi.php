<?php
namespace GSLOGO;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Integration Class
class Integration_Divi {

    private static $_instance = null;
    private $name;
    private $plugin_dir_url;
    protected $_bundle_dependencies = array();
    
    public static function get_instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;        
    }

    public function __construct() {

        add_action( 'divi_extensions_init', array( $this, 'init' ) );
        
    }

    public function init() {

        $this->name = 'gs-logo-divi';
        $this->plugin_dir_url = GSL_PLUGIN_URI . 'includes/integrations/assets/divi';

        add_action( 'et_builder_modules_loaded', 'GSLOGO\divi_widget_class' );
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_hook_enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'editor_style' ) );

    }

    public function editor_style() {

        if ( ! et_core_is_fb_enabled() ) return;

            $icon = GSL_PLUGIN_URI . 'assets/img/icon.svg';

            ob_start();

            ?>
            <style>

                .et-db #et-boc .et-l .et-fb-modules-list ul > li.gs_logo_slider:before {
                    background: url('<?php echo esc_url_raw( $icon ); ?>') no-repeat center center;
                    background-size: contain;
                    content: "";
                    height: 28px;
                }
                
                .et-db #et-boc .et-l .et-fb-modules-list ul > li.gs_logo_slider {
                    height: 67px;
                }

            </style>
            <?php

            echo ob_get_clean();

    }

    public function wp_hook_enqueue_scripts() {

        if ( et_core_is_fb_enabled() ) {

            // Load Styles
            plugin()->scripts->wp_enqueue_style_all( 'public' );

            // Load Scripts
            plugin()->scripts->wp_enqueue_script_all( 'public' );

            gsLogoAssetGenerator()->enqueue_prefs_custom_css();

            $bundle_url = "{$this->plugin_dir_url}/divi-builder.min.js";
            wp_enqueue_script( "{$this->name}-builder", $bundle_url, ['react-dom'], GSL_VERSION, true );

        }

    }

}


function divi_widget_class() {

    // Elementor Widget Class
    class Divi_Widget extends \ET_Builder_Module {

        public $slug       = 'gs_logo_slider';
        public $vb_support = 'on';
    
        public function init() {
            $this->name = esc_html__( 'GS Logo Slider', 'gslogo' );
        }
    
        public function get_fields() {
    
            return array(
                'shortcode'     => array(
                    'label'           => esc_html__( 'Select Shortcode', 'gslogo' ),
                    'type'            => 'select',
                    'option_category' => 'basic_option',
                    'description'     => esc_html__( 'Show Logo Slider by GS Logo Plugin', 'gslogo' ),
                    'toggle_slug'     => 'main_content',
                    'default'         => $this->get_default_item(),
                    'options'         => $this->get_shortcode_list(),
                    'computed_affects'   => array(
                        '__shortcode',
                    ),
                ),
                '__shortcode' => array(
                    'type'                => 'computed',
                    'computed_callback'   => array( 'GSLOGO\Divi_Widget', 'get_shortcode' ),
                    'computed_depends_on' => array(
                        'shortcode',
                    ),
                    'computed_minimum' => array(
                        'shortcode',
                    ),
                )
            );
    
        }
    
        static function get_shortcode( $args ) {
    
            $defaults = array(
                'shortcode' => ''
            );
    
            $args = wp_parse_args( $args, $defaults );
    
            return do_shortcode( sprintf( '[gslogo id="%s" /]', $args['shortcode'] ) );
    
        }
    
        public function render( $unprocessed_props, $content, $render_slug ) {
            
            $shortcode_id = $this->props['shortcode'];
            
            $output = sprintf(
                '<div id="%2$s" class="%3$s">
                    %1$s
                </div>',
                self::get_shortcode([
                    'shortcode' => $shortcode_id
                ]),
                $this->module_id(),
                $this->module_classname( $render_slug )
            );
    
            return $output;
    
        }
    
        protected function get_shortcode_list() {
    
            $shortcodes = get_shortcodes();
            
            if ( !empty($shortcodes) ) {
                return wp_list_pluck( $shortcodes, 'shortcode_name', 'id' );
            }
            
            return [];
    
        }
    
        protected function get_default_item() {

            $shortcodes = get_shortcodes();
            
            if ( !empty($shortcodes) ) {
                return $shortcodes[0]['id'];
            }
    
            return '';
    
        }
    
    }
    new Divi_Widget();

}


