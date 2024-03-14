<?php
/*
 * Elementor Charity Addon for Elementor Simple Cause
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'charitable/charitable.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Unique_Simple_Cause_Charitable extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_unique_single_cause_charitable';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Simple Cause', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-navigation-horizontal';
		}

		/**
		 * Retrieve the simple cause of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-unique-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Simple Cause widget controls.
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
				'section_donor',
				[
					'label' => esc_html__( 'Simple Cause Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'cause_id',
				[
					'label' => __( 'Form', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $CharityID,
					'description' => esc_html__( 'Select a Donation Form.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'goal_title',
				[
					'label' => esc_html__( 'Goal', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Goal Amount', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'income_title',
				[
					'label' => esc_html__( 'Achieved Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Raised', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_text',
				[
					'label' => esc_html__( 'Button Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Donate', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
			  'btn_icon',
			  [
			    'label' => esc_html__( 'Button Icon', 'charity-addon-for-elementor' ),
			    'type' => Controls_Manager::ICON,
			    'options' => NACEP_Controls_Helper_Output::get_include_icons(),
			    'frontend_available' => true,
			    'default' => 'fa fa-long-arrow-right',
			  ]
			);
			$this->end_controls_section();// end: Section

			$this->start_controls_section(
				'section_bar',
				[
					'label' => esc_html__( 'Progress Bar', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'bar_color',
				[
					'label' => esc_html__( 'Progress Bar Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
				]
			);
			$this->add_control(
				'bar_fill_color',
				[
					'label' => esc_html__( 'Progress Bar Fill Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
				]
			);
			$this->add_control(
				'bar_bg_color',
				[
					'label' => esc_html__( 'Progress Bar Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .circle-progressbar canvas' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'reverse',
				[
					'label' => esc_html__( 'Reverse Animation?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_responsive_control(
				'size',
				[
					'label' => esc_html__( 'Canvas Size', 'charity-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 1000,
					'step' => 1,
					'default' => 150,
				]
			);
			$this->add_responsive_control(
				'thickness',
				[
					'label' => esc_html__( 'Thickness', 'charity-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 100,
					'step' => 1,
					'default' => 10,
				]
			);
			$this->add_responsive_control(
				'start_angle',
				[
					'label' => esc_html__( 'Start Angle', 'charity-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 300,
					'step' => 1,
					'default' => 0.1,
				]
			);
			$this->end_controls_section();// end: Section

			// Section
				$this->start_controls_section(
					'sectn_style',
					[
						'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'box_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-give-simple-cause' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'cause_section_margin',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-give-simple-cause' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
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
						'size_units' => [ 'px', '%' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-give-simple-cause' => 'max-width:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'cause_section_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-give-simple-cause' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'secn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-give-simple-cause' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-give-simple-cause',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-give-simple-cause',
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
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'sasstp_title_typography',
						'selector' => '{{WRAPPER}} .nacep-simple-cause-item h3',
					]
				);
				$this->start_controls_tabs( 'title_style' );
					$this->start_controls_tab(
						'title_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'title_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-simple-cause-item h3, {{WRAPPER}} .nacep-simple-cause-item h3 a' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'title_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'title_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-simple-cause-item h3 a:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Content
				$this->start_controls_section(
					'section_content_style',
					[
						'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'content_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-simple-cause-item p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .nacep-simple-cause-item p',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-simple-cause-item p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Progress Bar
				$this->start_controls_section(
					'bar_style',
					[
						'label' => esc_html__( 'Progress Bar', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'bar_title_typography',
						'selector' => '{{WRAPPER}} .nacep-simple-cause-item .nacep-cause-bar h5',
					]
				);
				$this->add_control(
					'bar_title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-simple-cause-item .nacep-cause-bar h5' => 'color: {{VALUE}};',
						],
					]
				);
				$this->start_controls_tabs( 'prog_style' );
					$this->start_controls_tab(
						'prog_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'prog_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-simple-cause-item .nacep-cause-bar' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'section_box_shadow',
							'label' => esc_html__( 'Ripple Color', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-simple-cause-item .nacep-cause-bar span.nacep-cause-ripple, {{WRAPPER}} .nacep-simple-cause-item .nacep-cause-bar span.nacep-cause-ripple:before, {{WRAPPER}} .nacep-simple-cause-item .nacep-cause-bar span.nacep-cause-ripple:after',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'prog_hover',
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
							'selector' => '{{WRAPPER}} .nacep-simple-cause-item .nacep-cause-bar span',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Amounts
				$this->start_controls_section(
					'section_amount_style',
					[
						'label' => esc_html__( 'Amounts', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Amount Title Typography', 'charity-addon-for-elementor' ),
						'name' => 'amount_title_typography',
						'selector' => '{{WRAPPER}} .nacep-simple-cause-item h5 span',
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Amount Typography', 'charity-addon-for-elementor' ),
						'name' => 'amount_typography',
						'selector' => '{{WRAPPER}} .nacep-simple-cause-item h5',
					]
				);
				$this->add_control(
					'amount_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-simple-cause-item h5' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'in_amount_color',
					[
						'label' => esc_html__( 'Raised Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-simple-cause-item h5.income' => 'color: {{VALUE}};',
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
					'btn_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btn_margin',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'btn_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-btn:before, {{WRAPPER}} .nacep-btn:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .nacep-btn:before, {{WRAPPER}} .nacep-btn:after' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'btn_line_height',
					[
						'label' => esc_html__( 'Button Line Height', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .nacep-btn' => 'line-height:{{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'btn_typography',
						'selector' => '{{WRAPPER}} .nacep-btn',
					]
				);
				$this->add_responsive_control(
					'btn_icon_size',
					[
						'label' => esc_html__( 'Icon Size', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1500,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-btn i' => 'font-size:{{SIZE}}{{UNIT}};',
						],
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
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-btn:before',
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
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:hover' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_hover_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:hover i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:hover:before' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-btn:hover:before',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
					$this->start_controls_tab(
						'btn_active',
						[
							'label' => esc_html__( 'Active', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'btn_active_color',
						[
							'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:active, {{WRAPPER}} .nacep-btn:focus' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_icon_active_color',
						[
							'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:active i, {{WRAPPER}} .nacep-btn:focus i' => 'color: {{VALUE}};',
							],
						]
					);
					$this->add_control(
						'btn_bg_active_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-btn:active:after, {{WRAPPER}} .nacep-btn:focus:after' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'btn_active_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-btn:active:after, {{WRAPPER}} .nacep-btn:focus:after',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Simple Cause widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$cause_id 				= !empty( $settings['cause_id'] ) ? $settings['cause_id'] : '';
			$goal_title 				= !empty( $settings['goal_title'] ) ? $settings['goal_title'] : '';
			$income_title 				= !empty( $settings['income_title'] ) ? $settings['income_title'] : '';
			$btn_text 				= !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
			$btn_icon         = !empty( $settings['btn_icon'] ) ? $settings['btn_icon'] : '';
			$icon = $btn_icon ? ' <i class="'.$btn_icon.'" aria-hidden="true"></i>' : '';

			$bar_color 				= !empty( $settings['bar_color'] ) ? $settings['bar_color'] : '';
			$bar_fill_color 	= !empty( $settings['bar_fill_color'] ) ? $settings['bar_fill_color'] : '';
			$reverse 				  = !empty( $settings['reverse'] ) ? $settings['reverse'] : '';
			$size 						= !empty( $settings['size'] ) ? $settings['size'] : '';
			$thickness 				= !empty( $settings['thickness'] ) ? $settings['thickness'] : '';
			$start_angle 			= !empty( $settings['start_angle'] ) ? $settings['start_angle'] : '';

			$bar_color = $bar_color ? ' data-color="'.$bar_color.'"' : '';
			$bar_fill_color = $bar_fill_color ? ' data-fill="'.$bar_fill_color.'"' : '';
			$reverse = $reverse ? ' data-reverse="true"' : ' data-reverse="false"';
			$size = $size ? ' data-size="'.$size.'"' : '';
			$thickness = $thickness ? ' data-thickness="'.$thickness.'"' : '';
			$start_angle = $start_angle ? ' data-start="'.$start_angle.'"' : '';

			$cas_args = array(
	      'post_type' => 'campaign',
	      'posts_per_page' => 1,
	      'orderby' => 'none',
	      'order' => 'ASC',
	      'post__in' => (array) $cause_id,
	    );

			ob_start();
	    $nacep_cas = new \WP_Query( $cas_args );
	    if ($nacep_cas->have_posts()) : while ($nacep_cas->have_posts()) : $nacep_cas->the_post();

			$campaign        = new \Charitable_Campaign( get_the_ID() );
			$currency_helper = charitable_get_currency_helper();
			$income          = $campaign->get_donated_amount();
			$goal            = $campaign->get_meta( '_campaign_goal' );

			if ($income && $goal) {
				$progress = round( ( $income / $goal ) * 100, 2 );
			} else {
				$progress = '';
			}
			if ( $income >= $goal ) {
			  $progressCir = 1;
			} else {
			  $progressCir = '0.'.$progress;
			}
			?>

	  	<div class="nacep-give-simple-cause">
				<div class="nacep-simple-cause-item">
					<div class="col-na-row align-items-center">
						<div class="col-na-4">
							<h3 class="donation-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
							<p class="donation-description"><?php echo esc_html($campaign->description); ?></p>
						</div>
						<div class="col-na-3">
							<div class="circle-progressbar-wrap"<?php echo $bar_color . $bar_fill_color . $reverse . $size . $thickness . $start_angle; ?>>
								<div class="circle-progressbar" data-value="<?php echo esc_attr($progressCir); ?>">
	                <h3 class="circle-progressbar-counter"><span class="circle-counter"><?php echo esc_html($progress); ?></span>%</h3>
	              </div>
              </div>
						</div>
						<div class="col-na-5">
							<div class="col-na-row align-items-center">
								<div class="col-na-6">
									<h5><span><?php echo esc_html($goal_title); ?></span> <?php echo esc_html($currency_helper->get_monetary_amount( $goal )); ?></h5>
									<h5 class="income"><span><?php echo esc_html($income_title); ?></span> <?php echo esc_html($currency_helper->get_monetary_amount( $income )); ?></h5>
								</div>
								<div class="col-na-6">
									<div class="nacep-btn-wrap">
					  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="nacep-btn"><?php echo esc_html($btn_text); echo $icon; ?></a>
					  			</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		  <?php
		  endwhile;
	    endif;
	    wp_reset_postdata();
		  // Return outbut buffer
			echo ob_get_clean();

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Unique_Simple_Cause_Charitable() );
}
