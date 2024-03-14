<?php
/*
 * Elementor Charity Addon for Elementor Urgent Cause
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Unique_Urgent_Cause extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_unique_urgent_cause';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Urgent Cause', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-warning';
		}

		/**
		 * Retrieve the urgent cause of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-unique-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Urgent Cause widget controls.
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
				'section_donor',
				[
					'label' => esc_html__( 'Urgent Cause Options', 'charity-addon-for-elementor' ),
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
				'sub_title',
				[
					'label' => esc_html__( 'Sub Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'Urgent Cause', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type text here', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'need_content',
				[
					'label' => esc_html__( 'Need Content', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'false',
				]
			);
			$this->add_control(
				'need_counter',
				[
					'label' => esc_html__( 'Need Counter', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
					'default' => 'true',
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

			// Circle Progress
				$this->start_controls_section(
					'section_bar',
					[
						'label' => esc_html__( 'Circle Progress Bar', 'charity-addon-for-elementor' ),
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'bar_typography',
						'selector' => '{{WRAPPER}} .circle-progressbar h3.circle-progressbar-counter',
					]
				);
				$this->add_control(
					'bar_txt_color',
					[
						'label' => esc_html__( 'Text Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .circle-progressbar h3.circle-progressbar-counter' => 'color: {{VALUE}};',
						],
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
						'default' => 300,
					]
				);
				$this->end_controls_section();// end: Section

			$this->start_controls_section(
				'countdown_date',
				[
					'label' => esc_html__( 'Countdown Settings', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'count_type',
				[
					'label' => __( 'Countdown Type', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'static' => esc_html__( 'Static Date', 'charity-addon-for-elementor' ),
						'fake'    => esc_html__('Fake Date', 'charity-addon-for-elementor'),
					],
					'default' => 'static',
					'description' => esc_html__( 'Select your countdown date type.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'count_date_static',
				[
					'label' => esc_html__( 'Date & Time', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DATE_TIME,
					'picker_options' => [
						'dateFormat' => 'm/d/Y G:i:S',
						'enableTime' => 'true',
						'enableSeconds' => 'true',
					],
					'placeholder' => esc_html__( 'mm/dd/yyyy hh:mm:ss', 'charity-addon-for-elementor' ),
					'label_block' => true,
					'condition' => [
						'count_type' => 'static',
					],
				]
			);
			$this->add_control(
				'fake_date',
				[
					'label' => esc_html__( 'Fake Date', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( '3', 'charity-addon-for-elementor' ),
					'description' => esc_html__( 'Enter your fake day count here. Ex: 2 or 3(in days)', 'charity-addon-for-elementor' ),
					'condition' => [
						'count_type' => 'fake',
					],
				]
			);

			$this->add_responsive_control(
				'section_max_width',
				[
					'label' => esc_html__( 'Width', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1500,
							'step' => 2,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .nacep-countdown-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'countdown_format',
				[
					'label' => esc_html__( 'Countdown Format', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'dHMS', 'charity-addon-for-elementor' ),
					'description' => __( '<b>For Countdown Format Reference : <a href="http://keith-wood.name/countdown.html" target="_blank">Click Here</a></b>.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'need_separator',
				[
					'label' => esc_html__( 'Need Separator?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->end_controls_section();// end: Section

			$this->start_controls_section(
				'countdown_labels',
				[
					'label' => esc_html__( 'Countdown Labels', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'plural_labels',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Plural Labels</b></div>',
				]
			);

			$this->add_control(
				'label_years',
				[
					'label' => esc_html__( 'Years Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'years', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_months',
				[
					'label' => esc_html__( 'Months Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'months', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_weeks',
				[
					'label' => esc_html__( 'Weeks Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'weeks', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_days',
				[
					'label' => esc_html__( 'Days Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'days', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_hours',
				[
					'label' => esc_html__( 'Hours Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'hours', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_minutes',
				[
					'label' => esc_html__( 'Minutes Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'minutes', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_seconds',
				[
					'label' => esc_html__( 'Seconds Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'seconds', 'charity-addon-for-elementor' ),
				]
			);

			$this->add_control(
				'singular_label',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Singular Labels</b></div>',
				]
			);

			$this->add_control(
				'label_year',
				[
					'label' => esc_html__( 'Year Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'year', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_month',
				[
					'label' => esc_html__( 'Month Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'month', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_week',
				[
					'label' => esc_html__( 'Week Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'week', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_day',
				[
					'label' => esc_html__( 'Day Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'day', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_hour',
				[
					'label' => esc_html__( 'Hour Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'hour', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_minute',
				[
					'label' => esc_html__( 'Minute Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'minute', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'label_second',
				[
					'label' => esc_html__( 'Second Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'second', 'charity-addon-for-elementor' ),
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
							'{{WRAPPER}} .nacep-give-ur-cause' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .nacep-give-ur-cause' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .nacep-give-ur-cause' => 'max-width:{{SIZE}}{{UNIT}};',
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
							'{{WRAPPER}} .nacep-give-ur-cause' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'secn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-give-ur-cause' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-give-ur-cause',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-give-ur-cause',
					]
				);
				$this->end_controls_section();// end: Section

			// Sub Title
				$this->start_controls_section(
					'section_subtitle_style',
					[
						'label' => esc_html__( 'Sub Title', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'subtitle_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-ur-cause-item h5.sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'subtitle_typography',
						'selector' => '{{WRAPPER}} .nacep-ur-cause-item h5.sub-title',
					]
				);
				$this->add_control(
					'subtitle_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-ur-cause-item h5.sub-title' => 'color: {{VALUE}};',
						],
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
						'selector' => '{{WRAPPER}} .nacep-ur-cause-item h3',
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
								'{{WRAPPER}} .nacep-ur-cause-item h3, {{WRAPPER}} .nacep-ur-cause-item h3 a' => 'color: {{VALUE}};',
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
								'{{WRAPPER}} .nacep-ur-cause-item h3 a:hover' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .nacep-ur-cause-item p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .nacep-ur-cause-item p',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-ur-cause-item p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Value
				$this->start_controls_section(
					'section_value_style',
					[
						'label' => esc_html__( 'Countdown Value', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_responsive_control(
					'min_width',
					[
						'label' => esc_html__( 'Width', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 50,
								'max' => 1000,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-countdown-wrap .countdown-section' => 'min-width: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'value_typography',
						'selector' => '{{WRAPPER}} .nacep-countdown-wrap .countdown-section .countdown-amount',
					]
				);
				$this->add_control(
					'value_color',
					[
						'label' => esc_html__( 'Value Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-countdown-wrap .countdown-section .countdown-amount' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'value_bg_color',
					[
						'label' => esc_html__( 'Value Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-countdown-wrap .countdown-section' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'value_box_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-countdown-wrap .countdown-section',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'value_box_shadow',
						'label' => esc_html__( 'Image Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-countdown-wrap .countdown-section',
					]
				);
				$this->add_control(
					'value_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-countdown-wrap .countdown-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'value_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-countdown-wrap .countdown-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Separator
				$this->start_controls_section(
					'section_value_sep_style',
					[
						'label' => esc_html__( 'Countdown Separator', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'value_sep_typography',
						'selector' => '{{WRAPPER}} .countdown-section:after',
					]
				);
				$this->add_control(
					'value_sep_color',
					[
						'label' => esc_html__( 'Separator Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .countdown-section:after' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Title
				$this->start_controls_section(
					'count_title_style',
					[
						'label' => esc_html__( 'Countdown Title', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'count_title_typography',
						'selector' => '{{WRAPPER}} .nacep-countdown-wrap .countdown-section',
					]
				);
				$this->add_control(
					'count_title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-countdown-wrap .countdown-section' => 'color: {{VALUE}};',
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
		 * Render Urgent Cause widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$cause_id 				= !empty( $settings['cause_id'] ) ? $settings['cause_id'] : '';
			$sub_title 				= !empty( $settings['sub_title'] ) ? $settings['sub_title'] : '';
			$need_content 				= !empty( $settings['need_content'] ) ? $settings['need_content'] : '';
			$need_counter 				= !empty( $settings['need_counter'] ) ? $settings['need_counter'] : '';
			$btn_text 				= !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
			$btn_icon         = !empty( $settings['btn_icon'] ) ? $settings['btn_icon'] : '';
			$icon = $btn_icon ? ' <i class="'.$btn_icon.'" aria-hidden="true"></i>' : '';

			$count_type = !empty( $settings['count_type'] ) ? $settings['count_type'] : '';
			$count_date_static = !empty( $settings['count_date_static'] ) ? $settings['count_date_static'] : '';
			$fake_date = !empty( $settings['fake_date'] ) ? $settings['fake_date'] : '';
			$countdown_format = !empty( $settings['countdown_format'] ) ? $settings['countdown_format'] : '';
			$need_separator = !empty( $settings['need_separator'] ) ? $settings['need_separator'] : '';

			// Labels Plural
			$label_years = !empty( $settings['label_years'] ) ? $settings['label_years'] : '';
			$label_months = !empty( $settings['label_months'] ) ? $settings['label_months'] : '';
			$label_weeks = !empty( $settings['label_weeks'] ) ? $settings['label_weeks'] : '';
			$label_days = !empty( $settings['label_days'] ) ? $settings['label_days'] : '';
			$label_hours = !empty( $settings['label_hours'] ) ? $settings['label_hours'] : '';
			$label_minutes = !empty( $settings['label_minutes'] ) ? $settings['label_minutes'] : '';
			$label_seconds = !empty( $settings['label_seconds'] ) ? $settings['label_seconds'] : '';

			$label_years = $label_years ? esc_html($label_years) : esc_html__('Years','charity-addon-for-elementor');
			$label_months = $label_months ? esc_html($label_months) : esc_html__('Months','charity-addon-for-elementor');
			$label_weeks = $label_weeks ? esc_html($label_weeks) : esc_html__('Weeks','charity-addon-for-elementor');
			$label_days = $label_days ? esc_html($label_days) : esc_html__('Days','charity-addon-for-elementor');
			$label_hours = $label_hours ? esc_html($label_hours) : esc_html__('Hours','charity-addon-for-elementor');
			$label_minutes = $label_minutes ? esc_html($label_minutes) : esc_html__('Minutes','charity-addon-for-elementor');
			$label_seconds = $label_seconds ? esc_html($label_seconds) : esc_html__('Seconds','charity-addon-for-elementor');

			// Labels Singular
			$label_year = !empty( $settings['label_year'] ) ? $settings['label_year'] : '';
			$label_month = !empty( $settings['label_month'] ) ? $settings['label_month'] : '';
			$label_week = !empty( $settings['label_week'] ) ? $settings['label_week'] : '';
			$label_day = !empty( $settings['label_day'] ) ? $settings['label_day'] : '';
			$label_hour = !empty( $settings['label_hour'] ) ? $settings['label_hour'] : '';
			$label_minute = !empty( $settings['label_minute'] ) ? $settings['label_minute'] : '';
			$label_second = !empty( $settings['label_second'] ) ? $settings['label_second'] : '';

			$label_year = $label_year ? esc_html($label_year) : esc_html__('Year','charity-addon-for-elementor');
			$label_month = $label_month ? esc_html($label_month) : esc_html__('Month','charity-addon-for-elementor');
			$label_week = $label_week ? esc_html($label_week) : esc_html__('Week','charity-addon-for-elementor');
			$label_day = $label_day ? esc_html($label_day) : esc_html__('Day','charity-addon-for-elementor');
			$label_hour = $label_hour ? esc_html($label_hour) : esc_html__('Hour','charity-addon-for-elementor');
			$label_minute = $label_minute ? esc_html($label_minute) : esc_html__('Minute','charity-addon-for-elementor');
			$label_second = $label_second ? esc_html($label_second) : esc_html__('Second','charity-addon-for-elementor');

			$bar_color 				= !empty( $settings['bar_color'] ) ? $settings['bar_color'] : '';
			$bar_fill_color 	= !empty( $settings['bar_fill_color'] ) ? $settings['bar_fill_color'] : '';
			$reverse 				  = !empty( $settings['reverse'] ) ? $settings['reverse'] : '';
			$size 						= !empty( $settings['size'] ) ? $settings['size'] : '';
			$thickness 						= !empty( $settings['thickness'] ) ? $settings['thickness'] : '';
			$start_angle 						= !empty( $settings['start_angle'] ) ? $settings['start_angle'] : '';

			$bar_color = $bar_color ? ' data-color="'.$bar_color.'"' : '';
			$bar_fill_color = $bar_fill_color ? ' data-fill="'.$bar_fill_color.'"' : '';
			$reverse = $reverse ? ' data-reverse="true"' : ' data-reverse="false"';
			$size = $size ? ' data-size="'.$size.'"' : '';
			$thickness = $thickness ? ' data-thickness="'.$thickness.'"' : '';
			$start_angle = $start_angle ? ' data-start="'.$start_angle.'"' : '';

			$countdown_format = $countdown_format ? $countdown_format : '';

			if ($count_type === 'fake') {
				$count_date_actual = $fake_date;
			} else {
				$count_date_actual = $count_date_static;
			}

			if ($need_separator) {
				$sep_class = ' need-separator';
			} else {
				$sep_class = '';
			}

			// Turn output buffer on
			ob_start();

			$cas_args = array(
	      'post_type' => 'give_forms',
	      'posts_per_page' => 1,
	      'orderby' => 'none',
	      'order' => 'ASC',
	      'post__in' => (array) $cause_id,
	    );

	    $nacep_cas = new \WP_Query( $cas_args );
	    if ($nacep_cas->have_posts()) : while ($nacep_cas->have_posts()) : $nacep_cas->the_post();
	    $form        = new \Give_Donate_Form( get_the_ID() );
			$goal        = $form->goal;
			$income      = $form->get_earnings();

			if ($income && $goal) {
				$progress = round( ( $income / $goal ) * 100 );
			} else {
				$progress = '';
			}
			if ( $income >= $goal ) {
			  $progress = 1;
			} else {
			  $progress = '0.'.$progress;
			}
			$income = give_human_format_large_amount( give_format_amount( $income ) );
			$goal = give_human_format_large_amount( give_format_amount( $goal ) ); ?>

	  	<div class="nacep-give-ur-cause">
				<div class="nacep-ur-cause-item">
					<h5 class="sub-title"><?php echo esc_html($sub_title); ?></h5>
					<h3 class="donation-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
					<?php if ($need_content) { nacharity_excerpt(); } ?>
					<div class="nacep-ur-bar">
						<div class="circle-progressbar-wrap"<?php echo $bar_color . $bar_fill_color . $reverse . $size . $thickness . $start_angle; ?>>
							<div class="circle-progressbar" data-value="<?php echo esc_attr($progress); ?>">
                <h3 class="circle-progressbar-counter"><span class="circle-counter"><?php echo esc_html($progress); ?></span>%</h3>
              </div>
            </div>
					</div>
					<?php 
					if ($need_counter) {
					if ($count_date_static || $fake_date) { ?>
					<div class="nacep-countdown-wrap<?php echo esc_attr($sep_class); ?>">
	          <div class="nacep-countdown <?php echo esc_attr($count_type); ?>" data-date="<?php echo esc_attr($count_date_actual); ?>" data-years="<?php echo esc_attr($label_years); ?>" data-months="<?php echo esc_attr($label_months); ?>" data-weeks="<?php echo esc_attr($label_weeks); ?>" data-days="<?php echo esc_attr($label_days); ?>" data-hours="<?php echo esc_attr($label_hours); ?>" data-minutes="<?php echo esc_attr($label_minutes); ?>" data-seconds="<?php echo esc_attr($label_seconds); ?>" data-year="<?php echo esc_attr($label_year); ?>" data-month="<?php echo esc_attr($label_month); ?>" data-week="<?php echo esc_attr($label_week); ?>" data-day="<?php echo esc_attr($label_day); ?>" data-hour="<?php echo esc_attr($label_hour); ?>" data-minute="<?php echo esc_attr($label_minute); ?>" data-second="<?php echo esc_attr($label_second); ?>" data-format="<?php echo esc_attr($countdown_format); ?>"><div class="clearfix"></div>
	          </div>
	        </div>
					<?php } } ?>
					<div class="nacep-btn-wrap">
	  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="nacep-btn"><?php echo esc_html($btn_text); echo $icon; ?></a>
	  			</div>
				</div>
			</div>

		  <?php
		  endwhile;
	    wp_reset_postdata();
	    endif;
		  // Return outbut buffer
			echo ob_get_clean();

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Unique_Urgent_Cause() );
}
