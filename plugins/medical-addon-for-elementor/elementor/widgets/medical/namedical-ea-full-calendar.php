<?php
/*
 * Elementor Medical Addon for Elementor FullCalendar Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( is_plugin_active( 'easy-appointments/main.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Medical_Elementor_Addon_EA_FullCalendar extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'namedical_ea_fullcalendar';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Full Calendar', 'medical-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-calendar';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['namedical-ea-category'];
		}

		/**
		 * Register Medical Addon for Elementor FullCalendar widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function register_controls(){

			$this->start_controls_section(
				'section_fullcalendar',
				[
					'label' => esc_html__( 'FullCalendar Options', 'medical-addon-for-elementor' ),
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
		 * Render FullCalendar widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$location 						= !empty( $settings['location'] ) ? $settings['location'] : '';
			$service 							= !empty( $settings['service'] ) ? $settings['service'] : '';
			$worker 							= !empty( $settings['worker'] ) ? $settings['worker'] : '';

			$location = $location ? ' location="'.esc_attr($location).'"' : '';
			$service = $service ? ' service="'.esc_attr($service).'"' : '';
			$worker = $worker ? ' worker="'.esc_attr($worker).'"' : '';
			
			// Starts
			$output  = '<div class="namep-fullcalendar"><div class="namep-fullcalendar-form">';
			$output .= do_shortcode( '[ea_full_calendar'. $location . $service . $worker .']' );
			$output .= '</div></div>';

			echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_EA_FullCalendar() );
}