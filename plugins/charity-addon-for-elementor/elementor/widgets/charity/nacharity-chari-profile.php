<?php
/*
 * Elementor Charity Addon for Elementor Charitable Profile
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'charitable/charitable.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Charitable_Profile extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_charitable_profile';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Profile', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-person';
		}

		/**
		 * Retrieve the profile of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-charitable-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Charitable Profile widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .charitable-form-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .charitable-form-header',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .charitable-form-header' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Label
			$this->start_controls_section(
				'section_label_style',
				[
					'label' => esc_html__( 'Label', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'label_padding',
				[
					'label' => __( 'Padding', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .nacep-charitable-profile label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'label_typography',
					'selector' => '{{WRAPPER}} .nacep-charitable-profile label',
				]
			);
			$this->add_control(
				'label_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-charitable-profile label' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Form
			$this->start_controls_section(
				'section_form_style',
				[
					'label' => esc_html__( 'Form', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'form_typography',
					'selector' => '{{WRAPPER}} .nacep-charitable-profile input[type="text"],
					{{WRAPPER}} .nacep-charitable-profile input[type="email"],
					{{WRAPPER}} .nacep-charitable-profile input[type="date"],
					{{WRAPPER}} .nacep-charitable-profile input[type="time"],
					{{WRAPPER}} .nacep-charitable-profile input[type="number"],
					{{WRAPPER}} .nacep-charitable-profile input[type="password"],
					{{WRAPPER}} .nacep-charitable-profile input[type="url"],
					{{WRAPPER}} .nacep-charitable-profile textarea,
					{{WRAPPER}} .nacep-charitable-profile select ',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'form_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-charitable-profile input[type="text"],
					{{WRAPPER}} .nacep-charitable-profile input[type="email"],
					{{WRAPPER}} .nacep-charitable-profile input[type="date"],
					{{WRAPPER}} .nacep-charitable-profile input[type="time"],
					{{WRAPPER}} .nacep-charitable-profile input[type="number"],
					{{WRAPPER}} .nacep-charitable-profile input[type="password"],
					{{WRAPPER}} .nacep-charitable-profile input[type="url"],
					{{WRAPPER}} .nacep-charitable-profile textarea,
					{{WRAPPER}} .nacep-charitable-profile select ',
				]
			);
			$this->add_control(
				'placeholder_text_color',
				[
					'label' => __( 'Placeholder Text Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-charitable-profile input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-profile input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-profile input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-profile input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-profile textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-profile textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-profile textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-profile textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => __( 'Text Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-charitable-profile input[type="text"],
						{{WRAPPER}} .nacep-charitable-profile input[type="email"],
						{{WRAPPER}} .nacep-charitable-profile input[type="date"],
						{{WRAPPER}} .nacep-charitable-profile input[type="time"],
						{{WRAPPER}} .nacep-charitable-profile input[type="number"],
						{{WRAPPER}} .nacep-charitable-profile input[type="password"],
						{{WRAPPER}} .nacep-charitable-profile input[type="url"],
						{{WRAPPER}} .nacep-charitable-profile textarea,
						{{WRAPPER}} .nacep-charitable-profile select ' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->end_controls_section();// end: Section

			// Button
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Button', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .nacep-form button[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_padding',
				[
					'label' => __( 'Padding', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .nacep-form button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'btn_width',
				[
					'label' => esc_html__( 'Button Width', 'charity-addon-for-elementor' ),
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
						'{{WRAPPER}} .nacep-form button[type="submit"]' => 'min-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .nacep-form button[type="submit"]',
				]
			);
			$this->start_controls_tabs( 'btn_style' );
				$this->start_controls_tab(
					'btn_normal',
					[
						'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'btn_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-form button[type="submit"]' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-form button[type="submit"]' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-form button[type="submit"]',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_hover',
					[
						'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'btn_hover_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-form button[type="submit"]:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-form button[type="submit"]:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-form button[type="submit"]:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section
			
		}

		/**
		 * Render Charitable Profile widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {

	  	$output = '<div class="nacep-charitable-profile nacep-form">'.do_shortcode( '[charitable_profile]' ).'</div>';
		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Charitable_Profile() );
}
