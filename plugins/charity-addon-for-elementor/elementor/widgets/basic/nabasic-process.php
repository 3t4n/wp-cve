<?php
/*
 * Elementor Charity Addon for Elementor Process Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Charity_Elementor_Addon_Process extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'nacharity_basic_process';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Process', 'charity-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-anchor';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['nacharity-basic-category'];
	}

	/**
	 * Register Charity Addon for Elementor Process widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_process',
			[
				'label' => __( 'Process Item', 'charity-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'process_style',
			[
				'label' => __( 'Process Style', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'charity-addon-for-elementor' ),
					'two' => esc_html__( 'Number Style', 'charity-addon-for-elementor' ),
					'three' => esc_html__( 'Vertical Style', 'charity-addon-for-elementor' ),
				],
				'default' => 'default',
			]
		);
		$this->add_control(
			'process_col',
			[
				'label' => __( 'Process Column', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Four', 'charity-addon-for-elementor' ),
					'three' => esc_html__( 'Three', 'charity-addon-for-elementor' ),
					'two' => esc_html__( 'Two', 'charity-addon-for-elementor' ),
					'one' => esc_html__( 'One', 'charity-addon-for-elementor' ),
				],
				'default' => 'default',
				'condition' => [
					'process_style' => 'two',
				],
			]
		);
		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Alignment', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'charity-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'charity-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'charity-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .nacep-process-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'process_style' => 'two',
				],
			]
		);
		$this->add_control(
			'need_dot',
			[
				'label' => esc_html__( 'Need Animation Hover?', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
				'return_value' => 'true',
				'condition' => [
					'process_style' => 'default',
				],
			]
		);
		$this->add_control(
			'need_border_style',
			[
				'label' => esc_html__( 'Need Different Border?', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
				'return_value' => 'true',
				'condition' => [
					'process_style' => 'default',
				],
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'upload_type',
			[
				'label' => __( 'Icon Type', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'charity-addon-for-elementor' ),
					'icon' => esc_html__( 'Icon', 'charity-addon-for-elementor' ),
				],
				'default' => 'image',
			]
		);
		$repeater->add_control(
			'process_image',
			[
				'label' => esc_html__( 'Upload Icon', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'upload_type' => 'image',
				],
				'frontend_available' => true,
				'description' => esc_html__( 'Set your icon image.', 'charity-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'process_icon',
			[
				'label' => esc_html__( 'Select Icon', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NACEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-cog',
				'condition' => [
					'upload_type' => 'icon',
				],
			]
		);
		$repeater->add_control(
			'process_title',
			[
				'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'process_content',
			[
				'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'process_groups',
			[
				'label' => esc_html__( 'Process Items', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'process_title' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ process_title }}}',
				'condition' => [
					'process_style' => 'default',
				],
			]
		);

		$repeater_one = new Repeater();
		$repeater_one->add_control(
			'process_title',
			[
				'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater_one->add_control(
			'process_subtitle',
			[
				'label' => esc_html__( 'Sub Title', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Sub Title', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater_one->add_control(
			'process_content',
			[
				'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'processTwo_groups',
			[
				'label' => esc_html__( 'Process Items', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'process_title' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
					],

				],
				'fields' => $repeater_one->get_controls(),
				'title_field' => '{{{ process_title }}}',
				'condition' => [
					'process_style' => 'two',
				],
			]
		);

		$repeater_one = new Repeater();
		$repeater_one->add_control(
			'process_image',
			[
				'label' => esc_html__( 'Upload Icon', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description' => esc_html__( 'Set your image.', 'charity-addon-for-elementor'),
			]
		);
		$repeater_one->add_control(
			'process_title',
			[
				'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater_one->add_control(
			'process_subtitle',
			[
				'label' => esc_html__( 'Sub Title', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Sub Title', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater_one->add_control(
			'process_content',
			[
				'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'processThree_groups',
			[
				'label' => esc_html__( 'Process Items', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'process_title' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
					],

				],
				'fields' => $repeater_one->get_controls(),
				'title_field' => '{{{ process_title }}}',
				'condition' => [
					'process_style' => 'three',
				],
			]
		);

		$this->end_controls_section();// end: Section

		// Section
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'process_style' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'process_section_margin',
			[
				'label' => __( 'Margin', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'process_section_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'section_width',
			[
				'label' => esc_html__( 'Section Width', 'charity-addon-for-elementor' ),
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
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .process-info' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Section Two
		$this->start_controls_section(
			'section_two_style',
			[
				'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'process_style' => 'two',
				],
			]
		);
		$this->add_responsive_control(
			'process_section_two_margin',
			[
				'label' => __( 'Margin', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .process-number-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'process_section_two_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .process-number-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Section Three
		$this->start_controls_section(
			'section_three_style',
			[
				'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'process_style' => 'three',
				],
			]
		);
		$this->add_control(
			'lines',
			[
				'label' => __( 'Lines', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'vline_color',
			[
				'label' => esc_html__( 'Vertical Line Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-process-wrap.style-three:before' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'hline_color',
			[
				'label' => esc_html__( 'Horizontal Line Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item-vertical:before' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'circles',
			[
				'label' => __( 'Circles', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->start_controls_tabs( 'cir_style' );
			$this->start_controls_tab(
				'ico_cir_normal',
				[
					'label' => esc_html__( 'Start/End', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'cir_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-process-item-vertical:first-child:before, {{WRAPPER}} .nacep-process-item-vertical:last-child:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'cir_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-process-item-vertical:first-child:before, {{WRAPPER}} .nacep-process-item-vertical:last-child:after',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'ico_cir_hover',
				[
					'label' => esc_html__( 'Middle', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'mcir_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-process-item-vertical:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'mcir_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-process-item-vertical:after',
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

		// Number
		$this->start_controls_section(
			'section_count_style',
			[
				'label' => esc_html__( 'Count', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'process_style!' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'process_number_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'section_two_number_width',
			[
				'label' => esc_html__( 'Number Width/Height', 'charity-addon-for-elementor' ),
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
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .count:before' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'number_typography',
				'selector' => '{{WRAPPER}} .count:before',
			]
		);
		$this->add_control(
			'number_bg_color',
			[
				'label' => esc_html__( 'Number Background Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .count:before' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'number_color',
			[
				'label' => esc_html__( 'Number Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .count:before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'number_border_color',
			[
				'label' => esc_html__( 'Line Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .count:after' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Icon
		$this->start_controls_section(
			'icon_style',
			[
				'label' => esc_html__( 'Icon', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'process_style' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'process_icon_margin',
			[
				'label' => __( 'Margin', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item .nacep-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_width',
			[
				'label' => esc_html__( 'Width', 'charity-addon-for-elementor' ),
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
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item .nacep-icon' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon/Image Size', 'charity-addon-for-elementor' ),
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
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item .nacep-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .nacep-process-item .nacep-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'secn_style' );
			$this->start_controls_tab(
				'ico_secn_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'ico_secn_icon_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-process-item .nacep-icon' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'ico_secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-process-item .nacep-icon' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'ico_secn_bdr_color',
				[
					'label' => esc_html__( 'Line Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-process-item:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .border-style.nacep-process-item:before' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'ico_secn_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-process-item .nacep-icon',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'ico_secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-process-item .nacep-icon',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'ico_secn_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'ico_secn_hov_icon_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-process-item.process-done .nacep-icon' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'ico_secn_hov_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-process-item.process-done .nacep-icon, {{WRAPPER}} .nacep-process-item.process-done .nacep-icon span.circle' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'ico_secn_hov_bdr_color',
				[
					'label' => esc_html__( 'Line Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-process-item.process-done:after' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .process-done.border-style.nacep-process-item:before' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'ico_secn_hov_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-process-item.process-done .nacep-icon',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'ico_secn_hov_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-process-item.process-done .nacep-icon',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'process_title_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item h3, {{WRAPPER}} .process-number-item h3, {{WRAPPER}} .vertical-info h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'sastool_title_typography',
				'selector' => '{{WRAPPER}} .nacep-process-item h3, {{WRAPPER}} .process-number-item h3, {{WRAPPER}} .vertical-info h3',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item h3, {{WRAPPER}} .process-number-item h3, {{WRAPPER}} .vertical-info h3' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Sub Title
		$this->start_controls_section(
			'section_subtitle_style',
			[
				'label' => esc_html__( 'Sub Title', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'process_style!' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'process_subtitle_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .process-number-item h5, {{WRAPPER}} .vertical-info h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'sastool_subtitle_typography',
				'selector' => '{{WRAPPER}} .process-number-item h5, {{WRAPPER}} .vertical-info h5',
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .process-number-item h5, {{WRAPPER}} .vertical-info h5' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'process_cont_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item p, {{WRAPPER}} .process-number-item p, {{WRAPPER}} .vertical-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'sastool_content_typography',
				'selector' => '{{WRAPPER}} .nacep-process-item p, {{WRAPPER}} .process-number-item p, {{WRAPPER}} .vertical-info p',
			]
		);
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-process-item p, {{WRAPPER}} .process-number-item p, {{WRAPPER}} .vertical-info p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Process widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Process query
		$settings = $this->get_settings_for_display();
		$process = $this->get_settings_for_display( 'process_groups' );
		$processTwo = $this->get_settings_for_display( 'processTwo_groups' );
		$processThree = $this->get_settings_for_display( 'processThree_groups' );
		$process_style = !empty( $settings['process_style'] ) ? $settings['process_style'] : '';
		$process_col = !empty( $settings['process_col'] ) ? $settings['process_col'] : '';
		$need_dot = !empty( $settings['need_dot'] ) ? $settings['need_dot'] : '';
		$need_border_style = !empty( $settings['need_border_style'] ) ? $settings['need_border_style'] : '';

		if ($need_dot) {
			$dot = '<span class="circle"></span>';
		} else {
			$dot = '';
		}
		if ($need_border_style) {
			$border_class = ' border-style';
		} else {
			$border_class = '';
		}
		if ($process_style === 'two') {
			$style_class = ' style-two';
		} elseif ($process_style === 'three') {
			$style_class = ' style-three';
		} else {
			$style_class = '';
		}
		if ($process_col === 'one') {
			$col_class = ' one-col';
		} elseif ($process_col === 'two') {
			$col_class = ' two-col';
		} elseif ($process_col === 'three') {
			$col_class = ' three-col';
		} else {
			$col_class = '';
		}

		$output = '';

		$output .= '<div class="nacep-process-wrap'.esc_attr($style_class).'">';
			if ($process_style === 'two') {
				// Group Param Output
				$i=1;
				foreach ( $processTwo as $each_logo ) {
					$process_title = !empty( $each_logo['process_title'] ) ? $each_logo['process_title'] : '';
					$process_subtitle = !empty( $each_logo['process_subtitle'] ) ? $each_logo['process_subtitle'] : '';
					$process_content = !empty( $each_logo['process_content'] ) ? $each_logo['process_content'] : '';

			  	$title = !empty( $process_title ) ? '<h3 class="process-title">'.esc_html($process_title).'</h3>' : '';
			  	$subtitle = !empty( $process_subtitle ) ? '<h5>'.esc_html($process_subtitle).'</h5>' : '';
					$content = $process_content ? '<p>'.esc_html($process_content).'</p>' : '';
					if ($i >= 10) {
						$pre = '';
					} else {
						$pre = '0';
					}
					$count = '<span class="count" data-count="'.$pre.$i.'"></span>';

				  $output .= '<div class="process-number-item'.esc_attr($col_class).'">'.$title.$subtitle.$count.$content.'</div>';
				  $i++;
				}
			} elseif ($process_style === 'three') {
				// Group Param Output
				$i=1;
				foreach ( $processThree as $each_logo ) {
					$process_image = !empty( $each_logo['process_image']['id'] ) ? $each_logo['process_image']['id'] : '';
					$process_title = !empty( $each_logo['process_title'] ) ? $each_logo['process_title'] : '';
					$process_subtitle = !empty( $each_logo['process_subtitle'] ) ? $each_logo['process_subtitle'] : '';
					$process_content = !empty( $each_logo['process_content'] ) ? $each_logo['process_content'] : '';

			  	$title = !empty( $process_title ) ? '<h3 class="process-title">'.$process_title.'</h3>' : '';
			  	$subtitle = !empty( $process_subtitle ) ? '<h5>'.$process_subtitle.'</h5>' : '';
					$content = $process_content ? '<p>'.$process_content.'</p>' : '';
					$image_url = wp_get_attachment_url( $process_image );
					$process_image = $image_url ? '<div class="nacep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($process_title).'"></div>' : '';
					if ($i >= 10) {
						$pre = '';
					} else {
						$pre = '0';
					}
					$count = '<span class="count" data-count="'.$pre.$i.'"></span>';

				  $output .= '<div class="nacep-process-item-vertical"><div class="vertical-info">'.$count.$title.$subtitle.$content.'</div>'.$process_image.'</div>';
				  $i++;
				}
			} else {
				// Group Param Output
				foreach ( $process as $each_logo ) {
					$upload_type = !empty( $each_logo['upload_type'] ) ? $each_logo['upload_type'] : '';
					$process_title = !empty( $each_logo['process_title'] ) ? $each_logo['process_title'] : '';
					$process_image = !empty( $each_logo['process_image']['id'] ) ? $each_logo['process_image']['id'] : '';
					$process_icon = !empty( $each_logo['process_icon'] ) ? $each_logo['process_icon'] : '';
					$process_content = !empty( $each_logo['process_content'] ) ? $each_logo['process_content'] : '';

					$image_url = wp_get_attachment_url( $process_image );
					$process_image = $image_url ? '<img src="'.esc_url($image_url).'" alt="'.esc_attr($process_title).'">' : '';
					$process_icon = $process_icon ? '<i class="'.esc_attr($process_icon).'"></i>' : '';
					$content = $process_content ? '<p>'.esc_html($process_content).'</p>' : '';

					if ($upload_type === 'icon'){
					  $icon_main = $process_icon;
					} else {
					  $icon_main = $process_image;
					}

			  	$title = !empty( $process_title ) ? '<h3 class="process-title">'.$process_title.'</h3>' : '';

				  $output .= '<div class="nacep-process-item'.esc_attr($border_class).'">
									      <div class="process-info">
									        <div class="nacep-icon">
									          <div class="nacep-table-wrap">
									            <div class="nacep-align-wrap">
									              '.$dot.$icon_main.'
									            </div>
									          </div>
									        </div>
									        '.$title.$content.'
									      </div>
									    </div>';
				}
			}

		$output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Process() );
