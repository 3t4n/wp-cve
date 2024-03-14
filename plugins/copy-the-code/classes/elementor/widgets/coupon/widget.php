<?php

/**
 * Elementor Coupon Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */
namespace CopyTheCode\Elementor\Block;

use  Elementor\Utils ;
use  CopyTheCode\Helpers ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
/**
 * Coupon Block
 *
 * @since 3.1.0
 */
class Coupon extends Widget_Base
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
        // Block.
        wp_enqueue_style(
            'ctc-el-coupon',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/coupon/style.css',
            [],
            COPY_THE_CODE_VER,
            'all'
        );
        wp_enqueue_script(
            'ctc-el-coupon',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/coupon/script.js',
            [],
            COPY_THE_CODE_VER,
            true
        );
    }
    
    /**
     * Get style dependencies
     */
    public function get_style_depends()
    {
        return [ 'ctc-el-coupon' ];
    }
    
    /**
     * Get script dependencies
     */
    public function get_script_depends()
    {
        return [ 'ctc-el-coupon' ];
    }
    
    /**
     * Get name
     */
    public function get_name()
    {
        return 'ctc_coupon';
    }
    
    /**
     * Get title
     */
    public function get_title()
    {
        return esc_html__( 'Coupon Code', 'copy-the-code' );
    }
    
    /**
     * Get icon
     */
    public function get_icon()
    {
        return 'eicon-cart-solid';
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
        return Helpers::get_keywords( [
            'coupon',
            'offer',
            'coupon',
            'sale'
        ] );
    }
    
    /**
     * Render
     */
    public function render()
    {
        $image = $this->get_settings_for_display( 'image' );
        $heading = $this->get_settings_for_display( 'heading' );
        $description = $this->get_settings_for_display( 'description' );
        $slide_button_text = $this->get_settings_for_display( 'slide_button_text' );
        $coupon_code = $this->get_settings_for_display( 'coupon_code' );
        $button_link = $this->get_settings_for_display( 'button_link' );
        $link_target = $this->get_settings_for_display( 'link_target' );
        $details = $this->get_settings_for_display( 'details' );
        $toggle_details = $this->get_settings_for_display( 'toggle_details' );
        $button_text = $this->get_settings_for_display( 'button_text' );
        $button_text_copied = $this->get_settings_for_display( 'button_text_copied' );
        $show_icon = $this->get_settings_for_display( 'show_icon' );
        $icon_direction = $this->get_settings_for_display( 'icon_direction' );
        $display_slide_button = $this->get_settings_for_display( 'display_slide_button' );
        $clicked = ( 'yes' === $display_slide_button ? '' : ' ctc-coupon-clicked' );
        ?>
        <div class="ctc-block ctc-coupon <?php 
        echo  $clicked ;
        ?>">
            <div class="ctc-coupon-header">
                <?php 
        
        if ( $image['url'] ) {
            ?>
                    <div class="ctc-coupon-image">
                        <img src="<?php 
            echo  esc_url( $image['url'] ) ;
            ?>" alt="<?php 
            echo  esc_attr( $heading ) ;
            ?>">
                    </div>
                <?php 
        }
        
        ?>
                <div class="ctc-coupon-headings">
                    <h3 class="ctc-coupon-heading"><?php 
        echo  esc_html( $heading ) ;
        ?></h3>

                    <?php 
        
        if ( $description ) {
            ?>
                        <div class="ctc-coupon-description"><?php 
            echo  wp_kses_post( wpautop( $description ) ) ;
            ?></div>
                    <?php 
        }
        
        ?>

                </div>
                <div class="ctc-coupon-cta">
                    <span class="ctc-coupon-code"><?php 
        echo  esc_html( $coupon_code ) ;
        ?></span>
                    
                    <?php 
        
        if ( 'yes' === $display_slide_button ) {
            ?>
                        <a href="<?php 
            echo  esc_url( $button_link['url'] ) ;
            ?>" target="<?php 
            echo  esc_attr( $link_target ) ;
            ?>" class="ctc-coupon-link"><?php 
            echo  esc_html( $slide_button_text ) ;
            ?></a>
                    <?php 
        }
        
        ?>

                    <?php 
        echo  Helpers::get_copy_button( [
            'as_raw'                  => 'no',
            'copy_button_text'        => $button_text,
            'copy_button_text_copied' => $button_text_copied,
            'icon_direction'          => $icon_direction,
            'show_icon'               => $show_icon,
        ] ) ;
        ?>
                    <textarea class="ctc-copy-content" style="display: none;"><?php 
        echo  esc_html( $coupon_code ) ;
        ?></textarea>
                </div>
            </div>
            <?php 
        
        if ( $details ) {
            ?>
                <div class="ctc-coupon-details">
                    <?php 
            $toggle_details_class = ( 'yes' === $toggle_details ? 'ctc-toggle-details' : '' );
            if ( 'yes' === $toggle_details ) {
                echo  '<a href="#" class="ctc-coupon-toggle-link">Show Details</a>' ;
            }
            echo  '<div class="ctc-details-content ' . $toggle_details_class . '">' . wp_kses_post( wpautop( $details ) ) . '</div>' ;
            ?>
                </div>
            <?php 
        }
        
        ?>
        </div>
        <?php 
    }
    
    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section( 'image_section', [
            'label' => esc_html__( 'Coupon Image', 'copy-the-code' ),
        ] );
        $this->add_control( 'image', [
            'label'   => esc_html__( 'Coupon Image', 'copy-the-code' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [
            'url' => Utils::get_placeholder_image_src(),
        ],
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'heading_section', [
            'label' => esc_html__( 'Heading', 'copy-the-code' ),
        ] );
        $this->add_control( 'heading', [
            'label'   => esc_html__( 'Heading', 'copy-the-code' ),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__( 'Up to 50% off', 'copy-the-code' ),
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'description_section', [
            'label' => esc_html__( 'Short Description', 'copy-the-code' ),
        ] );
        $this->add_control( 'description', [
            'label'   => esc_html__( 'Short Description', 'copy-the-code' ),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => 'Get up to 50% off on all products. Offer valid till <b>31st December 2020</b>.',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'coupon_button_section', [
            'label' => esc_html__( 'Coupon Code', 'copy-the-code' ),
        ] );
        $this->add_control( 'coupon_code', [
            'label'   => esc_html__( 'Coupon Code', 'copy-the-code' ),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__( 'CTC30', 'copy-the-code' ),
        ] );
        $this->add_control( 'display_slide_button', [
            'label'   => esc_html__( 'Display Slide Button', 'copy-the-code' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );
        // Button Text.
        $this->add_control( 'slide_button_text', [
            'label'     => esc_html__( 'Slide Button Text', 'copy-the-code' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__( 'Show Coupon Code', 'copy-the-code' ),
            'condition' => [
            'display_slide_button' => 'yes',
        ],
        ] );
        // Button Link.
        $this->add_control( 'button_link', [
            'label'     => esc_html__( 'Button Link', 'copy-the-code' ),
            'type'      => Controls_Manager::URL,
            'default'   => [
            'url' => '#',
        ],
            'condition' => [
            'display_slide_button' => 'yes',
        ],
        ] );
        // Link target.
        $this->add_control( 'link_target', [
            'label'     => esc_html__( 'Link Target', 'copy-the-code' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => '_blank',
            'options'   => [
            '_self'  => esc_html__( 'Self', 'copy-the-code' ),
            '_blank' => esc_html__( 'Blank', 'copy-the-code' ),
        ],
            'condition' => [
            'display_slide_button' => 'yes',
        ],
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'details_section', [
            'label' => esc_html__( 'Details', 'copy-the-code' ),
        ] );
        // Enable Toggle Details.
        $this->add_control( 'toggle_details', [
            'label'   => esc_html__( 'Enable Toggle Details', 'copy-the-code' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );
        // Details.
        $this->add_control( 'details', [
            'label'   => esc_html__( 'Details', 'copy-the-code' ),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<ul><li>Get upto 50% off on all products.</li><li>
                Offer valid till <b>31st December 2020</b>.</li><li>No coupon code required.</li></ul>',
        ] );
        $this->end_controls_section();
        /**
         * Group - Copy Button
         */
        $this->start_controls_section( 'button_section', [
            'label' => esc_html__( 'Copy Button', 'copy-the-code' ),
        ] );
        $this->add_control( 'button_text', [
            'label'   => esc_html__( 'Copy Button Text', 'copy-the-code' ),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__( 'Copy Code', 'copy-the-code' ),
        ] );
        $this->add_control( 'button_text_copied', [
            'label'   => esc_html__( 'After Copy Button Text', 'copy-the-code' ),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__( 'Code Copied', 'copy-the-code' ),
        ] );
        // Show Icon.
        $this->add_control( 'show_icon', [
            'label'        => esc_html__( 'Show Icon', 'copy-the-code' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Show', 'copy-the-code' ),
            'label_off'    => esc_html__( 'Hide', 'copy-the-code' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );
        $this->add_control( 'icon_direction', [
            'label'     => esc_html__( 'Icon Direction', 'copy-the-code' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'before',
            'options'   => [
            'before' => esc_html__( 'Before', 'copy-the-code' ),
            'after'  => esc_html__( 'After', 'copy-the-code' ),
        ],
            'condition' => [
            'show_icon' => 'yes',
        ],
        ] );
        $this->add_responsive_control( 'icon_text_gap', [
            'label'      => esc_html__( 'Icon and Text Gap', 'copy-the-code' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range'      => [
            'px' => [
            'min' => 0,
            'max' => 100,
        ],
            'em' => [
            'min' => 0,
            'max' => 10,
        ],
        ],
            'selectors'  => [
            '{{WRAPPER}} .ctc-with-icon' => 'gap: {{SIZE}}{{UNIT}};',
        ],
            'condition'  => [
            'show_icon' => 'yes',
        ],
        ] );
        $this->end_controls_section();
        Helpers::register_pro_sections( $this, [
            'Header',
            'Heading',
            'Description',
            'Button',
            'Coupon Code',
            'Details',
            'Details > Link',
            'Details > Content'
        ] );
    }

}