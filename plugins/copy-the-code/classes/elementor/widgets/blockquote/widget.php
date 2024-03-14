<?php

/**
 * Elementor Blockquote Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */
namespace CopyTheCode\Elementor\Block;

use  CopyTheCode\Helpers ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
/**
 * Blockquote Block
 *
 * @since 3.1.0
 */
class Blockquote extends Widget_Base
{
    /**
     * Constructor
     * 
     * @param array $data
     * @param array $args
     * 
     * @since 3.1.0
     */
    public function __construct( $data = array(), $args = null )
    {
        parent::__construct( $data, $args );
        // Core.
        wp_enqueue_style(
            'ctc-blocks-core',
            COPY_THE_CODE_URI . 'classes/blocks/assets/css/style.css',
            [],
            COPY_THE_CODE_VER,
            'all'
        );
        wp_enqueue_script(
            'ctc-clipboard',
            COPY_THE_CODE_URI . 'assets/js/clipboard.js',
            [ 'jquery' ],
            COPY_THE_CODE_VER,
            true
        );
        wp_enqueue_script(
            'ctc-blocks-core',
            COPY_THE_CODE_URI . 'classes/blocks/assets/js/core.js',
            [ 'ctc-clipboard' ],
            COPY_THE_CODE_VER,
            true
        );
        // Block.
        wp_enqueue_style(
            'ctc-el-blockquote',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/blockquote/style.css',
            [ 'ctc-blocks-core' ],
            COPY_THE_CODE_VER,
            'all'
        );
    }
    
    /**
     * Get script dependencies
     */
    public function get_script_depends()
    {
        return [ 'ctc-el-blockquote' ];
    }
    
    /**
     * Get style dependencies
     */
    public function get_style_depends()
    {
        return [ 'ctc-clipboard', 'ctc-blocks-core' ];
    }
    
    /**
     * Get name
     */
    public function get_name()
    {
        return 'ctc_blockquote';
    }
    
    /**
     * Get title
     */
    public function get_title()
    {
        return esc_html__( 'Blockquote', 'copy-the-code' );
    }
    
    /**
     * Get icon
     */
    public function get_icon()
    {
        return 'eicon-blockquote';
    }
    
    /**
     * Get categories
     */
    public function get_categories()
    {
        return [ 'copy-the-code' ];
    }
    
    /**
     * Get keywords
     */
    public function get_keywords()
    {
        return Helpers::get_keywords( [ 'blockquote', 'quote' ] );
    }
    
    /**
     * Render
     */
    public function render()
    {
        $blockquote = $this->get_settings_for_display( 'blockquote' );
        $author = $this->get_settings_for_display( 'author' );
        $with_icon = ( 'yes' === $this->get_settings_for_display( 'show_icon' ) ? 'with-icon' : '' );
        ?>
        <div class="ctc-block ctc-blockquote">
            <div class="ctc-block-content">
                <div class="ctc-blockquote-box">
                    <div class="ctc-blockquote-message"><?php 
        echo  wp_kses_post( $blockquote ) ;
        ?></div>
                    <div class="ctc-blockquote-author"><?php 
        echo  esc_html( $author ) ;
        ?></div>
                </div>
            </div>
            <div class="ctc-block-actions">
                <?php 
        Helpers::render_copy_button( $this );
        ?>
            </div>
            <?php 
        Helpers::render_copy_content( $this );
        ?>
        </div>
        <?php 
    }
    
    /**
     * Register controls
     */
    protected function _register_controls()
    {
        // Copy Content Section.
        Helpers::register_copy_content_section( $this, [
            'default' => '"Top improve is to change; to be perfect is to change often."

— WINSTON CHURCHILL',
        ] );
        /**
         * Group: Blockquote Section
         */
        $this->start_controls_section( 'blockquote_section', [
            'label' => esc_html__( 'Blockquote', 'copy-the-code' ),
        ] );
        $this->add_control( 'blockquote', [
            'label'   => esc_html__( 'Blockquote', 'copy-the-code' ),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => '"Top improve is to change; to be perfect is to change often."',
            'rows'    => 10,
        ] );
        $this->add_control( 'author', [
            'label'   => esc_html__( 'Author', 'copy-the-code' ),
            'type'    => Controls_Manager::TEXT,
            'default' => '— WINSTON CHURCHILL',
        ] );
        $this->end_controls_section();
        // Copy Button Section.
        Helpers::register_copy_button_section( $this, [
            'button_text' => esc_html__( 'Copy Blockquote', 'copy-the-code' ),
        ] );
        Helpers::register_pro_sections( $this, [
            'Quote Box',
            'Message Box',
            'Quote',
            'Author'
        ] );
        // Copy Button Style Section.
        Helpers::register_copy_button_style_section( $this );
    }

}