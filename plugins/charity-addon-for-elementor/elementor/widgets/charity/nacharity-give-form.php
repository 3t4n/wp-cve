<?php
/*
 * Elementor Charity Addon for Elementor Give Form
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_GIVE_Form extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_give_form';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Give Form', 'charity-addon-for-elementor' );
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
			return ['nacharity-give-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Give Form widget controls.
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
				'form_id',
				[
					'label' => __( 'Select a Donation Form', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $CharityID,
					'description' => esc_html__( 'You must first select a Form!', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_title',
				[
					'label' => esc_html__( 'Show Title?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the title?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_goal',
				[
					'label' => esc_html__( 'Show Goal?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
					'description' => esc_html__( 'Do you want to display the goal\'s progress bar?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'show_content',
				[
					'label' => __( 'Show Content?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'none' => esc_html__( 'No Content', 'charity-addon-for-elementor' ),
						'above' => esc_html__( 'Above', 'charity-addon-for-elementor' ),
						'below' => esc_html__( 'Below', 'charity-addon-for-elementor' ),
					],
					'default' => 'none',
					'description' => esc_html__( 'Do you want to display the form content?', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'display_style',
				[
					'label' => __( 'Display Style', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'onpage' => esc_html__( 'All Fields', 'charity-addon-for-elementor' ),
						'modal' => esc_html__( 'Modal', 'charity-addon-for-elementor' ),
						'reveal' => esc_html__( 'Reveal', 'charity-addon-for-elementor' ),
						'button' => esc_html__( 'Button', 'charity-addon-for-elementor' ),
					],
					'default' => 'onpage',
					'description' => esc_html__( 'Show form as modal window or redirect to a new page?', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} h2.give-form-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'title_typography',
						'selector' => '{{WRAPPER}} h2.give-form-title',
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} h2.give-form-title' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Income
				$this->start_controls_section(
					'section_income_style',
					[
						'label' => esc_html__( 'Income Amount', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'income_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} span.income' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'income_typography',
						'selector' => '{{WRAPPER}} span.income',
					]
				);
				$this->add_control(
					'income_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} span.income' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Progress Bar Title
				$this->start_controls_section(
					'section_bar_title_style',
					[
						'label' => esc_html__( 'Progress Bar Title', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'bar_title_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .raised' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'bar_title_typography',
						'selector' => '{{WRAPPER}} .raised',
					]
				);
				$this->add_control(
					'bar_title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .raised' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Progress Bar
				$this->start_controls_section(
					'progress_style',
					[
						'label' => esc_html__( 'Progress Bar', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->start_controls_tabs( 'prog_style' );
					$this->start_controls_tab(
						'progress_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'progress_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-progress-bar' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'progress_hover',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'bar_gradient_background',
							'label' => __( 'Background', 'events-addon-for-elementor' ),
							'types' => [ 'classic', 'gradient' ],
							'selector' => '{{WRAPPER}} .give-progress-bar>span',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
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
							'{{WRAPPER}} .give-btn.give-btn-modal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .give-btn.give-btn-modal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .give-btn.give-btn-modal' => 'min-width:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'btn_typography',
						'selector' => '{{WRAPPER}} .give-btn.give-btn-modal',
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
								'{{WRAPPER}} .give-btn.give-btn-modal' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-btn.give-btn-modal' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-btn.give-btn-modal',
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
								'{{WRAPPER}} .give-btn.give-btn-modal:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .give-btn.give-btn-modal:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .give-btn.give-btn-modal:hover',
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
						'selector' => '{{WRAPPER}} .nacep-give-form input[type="text"],
						{{WRAPPER}} .nacep-give-form input[type="email"],
						{{WRAPPER}} .nacep-give-form input[type="date"],
						{{WRAPPER}} .nacep-give-form input[type="time"],
						{{WRAPPER}} .nacep-give-form input[type="number"],
						{{WRAPPER}} .nacep-give-form textarea,
						{{WRAPPER}} .nacep-give-form select ',
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'form_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-give-form input[type="text"],
						{{WRAPPER}} .nacep-give-form input[type="email"],
						{{WRAPPER}} .nacep-give-form input[type="date"],
						{{WRAPPER}} .nacep-give-form input[type="time"],
						{{WRAPPER}} .nacep-give-form input[type="number"],
						{{WRAPPER}} .nacep-give-form textarea,
						{{WRAPPER}} .nacep-give-form select ',
					]
				);
				$this->add_control(
					'placeholder_text_color',
					[
						'label' => __( 'Placeholder Text Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-give-form input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-give-form input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-give-form input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-give-form input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-give-form textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-give-form textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-give-form textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .nacep-give-form textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->add_control(
					'text_color',
					[
						'label' => __( 'Text Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-give-form input[type="text"],
							{{WRAPPER}} .nacep-give-form input[type="email"],
							{{WRAPPER}} .nacep-give-form input[type="date"],
							{{WRAPPER}} .nacep-give-form input[type="time"],
							{{WRAPPER}} .nacep-give-form input[type="number"],
							{{WRAPPER}} .nacep-give-form textarea,
							{{WRAPPER}} .nacep-give-form select ' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Give Form widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();

			$form_id 				= !empty( $settings['form_id'] ) ? $settings['form_id'] : '';
			$show_title 				= !empty( $settings['show_title'] ) ? $settings['show_title'] : '';
			$show_goal 	= !empty( $settings['show_goal'] ) ? $settings['show_goal'] : '';
			$show_content 	= !empty( $settings['show_content'] ) ? $settings['show_content'] : '';
			$display_style = !empty( $settings['display_style'] ) ? $settings['display_style'] : '';
			$continue_button_title 				= !empty( $settings['continue_button_title'] ) ? $settings['continue_button_title'] : '';

			$show_title = $show_title ? 'true' : 'false';
			$show_goal = $show_goal ? 'true' : 'false';

			$form_id 					   = $form_id ? ' id="'.$form_id.'"' : '';
			$show_title 	 	 = $show_title ? ' show_title="'.$show_title.'"' : '';
			$show_goal 	 		 = $show_goal ? ' show_goal="'.$show_goal.'"' : '';
			$show_content 	 = $show_content ? ' show_content="'.$show_content.'"' : '';
			$display_style   = $display_style ? ' display_style="'.$display_style.'"' : '';
			$continue_button_title 	 		 = $continue_button_title ? ' continue_button_title="'.$continue_button_title.'"' : '';

			$output = '';
	  	$output .= '<div class="nacep-give-form">'.do_shortcode( '[give_form'. $form_id . $show_title . $show_goal . $show_content . $display_style . $continue_button_title .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_GIVE_Form() );
}
