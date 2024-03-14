<?php
/*
 * Elementor Medical Addon for Elementor Appointment Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( is_plugin_active( 'easy-appointments/main.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Medical_Elementor_Addon_EA_Appointment extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'namedical_ea_appointment';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Appointment', 'medical-addon-for-elementor' );
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
			return ['namedical-ea-category'];
		}

		/**
		 * Register Medical Addon for Elementor Appointment widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function register_controls(){

			$this->start_controls_section(
				'section_appointment',
				[
					'label' => esc_html__( 'Appointment Options', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_responsive_control(
				'width',
				[
					'label' => esc_html__( 'Form Width', 'medical-addon-for-elementor' ),
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
				]
			);
			$this->add_control(
				'layout_cols',
				[
					'label' => __( 'Layout Column', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'1'       => esc_html__( 'One', 'give' ),
						'2'   		=> esc_html__( 'Two', 'give' ),
					],
					'default' => '1',
				]
			);
			$this->add_control(
				'location',
				[
					'label' => esc_html__( 'Location', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'predefined location, value {id number of location}, default value null', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'service',
				[
					'label' => esc_html__( 'Service', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'predefined service, value {id number of service}, default value null', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'worker',
				[
					'label' => esc_html__( 'Worker', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'predefined worker, value {id number of worker}, default value null', 'medical-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'rtl',
				[
					'label' => esc_html__( 'RTL?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'default_date',
				[
					'label' => esc_html__( 'Default Date ', 'event-elementor-addon' ),
					'type' => Controls_Manager::DATE_TIME,
					'picker_options' => [
						'dateFormat' => 'Y-m-d',
						'enableTime' => 'false',
					],
					'placeholder' => esc_html__( 'YYYY-MM-DD', 'event-elementor-addon' ),
					'label_block' => true,
					'description' => __( 'Set default date on calendar that will be selected when customer opens the form. Default value is current date.', 'event-elementor-addon' ),
				]
			);
			$this->add_control(
				'min_date',
				[
					'label' => esc_html__( 'Min Date ', 'event-elementor-addon' ),
					'type' => Controls_Manager::DATE_TIME,
					'picker_options' => [
						'dateFormat' => 'Y-m-d',
						'enableTime' => 'false',
					],
					'placeholder' => esc_html__( 'YYYY-MM-DD', 'event-elementor-addon' ),
					'label_block' => true,
					'description' => __( 'Set min date on calendar that can be selected during booking process by customer.', 'event-elementor-addon' ),
				]
			);
			$this->add_control(
				'max_date',
				[
					'label' => esc_html__( 'Max Date ', 'event-elementor-addon' ),
					'type' => Controls_Manager::DATE_TIME,
					'picker_options' => [
						'dateFormat' => 'Y-m-d',
						'enableTime' => 'false',
					],
					'placeholder' => esc_html__( 'YYYY-MM-DD', 'event-elementor-addon' ),
					'label_block' => true,
					'description' => __( 'Set max date on calendar that can be selected during booking process by customer.', 'event-elementor-addon' ),
				]
			);
			$this->add_control(
				'show_remaining_slots',
				[
					'label' => esc_html__( 'Remaining Slots?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'save_form_content',
				[
					'label' => esc_html__( 'Save Form Content?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'show_week',
				[
					'label' => esc_html__( 'Show Week?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Hide', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'Show', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Appointment widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$width 								= !empty( $settings['width']['size'] ) ? $settings['width']['size'] : '';
			$unit 								= !empty( $settings['width']['unit'] ) ? $settings['width']['unit'] : '';
			$layout_cols 					= !empty( $settings['layout_cols'] ) ? $settings['layout_cols'] : '';
			$location 						= !empty( $settings['location'] ) ? $settings['location'] : '';
			$service 							= !empty( $settings['service'] ) ? $settings['service'] : '';
			$worker 							= !empty( $settings['worker'] ) ? $settings['worker'] : '';
			$rtl 									= !empty( $settings['rtl'] ) ? $settings['rtl'] : '';
			$default_date 				= !empty( $settings['default_date'] ) ? $settings['default_date'] : '';
			$min_date 						= !empty( $settings['min_date'] ) ? $settings['min_date'] : '';
			$max_date 						= !empty( $settings['max_date'] ) ? $settings['max_date'] : '';
			$show_remaining_slots = !empty( $settings['show_remaining_slots'] ) ? $settings['show_remaining_slots'] : '';
			$save_form_content 		= !empty( $settings['save_form_content'] ) ? $settings['save_form_content'] : '';
			$show_week 						= !empty( $settings['show_week'] ) ? $settings['show_week'] : '';

			$rtl = $rtl ? '1' : '0';
			$show_remaining_slots  = $show_remaining_slots ? '1' : '0';
			$save_form_content  = $save_form_content ? '1' : '0';
			$show_week  = $show_week ? '1' : '0';

			$width = $width ? ' width="'.esc_attr($width.$unit).'"' : '';
			$layout_cols = $layout_cols ? ' layout_cols="'.esc_attr($layout_cols).'"' : '';
			$location = $location ? ' location="'.esc_attr($location).'"' : '';
			$service = $service ? ' service="'.esc_attr($service).'"' : '';
			$worker = $worker ? ' worker="'.esc_attr($worker).'"' : '';
			$rtl = $rtl ? ' rtl="'.esc_attr($rtl).'"' : '';
			$default_date = $default_date ? ' default_date="'.esc_attr($default_date).'"' : '';
			$min_date = $min_date ? ' min_date="'.esc_attr($min_date).'"' : '';
			$max_date = $max_date ? ' max_date="'.esc_attr($max_date).'"' : '';
			$show_remaining_slots = $show_remaining_slots ? ' show_remaining_slots="'.esc_attr($show_remaining_slots).'"' : '';
			$show_week = $show_week ? ' show_week="'.esc_attr($show_week).'"' : '';

			// Starts
			$output  = '<div class="namep-appointment namep-form"><div class="namep-appointment-form">';
			$output .= do_shortcode( '[ea_bootstrap'. $width . $layout_cols . $location . $service . $worker . $rtl . $default_date . $min_date . $max_date . $show_remaining_slots . $show_week . ']' );
			$output .= '</div></div>';

			echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_EA_Appointment() );
}