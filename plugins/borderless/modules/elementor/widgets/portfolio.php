<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Schemes\Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Css_Filter;
use \Elementor\Core\Schemes\Color;
use \Elementor\Repeater;
use Elementor\Utils;

class Portfolio extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-portfolio';
	}
	
	public function get_title() {
		return esc_html__('Portfolio', 'borderless');
	}
	
	public function get_icon() {
		return 'borderless-icon-portfolio';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}
	
	public function get_keywords()
	{
		return [
			'portfolio',
			'portfolio gallery',
			'borderless portfolio',
			'borderless portfolio gallery',
			'borderless'
		];
	}
	
	public function get_style_depends() {
		return [ 'elementor-widget-portfolio' ];
	}
	
	public function get_script_depends() {
		return [ 'borderless-elementor-isotope-script' ];
	}
	
	protected function _register_controls() {
		
		/*-----------------------------------------------------------------------------------*/
		/*  *.  Portfolio/Source - Content
		/*-----------------------------------------------------------------------------------*/
		
		$this->start_controls_section(
			'borderless_elementor_section_portfolio',
			[
			'label' => esc_html__( 'Content Source', 'borderless' ),
			'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
			$this->add_control(
			'borderless_elementor_portfolio_content_source',
			[
				'label' => __( 'Content Source', 'borderless' ),
				'description' => __( 'Choose Query to Use Post Types or Static to Create Each Item.', 'borderless' ), 
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static'  => __( 'Static', 'borderless' ),
					'query' => __( 'Query', 'borderless' ),
				],
				]
			);
			
		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Portfolio/Items - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_portfolio_items',
			[
			'label' => esc_html__( 'Items', 'borderless' ),
			'tab' => Controls_Manager::TAB_CONTENT,
			'condition' => array(
				'borderless_elementor_portfolio_content_source' => 'static',
			),
			]
		);

			$repeater = new Repeater();

			$repeater->add_control(
				'borderless_elementor_portfolio_item_title',
				[
					'label'			=> esc_html__( 'Title', 'borderless'),
					'type'			=> Controls_Manager::TEXT,
					'label_block'	=> true,
					'dynamic'		=> [ 'active' => true ]
				]
			);

			$repeater->add_control(
				'borderless_elementor_portfolio_item_description',
				[
					'label'			=> esc_html__( 'Description', 'borderless'),
					'type'			=> Controls_Manager::TEXTAREA,
					'dynamic'		=> [ 'active' => true ]
				]
			);

			$repeater->add_control(
				'borderless_elementor_portfolio_item_image',
				[
					'label' => __( 'Image', 'borderless' ),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'dynamic'		=> [ 'active' => true ],
					'separator' => 'before',
				]
			);

			$repeater->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'borderless_elementor_portfolio_item_image',
					'default' => 'large',
					'separator' => 'none',
				]
			);

			$repeater->add_control(
				'borderless_elementor_portfolio_item_image_link',
				[
					'label' => __( 'Image Link', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'static',
					'options' => [
						'none'  => __( 'None', 'borderless' ),
						'lightbox'  => __( 'Lightbox', 'borderless' ),
						'external' => __( 'External', 'borderless' ),
					],
				]
			);

			$repeater->add_control(
				'borderless_elementor_portfolio_item_button_text',
				[
					'label'			=> esc_html__( 'Button Text', 'borderless'),
					'type'			=> Controls_Manager::TEXT,
					'default' => esc_html__( 'View More', 'borderless' ),
					'dynamic'		=> [ 'active' => true ],
					'separator' => 'before',
				]
			);
	
			$repeater->add_control(
				'borderless_elementor_portfolio_item_button_link',
				array(
					'label'       => esc_html__( 'Button Link', 'borderless' ),
					'type'        => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => array(
						'url' => '',
					),
					'dynamic' => array( 'active' => true ),
				)
			);

			$repeater->add_control(
				'borderless_elementor_portfolio_item_filter',
				[
					'label'			=> esc_html__( 'Filter', 'borderless'),
					'description'	=> esc_html__( 'Enter Filter items separated by commas.', 'borderless'),
					'type'			=> Controls_Manager::TEXTAREA,
					'rows'          => 2,
					'separator'     => 'before',
				]
			);

			$repeater->add_control(
				'borderless_elementor_portfolio_item_sort',
				[
					'label'			=> esc_html__( 'Sort', 'borderless'),
					'description'	=> esc_html__( 'Enter Sort items separated by commas.', 'borderless'),
					'type'			=> Controls_Manager::TEXTAREA,
					'rows'          => 2,
				]
			);

			$this->add_control(
				'borderless_elementor_portfolio_item_strings',
				[
					'type'        => Controls_Manager::REPEATER,
					'show_label'  => true,
					'fields'      =>  $repeater->get_controls(),
					'title_field' => '{{ borderless_elementor_portfolio_item_title }}',
					'default'     => [
						[
							'borderless_elementor_portfolio_item_title',
						],
						[
							'borderless_elementor_portfolio_item_title',
						],
						[
							'borderless_elementor_portfolio_item_title',
						],
						[
							'borderless_elementor_portfolio_item_title',
						],
						[
							'borderless_elementor_portfolio_item_title',
						],
						[
							'borderless_elementor_portfolio_item_title',
						],
					],
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Portfolio/Data Source - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_portfolio_data_source',
			[
			'label' => esc_html__( 'Data Source', 'borderless' ),
			'tab' => Controls_Manager::TAB_CONTENT,
			'condition' => array(
				'borderless_elementor_portfolio_content_source' => 'query',
			),
			]
		);

		$this->end_controls_section();

		
		/*-----------------------------------------------------------------------------------*/
		/*  *.  Portfolio/Settings - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_portfolio_settings',
			[
			'label' => esc_html__( 'Settings', 'borderless' ),
			'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'borderless_elementor_portfolio_item_columns',
				[
					'label' => __( 'Columns', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'borderless-cell-4@md',
					'options' => [
						'borderless-cell-12@md'  => __( '1 Column', 'borderless' ),
						'borderless-cell-6@md'  => __( '2 Columns', 'borderless' ),
						'borderless-cell-4@md'  => __( '3 Columns', 'borderless' ),
						'borderless-cell-3@md'  => __( '4 Columns', 'borderless' ),
						'borderless-cell-2@md'  => __( '6 Columns', 'borderless' ),
					],
				]
			);

			$this->add_control(
				'borderless_elementor_portfolio_default_filter_label',
				[
					'label'			=> esc_html__( 'Default Filter Label', 'borderless'),
					'type'			=> Controls_Manager::TEXT,
					'default' => 'All',
					'dynamic'		=> [ 'active' => true ]
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Portfolio/Item - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_item_style',
			[
				'label' => esc_html__( 'Portfolio Item', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
			/*
			$this->add_control(
				'borderless_elementor_portfolio_item_layout',
				[
					'label' => __( 'Layout', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '1',
					'options' => [
						'1'  => __( 'Masonry', 'borderless' ),
						'2'  => __( 'Masonry Horizontal', 'borderless' ),
						'3'  => __( 'Fit Columns', 'borderless' ),
						'4'  => __( 'Fit Rows', 'borderless' ),
						'5'  => __( 'Cells By Column', 'borderless' ),
						'6'  => __( 'Cells By Row', 'borderless' ),
						'7'  => __( 'Horizontal', 'borderless' ),
						'8'  => __( 'Vertical', 'borderless' ),
						'9'  => __( 'Packery', 'borderless' ),
					],
				]
			);
			*/
			$this->add_responsive_control(
				'borderless_elementor_portfolio_item_gap',
				[
					'label' => __( 'Gap', 'borderless' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 1000,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 10,
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-inner' => 'margin: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .borderless-elementor-portfolio-items' => 'margin: -{{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_portfolio_item_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_portfolio_item_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-inner',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_portfolio_item_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_portfolio_item_border_box_shadow',
					'exclude' => [
						'box_shadow_position',
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item',
				]
			);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Portfolio/Item Content - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_item_content_style',
			[
				'label' => esc_html__( 'Portfolio Item Content', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_control(
				'borderless_elementor_item_content_container',
				[
					'label' => __( 'Container', 'borderless' ),
					'type' => \Elementor\Controls_Manager::HEADING,
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'borderless_elementor_item_content_background',
					'label' => __( 'Background', 'borderless' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-content',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_item_content_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-content',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_item_content_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-content' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_item_content_box_shadow',
					'exclude' => [
						'box_shadow_position',
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-content',
				]
			);

			$this->add_control(
				'borderless_elementor_item_content_image',
				[
					'label' => __( 'Image', 'borderless' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Css_Filter::get_type(),
				[
					'name' => 'borderless_elementor_item_content_image_css_filters',
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-inner img',
				]
			);

			$this->add_control(
				'borderless_elementor_item_content_image_opacity',
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
						'{{WRAPPER}} .borderless-elementor-portfolio-item-inner img' => 'opacity: {{SIZE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_item_content_image_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-inner img',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_item_content_image_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-inner img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_item_content_image_box_shadow',
					'exclude' => [
						'box_shadow_position',
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-inner img',
				]
			);

			$this->add_control(
				'borderless_elementor_item_content_title',
				[
					'label' => __( 'Title', 'borderless' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_title_align',
				[
					'label' => __( 'Alignment', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'prefix_class' => 'e-grid-align-',
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-title' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_item_content_title_color',
				[
					'label' => __( 'Color', 'borderless' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_item_content_title_typography',
					'label' => __('Typography', 'borderless'),
					'scheme' => Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-title',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_title_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_title_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_item_content_description',
				[
					'label' => __( 'Description', 'borderless' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_description_align',
				[
					'label' => __( 'Alignment', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'prefix_class' => 'e-grid-align-',
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-description' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_item_content_description_color',
				[
					'label' => __( 'Color', 'borderless' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-description' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_item_content_description_typography',
					'label' => __('Typography', 'borderless'),
					'scheme' => Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-description',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_description_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_description_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_item_content_button',
				[
					'label' => __( 'Button', 'borderless' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_button_alignment',
				[
					'label' => __( 'Alignment', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'prefix_class' => 'e-grid-align-',
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-button-container' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_button_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_item_content_button_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-item-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'borderless_elementor_item_content_button_tabs' );

				$this->start_controls_tab( 'borderless_elementor_item_content_button_tab_normal',
					[
						'label' => __( 'Normal', 'borderless' ),
					]
				);

					$this->add_control(
						'borderless_elementor_item_content_button_color',
						[
							'label' => __( 'Text Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-item-button' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_item_content_button_background',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-button',
						]
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name' => 'borderless_elementor_item_content_button_typography',
							'label' => __('Typography', 'borderless'),
							'scheme' => Typography::TYPOGRAPHY_1,
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-button',
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'borderless_elementor_item_content_button_border',
							'label' => esc_html__( 'Border', 'borderless'),
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-button',
						]
					);
				
					$this->add_responsive_control(
						'borderless_elementor_item_content_button_radius',
						[
							'label' => esc_html__( 'Border Radius', 'borderless'),
							'type' => Controls_Manager::DIMENSIONS,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-item-button' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
							],
						]
					);
		
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'borderless_elementor_item_content_button_box_shadow',
							'exclude' => [
								'box_shadow_position',
							],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-button',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_item_content_button_tab_hover',
					[
						'label' => __( 'Hover', 'borderless' ),
					]
				);

					$this->add_control(
						'borderless_elementor_item_content_button_color_hover',
						[
							'label' => __( 'Text Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-item-button:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_item_content_button_background_hover',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-button:hover',
						]
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name' => 'borderless_elementor_item_content_button_typography_hover',
							'label' => __('Typography', 'borderless'),
							'scheme' => Typography::TYPOGRAPHY_1,
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-button:hover',
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'borderless_elementor_item_content_button_border_hover',
							'label' => esc_html__( 'Border', 'borderless'),
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-button:hover',
						]
					);
				
					$this->add_responsive_control(
						'borderless_elementor_item_content_button_radius_hover',
						[
							'label' => esc_html__( 'Border Radius', 'borderless'),
							'type' => Controls_Manager::DIMENSIONS,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-item-button:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
							],
						]
					);
		
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'borderless_elementor_item_content_button_box_shadow_hover',
							'exclude' => [
								'box_shadow_position',
							],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-item-button:hover',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Portfolio/Filter - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_filter_style',
			[
				'label' => esc_html__( 'Filter', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'borderless_elementor_filter_background',
					'label' => __( 'Background', 'borderless' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filters',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_filter_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_filter_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'default'   => [
						'top' => '0',
						'right' => '0',
						'bottom' => '30',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => false,
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-filters' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_filter_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filters',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_filter_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-filters' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_filter_box_shadow',
					'exclude' => [
						'box_shadow_position',
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filters',
				]
			);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Portfolio/Filter Item - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_filter_item_style',
			[
				'label' => esc_html__( 'Filter Item', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_responsive_control(
				'borderless_elementor_filter_item_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-filter-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_filter_item_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-portfolio-filter-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'borderless_elementor_portfolio_filter_item_tabs' );

				$this->start_controls_tab( 'borderless_elementor_portfolio_filter_item_tab_normal',
					[
						'label' => __( 'Normal', 'borderless' ),
					]
				);

					$this->add_control(
						'borderless_elementor_filter_item_color',
						[
							'label' => __( 'Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-filter-item' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_typography',
							'label' => __('Typography', 'borderless'),
							'scheme' => Typography::TYPOGRAPHY_1,
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item',
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_background',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item',
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_border',
							'label' => esc_html__( 'Border', 'borderless'),
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item',
						]
					);
				
					$this->add_responsive_control(
						'borderless_elementor_filter_item_radius',
						[
							'label' => esc_html__( 'Border Radius', 'borderless'),
							'type' => Controls_Manager::DIMENSIONS,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-filter-item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_box_shadow',
							'exclude' => [
								'box_shadow_position',
							],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_portfolio_filter_item_tab_hover',
					[
						'label' => __( 'Hover', 'borderless' ),
					]
				);

					$this->add_control(
						'borderless_elementor_filter_item_color_hover',
						[
							'label' => __( 'Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-filter-item:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_typography_hover',
							'label' => __('Typography', 'borderless'),
							'scheme' => Typography::TYPOGRAPHY_1,
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item:hover',
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_background_hover',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item:hover',
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_border_hover',
							'label' => esc_html__( 'Border', 'borderless'),
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item:hover',
						]
					);
				
					$this->add_responsive_control(
						'borderless_elementor_filter_item_radius_hover',
						[
							'label' => esc_html__( 'Border Radius', 'borderless'),
							'type' => Controls_Manager::DIMENSIONS,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-filter-item:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_box_shadow_hover',
							'exclude' => [
								'box_shadow_position',
							],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item:hover',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_portfolio_filter_item_tab_active',
					[
						'label' => __( 'Active', 'borderless' ),
					]
				);

					$this->add_control(
						'borderless_elementor_filter_item_color_active',
						[
							'label' => __( 'Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-filter-item.is-checked' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_typography_active',
							'label' => __('Typography', 'borderless'),
							'scheme' => Typography::TYPOGRAPHY_1,
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item.is-checked',
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_background_active',
							'label' => __( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item.is-checked',
						]
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_border_active',
							'label' => esc_html__( 'Border', 'borderless'),
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item.is-checked',
						]
					);
				
					$this->add_responsive_control(
						'borderless_elementor_filter_item_radius_active',
						[
							'label' => esc_html__( 'Border Radius', 'borderless'),
							'type' => Controls_Manager::DIMENSIONS,
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-portfolio-filter-item.is-checked' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'borderless_elementor_filter_item_box_shadow_active',
							'exclude' => [
								'box_shadow_position',
							],
							'selector' => '{{WRAPPER}} .borderless-elementor-portfolio-filter-item.is-checked',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
			
	}
		
	/*-----------------------------------------------------------------------------------*/
	/*  *.  Render
	/*-----------------------------------------------------------------------------------*/
	
	protected function render() {
		
		$settings = $this->get_settings_for_display();

		$target = $settings['borderless_elementor_portfolio_item_button_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['borderless_elementor_portfolio_item_button_link']['nofollow'] ? ' rel="nofollow"' : '';

		
		if ( $settings['borderless_elementor_portfolio_item_strings'] ) {
			foreach (  $settings['borderless_elementor_portfolio_item_strings'] as $portfolio_string ) {
				$borderless_elementor_portfolio_item_strings = $portfolio_string['borderless_elementor_portfolio_item_filter'];

				$filters = explode(',', $borderless_elementor_portfolio_item_strings);
				$filters = array_map('trim', $filters);

				foreach ( $filters as $filter_title ) { $filter_title_items[] = $filter_title; }
				foreach ( $filters as $filter_classe ) { $filter_classe_items[] = strtolower( str_replace(' ', '', $filter_classe) ); }
				
			}
			$filter_title_items = array_unique($filter_title_items);
			$filter_classe_items = array_unique($filter_classe_items);
		}

		?>

		<div class="borderless-elementor-portfolio-widget">
			<div class="borderless-elementor-portfolio borderless-container" data-portfolio-gutter="<?php echo wp_kses( ( $settings['borderless_elementor_portfolio_item_gap']['size'] ), true ); ?>">
				<div class="borderless-elementor-portfolio-filters">
					<div class="borderless-elementor-portfolio-filter" data-portfolio-filter-group="filter">
					<div class="borderless-elementor-portfolio-filter-item is-checked" data-portfolio-filter=""><span><?php echo wp_kses( ( $settings['borderless_elementor_portfolio_default_filter_label'] ), true ); ?></span></div>
						<?php
							if ( $settings['borderless_elementor_portfolio_item_strings'] ) {
								foreach (array_combine($filter_title_items, $filter_classe_items) as $filter_title_item => $filter_classe_item) {
									echo '<div class="borderless-elementor-portfolio-filter-item" data-portfolio-filter=".'.$filter_classe_item.'">
										<span class="borderless-elementor-portfolio-filter-item-title">'.$filter_title_item.'</span>
									</div>';
								}
							}
						?>
					</div>
				</div>
				<div class="borderless-elementor-portfolio-items borderless-grid">
					<?php
						if ( $settings['borderless_elementor_portfolio_item_strings'] ) {
							foreach (  $settings['borderless_elementor_portfolio_item_strings'] as $portfolio_string ) {
								$filter = $portfolio_string['borderless_elementor_portfolio_item_filter'];
								$filter = strtolower( str_replace(' ', '', $filter) );
								$filter = strtolower( str_replace(',', ' ', $filter) );
								echo '<div class="borderless-elementor-portfolio-item ' . wp_kses( ( $settings['borderless_elementor_portfolio_item_columns'] ), true ) . ' '.$filter.'">';
									echo '<div class="borderless-elementor-portfolio-item-inner">';
										echo '<img src="' . wp_kses( ( $portfolio_string['borderless_elementor_portfolio_item_image']['url'] ), true ) . '">';
										echo '<div class="borderless-elementor-portfolio-item-content">';
											echo '<div class="borderless-elementor-portfolio-item-content-inner">';
												echo '<h2 class="borderless-elementor-portfolio-item-title">'.wp_kses( ( $portfolio_string['borderless_elementor_portfolio_item_title'] ), true ).'</h2>';
												echo '<span class="borderless-elementor-portfolio-item-description">'.wp_kses( ( $portfolio_string['borderless_elementor_portfolio_item_description'] ), true ).'</span>';
												echo '<div class="borderless-elementor-portfolio-item-button-container">';
													echo '<a class="borderless-elementor-portfolio-item-button borderless-btn borderless-btn--primary" href="' . wp_kses( ( $portfolio_string['borderless_elementor_portfolio_item_button_link']['url'] ), true ) . '"' . $target . $nofollow . '>'.wp_kses( ( $portfolio_string['borderless_elementor_portfolio_item_button_text'] ), true ).'</a>';
												echo '</div>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}
						}
					?>
				</div>
			</div>
		</div>

		<?php
		
	}
	
	protected function _content_template() {
		
	}
		
		
}