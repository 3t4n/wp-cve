<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Text_Stroke;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Css_Filter;
use \Elementor\Core\Schemes\Typography;
use Elementor\Icons_Manager;
use \Elementor\Repeater;
use Elementor\Utils;

class Hero extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-hero';
	}
	
	public function get_title() {
		return 'Hero';
	}
	
	public function get_icon() {
		return 'borderless-icon-hero';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}

	public function get_keywords()
	{
        return [
			'hero',
			'title',
			'title hero',
			'borderless'
		];
    }

	public function get_style_depends() {
		return 
			[ 
				'borderless-elementor-style',
				'elementor-widget-hero' 
			];
	}

	public function get_custom_help_url()
	{
        return 'https://visualmodo.com/';
    }
	
	protected function _register_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_content',
			[
				'label' => esc_html__( 'Content', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'borderless_elementor_hero_title',
				[
					'label'			=> esc_html__( 'Title', 'borderless'),
					'type'			=> Controls_Manager::TEXT,
					'default'       => esc_html__( 'Enter Your Title', 'borderless' ),
					'label_block'	=> true,
					'dynamic'		=> [ 'active' => true ]
				]
			);

			$this->add_control(
				'borderless_elementor_hero_subtitle',
				[
					'label'			=> esc_html__( 'Subtitle', 'borderless'),
					'type'			=> Controls_Manager::TEXT,
					'default'       => esc_html__( 'Enter Your Subtitle', 'borderless' ),
					'label_block'	=> true,
					'dynamic'		=> [ 'active' => true ]
				]
			);

			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'borderless_elementor_tabs_hero_button_repeater' );

				$repeater->start_controls_tab( 'borderless_elementor_tab_hero_button_content', [ 'label' => __( 'Content', 'borderless' ) ] );

					$repeater->add_control(
						'borderless_elementor_hero_button_item_text',
						[
							'label'			=> esc_html__( 'Button Text', 'borderless'),
							'type'			=> Controls_Manager::TEXT,
							'dynamic'		=> [ 'active' => true ]
						]
					);

					$repeater->add_control(
						'borderless_elementor_hero_button_item_link',
						array(
							'label'       => esc_html__( 'Button Link', 'borderless' ),
							'type'        => Controls_Manager::URL,
							'default' => array( 'url' => '#' ),
							'dynamic' => array( 'active' => true ),
						)
					);

					$repeater->add_control(
						'borderless_elementor_hero_button_item_icon',
						[
							'label' => esc_html__( 'Icon', 'borderless' ),
							'type' => Controls_Manager::ICONS,
							'fa4compatibility' => 'icon',
							'skin' => 'inline',
							'label_block' => false,
						]
					);

					$repeater->add_control(
						'borderless_elementor_hero_button_item_icon_align',
						[
							'label' => esc_html__( 'Icon Position', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'left',
							'options' => [
								'left' => esc_html__( 'Before', 'borderless' ),
								'right' => esc_html__( 'After', 'borderless' ),
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_hero_button_item_icon_indent',
						[
							'label' => __( 'Icon Spacing', 'borderless' ),
							'type' => \Elementor\Controls_Manager::NUMBER,
							'min' => 0,
							'max' => 100,
							'step' => 1,
							'default' => 10,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}' => 'gap: {{VALUE}}px',
							],
						]
					);

				$repeater->end_controls_tab();

				$repeater->start_controls_tab( 'borderless_elementor_tab_hero_button_style', [ 'label' => __( 'Style', 'borderless' ) ] );

					$repeater->add_control(
						'borderless_elementor_hero_button_custom_style',
						[
							'label' => __( 'Custom', 'borderless' ),
							'type' => Controls_Manager::SWITCHER,
							'description' => __( 'Set custom style that will only affect this specific button.', 'borderless' ),
						]
					);

					$repeater->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name' => 'borderless_elementor_hero_button_typography_custom',
							'label' => __('Typography', 'borderless'),
							'selector' => '{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);
		
					$repeater->add_group_control(
						Group_Control_Text_Shadow::get_type(),
						[
							'name' => 'borderless_elementor_hero_button_text_shadow_custom',
							'selector' => '{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'borderless_elementor_hero_button_heading_normal_custom',
						[
							'label' => esc_html__( 'Normal', 'borderless' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);					

					$repeater->add_control(
						'borderless_elementor_hero_button_text_color_custom',
						[
							'label' => __( 'Text Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_hero_button_background_custom',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'borderless_elementor_hero_button_heading_hover_custom',
						[
							'label' => esc_html__( 'Hover', 'borderless' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'borderless_elementor_hero_button_text_color_hover_custom',
						[
							'label' => __( 'Hover Text Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}:hover' => 'color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_hero_button_background_hover_custom',
							'label' => __( 'Hover Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}:hover',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'borderless_elementor_hero_button_border_color_hover_custom',
						[
							'label' => __( 'Hover Border Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}:hover' => 'border-color: {{VALUE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'borderless_elementor_hero_button_border_custom',
							'label' => esc_html__( 'Border', 'borderless'),
							'selector' => '{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}',
							'separator' => 'before',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'borderless_elementor_hero_button_border_radius_custom',
						[
							'label' => esc_html__( 'Border Radius', 'borderless'),
							'type' => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'borderless_elementor_hero_button_box_shadow_custom',
							'selector' => '{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_hero_button_padding_custom',
						[
							'label' => esc_html__( 'Padding', 'borderless'),
							'type' => Controls_Manager::DIMENSIONS,
							'separator' => 'before',
							'size_units' => [ 'px', 'em', '%' ],
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_hero_button_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

				$repeater->end_controls_tab();

			$repeater->end_controls_tabs();

			$this->add_control(
				'borderless_elementor_hero_button_item_strings',
				[
					'type'        => Controls_Manager::REPEATER,
					'show_label'  => true,
					'fields'      =>  $repeater->get_controls(),
					'title_field' => '{{ borderless_elementor_hero_button_item_text }}',
					'default'     => [
						['borderless_elementor_hero_button_item_text' => esc_html__('Button', 'borderless')],
					],
				]
			);

		$this->end_controls_section();

		

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero/Settings - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_settings',
			[
				'label' => esc_html__( 'Settings', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'borderless_elementor_hero_show_title',
				[
					'label' => __( 'Show Title', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			$this->add_control(
				'borderless_elementor_hero_show_subtitle',
				[
					'label' => __( 'Show Subtitle', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			$this->add_control(
				'borderless_elementor_hero_show_buttons',
				[
					'label' => __( 'Show Buttons', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'default' => '',
				]
			);

			$this->add_control(
				'borderless_elementor_hero_title_html_tag',
				[
					'label' => esc_html__( 'Title HTML Tag', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
						'span' => 'span',
						'p' => 'p',
					],
					'default' => 'h1',
					'condition' => array(
						'borderless_elementor_hero_show_title' => 'true',
					),
				]
			);

			$this->add_control(
				'borderless_elementor_hero_subtitle_html_tag',
				[
					'label' => esc_html__( 'Subtitle HTML Tag', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
						'span' => 'span',
						'p' => 'p',
					],
					'default' => 'span',
					'condition' => array(
						'borderless_elementor_hero_show_subtitle' => 'true',
					),
				]
			);

			$this->add_control(
				'borderless_elementor_hero_animation',
				[
					'label' => esc_html__( 'Hero Animation', 'borderless' ),
					'type' => \Elementor\Controls_Manager::ANIMATION,
					'prefix_class' => 'animated ',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'borderless_elementor_hero_content_width_layout',
				[
					'label' => esc_html__( 'Content Width', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'boxed' => 'Boxed',
						'full' => 'Full Width',
					],
					'default' => 'full',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_hero_content_width',
				[
					'label' => esc_html__( 'Width', 'borderless' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 500,
							'max' => 1600,
						],
					],
					'default'	=> [
						'unit'	=> 'px',
						'size'	=> '1200'
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__container' => 'max-width: {{SIZE}}{{UNIT}};'
					],
					'condition' => array(
						'borderless_elementor_hero_content_width_layout' => 'boxed',
					),
				]
			);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero/Hero - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_style',
			[
				'label' => esc_html__( 'Hero', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_responsive_control(
				'borderless_elementor_hero_height',
				[
					'label' => esc_html__( 'Height', 'borderless'),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'vh' ],
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 9999,
						],
						'vh' => [
							'min' => 10,
							'max' => 100,
						],
					],
					'default'	=> [
						'unit'	=> 'vh',
						'size'	=> '100'
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__container-inner' => 'min-height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_hero_width',
				[
					'label' => esc_html__( 'Width', 'borderless'),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [
						'%' => [
							'min' => 20,
							'max' => 100,
						],
					],
					'default'	=> [
						'unit'	=> '%',
						'size'	=> '80'
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__content' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_hero_vertical_position',
				[
					'label' => __( 'Vertical Position', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => 'center',
					'options' => [
						'flex-start' => [
							'title' => __( 'Top', 'borderless' ),
							'icon' => 'eicon-v-align-top',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-v-align-middle',
						],
						'flex-end' => [
							'title' => __( 'Bottom', 'borderless' ),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__container-inner' => 'align-items: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_hero_horizontal_position',
				[
					'label' => __( 'Horizontal Position', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => 'center',
					'options' => [
						'flex-start' => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-h-align-center',
						],
						'flex-end' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__container-inner' => 'justify-content: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_hero_title_align',
				[
					'label' => __( 'Title Align', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'start' => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-text-align-center',
						],
						'end' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__title' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_hero_subtitle_align',
				[
					'label' => __( 'Subtitle Align', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'start' => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-text-align-center',
						],
						'end' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__subtitle' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_hero_button_align',
				[
					'label' => __( 'Button Align', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'flex-start' => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-text-align-center',
						],
						'flex-end' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__buttons' => 'justify-content: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_hero_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'separator' => 'before',
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero/Hero Background - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_background_style',
			[
				'label' => esc_html__( 'Hero Background', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->start_controls_tabs( 'borderless_elementor_tabs_hero_background_style' );

				$this->start_controls_tab( 'borderless_elementor_tab_normal_hero_background_style',
					[
						'label' => __( 'Normal', 'borderless' ),
					]
				);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_hero_background',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-hero',
							'fields_options' => [
								'background' => [
									'default' => 'classic',
								],
								'color' => [
									'default' => '#000000',
								],
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_tab_hover_hero_background_style',
					[
						'label' => __( 'Hover', 'borderless' ),
					]
				);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_hero_background_hover',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-hero:hover',
						]
					);

					$this->add_control(
						'borderless_elementor_hero_background_hover_transition',
						[
							'label' => __( 'Transition Duration', 'borderless' ),
							'type' => Controls_Manager::SLIDER,
							'separator' => 'before',
							'range' => [
								'px' => [
									'max' => 3,
									'step' => 0.1,
								],
							],
							'default' => [
								'size' => 0.3,
							],
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero:hover' => 'transition-duration: {{SIZE}}s',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero/Hero Background Overlay - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_background_overlay_style',
			[
				'label' => esc_html__( 'Hero Background Overlay', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->start_controls_tabs( 'borderless_elementor_tabs_hero_background_overlay_style' );

				$this->start_controls_tab( 'borderless_elementor_tab_normal_hero_background_overlay_style',
					[
						'label' => __( 'Normal', 'borderless' ),
					]
				);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_hero_background_overlay',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-hero__overlay',
						]
					);

					$this->add_control(
						'borderless_elementor_hero_background_overlay_opacity',
						[
							'label' => __( 'Opacity', 'borderless' ),
							'type' => Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max' => 1,
									'min' => 0.10,
									'step' => 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero__overlay' => 'opacity: {{SIZE}};',
							],
						]
					);
				
					$this->add_group_control(
						Group_Control_Css_Filter::get_type(),
						[
							'name' => 'borderless_elementor_hero_background_overlay_css_filter',
							'selector' => '{{WRAPPER}} .borderless-elementor-hero__overlay',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_tab_hover_hero_background_overlay_style',
					[
						'label' => __( 'Hover', 'borderless' ),
					]
				);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_hero_background_overlay_hover',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-hero:hover .borderless-elementor-hero__overlay',
						]
					);

					$this->add_control(
						'borderless_elementor_hero_background_overlay_hover_opacity',
						[
							'label' => __( 'Opacity', 'borderless' ),
							'type' => Controls_Manager::SLIDER,
							'range' => [
								'px' => [
									'max' => 1,
									'min' => 0.10,
									'step' => 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero:hover .borderless-elementor-hero__overlay' => 'opacity: {{SIZE}};',
							],
						]
					);
				
					$this->add_group_control(
						Group_Control_Css_Filter::get_type(),
						[
							'name' => 'borderless_elementor_hero_background_overlay_hover_css_filter',
							'selector' => '{{WRAPPER}} .borderless-elementor-hero:hover .borderless-elementor-hero__overlay',
						]
					);

					$this->add_control(
						'borderless_elementor_hero_background_overlay_hover_transition',
						[
							'label' => __( 'Transition Duration', 'borderless' ),
							'type' => Controls_Manager::SLIDER,
							'separator' => 'before',
							'range' => [
								'px' => [
									'max' => 3,
									'step' => 0.1,
								],
							],
							'default' => [
								'size' => 0.3,
							],
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero:hover .borderless-elementor-hero__overlay' => 'transition-duration: {{SIZE}}s',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero/Title - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_title_style',
			[
				'label' => esc_html__( 'Title', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_responsive_control(
				'borderless_elementor_hero_title_color',
				[
					'label' => __( 'Text Color', 'borderless' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_hero_title_typography',
					'label' => __('Typography', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__title',
				]
			);

			$this->add_group_control(
				Group_Control_Text_Stroke::get_type(),
				[
					'name' => 'borderless_elementor_hero_title_text_stroke',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__title',
				]
			);
	
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_hero_title_text_shadow',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__title',
				]
			);
	
			$this->add_control(
				'borderless_elementor_hero_title_blend_mode',
				[
					'label' => esc_html__( 'Blend Mode', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'' => esc_html__( 'Normal', 'borderless' ),
						'multiply' => 'Multiply',
						'screen' => 'Screen',
						'overlay' => 'Overlay',
						'darken' => 'Darken',
						'lighten' => 'Lighten',
						'color-dodge' => 'Color Dodge',
						'saturation' => 'Saturation',
						'color' => 'Color',
						'difference' => 'Difference',
						'exclusion' => 'Exclusion',
						'hue' => 'Hue',
						'luminosity' => 'Luminosity',
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__title' => 'mix-blend-mode: {{VALUE}}',
					],
					'separator' => 'none',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_hero_title_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'separator' => 'before',
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_hero_title_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_hero_title_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__title',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_hero_title_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__title' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_hero_title_box_shadow',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__title',
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero/Subtitle - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_subtitle_style',
			[
				'label' => esc_html__( 'Subtitle', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_responsive_control(
				'borderless_elementor_hero_subtitle_color',
				[
					'label' => __( 'Text Color', 'borderless' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__subtitle' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_hero_subtitle_typography',
					'label' => __('Typography', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__subtitle',
				]
			);

			$this->add_group_control(
				Group_Control_Text_Stroke::get_type(),
				[
					'name' => 'borderless_elementor_hero_subtitle_text_stroke',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__subtitle',
				]
			);
	
			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_hero_subtitle_text_shadow',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__subtitle',
				]
			);
	
			$this->add_control(
				'borderless_elementor_hero_subtitle_blend_mode',
				[
					'label' => esc_html__( 'Blend Mode', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'' => esc_html__( 'Normal', 'borderless' ),
						'multiply' => 'Multiply',
						'screen' => 'Screen',
						'overlay' => 'Overlay',
						'darken' => 'Darken',
						'lighten' => 'Lighten',
						'color-dodge' => 'Color Dodge',
						'saturation' => 'Saturation',
						'color' => 'Color',
						'difference' => 'Difference',
						'exclusion' => 'Exclusion',
						'hue' => 'Hue',
						'luminosity' => 'Luminosity',
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__subtitle' => 'mix-blend-mode: {{VALUE}}',
					],
					'separator' => 'none',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_hero_subtitle_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'separator' => 'before',
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_hero_subtitle_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_hero_subtitle_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__subtitle',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_hero_subtitle_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__subtitle' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_hero_subtitle_box_shadow',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__subtitle',
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero/Buttons - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_buttons_style',
			[
				'label' => esc_html__( 'Buttons', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_responsive_control(
				'borderless_elementor_hero_buttons_direction',
				[
					'label' => __( 'Direction', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => 'row',
					'options' => [
						'row' => [
							'title' => __( 'Row', 'borderless' ),
							'icon' => 'eicon-h-align-center',
						],
						'column' => [
							'title' => __( 'Column', 'borderless' ),
							'icon' => 'eicon-v-align-middle',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__buttons' => 'flex-direction: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_buttons_gap',
				[
					'label' => __( 'Gap', 'borderless' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 99999,
					'step' => 1,
					'default' => 50,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__buttons' => 'gap: {{VALUE}}px',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_buttons_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'separator' => 'before',
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__buttons' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_buttons_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__buttons' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_buttons_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'separator' => 'before',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__buttons',
				]
			);

			$this->add_control(
				'borderless_elementor_hero_buttons_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__buttons' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Hero/Button - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_hero_button_style',
			[
				'label' => esc_html__( 'Button', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_hero_button_typography',
					'label' => __('Typography', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__button',
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_hero_button_text_shadow',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__button',
				]
			);

			$this->start_controls_tabs( 'borderless_elementor_tabs_hero_button_style' );

				$this->start_controls_tab( 'borderless_elementor_tab_normal_hero_button_style',
					[
						'label' => __( 'Normal', 'borderless' ),
					]
				);

					$this->add_control(
						'borderless_elementor_hero_button_text_color',
						[
							'label' => __( 'Text Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero__button' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_hero_button_background',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-hero__button',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_tab_hover_hero_button_style',
					[
						'label' => __( 'Hover', 'borderless' ),
					]
				);

					$this->add_control(
						'borderless_elementor_hero_button_text_color_hover',
						[
							'label' => __( 'Text Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero__button:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_hero_button_background_hover',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-hero__button:hover',
						]
					);

					$this->add_control(
						'borderless_elementor_hero_button_border_color_hover',
						[
							'label' => __( 'Border Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-hero__button:hover' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_hero_button_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__button',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'borderless_elementor_hero_button_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_hero_button_box_shadow',
					'selector' => '{{WRAPPER}} .borderless-elementor-hero__button',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_hero_button_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'separator' => 'before',
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-hero__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

	}
	
	protected function render() {

		$settings = $this->get_settings_for_display();	 

		?>

		<div class="borderless-elementor-hero-widget <?php echo esc_attr( $settings['borderless_elementor_hero_animation'] ); ?>">
			<div class="borderless-elementor-hero">
				<div class="borderless-elementor-hero__overlay"></div>
				<div class="borderless-elementor-hero__container">
					<div class="borderless-elementor-hero__container-inner">
						<div class="borderless-elementor-hero__content">

							<?php if ( $settings['borderless_elementor_hero_show_title'] ) { ?>
								<<?php echo wp_kses( ( $settings['borderless_elementor_hero_title_html_tag'] ), true ); ?> class="borderless-elementor-hero__title">
									<?php echo wp_kses( ( $settings['borderless_elementor_hero_title'] ), true ); ?>
								</<?php echo wp_kses( ( $settings['borderless_elementor_hero_title_html_tag'] ), true ); ?>>
							<?php } ?>

							<?php if ( $settings['borderless_elementor_hero_show_subtitle'] ) { ?>
								<<?php echo wp_kses( ( $settings['borderless_elementor_hero_subtitle_html_tag'] ), true ); ?> class="borderless-elementor-hero__subtitle">
									<?php echo wp_kses( ( $settings['borderless_elementor_hero_subtitle'] ), true ); ?>
								</<?php echo wp_kses( ( $settings['borderless_elementor_hero_subtitle_html_tag'] ), true ); ?>> 
							<?php } ?>

							<?php if ( $settings['borderless_elementor_hero_show_buttons'] ) { ?>
								<div class="borderless-elementor-hero__buttons">
									<?php foreach (  $settings['borderless_elementor_hero_button_item_strings'] as $hero_string ) { ?>
										<a class="borderless-elementor-hero__button elementor-repeater-item-<?php echo $hero_string['_id']; ?>">

											<?php if ( $hero_string['borderless_elementor_hero_button_item_icon_align'] == 'left' ) { ?>
												<?php Icons_Manager::render_icon( $hero_string['borderless_elementor_hero_button_item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
											<?php } ?>

											<?php echo wp_kses( ( $hero_string['borderless_elementor_hero_button_item_text'] ), true ); ?>

											<?php if ( $hero_string['borderless_elementor_hero_button_item_icon_align'] == 'right' ) { ?>
												<?php Icons_Manager::render_icon( $hero_string['borderless_elementor_hero_button_item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
											<?php } ?>
										</a>
									<?php } ?>
								</div>
							<?php } ?>

						</div>
					</div>
				</div>
			</div>
		</div>

		<?php

	}
	
	protected function _content_template() {

    }
	
	
}