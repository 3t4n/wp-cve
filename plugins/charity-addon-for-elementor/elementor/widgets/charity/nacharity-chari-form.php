<?php
/*
 * Elementor Charity Addon for Elementor Charitable Form
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'charitable/charitable.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Charitable_Form extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_charitable_form';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Form', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-form-horizontal';
		}

		/**
		 * Retrieve the form of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-charitable-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Charitable Form widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$charity = get_posts( 'post_type="campaign"&numberposts=-1' );
	    $CharityID = array();
	    if ( $charity ) {
	      foreach ( $charity as $form ) {
	        $CharityID[ $form->ID ] = $form->post_title;
	      }
	    } else {
	      $CharityID[ __( 'No ID\'s found', 'charity-addon-for-elementor' ) ] = 0;
	    }

	    $this->start_controls_section(
				'section_form',
				[
					'label' => esc_html__( 'Form Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'id',
				[
					'label' => __( 'Select a Donation Form', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $CharityID,
					'description' => esc_html__( 'You must first select a Form!', 'charity-addon-for-elementor' ),
				]
			);
			$this->end_controls_section();// end: Section

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
						'{{WRAPPER}} .nacep-charitable-form label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
					'name' => 'label_typography',
					'selector' => '{{WRAPPER}} .nacep-charitable-form label',
				]
			);
			$this->add_control(
				'label_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-charitable-form label' => 'color: {{VALUE}} !important;',
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
					'selector' => '{{WRAPPER}} .nacep-charitable-form input[type="text"],
					{{WRAPPER}} .nacep-charitable-form input[type="email"],
					{{WRAPPER}} .nacep-charitable-form input[type="date"],
					{{WRAPPER}} .nacep-charitable-form input[type="time"],
					{{WRAPPER}} .nacep-charitable-form input[type="number"],
					{{WRAPPER}} .nacep-charitable-form input[type="password"],
					{{WRAPPER}} .nacep-charitable-form input[type="url"],
					{{WRAPPER}} .nacep-charitable-form textarea,
					{{WRAPPER}} .nacep-charitable-form select ',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'form_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-charitable-form input[type="text"],
					{{WRAPPER}} .nacep-charitable-form input[type="email"],
					{{WRAPPER}} .nacep-charitable-form input[type="date"],
					{{WRAPPER}} .nacep-charitable-form input[type="time"],
					{{WRAPPER}} .nacep-charitable-form input[type="number"],
					{{WRAPPER}} .nacep-charitable-form input[type="password"],
					{{WRAPPER}} .nacep-charitable-form input[type="url"],
					{{WRAPPER}} .nacep-charitable-form textarea,
					{{WRAPPER}} .nacep-charitable-form select ',
				]
			);
			$this->add_control(
				'placeholder_text_color',
				[
					'label' => __( 'Placeholder Text Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-charitable-form input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-form input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-form input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-form input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-form textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-form textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-form textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .nacep-charitable-form textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => __( 'Text Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-charitable-form input[type="text"],
						{{WRAPPER}} .nacep-charitable-form input[type="email"],
						{{WRAPPER}} .nacep-charitable-form input[type="date"],
						{{WRAPPER}} .nacep-charitable-form input[type="time"],
						{{WRAPPER}} .nacep-charitable-form input[type="number"],
						{{WRAPPER}} .nacep-charitable-form input[type="password"],
						{{WRAPPER}} .nacep-charitable-form input[type="url"],
						{{WRAPPER}} .nacep-charitable-form textarea,
						{{WRAPPER}} .nacep-charitable-form select ' => 'color: {{VALUE}} !important;',
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
		 * Render Charitable Form widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {

			$settings = $this->get_settings_for_display();
			$id = !empty( $settings['id'] ) ? $settings['id'] : '';
			$id = $id ? ' campaign_id="'.$id.'"' : '';

	  	$output = '<div class="nacep-charitable-form nacep-form">'.do_shortcode( '[charitable_donation_form'.$id.']' ).'</div>';
		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Charitable_Form() );
}
