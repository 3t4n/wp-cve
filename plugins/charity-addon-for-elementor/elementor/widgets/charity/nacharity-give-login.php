<?php
/*
 * Elementor Charity Addon for Elementor Give Login
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'give/give.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_GIVE_Login extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_give_login';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Login', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-lock-user';
		}

		/**
		 * Retrieve the login of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-give-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Give Login widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_donor',
				[
					'label' => esc_html__( 'Login Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'login_redirect',
				[
					'label' => esc_html__( 'Login Redirect URL (optional)', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'Enter an URL here to redirect to after login.', 'charity-addon-for-elementor' ),
				]
			);
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render Give Login widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$login_redirect 	= !empty( $settings['login_redirect'] ) ? $settings['login_redirect'] : '';

			$login_redirect = $login_redirect ? ' login-redirect="'.$login_redirect.'"' : '';

	  	$output = '<div class="nacep-give-login">'.do_shortcode( '[give_login'. $login_redirect .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_GIVE_Login() );
}
