<?php

/**
 * Elementor SMS Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */
namespace CopyTheCode\Elementor\Block;

use  CopyTheCode\Helpers ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
/**
 * SMS Block
 *
 * @since 3.1.0
 */
class SMS extends Widget_Base
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
            'ctc-el-sms',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/sms/style.css',
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
        return [ 'ctc-el-sms' ];
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
        return 'ctc_sms';
    }
    
    /**
     * Get title
     */
    public function get_title()
    {
        return esc_html__( 'SMS', 'copy-the-code' );
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
        return Helpers::get_keywords( [ 'sms' ] );
    }
    
    /**
     * Render
     */
    public function render()
    {
        $sms = $this->get_settings_for_display( 'sms' );
        if ( empty($sms) ) {
            return;
        }
        $sms = wpautop( $sms );
        ?>
        <div class="ctc-block ctc-sms">
            <div class="ctc-block-content">
                <div class="ctc-sms-box">
                    <div class="ctc-sms-text"><?php 
        echo  wp_kses_post( $sms ) ;
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
        $default = 'You are the most special person in my life. I love you from the deepest core of my heart.';
        Helpers::register_copy_content_section( $this, [
            'default' => $default,
        ] );
        /**
         * Group: SMS Section
         */
        $this->start_controls_section( 'sms_section', [
            'label' => esc_html__( 'SMS', 'copy-the-code' ),
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
            '{{WRAPPER}} .ctc-sms-text p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_control( 'sms', [
            'label'   => esc_html__( 'SMS', 'copy-the-code' ),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => $default,
        ] );
        $this->end_controls_section();
        Helpers::register_copy_button_section( $this, [
            'button_text' => esc_html__( 'Copy SMS', 'copy-the-code' ),
        ] );
        Helpers::register_pro_sections( $this, [ 'SMS Box', 'SMS' ] );
        Helpers::register_copy_button_style_section( $this );
    }

}