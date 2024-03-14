<?php
namespace Enteraddons\Widgets\Advanced_Animation_Title;

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
 * Enteraddons elementor Advanced Animation Title widget.
 *
 * @since 1.0
 */

class Advanced_Animation_Title extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-advanced-animation-title';
	}

	public function get_title() {
		return esc_html__( 'Animation Title', 'enteraddons-pro' );
	}

	public function get_icon() {
		return 'entera entera-title-reveal-animation';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Advanced Animation Title content ------------------------------
        $this->start_controls_section(
            'enteraddons_advanced_animation_title_settings',
            [
                'label' => esc_html__( 'Advanced Animation Title', 'enteraddons-pro' ),
            ]
        );
        $this->add_control(
            'animation_type',
            [
                'label' => esc_html__( 'Animation Type', 'enteraddons-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1'  => esc_html__( 'Style 1', 'enteraddons-pro' ),
                    '2'  => esc_html__( 'Style 2', 'enteraddons-pro' ),
                    '3'  => esc_html__( 'Style 3', 'enteraddons-pro' ),
                    '4'  => esc_html__( 'Style 4', 'enteraddons-pro' ),
                    '5'  => esc_html__( 'Style 5', 'enteraddons-pro' ),
                    '6'  => esc_html__( 'Style 6', 'enteraddons-pro' ),
                    '7'  => esc_html__( 'Style 7', 'enteraddons-pro' ),
                    'rainbow'  => esc_html__( 'Style 8', 'enteraddons-pro' ),
                ],
            ]
        );
        $this->add_control(
			'text_direction',
			[
				'label' => esc_html__( 'Title Direction', 'enteraddons-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Row', 'enteraddons-pro' ),
					'aat-direction'  => esc_html__( 'Column', 'enteraddons-pro' ),

				],
			]
		);
        $this->add_control(
			'title_gap',
			[
				'label' => esc_html__( 'Title Gap', 'enteraddons-pro' ),
                'condition' => [ 'text_direction' => ['aat-direction'] ],
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .ea-aat-title' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
            'tag',
            [
                'label' => esc_html__( 'Set Heading Tag', 'enteraddons-pro' ),
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
                'default' => 'h2'
            ]
        );
        $this->add_control(
            'first_text',
            [
                'label' => esc_html__( 'Before Text', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'EnterAddons '
            ]
        );
        $this->add_control(
            'animation_text',
            [
                'label' => esc_html__( 'Animation Text', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__(' Ultimate Template Builder ', 'enteraddons-pro'), 
            ]
        );
        $this->add_control(
            'second_text',
            [
                'label' => esc_html__( 'After Text', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => ' for Elementor'
            ]
        );
        $this->add_control(
			'show_background_text',
			[
				'label' => esc_html__( 'Show Background Text', 'enteraddons-pro' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'enteraddons-pro' ),
				'label_off' => esc_html__( 'Hide', 'enteraddons-pro' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
            'background_text',
            [
                'label' => esc_html__( 'Background Text', 'enteraddons-pro' ),
                'condition'=>['show_background_text'=>'yes'],
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'ThemeLooks'
            ]
        );
        $this->end_controls_section(); // End Advanced Animation Title content

        /**
         * Style Tab
         * ------------------------------Advanced Animation Title Wrapper Style ------------------------------
         *
         */
         $this->start_controls_section(
            'enteraddons_animation_title_wrapper_style_settings', [
                'label' => esc_html__( 'Wrapper Style', 'enteraddons-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        ); 
        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'wrapper_border',
                'label'     => esc_html__( 'Border', 'enteraddons-pro' ),
                'selector'  => '{{WRAPPER}} .ea-aat-wrapper',
            ]
        );
        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons-pro' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-aat-wrapper',
            ]
        );
        $this->end_controls_section();

        /**
        * Style Tab
        * ------------------------------ Normal Title Style ------------------------------
        *
        */
        $this->start_controls_section(
            'enteraddons__title_style_settings', [
                'label' => esc_html__( 'Text Style', 'enteraddons-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'title_stroke',
				'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title',
			]
		);
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'label' => esc_html__( 'Border', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title',
            ]
        );
        $this->add_responsive_control(
            'title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title',
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'title_background',
				'label' => esc_html__( 'Background', 'enteraddons-pro' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title',
			]
		);
        $this->add_control(
            'title_alignment',
            [
                'label' => esc_html__( 'Title Alignment', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons-pro' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons-pro' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons-pro' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition' => [ 'text_direction' => [''] ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_control(
            'title_vertical_alignment',
            [
                'label' => esc_html__( 'Title Alignment', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Start', 'enteraddons-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons-pro' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'End', 'enteraddons-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'condition' => [ 'text_direction' => ['aat-direction'] ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title' => 'align-items: {{VALUE}} !important',
                ],
            ]
        );
        $this->end_controls_section();

        /**
        * Style Tab
        * ------------------------------ Animation Title Style ------------------------------
        *
        */
        $this->start_controls_section(
            'enteraddons_animation_title_style_settings', [
                'label' => esc_html__( 'Animation Text Style', 'enteraddons-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'animation_title_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text span' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'animation_title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'animation_title_stroke',
				'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text',
			]
		);
        $this->add_responsive_control(
            'animation_title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'animation_title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'animation_title_border',
                'label' => esc_html__( 'Border', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text',
            ]
        );
        $this->add_responsive_control(
            'animation_title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'animation_title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text',
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'animation_title_background',
				'label' => esc_html__( 'Background', 'enteraddons-pro' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-text',
			]
		);
        $this->end_controls_section();

        /**
        * Style Tab
        * ------------------------------ background Text  Style ------------------------------
        *
        */
        $this->start_controls_section(
            'enteraddons_background_text_style_settings', [
                'label' => esc_html__( 'Background Text Style', 'enteraddons-pro' ),
                'condition'=>['show_background_text'=>'yes'],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'background_text_offset_y',
            [
                'label'      => __('Offset Y', 'enteraddons-pro'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before'=> 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_text_offset_x',
            [
                'label'      => __('Offset X', 'enteraddons-pro'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                   
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
			'background_text_opacity',
			[
				'label' => esc_html__( 'Opacity', 'enteraddons-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => .1,
					],
				],
				'default' => [
					'unit' => 'px',
                    'default'=> 0.3,
				],
				'selectors' => [
					'{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before' => 'opacity: {{SIZE}}',
				],
			]
		); 
        $this->add_control(
            'background_text_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'background_text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'background_text_stroke',
				'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before',
			]
		);
        $this->add_responsive_control(
            'background_text_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'background_text_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'background_text_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons-pro' ),
                'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background_text_background',
				'label' => esc_html__( 'Background', 'enteraddons-pro' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-aat-wrapper .ea-aat-title::before',
			]
		);
        $this->end_controls_section();
	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        // template render
        $obj = new Advanced_Animation_Title_Template();
        $obj::setDisplaySettings( $settings );
        $obj::setDisplayID( $id );
        $obj->renderTemplate();

    }

    public function get_script_depends() {
        return [ 'enteraddons-main','anime'];
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }

}
