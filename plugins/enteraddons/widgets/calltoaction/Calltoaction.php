<?php
namespace Enteraddons\Widgets\Calltoaction;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enteraddons elementor Calltoaction widget.
 *
 * @since 1.0
 */
class Calltoaction extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-calltoaction';
	}

	public function get_title() {
		return esc_html__( 'Call To Action', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-call-to-action';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        // ----------------------------------------  Call to action content ------------------------------
        $this->start_controls_section(
            'enteraddons_calltoaction_layout_settings',
            [
                'label' => esc_html__( 'Layout', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'layout',
            [
                'label' => esc_html__( 'Layout', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1' => esc_html__( 'Layout 1', 'enteraddons' ),
                    '2' => esc_html__( 'Layout 2', 'enteraddons' )
                ]
            ]
        );
        $this->add_responsive_control(
            'direction',
            [
                'label' => esc_html__( 'Direction', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'condition' => [ 'layout' => '2' ],
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Row', 'enteraddons' ),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Column', 'enteraddons' ),
                        'icon' => 'eicon-justify-space-evenly-v',
                    ]
                ],
                'default' => 'row',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper.layout-2' => 'flex-direction: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->end_controls_section(); // End Team content

        // ----------------------------------------  Call to action content ------------------------------
        $this->start_controls_section(
            'enteraddons_calltoaction_content_settings',
            [
                'label' => esc_html__( 'Call To Action Content', 'enteraddons' ),
            ]
        );
       
        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Discover The Future'
            ]
        );
        $this->add_control(
            'tag',
            [
                'label' => esc_html__( 'Set HTML Tag', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'span' => 'span',
                    'p' => 'p',
                    'div' => 'div'
                ],
                'default' => 'p'
            ]
        );
        $this->add_control(
            'descriptions',
            [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis, ullam, ex recusandae eius excepturi asperiores laborum.'
            ]
        );
        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        $this->add_control(
            'link_label',
            [
                'label' => esc_html__( 'Link Label', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Get Now', 'enteraddons' )
            ]
        );

        $this->end_controls_section(); // End  content

        //------------------------------ CTA Wrapper Style -------------------
        $this->start_controls_section(
            'enteraddons_cta_wrapper_style', [
                'label' => esc_html__( 'Wrapper Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-call-to-action-wrapper',
            ]
        );
        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-call-to-action-wrapper',
            ]
        );
        $this->end_controls_section();

        //------------------------------ CTA Title Style ------------------------------
        $this->start_controls_section(
            'enteraddons_cta_title_style', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-title',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        //------------------------------ CTA Description Style ------------------------------
        $this->start_controls_section(
            'enteraddons_cta_description_style', [
                'label' => esc_html__( 'Description', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-desc' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-desc',
            ]
        );
        $this->add_responsive_control(
            'desc_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'desc_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'desc_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '570',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper .cta-desc' => 'max-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_section();

        //------------------------------ CTA Button Style ------------------------------
        $this->start_controls_section(
            'enteraddons_cta_button_style', [
                'label' => esc_html__( 'Button', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'btn_display',
            [
                'label' => esc_html__( 'Display', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'inline-block'  => esc_html__( 'Inline Block', 'enteraddons' ),
                    'block' => esc_html__( 'block', 'enteraddons' ),
                    'none' => esc_html__( 'None', 'enteraddons' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn' => 'display: {{VALUE}};',
                ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'before_icon',
            [
                'label' => esc_html__( 'Button Icon Before', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => '',
                ],
            ]
        );
        $this->add_control(
            'after_icon',
            [
                'label' => esc_html__( 'Button Icon After', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => '13',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        // start controls tabs
        
        $this->start_controls_tabs( 'tab_btn_style' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'tab_btn_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'btn_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-call-to-action-wrapper i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn',
            ]
        );
        $this->end_controls_tab();


        //  Controls tab For Hover
        $this->start_controls_tab(
            'tab_btn_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__( 'Hover Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'btn_icon_hover_color',
            [
                'label' => esc_html__( 'Hover Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_hover_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn:hover',
            ]
        );
        $this->add_responsive_control(
            'btn_border_hover_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_hover_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn:hover',
            ]
        );
        $this->add_control(
            'btn_hover_effect',
            [
                'label' => esc_html__( 'Hover Effect', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hover-effect-normal',
                'options' => [
                    'hover-effect-normal'   => esc_html__( 'Normal', 'enteraddons' ),
                    'hover-border-btm-effect'   => esc_html__( 'Border Bottom Effect', 'enteraddons' ),
                    'hover-saclup-effect'  => esc_html__( 'Saclup Effect', 'enteraddons' ),
                    'dual-bg-effect'  => esc_html__( 'Dual Background Effect', 'enteraddons' ),
                    'interactive-bg'  => esc_html__( 'Interactive Background', 'enteraddons' ),
                    'dual-icon-btn'   => esc_html__( 'Dual Icon Btn', 'enteraddons' )
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_hover_background',
                'label' => esc_html__( 'Hover Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .cta-button-wrapper .enteraddons-btn:hover',
            ]
        );
        $this->end_controls_tabs(); //  end controls section

        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Calltoaction_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
	
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
