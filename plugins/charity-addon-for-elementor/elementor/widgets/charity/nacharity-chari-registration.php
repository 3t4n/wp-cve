<?php
/*
 * Elementor Charity Addon for Elementor Charitable Registration
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'charitable/charitable.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Charitable_Registration extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_charitable_registration';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Registration', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-plus-circle-o';
		}

		/**
		 * Retrieve the registration of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-charitable-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Charitable Registration widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_donor',
				[
					'label' => esc_html__( 'Registration Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'logged_in_message',
				[
					'label' => esc_html__( 'Logged In Message', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'label_block' => true,
					'description' => esc_html__( 'You can optionally set the message that will be displayed to users when they are already logged in.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'login_link_text',
				[
					'label' => esc_html__( 'Registration Link Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'You can also customize the text used for the link to the registration page.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'redirect',
				[
					'label' => esc_html__( 'Redirect', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'Enter redirect link.', 'charity-addon-for-elementor' ),
				]
			);
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
							'{{WRAPPER}} .nacep-charitable-registration p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .nacep-charitable-registration p',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-charitable-registration p' => 'color: {{VALUE}} !important;',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Link
				$this->start_controls_section(
					'section_link_style',
					[
						'label' => esc_html__( 'Link', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'link_typography',
						'selector' => '{{WRAPPER}} .nacep-charitable-registration a',
					]
				);
				$this->start_controls_tabs( 'link_style' );
					$this->start_controls_tab(
						'link_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'link_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-charitable-registration a' => 'color: {{VALUE}} !important;',
							],
						]
					);
					$this->end_controls_tab();  // end:Normal tab
					$this->start_controls_tab(
						'link_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'link_hover_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-charitable-registration a:hover' => 'color: {{VALUE}} !important;',
							],
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Charitable Registration widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$logged_in_message 	= !empty( $settings['logged_in_message'] ) ? $settings['logged_in_message'] : '';
			$login_link_text 	= !empty( $settings['login_link_text'] ) ? $settings['login_link_text'] : '';
			$redirect 	= !empty( $settings['redirect'] ) ? $settings['redirect'] : '';

			$logged_in_message = $logged_in_message ? ' logged_in_message="'.$logged_in_message.'"' : '';
			$login_link_text = $login_link_text ? ' login_link_text="'.$login_link_text.'"' : '';
			$redirect = $redirect ? ' redirect="'.$redirect.'"' : '';

	  	$output = '<div class="nacep-charitable-registration nacep-form">'.do_shortcode( '[charitable_registration'. $logged_in_message . $login_link_text . $redirect .']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Charitable_Registration() );
}
