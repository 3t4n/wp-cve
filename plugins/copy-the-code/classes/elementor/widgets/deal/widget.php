<?php

/**
 * Elementor Deal Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */
namespace CopyTheCode\Elementor\Block;

use  CopyTheCode\Helpers ;
use  Elementor\Utils ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
/**
 * Deal Block
 *
 * @since 3.1.0
 */
class Deal extends Widget_Base
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
            'ctc-el-deal',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/deal/style.css',
            [],
            COPY_THE_CODE_VER,
            'all'
        );
        wp_enqueue_script(
            'ctc-el-deal',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/deal/script.js',
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
        return [ 'ctc-el-deal' ];
    }
    
    /**
     * Get script dependencies
     */
    public function get_script_depends()
    {
        return [ 'ctc-el-deal' ];
    }
    
    /**
     * Get name
     */
    public function get_name()
    {
        return 'ctc_deal';
    }
    
    /**
     * Get title
     */
    public function get_title()
    {
        return esc_html__( 'Deal', 'copy-the-code' );
    }
    
    /**
     * Get icon
     */
    public function get_icon()
    {
        return 'eicon-basket-solid';
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
            'deal',
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
        $button_text = $this->get_settings_for_display( 'button_text' );
        $button_link = $this->get_settings_for_display( 'button_link' );
        $link_target = $this->get_settings_for_display( 'link_target' );
        $details = $this->get_settings_for_display( 'details' );
        $toggle_details = $this->get_settings_for_display( 'toggle_details' );
        ?>
        <div class="ctc-block ctc-deal">
            <div class="ctc-deal-header">
                <?php 
        
        if ( $image['url'] ) {
            ?>
                    <div class="ctc-deal-image">
                        <img src="<?php 
            echo  esc_url( $image['url'] ) ;
            ?>" alt="<?php 
            echo  esc_attr( $heading ) ;
            ?>">
                    </div>
                <?php 
        }
        
        ?>
                <div class="ctc-deal-headings">
                    <h3 class="ctc-deal-heading"><?php 
        echo  esc_html( $heading ) ;
        ?></h3>

                    <?php 
        
        if ( $description ) {
            ?>
                        <div class="ctc-deal-description"><?php 
            echo  wp_kses_post( wpautop( $description ) ) ;
            ?></div>
                    <?php 
        }
        
        ?>

                </div>
                <div class="ctc-deal-cta">
                    <a href="<?php 
        echo  esc_url( $button_link['url'] ) ;
        ?>" target="<?php 
        echo  esc_attr( $link_target ) ;
        ?>" class="ctc-deal-link"><?php 
        echo  esc_html( $button_text ) ;
        ?></a>
                </div>
            </div>
            <?php 
        
        if ( $details ) {
            ?>
                <div class="ctc-deal-details">
                    <?php 
            $toggle_details_class = ( 'yes' === $toggle_details ? 'ctc-toggle-details' : '' );
            if ( 'yes' === $toggle_details ) {
                echo  '<a href="#" class="ctc-deal-toggle-link">Show Details</a>' ;
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
            'label' => esc_html__( 'Deal Image', 'copy-the-code' ),
        ] );
        $this->add_control( 'image', [
            'label'   => esc_html__( 'Deal Image', 'copy-the-code' ),
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
            'default' => esc_html__( 'Get up to 10% off on all products', 'copy-the-code' ),
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
        $this->start_controls_section( 'button_section', [
            'label' => esc_html__( 'Button', 'copy-the-code' ),
        ] );
        // Button Text.
        $this->add_control( 'button_text', [
            'label'   => esc_html__( 'Button Text', 'copy-the-code' ),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__( 'Get Deal', 'copy-the-code' ),
        ] );
        // Button Link.
        $this->add_control( 'button_link', [
            'label'   => esc_html__( 'Button Link', 'copy-the-code' ),
            'type'    => Controls_Manager::URL,
            'default' => [
            'url' => '#',
        ],
        ] );
        // Link target.
        $this->add_control( 'link_target', [
            'label'   => esc_html__( 'Link Target', 'copy-the-code' ),
            'type'    => Controls_Manager::SELECT,
            'default' => '_blank',
            'options' => [
            '_self'  => esc_html__( 'Self', 'copy-the-code' ),
            '_blank' => esc_html__( 'Blank', 'copy-the-code' ),
        ],
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'details_section', [
            'label' => esc_html__( 'Details', 'copy-the-code' ),
        ] );
        // Details.
        $this->add_control( 'details', [
            'label'   => esc_html__( 'Details', 'copy-the-code' ),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<ul><li>Get upto 50% off on all products.</li><li>
                Offer valid till <b>31st December 2020</b>.</li><li>No coupon code required.</li></ul>',
        ] );
        // Enable Toggle Details.
        $this->add_control( 'toggle_details', [
            'label'   => esc_html__( 'Enable Toggle Details', 'copy-the-code' ),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );
        $this->end_controls_section();
        Helpers::register_pro_sections( $this, [
            'Header',
            'Heading',
            'Description',
            'Button',
            'Details',
            'Details > Link',
            'Details > Content'
        ] );
    }

}