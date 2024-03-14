<?php
/*
 * Elementor Charity Addon for Elementor Give paypal
 * Author & Copyright: NicheAddon
*/
namespace Elementor;

if ( is_plugin_active( 'paypal-donations/paypal-donations.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_GIVE_paypal extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_paypal_donation';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'PayPal', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-button';
		}

		/**
		 * Retrieve the login of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-paypal-category'];
		}

		/**
		 * Register Charitys Addon for Elementor Give paypal widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_donor',
				[
					'label' => esc_html__( 'PayPal Options', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title',
				[
					'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'Enter title.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'text',
				[
					'label' => esc_html__( 'Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'label_block' => true,
					'description' => esc_html__( 'Enter content.', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'purpose',
				[
					'label' => esc_html__( 'Purpose', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
				]
			);
			$this->add_control(
				'reference',
				[
					'label' => esc_html__( 'Reference', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
				]
			);
			$this->add_responsive_control(
				'section_alignment',
				[
					'label' => esc_html__( 'Alignment', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .nacep-paypal-donation' => 'text-align: {{VALUE}};',
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
				$this->add_control(
					'title_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-paypal-donation h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'title_typography',
						'selector' => '{{WRAPPER}} .nacep-paypal-donation h3',
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-paypal-donation h3' => 'color: {{VALUE}};',
						],
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
							'{{WRAPPER}} .nacep-paypal-donation p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .nacep-paypal-donation p',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-paypal-donation p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Give paypal widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			$settings = $this->get_settings_for_display();
			$title 	= !empty( $settings['title'] ) ? $settings['title'] : '';
			$text 	= !empty( $settings['text'] ) ? $settings['text'] : '';
			$purpose 	= !empty( $settings['purpose'] ) ? $settings['purpose'] : '';
			$reference 	= !empty( $settings['reference'] ) ? $settings['reference'] : '';

			$title = $title ? '<h3 class="donation-title">'.$title.'</h3>' : '';
			$text = $text ? '<p>'.$text.'</p>' : '';
			$purpose = $purpose ? ' purpose="'.$purpose.'"' : '';
			$reference = $reference ? ' reference="'.$reference.'"' : '';

	  	$output = '<div class="nacep-paypal-donation">'.$title.$text.do_shortcode( '[paypal-donation'.$purpose.$reference.']' ).'</div>';

		  echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_GIVE_paypal() );
}
