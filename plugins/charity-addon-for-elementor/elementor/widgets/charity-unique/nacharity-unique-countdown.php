<?php
/*
 * Elementor Charity Addon for Elementor Unique Countdown Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Unique_Countdown extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_unique_countdown';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Countdown', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-countdown';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-unique-category'];
		}

		/**
		 * Register Events Addon for Elementor Unique Countdown widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'countdown_date',
				[
					'label' => esc_html__( 'Settings', 'charity-addon-for-elementor' ),
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
				'section_title_style',
				[
					'label' => esc_html__( 'Countdown Title', 'charity-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .nacep-countdown-wrap .countdown-section',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-countdown-wrap .countdown-section' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Countdown widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
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

			$output = '';
			$output .= '<div class="nacep-countdown-wrap'.$sep_class.'">
					          <div class="nacep-countdown '.esc_attr($count_type).'" data-date="'.esc_attr($count_date_actual).'" data-years="'.esc_attr($label_years).'" data-months="'.esc_attr($label_months).'" data-weeks="'.esc_attr($label_weeks).'" data-days="'.esc_attr($label_days).'" data-hours="'.esc_attr($label_hours).'" data-minutes="'.esc_attr($label_minutes).'" data-seconds="'.esc_attr($label_seconds).'" data-year="'.esc_attr($label_year).'" data-month="'.esc_attr($label_month).'" data-week="'.esc_attr($label_week).'" data-day="'.esc_attr($label_day).'" data-hour="'.esc_attr($label_hour).'" data-minute="'.esc_attr($label_minute).'" data-second="'.esc_attr($label_second).'" data-format="'.esc_attr($countdown_format).'"><div class="clearfix"></div>
					          </div>
					        </div>';

			echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Unique_Countdown() );
}
