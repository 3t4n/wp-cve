<?php

namespace GSLOGO;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Integration_Gutenberg {

	private static $_instance = null;
        
    public static function get_instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;        
    }

    public function __construct() {
        add_action( 'init', [ $this, 'load_block_script' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );        
    }

    public function enqueue_block_editor_assets() {
        plugin()->scripts->wp_enqueue_style_all( 'public', ['gs-logo-divi-public'] );
        plugin()->scripts->wp_enqueue_script_all( 'public' );
    }

    public function load_block_script() {

        gsLogoAssetGenerator()->enqueue_builder_preview_assets();

        wp_add_inline_style( 'wp-block-editor', $this->get_block_css() );

        wp_register_script( 'gs-logo-block', GSL_PLUGIN_URI . '/includes/integrations/assets/gutenberg/gutenberg-widget.min.js', ['wp-blocks', 'wp-editor'], GSL_VERSION );
        
        $gs_logo_slider_block = array(
            'select_shortcode'        => __( 'Select Shortcode', 'gslogo' ),
            'edit_description_text'   => __( 'Edit this shortcode', 'gslogo' ),
            'edit_link_text'          => __( 'Edit', 'gslogo' ),
            'create_description_text' => __( 'Create new shortcode', 'gslogo' ),
            'create_link_text'        => __( 'Create', 'gslogo' ),
            'edit_link'               => admin_url( "edit.php?post_type=gs-logo-slider&page=gs-logo-shortcode#/shortcode/" ),
            'create_link'             => admin_url( 'edit.php?post_type=gs-logo-slider&page=gs-logo-shortcode#/shortcode' ),
            'shortcodes'              => $this->get_shortcode_list()
		);
		wp_localize_script( 'gs-logo-block', 'gs_logo_slider_block', $gs_logo_slider_block );

        register_block_type( 'gslogo/shortcodes', array(
            'editor_script' => 'gs-logo-block',
            'attributes' => [
                'shortcode' => [
                    'type'    => 'string',
                    'default' => $this->get_default_item()
                ],
                'align' => [
                    'type'=> 'string',
                    'default'=> 'wide'
                ]
            ],
            'render_callback' => [$this, 'shortcodes_dynamic_render_callback']
        ));

    }

    public function shortcodes_dynamic_render_callback( $block_attributes ) {
        $shortcode_id = ( ! empty($block_attributes) && ! empty($block_attributes['shortcode']) ) ? absint( $block_attributes['shortcode'] ) : $this->get_default_item();
        return do_shortcode( sprintf( '[gslogo id="%u"]', esc_attr($shortcode_id) ) );
    }

    public function get_block_css() {

        ob_start(); ?>
    
        .gslogo--toolbar {
            padding: 20px;
            border: 1px solid #1f1f1f;
            border-radius: 2px;
        }

        .gslogo--toolbar label {
            display: block;
            margin-bottom: 6px;
            margin-top: -6px;
        }

        .gslogo--toolbar select {
            width: 250px;
            max-width: 100% !important;
            line-height: 42px !important;
        }

        .gslogo--toolbar .gs-logo-slider-block--des {
            margin: 10px 0 0;
            font-size: 16px;
        }

        .gslogo--toolbar .gs-logo-slider-block--des span {
            display: block;
        }

        .gslogo--toolbar p.gs-logo-slider-block--des a {
            margin-left: 4px;
        }

        .editor-styles-wrapper .wp-block h3.gs_logo_title {
            font-size: 16px;
            font-weight: 400;
            margin: 0px;
            margin-top: 20px;
        }
    
        <?php return ob_get_clean();
    }

    protected function get_shortcode_list() {
        return get_shortcodes();
    }

    protected function get_default_item() {
    
        $shortcodes = get_shortcodes();

        if ( !empty($shortcodes) ) {
            return $shortcodes[0]['id'];
        }

        return '';

    }

}