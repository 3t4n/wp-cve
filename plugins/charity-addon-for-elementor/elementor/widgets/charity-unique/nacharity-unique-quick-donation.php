<?php
/*
 * Elementor Charity Addon for Elementor Quick Donation
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Unique_Donation extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_unique_quick_donation';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Quick Donation', 'charity-addon-for-elementor' );
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
			return ['nacharity-unique-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Quick Donation widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$charity = get_posts( 'post_type="give_forms"&numberposts=-1' );
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
				'cause_id',
				[
					'label' => __( 'Select a Donation Form', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $CharityID,
					'description' => esc_html__( 'You must first select a Form!', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title',
				[
					'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Make a Donation', 'charity-addon-for-elementor' ),
					'description' => esc_html__( 'Type text here.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'continue_button_title',
				[
					'label' => esc_html__( 'Button Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'The button label for displaying the additional payment fields.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'hide_info_box',
				[
					'label' => esc_html__( 'Hide Info/Warning box?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'hide_custom_amount',
				[
					'label' => esc_html__( 'Hide Custom Amount?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .nacep-quick-donation h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'title_typography',
						'selector' => '{{WRAPPER}} .nacep-quick-donation h3',
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation h3' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal' => 'min-width:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'btn_typography',
						'selector' => '{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal',
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
								'{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal',
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
								'{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-quick-donation .give-btn.give-btn-modal:hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
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
						'selector' => '{{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button',
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'form_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-quick-donation form[id*=give-form] .give-donation-amount #give-amount,
						{{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button',
					]
				);
				$this->add_control(
					'input_color',
					[
						'label' => __( 'Input Border Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount input, {{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button, {{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount span.give-currency-symbol' => 'border-color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount span.give-currency-symbol' => 'background-color: {{VALUE}} !important;',
						],
					]
				);
				$this->add_control(
					'placeholder_text_color',
					[
						'label' => __( 'Placeholder Text Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount input' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->add_control(
					'currency_color',
					[
						'label' => __( 'Currency Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount span.give-currency-symbol' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->add_control(
					'text_color',
					[
						'label' => __( 'Text Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button, {{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount input' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->add_control(
					'text_bg_color',
					[
						'label' => __( 'Amount Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button, {{WRAPPER}} .nacep-quick-donation .give-total-wrap .give-donation-amount input' => 'background-color: {{VALUE}} !important;',
						],
					]
				);
				$this->add_control(
					'amt_hov_color',
					[
						'label' => __( 'Amount Hover Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:hover, {{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:active, {{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:focus' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->add_control(
					'amt_hov_bdr_color',
					[
						'label' => __( 'Amount Hover Border Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:hover, {{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:active, {{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:focus' => 'border-color: {{VALUE}} !important;',
						],
					]
				);
				$this->add_control(
					'amt_hov_bg_color',
					[
						'label' => __( 'Amount Hover Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:hover, {{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:active, {{WRAPPER}} .nacep-quick-donation .give-donation-levels-wrap li button:focus' => 'background-color: {{VALUE}} !important;',
						],
					]
				);
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Quick Donation widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();

			$cause_id 				= !empty( $settings['cause_id'] ) ? $settings['cause_id'] : '';
			$title 				= !empty( $settings['title'] ) ? $settings['title'] : '';
			$continue_button_title 				= !empty( $settings['continue_button_title'] ) ? $settings['continue_button_title'] : '';
			$hide_info_box 		= !empty( $settings['hide_info_box'] ) ? $settings['hide_info_box'] : '';
			$hide_custom_amount 		= !empty( $settings['hide_custom_amount'] ) ? $settings['hide_custom_amount'] : '';

			if($hide_info_box) {
				$info_cls = ' hide-infobox';
			} else {
				$info_cls = '';
			}
			if($hide_custom_amount) {
				$amount_cls = ' hide-custom-amount';
			} else {
				$amount_cls = '';
			}

			$title = $title ? '<h3 class="donation-title">'.$title.'</h3>' : '';

			$cause_id 					   = $cause_id ? ' id="'.$cause_id.'"' : '';
			$continue_button_title 	 		 = $continue_button_title ? ' continue_button_title="'.$continue_button_title.'"' : '';

	  	$output = '<div class="nacep-quick-donation '.$info_cls.$amount_cls.'">'.esc_attr($title).do_shortcode( '[give_form'. $cause_id . $continue_button_title .' show_title="false" show_goal="false" show_content="none" display_style="modal"]' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Unique_Donation() );
}
