<?php

/**
 * Elementor Wish Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */
namespace CopyTheCode\Elementor\Block;

use  CopyTheCode\Helpers ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
/**
 * Wish Block
 *
 * @since 3.1.0
 */
class Wish extends Widget_Base
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
            'ctc-el-wish',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/wish/style.css',
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
        return [ 'ctc-el-wish' ];
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
        return 'ctc_wish';
    }
    
    /**
     * Get title
     */
    public function get_title()
    {
        return esc_html__( 'Wish', 'copy-the-code' );
    }
    
    /**
     * Get icon
     */
    public function get_icon()
    {
        return 'eicon-columns';
    }
    
    /**
     * Get categories
     */
    public function get_categories()
    {
        return Helpers::get_categories();
    }
    
    /**
     * Get keywords
     */
    public function get_keywords()
    {
        return Helpers::get_keywords( [ 'wish' ] );
    }
    
    /**
     * Render
     */
    public function render()
    {
        $wish = $this->get_settings_for_display( 'wish' );
        if ( empty($wish) ) {
            return;
        }
        $wish = wpautop( $wish );
        ?>
        <div class="ctc-block ctc-wish">
            <div class="ctc-block-content">
                <div class="ctc-wish-box">
                    <div class="ctc-wish-text"><?php 
        echo  wp_kses_post( $wish ) ;
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
        $default = 'Wishing you a very happy and prosperous Diwali.

May the light of Diwali fill your home with light of joy and happiness.

On this great day, I wish you a happy Diwali.';
        Helpers::register_copy_content_section( $this, [
            'default' => $default,
        ] );
        /**
         * Group: Wish Section
         */
        $this->start_controls_section( 'wish_section', [
            'label' => esc_html__( 'Wish', 'copy-the-code' ),
        ] );
        // Two paragraph gap in pixcel.
        $this->add_responsive_control( 'line_gap', [
            'label'      => esc_html__( 'Line Gap', 'copy-the-code' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range'      => [
            'px' => [
            'min' => 0,
            'max' => 100,
        ],
        ],
            'selectors'  => [
            '{{WRAPPER}} .ctc-wish-text p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_control( 'wish', [
            'label'   => esc_html__( 'Wish', 'copy-the-code' ),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => $default,
        ] );
        $this->end_controls_section();
        Helpers::register_copy_button_section( $this, [
            'button_text' => esc_html__( 'Copy Wish', 'copy-the-code' ),
        ] );
        Helpers::register_pro_sections( $this, [ 'Wish Box', 'Wish' ] );
        Helpers::register_copy_button_style_section( $this );
    }

}