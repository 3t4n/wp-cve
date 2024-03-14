<?php
namespace GSLOGO;
use FLBuilder;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Integration_Beaver {

    private static $_instance = null;
    
    public static function get_instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    public function init() {
        if ( class_exists( '\FLBuilder' ) ) {
            require_once GSL_PLUGIN_DIR . 'includes/integrations/beaver/beaver-widget-logo.php';
            FLBuilder::register_module( 'GSLOGO\Beaver', array(
                'my-tab-1'      => array(
                    'title'         => __( 'Tab 1', 'gslogo' ),
                    'sections'      => array(
                        'my-section-1'  => array(
                            'title'         => __( 'Shortcode', 'gslogo' ),
                            'fields'        => array(
                                'shortcode_id' => array(
                                    'type'          => 'select',
                                    'label'         => __('Select Shortcode', 'gslogo'),
                                    'options'       => $this->get_shortcode_list(),
                                    'preview'      => array(
                                        'type'         => 'none'
                                    )
                                ),
                            )
                        )
                    )
                )
            ));
        }
    }

    protected function get_shortcode_list() {

        $shortcodes = get_shortcodes();

        if ( !empty($shortcodes) ) {
            return wp_list_pluck( $shortcodes, 'shortcode_name', 'id' );
        }
        
        return [];

    }

}