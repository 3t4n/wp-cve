<?php
/*
 * Elementor Education Addon Classes Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_classes'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Classes extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_classes';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Classes Form', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Classes widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_classes',
			[
				'label' => esc_html__( 'Classes Options', 'education-addon' ),
			]
		);
		$this->add_control(
			'classes_style',
			[
				'label' => esc_html__( 'Classes Style', 'education-addon' ),
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
			'bg_image',
			[
				'label' => esc_html__( 'Video Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'classes_title',
			[
				'label' => esc_html__( 'Title Text', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Create your free account now & get immediate access to 100s of online courses.', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'video_link',
			[
				'label' => esc_html__( 'Video Link', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter your link here', 'education-addon' ),
				'separator' => 'before',
			]
		);
		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn More', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
				'condition' => [
					'classes_style' => 'two',
				],
			]
		);
		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Name Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'condition' => [
					'classes_style' => 'two',
				],
			]
		);
		$this->add_control(
			'form_id',
			[
				'label' => esc_html__( 'Select Class Form', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => NAEDU_Controls_Helper_Output::get_posts('wpcf7_contact_form'),
				'separator' => 'before',
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'section_box_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_width',
				[
					'label' => esc_html__( 'Section Width', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .classes-wrap' => 'max-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .classes-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .classes-wrap' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'section_vin_bg_color',
				[
					'label' => esc_html__( 'Video Inner Background', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .video-inner:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'section_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .classes-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'section_box_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .classes-wrap',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Image Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .classes-wrap',
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
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-classes h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .naedu-classes h3',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-classes h3' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Video Button
			$this->start_controls_section(
				'section_vid_button_style',
				[
					'label' => esc_html__( 'Video Button', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'vid_button_typography',
					'selector' => '{{WRAPPER}} .video-btn',
				]
			);
			$this->add_responsive_control(
				'vid_btn_width',
				[
					'label' => esc_html__( 'Width', 'education-addon' ),
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
						'{{WRAPPER}} .video-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'vid_btn_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .video-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'vid_button_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .video-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'vid_button_style' );
				$this->start_controls_tab(
					'vid_button_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'vid_button_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .video-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'vid_button_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .video-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'vid_button_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .video-btn',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'vid_button_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'vid_button_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .video-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'vid_button_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .video-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'vid_button_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .video-btn:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Learn More Button
			$this->start_controls_section(
				'section_lm_button_style',
				[
					'label' => esc_html__( 'Learn More Button', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'lm_button_typography',
					'selector' => '{{WRAPPER}} .naedu-btn',
				]
			);
			$this->add_responsive_control(
				'lm_btn_width',
				[
					'label' => esc_html__( 'Width', 'education-addon' ),
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
						'{{WRAPPER}} .naedu-btn' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'lm_btn_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'lm_button_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'lm_button_style' );
				$this->start_controls_tab(
					'lm_button_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'lm_button_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'lm_button_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'lm_button_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'lm_button_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'lm_button_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'lm_button_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'lm_button_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-btn:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Form
			$this->start_controls_section(
				'section_form_style',
				[
					'label' => esc_html__( 'Form', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'input_margin',
				[
					'label' => __( 'Text Field Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-classes input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'textarea_margin',
				[
					'label' => __( 'Textarea Field Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-classes textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'form_padding',
				[
					'label' => __( 'Form Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-classes input[type="text"],
						{{WRAPPER}} .naedu-classes input[type="email"],
						{{WRAPPER}} .naedu-classes input[type="date"],
						{{WRAPPER}} .naedu-classes input[type="time"],
						{{WRAPPER}} .naedu-classes input[type="number"],
						{{WRAPPER}} .naedu-classes input[type="password"], 
						{{WRAPPER}} .naedu-classes input[type="tel"], 
						{{WRAPPER}} .naedu-classes input[type="search"], 
						{{WRAPPER}} .naedu-classes input[type="url"], 
						{{WRAPPER}} .naedu-classes textarea,
						{{WRAPPER}} .naedu-classes select,
						{{WRAPPER}} .naedu-classes .form-control,
						{{WRAPPER}} .naedu-classes .nice-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .naedu-classes p',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Label Typography', 'education-addon' ),
					'name' => 'label_typography',
					'selector' => '{{WRAPPER}} .naedu-form form label',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Input Typography', 'education-addon' ),
					'name' => 'input_typography',
					'selector' => '{{WRAPPER}} .naedu-classes input[type="text"],
					{{WRAPPER}} .naedu-classes input[type="email"],
					{{WRAPPER}} .naedu-classes input[type="date"],
					{{WRAPPER}} .naedu-classes input[type="time"],
					{{WRAPPER}} .naedu-classes input[type="number"],
					{{WRAPPER}} .naedu-classes input[type="password"], 
					{{WRAPPER}} .naedu-classes input[type="tel"], 
					{{WRAPPER}} .naedu-classes input[type="search"], 
					{{WRAPPER}} .naedu-classes input[type="url"], 
					{{WRAPPER}} .naedu-classes textarea,
					{{WRAPPER}} .naedu-classes select,
					{{WRAPPER}} .naedu-classes .form-control,
					{{WRAPPER}} .naedu-classes .nice-select,
					{{WRAPPER}} .naedu-classes input:not([type="submit"])::-webkit-input-placeholder,
					{{WRAPPER}} .naedu-classes input:not([type="submit"])::-moz-placeholder,
					{{WRAPPER}} .naedu-classes input:not([type="submit"])::-ms-input-placeholder,
					{{WRAPPER}} .naedu-classes input:not([type="submit"])::-o-placeholder,
					{{WRAPPER}} .naedu-classes textarea::-webkit-input-placeholder,
					{{WRAPPER}} .naedu-classes textarea::-moz-placeholder,
					{{WRAPPER}} .naedu-classes textarea::-ms-input-placeholder,
					{{WRAPPER}} .naedu-classes textarea::-o-placeholder',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'form_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-classes input[type="text"],
					{{WRAPPER}} .naedu-classes input[type="email"],
					{{WRAPPER}} .naedu-classes input[type="date"],
					{{WRAPPER}} .naedu-classes input[type="time"],
					{{WRAPPER}} .naedu-classes input[type="number"],
					{{WRAPPER}} .naedu-classes input[type="password"], 
					{{WRAPPER}} .naedu-classes input[type="tel"], 
					{{WRAPPER}} .naedu-classes input[type="search"], 
					{{WRAPPER}} .naedu-classes input[type="url"], 
					{{WRAPPER}} .naedu-classes textarea,
					{{WRAPPER}} .naedu-classes select,
					{{WRAPPER}} .naedu-classes .form-control,
					{{WRAPPER}} .naedu-classes .nice-select',
				]
			);
			$this->add_control(
				'placeholder_text_color',
				[
					'label' => __( 'Placeholder Text Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-classes input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-classes input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-classes input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-classes input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-classes textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-classes textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-classes textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-classes textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => __( 'Text Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-classes input[type="text"],
						{{WRAPPER}} .naedu-classes input[type="email"],
						{{WRAPPER}} .naedu-classes input[type="date"],
						{{WRAPPER}} .naedu-classes input[type="time"],
						{{WRAPPER}} .naedu-classes input[type="number"],
						{{WRAPPER}} .naedu-classes input[type="password"], 
						{{WRAPPER}} .naedu-classes input[type="tel"], 
						{{WRAPPER}} .naedu-classes input[type="search"], 
						{{WRAPPER}} .naedu-classes input[type="url"], 
						{{WRAPPER}} .naedu-classes textarea,
						{{WRAPPER}} .naedu-classes select,
						{{WRAPPER}} .naedu-classes .form-control,
						{{WRAPPER}} .naedu-classes .nice-select' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'form_bg_color',
				[
					'label' => __( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-classes input[type="text"],
						{{WRAPPER}} .naedu-classes input[type="email"],
						{{WRAPPER}} .naedu-classes input[type="date"],
						{{WRAPPER}} .naedu-classes input[type="time"],
						{{WRAPPER}} .naedu-classes input[type="number"],
						{{WRAPPER}} .naedu-classes input[type="password"], 
						{{WRAPPER}} .naedu-classes input[type="tel"], 
						{{WRAPPER}} .naedu-classes input[type="search"], 
						{{WRAPPER}} .naedu-classes input[type="url"], 
						{{WRAPPER}} .naedu-classes textarea,
						{{WRAPPER}} .naedu-classes select,
						{{WRAPPER}} .naedu-classes .form-control,
						{{WRAPPER}} .naedu-classes .nice-select' => 'background-color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'form_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-classes input[type="text"],
						{{WRAPPER}} .naedu-classes input[type="email"],
						{{WRAPPER}} .naedu-classes input[type="date"],
						{{WRAPPER}} .naedu-classes input[type="time"],
						{{WRAPPER}} .naedu-classes input[type="number"],
						{{WRAPPER}} .naedu-classes input[type="password"], 
						{{WRAPPER}} .naedu-classes input[type="tel"], 
						{{WRAPPER}} .naedu-classes input[type="search"], 
						{{WRAPPER}} .naedu-classes input[type="url"], 
						{{WRAPPER}} .naedu-classes textarea,
						{{WRAPPER}} .naedu-classes select,
						{{WRAPPER}} .naedu-classes .form-control,
						{{WRAPPER}} .naedu-classes .nice-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_button_style',
				[
					'label' => esc_html__( 'Button', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'selector' => '{{WRAPPER}} .naedu-classes input[type="submit"]',
				]
			);
			$this->add_responsive_control(
				'btn_width',
				[
					'label' => esc_html__( 'Width', 'education-addon' ),
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
						'{{WRAPPER}} .naedu-classes input[type="submit"]' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-classes input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'button_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-classes input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'button_style' );
				$this->start_controls_tab(
					'button_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'button_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-classes input[type="submit"]' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-classes input[type="submit"]' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-classes input[type="submit"]',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'button_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'button_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-classes input[type="submit"]:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-classes input[type="submit"]:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-classes input[type="submit"]:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Classes widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$classes_style = !empty( $settings['classes_style'] ) ? $settings['classes_style'] : '';
		$form_id = !empty( $settings['form_id'] ) ? $settings['form_id'] : '';
		$classes_title = !empty( $settings['classes_title'] ) ? $settings['classes_title'] : '';
		$bg_image = !empty( $settings['bg_image']['id'] ) ? $settings['bg_image']['id'] : '';
		$video_link = !empty( $settings['video_link'] ) ? $settings['video_link'] : '';
		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? esc_url($settings['btn_link']['url']) : '';
		$btn_link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link['url'] ) ?  $btn_link_external.' '.$btn_link_nofollow : '';

		if ($classes_style === 'two') {
			$style_cls = ' classes-style-two';
		} else {
			$style_cls = '';
		}

		$title = $classes_title ? '<h3>'.$classes_title.'</h3>' : '';
		$video = $video_link ? '<a href="'.esc_url($video_link).'" target="_blank" class="video-btn naedu-popup-video"><i class="fas fa-play"></i></a>' : '';
		$button = $btn_link ? '<a href="'.esc_url($btn_link).'" class="naedu-btn" '.$btn_link_attr.'>'.esc_html($btn_text).'</a>' : '';

		$image_url = wp_get_attachment_url( $bg_image );
		$bg_img = $image_url ? ' style="background-image: url('.esc_url($image_url).');"' : '';

		// Starts
			$output = '<div class="naedu-classes naedu-form'.$style_cls.'">
									  <div class="nich-row nich-no-gutters classes-wrap">';
										if ($classes_style === 'two') {
					$output .= '<div class="nich-col-lg-7">'.do_shortcode( '[contact-form-7 id="'. $form_id .'"]' ).'</div>
							        <div class="nich-col-lg-5">
							          <div class="online-video"'.$bg_img.'><div class="video-inner">'.$video.$title.$button.'</div></div>
							        </div>';
										} else {
			    $output .= '<div class="nich-col-lg-6">
									      <div class="online-video"'.$bg_img.'>'.$video.$title.'</div>
									    </div>
									    <div class="nich-col-lg-6">'.do_shortcode( '[contact-form-7 id="'. $form_id .'"]' ).'</div>';
										}
			  	$output .= '</div>
									</div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Classes() );

} // enable & disable