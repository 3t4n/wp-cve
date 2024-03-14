<?php
/*
 * Elementor Medical Addon for Elementor Appointment Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( is_plugin_active( 'bookly-responsive-appointment-booking-tool/main.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Medical_Elementor_Addon_Bookly_Appointment extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'namedical_bookly_appointment';
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
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Appointment widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();

			// Starts
			$output  = '<div class="namep-bookly-appointment">'.do_shortcode( '[bookly-form]' ).'</div>';

			echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Bookly_Appointment() );
}