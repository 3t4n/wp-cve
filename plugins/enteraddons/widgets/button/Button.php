<?php
namespace Enteraddons\Widgets\Button;

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
 * Enteraddons elementor widget.
 *
 * @since 1.0
 */

class Button extends Widget_Base {

	public function get_name() {
		return 'enteraddons-button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-button';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}
    
	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Button ------------------------------
        $this->start_controls_section(
            'enteraddons_button_content',
            [
                'label' => esc_html__( 'Button Content', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Read More', 'enteraddons' )
            ]
        );
        $this->add_control(
            'small_text',
            [
                'label' => esc_html__( 'Small Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );
        $this->add_control(
            'small_text_direction',
            [
                'label' => esc_html__( 'Small Text Direction', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'column-reverse',
                'options' => [
                    'column' => esc_html__( 'Top', 'enteraddons' ),
                    'row-reverse' => esc_html__( 'Right', 'enteraddons' ),
                    'row'  => esc_html__( 'Left', 'enteraddons' ),
                    'column-reverse'  => esc_html__( 'Bottom', 'enteraddons' ),
                ],
                'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link .entera-btn-text-wrap' => 'flex-direction: {{VALUE}};',
                    ],
                
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
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        
        $this->end_controls_section(); // End content

        // ----------------------------------------  Button ------------------------------
        $this->start_controls_section(
            'enteraddons_button_icon_content',
            [
                'label' => esc_html__( 'Button Icon', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'icon_direction',
            [
                'label' => esc_html__( 'Icon Direction', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'row-reverse',
                'condition' => [ 'show_icon' => 'yes' ],
                'options' => [
                    'column' => esc_html__( 'Top', 'enteraddons' ),
                    'row-reverse' => esc_html__( 'Right', 'enteraddons' ),
                    'inherit'  => esc_html__( 'Left', 'enteraddons' ),
                    'column-reverse'  => esc_html__( 'Bottom', 'enteraddons' ),
                ],
                'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link' => 'flex-direction: {{VALUE}};',
                    ],
                
            ]
        );
        $this->add_control(
            'icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'condition' => [ 'show_icon' => 'yes' ],
                'options' => [
                    'right' => esc_html__( 'Right', 'enteraddons' ),
                    'left'  => esc_html__( 'Left', 'enteraddons' ),
                ]
            ]
        );
        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__( 'Normal Icon/Before Text Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'show_icon' => 'yes' ],
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ]
            ]
        );
        $this->add_control(
            'button_hover_icon',
            [
                'label' => esc_html__( 'Hover Icon/After Text Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'show_icon' => 'yes' ],
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ]
            ]
        );
        $this->end_controls_section(); // End Icon content

        /**
         * Style Tab
         * ------------------------------ Button Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_button_content_style_settings', [
                'label' => esc_html__( 'Button Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btn_width',
            [
                'label' => esc_html__( 'Button Width', 'enteraddons' ),
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
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_height',
            [
                'label' => esc_html__( 'Button Height', 'enteraddons' ),
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
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_alignment',
            [
                'label' => esc_html__( 'Button Alignment', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-anchor-btn-wrap' => 'text-align:{{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_text_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'enteraddons' ),
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
                    ]
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link' => 'justify-content:{{VALUE}};',
                    ],
            ]
        );
        $this->start_controls_tabs( 'tab_button' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

            $this->add_responsive_control(
                'item_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link',
                ]
            );
            $this->add_responsive_control(
                'item_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link',
                ]
            );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'item_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_hover_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link:hover',
                ]
            );
            $this->add_responsive_control(
                'item_hover_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_hover_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link:hover',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_hover_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-anchor-btn-wrap .enteraddons-anchor-link:hover',
                ]
            );


        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Button Text Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_button_text_content_style_settings', [
                'label' => esc_html__( 'Button Text Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-anchor-link .btn-min-text',
            ]
        );

        $this->start_controls_tabs( 'tab_text_button' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'item_text_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
            $this->add_control(
                'text_color',
                [
                    'label' => esc_html__( 'Text Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-link .btn-min-text' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_responsive_control(
                'text_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-link .btn-min-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'text_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-link .btn-min-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'text_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'hover_text_color',
            [
                'label' => esc_html__( 'Hover Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link:hover .btn-min-text' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Button Small Text Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_button_small_text_content_style_settings', [
                'label' => esc_html__( 'Button Small Text Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'small_btn_text_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ]
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-btn-wrap .entera-btn-text-wrap' => 'align-items:{{VALUE}};',
                ],
                'condition' => [ 'small_text_direction' => ['column-reverse', 'column'] ]
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'small_text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-anchor-link .btn-small-text',
            ]
        );

        $this->start_controls_tabs( 'tab_small_text_button' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'small_text_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
            $this->add_control(
                'small_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-link .btn-small-text' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_responsive_control(
                'small_text_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-link .btn-small-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'small_text_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-anchor-link .btn-small-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'small_text_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'hover_small_text_color',
            [
                'label' => esc_html__( 'Hover Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link:hover .btn-small-text' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section(); 
        /**
         * Style Tab
         * ------------------------------ Icon Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_btn_icon_settings', [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_btn_icon' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'icon_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .btn-icons .btn--normal-icon i' => 'color: {{VALUE}}',
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
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link .btn-icons i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .enteraddons-anchor-link .btn-icons img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_width',
            [
                'label' => esc_html__( 'Icon Container Width', 'enteraddons' ),
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
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link .btn-icons' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_height',
            [
                'label' => esc_html__( 'Icon Container Height', 'enteraddons' ),
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
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link .btn-icons' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link .btn-icons' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link .btn-icons' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-anchor-link .btn-icons',
            ]
        );
        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link .btn-icons' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-anchor-link .btn-icons',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-anchor-link .btn-icons',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'icon_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-anchor-link:hover .btn-icons i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-anchor-link:hover .btn-icons',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Hover Effect Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_btn_hover_effect_settings', [
                'label' => esc_html__( 'Hover Effect', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                'name' => 'btn_hover_effect_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .dual-bg-effect:before,{{WRAPPER}} .dual-bg-effect:after,{{WRAPPER}} .interactive-bg:after,.hover-border-btm-effect:after',
                'condition' => [ 'btn_hover_effect' => ['interactive-bg', 'dual-bg-effect','hover-border-btm-effect'] ]
            ]
        );
        $this->end_controls_section();
        

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Template render
        $obj = new \Enteraddons\Widgets\Button\Button_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
