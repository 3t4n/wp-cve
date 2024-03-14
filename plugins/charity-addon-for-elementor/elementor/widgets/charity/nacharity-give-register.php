<?php
/*
 * Elementor Charity Addon for Elementor Give Register
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_GIVE_Register extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_give_register';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Register', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-check-circle';
		}

		/**
		 * Retrieve the register of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-give-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Give Register widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_donor',
				[
					'label' => esc_html__( 'Register Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'redirect',
				[
					'label' => esc_html__( 'Register Redirect URL (optional)', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'Enter an URL here to redirect to after register.', 'charity-addon-for-elementor' ),
				]
			);
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Give Register widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$redirect 	= !empty( $settings['redirect'] ) ? $settings['redirect'] : '';

			$redirect = $redirect ? ' redirect="'.$redirect.'"' : '';

	  	$output = '<div class="nacep-give-register">'.do_shortcode( '[give_register'. $redirect .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_GIVE_Register() );
}
