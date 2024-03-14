<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Icons_Manager;

class MEAFE_Blockquote extends Widget_Base
{

    public function get_name() {
        return 'meafe-blockquote';
    }

    public function get_title() {
        return esc_html__( 'Blockquote', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-blockquote';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-blockquote'];
    }

    protected function register_controls()
    {
        /**
         * Blockquote General Settings
         */
        $this->start_controls_section(
            'meafe_blockquote_content_general_settings',
            [
                'label' => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );
        $this->add_control(
            'bbcgs_blockquote_type',
            [
                'label'         => esc_html__( 'Blockquote Type', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'quotation',
                'label_block'   => false,
                'options'       => [
                    'quotation' => esc_html__( 'Quotation', 'mega-elements-addons-for-elementor' ),
                    'clean'     => esc_html__( 'Clean', 'mega-elements-addons-for-elementor' ),
                ],
                'prefix_class'  => 'meafe-blockquote-type-'
            ]
        );
        $this->add_control(
            'bbcgs_blockquote_alignment',
            [
                'label'         => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'     => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'    => [
                        'title'     => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'     => [
                        'title'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'prefix_class'  => 'meafe-blockquote-align-',
                'separator'       => 'after',
            ]
        );

        $this->add_control(
            'bbcgs_quote_bg_image',
            [
                'label'         => esc_html__( 'Choose Image', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
                'default'       => [
                    'url'       => Utils::get_placeholder_image_src(),
                ],
                'show_label'    => false,
            ]                    
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'         => 'bbcgs_quote_bg_image',
                'label'         => esc_html__( 'Image Resolution', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => 'full',
                'condition'     => [
                    'bbcgs_quote_bg_image[id]!' => '',
                ],
                'separator'     => 'none',
            ]                    
        );
        
        $this->add_control(
            'bbsqs_quote_background_color',
            [
                'label'         => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-blockquote' => 'background-color: {{VALUE}}',
                ],
                'condition'     => [
                    'bbcgs_quote_bg_image[id]' => '',
                ],
            ]
        );

        $this->add_control(
            'bbcgs_blockquote_selected_icon',
            [
                'label'     => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::ICONS,
                'fa4compatibility' => 'bbcgs_blockquote_icon',
                'default'   => [
                    'value' => 'fas fa-quote-left',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'bbcgs_blockquote_content',
            [
                'label'         => esc_html__( 'Content', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXTAREA,
                'default'       => esc_html__( 'Click Edit Button to changr his text. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit.', 'mega-elements-addons-for-elementor' ),
                'placeholder'   => esc_html__( 'Enter Your Quote', 'mega-elements-addons-for-elementor' ),
                'rows'          => 10,
            ]                    
        );

        $this->add_control(
            'bbcgs_blockquote_author',
            [
                'label'         => esc_html__( 'Author', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__( 'Doe Mank', 'mega-elements-addons-for-elementor' ),
                'label_block'   => false,
                'rows'          => 10,
            ]                    
        );

        $this->end_controls_section();

        /**
         * Blockquote Content Style 
         */
        $this->start_controls_section(
            'meafe_blockquote_style_content_style',
            [
                'label' => esc_html__( 'Content Style', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bbscs_content_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-blockquote-content' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbscs_content_typography',
                'selector'  => '{{WRAPPER}} .meafe-blockquote-content',
            ]
        );

        $this->add_control(
            'bbscs_content_gap',
            [
                'label'     => esc_html__( 'Gap', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .meafe-blockquote-content' => 'margin-top: {{SIZE}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'bbscs_heading_author_style',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Author', 'mega-elements-addons-for-elementor' ),
                'selector'  => 'before',
            ]
        );

        $this->add_control(
            'bbscs_author_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-blockquote-author' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbscs_author_typography',
                'selector'  => '{{WRAPPER}} .meafe-blockquote-author',
            ]
        );

        $this->add_control(
            'bbscs_author_gap',
            [
                'label'     => esc_html__( 'Gap', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .author-wrapper' => 'margin-top: {{SIZE}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Quote Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_blockquote_style_quote_style',
            [
                'label'     => esc_html__( 'Quote Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bbsqs_quote_text_color',
            [
                'label'     => esc_html__( 'Quote Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-blockquote-icon-wrapper' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsqs_quote_size',
            [
                'label'         => esc_html__( 'Quote Size', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => '30',
                'selectors'     => [
                    '{{WRAPPER}} .meafe-blockquote-icon-wrapper' => 'font-size: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'bbsqs_quote_gap',
            [
                'label'     => esc_html__( 'Gap', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .meafe-blockquote-icon-wrapper' => 'margin-top: {{SIZE}}{{UNIT}}',
                ]
            ]
        );
       

        $this->start_controls_tabs( 'bbsqs_tabs_quote_style' );

        $this->start_controls_tab(
            'bbsqs_quote_normal',
            [
                'label'         => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'bbsqs_quote_border',
                'selector' => '{{WRAPPER}} .meafe-blockquote-icon-wrapper',
            ]
        );

        $this->add_responsive_control(
            'bbsqs_quote_border_radius',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-blockquote-icon-wrapper' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'bbsqs_quote_box_shadow',
                'exculde'  => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .meafe-blockquote-icon-wrapper',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'bbsqs_quote_hover',
            [
                'label'         => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'bbsqs_quote_border_hover',
                'selector' => '{{WRAPPER}} .meafe-blockquote-icon-wrapper:hover',
            ]
        );

        $this->add_responsive_control(
            'bbsqs_quote_border_radius_hover',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-blockquote-icon-wrapper:hover' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'bbsqs_quote_box_shadow_hover',
                'exculde'  => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .meafe-blockquote-icon-wrapper:hover',
            ]
        );

        $this->end_controls_tab();        

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings();

        $migrated = isset( $settings['__fa4_migrated']['bbcgs_blockquote_selected_icon'] );

        if ( ! isset( $settings['bbcgs_blockquote_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
            $settings['bbcgs_blockquote_icon'] = 'fa fa-quote-left';
        }

        $is_new = empty( $settings['bbcgs_blockquote_icon'] ) && Icons_Manager::is_migration_allowed();

        $bg_image = '';
        $bg_image_class = '';
        if ( ! empty( $settings['bbcgs_quote_bg_image']['id'] ) ) {
            $bg_image = Group_Control_Image_Size::get_attachment_image_src( $settings['bbcgs_quote_bg_image']['id'], 'bbcgs_quote_bg_image', $settings );
            $bg_image_class = 'meafe-blockquote-bg';
        } elseif ( ! empty( $settings['bbcgs_quote_bg_image']['url'] ) ) {
            $bg_image = $settings['bbcgs_quote_bg_image']['url'];
            $bg_image_class = 'meafe-blockquote-bg';
        }

        $this->add_render_attribute( 'meafe_blockquote', 'class', [
            'meafe-blockquote', $bg_image_class
        ] );

        $this->add_render_attribute( 'quote_background_image', 'style', [
            'background-image: url(' . $bg_image . ');',
        ] );

        $this->add_render_attribute( [
            'bbcgs_blockquote_content'  => [ 'class' => 'meafe-blockquote-content' ],
            'bbcgs_blockquote_author'   => [ 'class' => 'meafe-blockquote-author' ],
        ] );

        if ( ! empty ( $settings['bbcgs_blockquote_icon'] )  ) {
            $this->add_render_attribute( 'bbcgs_blockquote_icon', 'class', $settings['bbcgs_blockquote_icon'] );
            $this->add_render_attribute( 'bbcgs_blockquote_icon', 'aria-hidden', 'true' );
        }

        $this->add_inline_editing_attributes( 'bbcgs_blockquote_content' );
        $this->add_inline_editing_attributes( 'bbcgs_blockquote_author', 'none' );
        ?>
        <blockquote <?php echo $this->get_render_attribute_string( 'meafe_blockquote' ); ?> <?php echo $this->get_render_attribute_string( 'quote_background_image' ); ?>>  
            <div class="meafe-blockquote-icon-wrapper">  
                <?php
                if ( $is_new || $migrated ) { ?>
                    <?php Icons_Manager::render_icon( $settings['bbcgs_blockquote_selected_icon'] ); ?>
                <?php } else { ?>
                    <i <?php echo $this->get_render_attribute_string( 'bbcgs_blockquote_icon' ); ?>></i>
                <?php } ?>
            </div>
            <p <?php echo $this->get_render_attribute_string( 'bbcgs_blockquote_content' ); ?>>
                <?php echo wp_kses_post( $settings['bbcgs_blockquote_content'] ); ?>
            </p>
            <div class="author-wrapper">
                <?php if( !empty( $settings['bbcgs_blockquote_author'] ) ) : ?>
                    <cite <?php echo $this->get_render_attribute_string( 'bbcgs_blockquote_author' ); ?>><?php echo esc_html( $settings['bbcgs_blockquote_author'] ); ?></cite>
                <?php endif; ?>
            </div>
        </blockquote>
    <?php
    }

    protected function content_template() { ?>
        
        <#
            if ( '' !== settings.bbcgs_quote_bg_image.url ) {
                var bg_image = {
                    id: settings.bbcgs_quote_bg_image.id,
                    url: settings.bbcgs_quote_bg_image.url,
                    size: settings.bbcgs_quote_bg_image_size,
                    dimension: settings.bbcgs_quote_bg_image_custom_dimension,
                    model: view.getEditModel()
                };

                var bgImageUrl = elementor.imagesManager.getImageUrl( bg_image );
            }

            view.addRenderAttribute( 'quote_background_image', 'style', 'background-image: url(' + bgImageUrl + ');' );
            view.addRenderAttribute( 'meafe_quote_background', 'class', [
                'meafe-blockquote', 'meafe-blockquote-bg'
            ] );

            view.addRenderAttribute( 'bbcgs_blockquote_icon', 'class', settings.bbcgs_blockquote_icon );
            
            var iconHTML = elementor.helpers.renderIcon( view, settings.bbcgs_blockquote_selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
            migrated = elementor.helpers.isIconMigrated( settings, 'bbcgs_blockquote_selected_icon' );
        #>
        <blockquote {{{ view.getRenderAttributeString( 'meafe_quote_background' ) }}} {{{ view.getRenderAttributeString( 'quote_background_image' ) }}}>
            <div class="meafe-blockquote-icon-wrapper">
                <# if ( iconHTML && iconHTML.rendered && ( ! settings.bbcgs_blockquote_icon || migrated ) ) { #>
                    {{{ iconHTML.value }}}
                <# } else { #>
                    <i {{{ view.getRenderAttributeString( 'bbcgs_blockquote_icon' ) }}} aria-hidden="true"></i>
                <# } #>
            </div>    
            <p class="meafe-blockquote-content">
                {{{ settings.bbcgs_blockquote_content }}}
            </p>
            <div class="author-wrapper">
                <# if( settings.bbcgs_blockquote_author ) { #>
                    <cite class="meafe-blockquote-author">
                        {{{ settings.bbcgs_blockquote_author }}}
                    </cite>
                <# } #>
            </div>
        </blockquote>
        <?php
    }
}