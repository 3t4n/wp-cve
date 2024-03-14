<?php
/*
 * Elementor Charity Addon for Elementor PayPal Button
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'easy-paypal-donation/easy-paypal-donation.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_PayPal_Button extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_paypal_button';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'PayPal Button', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-button';
		}

		/**
		 * Retrieve the totals of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-paypal-category'];
		}

		/**
		 * Register Charitys Addon for Elementor PayPal Button widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$Button = get_posts( 'post_type="wpplugin_don_button"&numberposts=-1' );
	    $ButtonID = array();
	    if ( $Button ) {
	      foreach ( $Button as $form ) {
	        $ButtonID[ $form->ID ] = $form->post_title;
	      }
	    } else {
	      $ButtonID[ __( 'No Button\'s found', 'charity-addon-for-elementor' ) ] = 0;
	    }

			$this->start_controls_section(
				'section_btn',
				[
					'label' => esc_html__( 'Button Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'id',
				[
					'label' => __( 'Select a Donation Button', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => [],
					'options' => $ButtonID,
					'description' => esc_html__( 'You must first select a Button!', 'charity-addon-for-elementor' ),
				]
			);
			$this->end_controls_section();// end: Section

		}

		/**
		 * Render PayPal Button widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$id 				= !empty( $settings['id'] ) ? $settings['id'] : '';

			$id 					 = $id ? ' id="'.$id.'"' : '';

	  	$output = '<div class="nacep-paypal-btn">'.do_shortcode( '[wpedon'. $id .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_PayPal_Button() );
}
