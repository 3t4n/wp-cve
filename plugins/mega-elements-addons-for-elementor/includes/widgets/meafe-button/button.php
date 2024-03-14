<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;

class MEAFE_Button extends Widget_Base
{

    public function get_name() {
        return 'meafe-button';
    }

    public function get_title() {
        return esc_html__( 'Button', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-button';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-button'];
    }

    public function get_all_pages() {
        
        $posts = get_posts([
            'post_type'         => 'page',
            'post_style'        => 'all_types',
            'posts_per_page'    => '-1',
        ]);

        if ( !empty( $posts ) ) {
            return wp_list_pluck( $posts, 'post_title', 'ID' );
        }

        return [];
    }

    protected function register_controls() 
    {
        /**
         * Button General Settings
        */
        $this->start_controls_section(
            'meafe_button_content_general_settings',
            [
                'label'     => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );
    
        $this->add_control(
            'bbcgs_button_text',
            [
                'label'         => esc_html__( 'Text', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => esc_html__( 'Click here', 'mega-elements-addons-for-elementor' ),
                'label_block'   => true,
            ]
        );
        
        $this->add_control(
            'bbcgs_button_link_selection', 
            [
                'label'         => esc_html__( 'Link Type', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => esc_html__( 'URL', 'mega-elements-addons-for-elementor' ),
                    'link'  => esc_html__( 'Existing Page', 'mega-elements-addons-for-elementor' ),
                ],
                'default'       => 'url',
                'label_block'   => true,
            ]
        );
        
        $this->add_control(
            'bbcgs_button_link',
            [
                'label'         => esc_html__( 'Link', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'   => '#',
                ],
                'placeholder'   => 'https://',
                'label_block'   => true,
                'condition'     => [
                    'bbcgs_button_link_selection' => 'url'
                ]
            ]
        );
        
        $this->add_control(
            'bbcgs_button_existing_link',
            [
                'label'         => esc_html__( 'Existing Page', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->get_all_pages(),
                'condition'     => [
                    'bbcgs_button_link_selection'     => 'link',
                ],
                'multiple'      => false,
                'label_block'   => true,
            ]
        );

        $this->add_control(
            'bbcgs_button_icon_switcher',
            [
                'label'         => esc_html__( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => esc_html__( 'Enable or disable button icon', 'mega-elements-addons-for-elementor' ),
                'separator'     => 'before'
            ]
        );
        
        $this->add_control(
            'bbcgs_button_icon_selection_updated',
            [
                'label'             => esc_html__( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'bbcgs_button_icon_selection',
                'default' => [
                    'value'     => 'fas fa-bars',
                    'library'   => 'fa-solid',
                ],
                'condition'         => [
                    'bbcgs_button_icon_switcher'  => 'yes',
                ],
                'label_block'   => true,
            ]
        );

        $this->add_control(
            'bbcgs_button_icon_position', 
            [
                'label'         => esc_html__( 'Icon Position', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'before',
                'options'       => [
                    'before'        => esc_html__( 'Before', 'mega-elements-addons-for-elementor' ),
                    'after'         => esc_html__( 'After', 'mega-elements-addons-for-elementor' ),
                ],
                'condition'     => [
                    'bbcgs_button_icon_switcher' => 'yes',
                ],
                'label_block'   => true,
            ]
        );
        
        $this->add_responsive_control(
            'bbcgs_button_icon_before_size',
            [
                'label'         => esc_html__( 'Icon Size', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'condition'     => [
                    'bbcgs_button_icon_switcher' => 'yes',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-button-text-icon-wrapper svg' => 'width: {{SIZE}}px; height: {{SIZE}}px',
                ]
            ]
        );

        $this->add_control(
            'bbcgs_button_size', 
            [
                'label'         => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'sm',
                'options'       => [
                        'sm'          => esc_html__( 'Small', 'mega-elements-addons-for-elementor' ),
                        'md'          => esc_html__( 'Medium', 'mega-elements-addons-for-elementor' ),
                        'lg'          => esc_html__( 'Large', 'mega-elements-addons-for-elementor' ),
                        'block'       => esc_html__( 'Block', 'mega-elements-addons-for-elementor' ),
                    ],
                'label_block'   => true,
                'separator'     => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'bbcgs_button_align',
            [
                'label'             => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::CHOOSE,
                'options'           => [
                    'left'      => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title' => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors'         => [
                    '{{WRAPPER}} .meafe-button-container' => 'text-align: {{VALUE}}',
                ],
                'default' => 'center',
            ]
        );
        
        $this->add_control(
            'bbcgs_button_event_switcher', 
            [
                'label'         => esc_html__( 'onclick Event', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'separator'     => 'before',
            ]
        );

        $this->add_control(
            'bbcgs_button_event_function', 
            [
                'label'         => esc_html__( 'Example: myFunction();', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXTAREA,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'bbcgs_button_event_switcher' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Button General Style
        */
        $this->start_controls_section(
            'meafe_button_style_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );    
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'bbsgs_button_typo',
                'selector'          => '{{WRAPPER}} .meafe-button',
            ]
        );

        $this->add_responsive_control(
            'bbsgs_button_width_normal',
            [
                'label'         => esc_html__( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => [ '%','px','em'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-button' => 'width: {{SIZE}}{{UNIT}}',
                ]
            ]
        );
        
        $this->start_controls_tabs( 'bbsgs_button_style_tabs' );
        
        $this->start_controls_tab( 
            'bbsgs_button_style_normal',
            [
                'label'             => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );
        
        $this->add_control(
            'bbsgs_button_text_color_normal',
            [
                'label'             => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '{{WRAPPER}} .meafe-button .meafe-button-text-icon-wrapper span'   => 'color: {{VALUE}}',
                ]
            ]);
        
        $this->add_control(
            'bbsgs_button_icon_color_normal',
            [
                'label'             => esc_html__( 'Icon Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '{{WRAPPER}} .meafe-button-text-icon-wrapper svg'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'bbcgs_button_icon_switcher'  => 'yes',
            ]
        ]);
        

        $this->add_control(
            'bbsgs_button_background_color_normal',
            [
                'label'             => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'      => [
                    '{{WRAPPER}} .meafe-button'  => 'background-color: {{VALUE}}',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'bbsgs_button_border_normal',
                'selector'      => '{{WRAPPER}} .meafe-button',
            ]
        );
        
        $this->add_control(
            'bbsgs_button_border_radius_normal',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-button' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'bbsgs_button_icon_shadow_normal',
                'label'         => esc_html__( 'Icon Shadow', 'mega-elements-addons-for-elementor' ),
                'selector'      => '{{WRAPPER}} .meafe-button-text-icon-wrapper svg',
                'condition'         => [
                    'bbcgs_button_icon_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'bbsgs_button_text_shadow_normal',
                'label'         => esc_html__( 'Text Shadow', 'mega-elements-addons-for-elementor' ),
                'selector'      => '{{WRAPPER}} .meafe-button-text-icon-wrapper span',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'bbsgs_button_box_shadow_normal',
                    'label'         => esc_html__( 'Button Shadow', 'mega-elements-addons-for-elementor' ),
                    'selector'      => '{{WRAPPER}} .meafe-button',
                ]
                );
        
        $this->add_responsive_control(
            'bbsgs_button_margin_normal',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'bbsgs_button_padding_normal',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'bbsgs_button_style_hover',
            [
                'label'         => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );
        
        $this->add_control(
            'bbsgs_button_text_color_hover',
            [
                'label'             => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '{{WRAPPER}} .meafe-button:hover .meafe-button-text-icon-wrapper span'   => 'color: {{VALUE}}',
                ],
            ]);
        
        $this->add_control(
            'bbsgs_button_icon_color_hover',
            [
                'label'             => esc_html__( 'Icon Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'         => [
                    '{{WRAPPER}} .meafe-button:hover .meafe-button-text-icon-wrapper svg'   => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'bbcgs_button_icon_switcher'  => 'yes',
                ]
            ]);
    
        
        $this->add_control(
            'bbsgs_button_background_hover',
            [
                'label'             => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::COLOR,
                'selectors'          => [
                    '{{WRAPPER}} .meafe-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'bbsgs_button_border_hover',
                'selector'      => '{{WRAPPER}} .meafe-button:hover',
            ]
        );
        
        $this->add_control(
            'bbsgs_button_border_radius_hover',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-button:hover' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => esc_html__( 'Icon Shadow', 'mega-elements-addons-for-elementor' ),
                    'name'          => 'bbsgs_button_icon_shadow_hover',
                    'selector'      => '{{WRAPPER}} .meafe-button:hover .meafe-button-text-icon-wrapper svg',
                    'condition'         => [
                            'bbcgs_button_icon_switcher'  => 'yes',
                        ]
                    ]
                );
        
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => esc_html__( 'Text Shadow', 'mega-elements-addons-for-elementor' ),
                'name'          => 'bbsgs_button_text_shadow_hover',
                'selector'      => '{{WRAPPER}} .meafe-button:hover .meafe-button-text-icon-wrapper span',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => esc_html__( 'Button Shadow', 'mega-elements-addons-for-elementor' ),
                'name'          => 'bbsgs_button_box_shadow_hover',
                'selector'      => '{{WRAPPER}} .meafe-button:hover',
            ]
        );
    
        $this->add_responsive_control(
            'bbsgs_button_margin_hover',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control( 
            'bbsgs_button_padding_hover',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes( 'bbcgs_button_text');
        
        $button_text = $settings['bbcgs_button_text'];
        
        if( $settings['bbcgs_button_link_selection'] == 'url' ){
            $button_url = $settings['bbcgs_button_link']['url'];
        } else {
            $button_url = get_permalink( $settings['bbcgs_button_existing_link'] );
        }
        
        $button_size = 'meafe-button-' . $settings['bbcgs_button_size'];
        
        $button_event = $settings['bbcgs_button_event_function'];
        
        if ( ! empty ( $settings['bbcgs_button_icon_selection'] ) ) {
            $this->add_render_attribute( 'icon', 'class', $settings['bbcgs_button_icon_selection'] );
            $this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
        }
        
        $migrated = isset( $settings['__fa4_migrated']['bbcgs_button_icon_selection_updated'] );
        $is_new = empty( $settings['bbcgs_icon_selection'] ) && Icons_Manager::is_migration_allowed();
        
        
        $this->add_render_attribute( 'button', 'class', array(
            'meafe-button',
            $button_size,
        ));
        
        if( ! empty( $button_url ) ) {
        
            $this->add_render_attribute( 'button', 'href', esc_url($button_url) );
            
            if( ! empty( $settings['bbcgs_button_link']['is_external'] ) )
                $this->add_render_attribute( 'button', 'target', '_blank' );
            
            if( ! empty( $settings['bbcgs_button_link']['nofollow'] ) )
                $this->add_render_attribute( 'button', 'rel', 'nofollow' );
        }
        
        if( 'yes' === $settings['bbcgs_button_event_switcher'] && ! empty( $button_event ) ) {
            $this->add_render_attribute( 'button', 'onclick', $button_event );
        }
        
        ?>

        <div class="meafe-button-container">
            <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                <div class="meafe-button-text-icon-wrapper">
                    <?php if( 'yes' === $settings['bbcgs_button_icon_switcher'] && $settings['bbcgs_button_icon_position'] === 'before' ) :
                        if ( $is_new || $migrated ) :
                            Icons_Manager::render_icon( $settings['bbcgs_button_icon_selection_updated'], [ 'aria-hidden' => 'true' ] );
                        else: ?>
                            <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                        <?php endif;
                    endif; ?>
                    <span <?php echo $this->get_render_attribute_string( 'bbcgs_button_text' ); ?>><?php echo esc_html( $button_text ); ?></span>
                    <?php if( 'yes' === $settings['bbcgs_button_icon_switcher'] && $settings['bbcgs_button_icon_position'] === 'after' ) :
                        if ( $is_new || $migrated ) :
                            Icons_Manager::render_icon( $settings['bbcgs_button_icon_selection_updated'], [ 'aria-hidden' => 'true' ] );
                        else: ?>
                            <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                        <?php endif;
                    endif; ?>
                </div>
            </a>
        </div>

        <?php
    }

    protected function content_template() {
        ?>
        <#
        
        view.addInlineEditingAttributes( 'bbcgs_button_text' );
        
        var buttonText = settings.bbcgs_button_text,
            buttonUrl,
            styleDir,
            slideIcon,
            buttonSize = 'meafe-button-' + settings.bbcgs_button_size,
            buttonEvent = settings.bbcgs_button_event_function,
            buttonIcon = settings.bbcgs_button_icon_selection;
        
        if( 'url' == settings.bbcgs_button_link_selection ) {
            buttonUrl = settings.bbcgs_button_link.url;
        } else {
            buttonUrl = settings.bbcgs_button_existing_link;
        }
        
        var iconHTML = elementor.helpers.renderIcon( view, settings.bbcgs_button_icon_selection_updated, { 'aria-hidden': true }, 'i' , 'object' ),
            migrated = elementor.helpers.isIconMigrated( settings, 'bbcgs_button_icon_selection_updated' );
        
        #>
        
        <div class="meafe-button-container">
            <a class="meafe-button {{ buttonSize }}" href="{{ buttonUrl }}" onclick="{{ buttonEvent }}">
                <div class="meafe-button-text-icon-wrapper">
                    <# if( 'yes' === settings.bbcgs_button_icon_switcher && 'before' == settings.bbcgs_button_icon_position ) {
                        if ( iconHTML && iconHTML.rendered && ( ! buttonIcon || migrated ) ) { #>
                            {{{ iconHTML.value }}}
                        <# } else { #>
                            <i class="{{ buttonIcon }}" aria-hidden="true"></i>
                        <# }
                    } #>
                    <span {{{ view.getRenderAttributeString('bbcgs_button_text') }}}>{{{ buttonText }}}</span>
                    <# if( 'yes' === settings.bbcgs_button_icon_switcher && 'after' == settings.bbcgs_button_icon_position ) {
                        if ( iconHTML && iconHTML.rendered && ( ! buttonIcon || migrated ) ) { #>
                            {{{ iconHTML.value }}}
                        <# } else { #>
                            <i class="{{ buttonIcon }}" aria-hidden="true"></i>
                        <# }
                    } #>
                </div>
            </a>
        </div>
        
        <?php
    }
}
