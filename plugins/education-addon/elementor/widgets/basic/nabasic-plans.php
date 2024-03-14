<?php
/*
 * Elementor Education Addon Plans Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_plans'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Plans extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_plans';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Plans', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-price-table';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Plans widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_plans',
			[
				'label' => __( 'Plans Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'plans_style',
			[
				'label' => esc_html__( 'Plans Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_control(
			'plans_bg',
			[
				'label' => esc_html__( 'Background Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your background image.', 'education-addon'),
				'selectors' => [
					'{{WRAPPER}} .plan-price' => 'background-image: url({{url}});',
				],
				'separator' => 'before',
				'condition' => [
					'plans_style' => array('one'),
				],
			]
		);
		$this->add_control(
			'upload_type',
			[
				'label' => __( 'Icon Type', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'education-addon' ),
					'icon' => esc_html__( 'Icon', 'education-addon' ),
				],
				'default' => 'image',
				'condition' => [
					'plans_style' => array('two'),
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'icon_image',
			[
				'label' => esc_html__( 'Upload Icon', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'upload_type' => 'image',
					'plans_style' => array('two'),
				],
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your icon image.', 'education-addon'),
			]
		);
		$this->add_responsive_control(
			'img_width',
			[
				'label' => esc_html__( 'Image Width', 'education-addon' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'condition' => [
					'upload_type' => 'image',
					'plans_style' => array('two'),
				],
				'selectors' => [
					'{{WRAPPER}} .napae-flip-box .napae-icon' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'plans_icon',
			[
				'label' => esc_html__( 'Icon', 'education-addon' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'condition' => [
					'upload_type' => 'icon',
					'plans_style' => array('two'),
				],
			]
		);
		$this->add_control(
			'plans_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Basic', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'price',
			[
				'label' => esc_html__( 'Time', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '20', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'currency',
			[
				'label' => esc_html__( 'Currency', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '€', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'time',
			[
				'label' => esc_html__( 'Time', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '/ mo', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'before_text',
			[
				'label' => esc_html__( 'Normal Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Courses included: ', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'bold_text',
			[
				'label' => esc_html__( 'Blod Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '10 Members', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'after_text',
			[
				'label' => esc_html__( 'Normal Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'listItems_groups',
			[
				'label' => esc_html__( 'List Items', 'education-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ bold_text }}}',
				'prevent_empty' => false,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Subscribe Now', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Button Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_bdr_rad',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .plan-wrap' => 'background-color: {{VALUE}};',
					],
				]
			);		
			$this->add_control(
				'secn_bg_hover_color',
				[
					'label' => esc_html__( 'Background Hover Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .plan-wrap:hover .plan-price' => 'background-color: {{VALUE}};',
					],
				]
			);	
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .plan-wrap',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .plan-wrap',
				]
			);
			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'section_image_ico_style',
				[
					'label' => esc_html__( 'Icon', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'plans_style' => 'two',
					],
				]
			);
			$this->add_control(
				'social_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'image_ico_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'image_ico_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'image_ico_size',
				[
					'label' => esc_html__( 'Icon Size', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
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
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .plan-icon' => 'font-size:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'image_ico_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .plan-icon' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'image_ico_bg',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .plan-icon' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'image_ico_border',
				[
					'label' => esc_html__( 'Border Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .plan-icon' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'image_ico_shadow',
					'label' => esc_html__( 'Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .plan-icon',
				]
			);		
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-plans h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-plans h3',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-plans h3, {{WRAPPER}} .naedu-plans h3 a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Price
			$this->start_controls_section(
				'section_price_style',
				[
					'label' => esc_html__( 'Price', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'price_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-plans h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Currency Typography', 'education-addon' ),
					'name' => 'price_currency_typography',
					'selector' => '{{WRAPPER}} .naedu-plans h4 sup',
					'separator' => 'before',
				]
			);
			$this->add_control(
				'price_currency_color',
				[
					'label' => esc_html__( 'Currency Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-plans h4 sup' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Price Typography', 'education-addon' ),
					'name' => 'price_typography',
					'selector' => '{{WRAPPER}} .naedu-plans h4',
					'separator' => 'before',
				]
			);
			$this->add_control(
				'price_color',
				[
					'label' => esc_html__( 'Price Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-plans h4' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Time Typography', 'education-addon' ),
					'name' => 'price_time_typography',
					'selector' => '{{WRAPPER}} .naedu-plans h4 sub',
					'separator' => 'before',
				]
			);
			$this->add_control(
				'price_time_color',
				[
					'label' => esc_html__( 'Time Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-plans h4 sub' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'List Content', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-info li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .plan-info li',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .plan-info li' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'list_icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .plan-info li:before' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-info .naedu-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_padding',
				[
					'label' => __( 'Button Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .plan-info .naedu-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .plan-info .naedu-btn',
				]
			);
			$this->start_controls_tabs( 'btn_style' );
				$this->start_controls_tab(
					'btn_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .plan-info .naedu-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .plan-info .naedu-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .plan-info .naedu-btn',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .plan-info .naedu-btn',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .plan-info .naedu-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .plan-info .naedu-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .plan-info .naedu-btn:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .plan-info .naedu-btn:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Plans widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Plans query
		$settings = $this->get_settings_for_display();
		$plans_style = !empty( $settings['plans_style'] ) ? $settings['plans_style'] : '';
		$upload_type = !empty( $settings['upload_type'] ) ? $settings['upload_type'] : '';
		$icon_image = !empty( $settings['icon_image']['id'] ) ? $settings['icon_image']['id'] : '';
		$plans_icon = !empty( $settings['plans_icon'] ) ? $settings['plans_icon'] : '';
		$plans_title = !empty( $settings['plans_title'] ) ? $settings['plans_title'] : '';
		$price = !empty( $settings['price'] ) ? $settings['price'] : '';
		$currency = !empty( $settings['currency'] ) ? $settings['currency'] : '';
		$time = !empty( $settings['time'] ) ? $settings['time'] : '';
		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';

		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? esc_url($settings['btn_link']['url']) : '';
		$btn_link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link['url'] ) ?  $btn_link_external.' '.$btn_link_nofollow : '';

		$plans_title = $plans_title ? '<h3>'.$plans_title.'</h3>' : '';
		$currency = $currency ? '<sup>'.$currency.'</sup>' : '';
		$time = $time ? '<sub>'.$time.'</sub>' : '';
		$price = $price ? '<h4>'.$currency.$price.$time.'</h4>' : '';
		$button = $btn_link ? '<a href="'.esc_url($btn_link).'" class="naedu-btn" '.$btn_link_attr.'>'.esc_html($btn_text).'</a>' : '';

		$ico_url = wp_get_attachment_url( $icon_image );
		$ico_image = $ico_url ? '<div class="plan-icon"><img src="'.$ico_url.'" alt="Img"></div>' : '';
		$icon = $plans_icon ? '<div class="plan-icon"><i class="'.esc_attr($plans_icon).'"></i></div>' : '';

		if ($upload_type === 'icon'){
		  $icon_main = $icon;
		} else {
		  $icon_main = $ico_image;
		}

		if ($plans_style === 'two') {
			$style_cls = ' plans-style-two';
		} else {
			$style_cls = '';
		}

		$output = '<div class="naedu-plans'.$style_cls.'">';
		if ($plans_style === 'two') {
	    $output .= '<div class="plan-wrap">
				            <div class="plan-price">'.$plans_title.$icon_main.$price.'</div>
				            <div class="plan-info">
				            	'.$button.'
				              <ul>';
			                	// Group Param Output
												if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
												  foreach ( $listItems_groups as $each_list ) {
												  $before_text = !empty( $each_list['before_text'] ) ? $each_list['before_text'] : '';
												  $bold_text = !empty( $each_list['bold_text'] ) ? $each_list['bold_text'] : '';
												  $after_text = !empty( $each_list['after_text'] ) ? $each_list['after_text'] : '';
													
													$bold_text = $bold_text ? '<span>'.$bold_text.'</span>' : '';

												  $output .= '<li>'.$before_text.$bold_text.$after_text.'</li>';
												} }
          $output .= '</ul>
				            </div>
				          </div>';
	  } else {
	  	$output .= '<div class="plan-wrap">
				            <div class="plan-price">'.$plans_title.$price.'</div>
				            <div class="plan-info">
				              <ul>';
			                	// Group Param Output
												if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
												  foreach ( $listItems_groups as $each_list ) {
												  $before_text = !empty( $each_list['before_text'] ) ? $each_list['before_text'] : '';
												  $bold_text = !empty( $each_list['bold_text'] ) ? $each_list['bold_text'] : '';
												  $after_text = !empty( $each_list['after_text'] ) ? $each_list['after_text'] : '';
													
													$bold_text = $bold_text ? '<span>'.$bold_text.'</span>' : '';

												  $output .= '<li>'.$before_text.$bold_text.$after_text.'</li>';
												} }
          $output .= '</ul>
				              '.$button.'
				            </div>
				          </div>';
	  }
	  $output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Plans() );

} // enable & disable
