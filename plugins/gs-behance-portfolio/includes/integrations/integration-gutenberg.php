<?php
namespace GSBEH;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Integration_Gutenberg' ) ) :

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

        plugin()->scripts->wp_enqueue_script_all( 'public' );
        plugin()->scripts->wp_enqueue_style_all( 'public', [] );
    }

    public function load_block_script() {

        wp_add_inline_style( 'wp-block-editor', $this->get_block_css() );

        wp_register_script( 'gs-beh-block', GSBEH_PLUGIN_URI . '/includes/integrations/assets/gutenberg/gutenberg-widget.min.js', ['wp-blocks', 'wp-editor'], GSBEH_VERSION );
        
        $gs_beh_block = array(
            'select_shortcode'        => __( 'Select Shortcode', 'gs-behance' ),
            'edit_description_text'   => __( 'Edit this shortcode', 'gs-behance' ),
            'edit_link_text'          => __( 'Edit', 'gs-behance' ),
            'create_description_text' => __( 'Create new shortcode', 'gs-behance' ),
            'create_link_text'        => __( 'Create', 'gs-behance' ),
            'edit_link'               => admin_url( "admin.php?page=gs-behance-shortcode#" ),
            'create_link'             => admin_url( 'admin.php?page=gs-behance-shortcode#' ),
            'shortcodes'              => $this->get_shortcode_list()
		);
		wp_localize_script( 'gs-beh-block', 'gs_beh_block', $gs_beh_block );

        register_block_type( 'gs-beh/behance', array(
            'editor_script' => 'gs-beh-block',
            'attributes' => [
                'shortcodeID' => [
                    'type'    => 'string',
                    'default' => $this->get_default_item()
                ],
                'align' => [
                    'type'    => 'string',
                    'default' => 'wide'
                ]
            ],
            'render_callback' => [$this, 'shortcodes_dynamic_render_callback']
        ));

    }

    public function shortcodes_dynamic_render_callback( $block_attributes ) {

        $shortcode_id = ( ! empty($block_attributes) && ! empty($block_attributes['shortcodeID']) ) ? absint( $block_attributes['shortcodeID'] ) : $this->get_default_item();
        return do_shortcode( sprintf( '[gs_behance id="%u"]', esc_attr($shortcode_id) ) );
    }

    public function get_block_css() {

        ob_start(); ?>
    
        .gs-beh--toolbar {
            padding: 20px;
            border: 1px solid #1f1f1f;
            border-radius: 2px;
        }

        .gs-beh--toolbar label {
            display: block;
            margin-bottom: 6px;
            margin-top: -6px;
        }

        .gs-beh--toolbar select {
            width: 250px;
            max-width: 100% !important;
            line-height: 42px !important;
        }

        .gs-beh--toolbar .gs-beh-block--des {
            margin: 10px 0 0;
            font-size: 16px;
        }

        .gs-beh--toolbar .gs-beh-block--des span {
            display: block;
        }

        .gs-beh--toolbar p.gs-beh-block--des a {
            margin-left: 4px;
        }
    
        <?php return ob_get_clean();
    
    }

    protected function get_shortcode_list() {

        $get_shortcode = (array) plugin()->builder->fetch_shortcodes();

        foreach ( $get_shortcode as &$shortcode ) {
            unset( $shortcode['shortcode_settings'] );
            unset( $shortcode['updated_at'] );
            unset( $shortcode['created_at'] );
        }

        return $get_shortcode;
    }

    protected function get_default_item() {
    
        $get_shortcode = $this->get_shortcode_list();

        if ( !empty($get_shortcode) ) {
            return $get_shortcode[0]['id'];
        }

        return '';

    }
}

endif;