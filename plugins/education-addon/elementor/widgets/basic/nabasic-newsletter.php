<?php
/*
 * Elementor Education Addon Newsletter Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_newsletter'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Newsletter extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_newsletter';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Newsletter Form', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-mailchimp';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Newsletter widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_newsletter',
			[
				'label' => esc_html__( 'Newsletter Options', 'education-addon' ),
			]
		);
		$this->add_control(
			'newsletter_style',
			[
				'label' => esc_html__( 'Newsletter Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_control(
			'form_id',
			[
				'label' => esc_html__( 'Form Short Code', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '[mc4wp_form id="942"]', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'section_box_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_width',
				[
					'label' => esc_html__( 'Section Width', 'education-addon' ),
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
						'{{WRAPPER}} .newsletter-info' => 'max-width:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .newsletter-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .newsletter-info' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'section_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .newsletter-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'section_box_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .newsletter-info',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Image Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .newsletter-info',
				]
			);
			$this->end_controls_section();// end: Section

		// Form
			$this->start_controls_section(
				'section_form_style',
				[
					'label' => esc_html__( 'Form', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'input_margin',
				[
					'label' => __( 'Text Field Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'form_padding',
				[
					'label' => __( 'Form Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Input Typography', 'education-addon' ),
					'name' => 'input_typography',
					'selector' => '{{WRAPPER}} .naedu-newsletter input[type="text"],
					{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input,
					{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input::-webkit-input-placeholder,
					{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input::-moz-placeholder,
					{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input::-ms-input-placeholder,
					{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input::-o-placeholder',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'form_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input',
				]
			);
			$this->add_control(
				'placeholder_text_color',
				[
					'label' => __( 'Placeholder Text Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input::-moz-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input::-o-placeholder' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => __( 'Text Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input' => 'color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'form_bg_color',
				[
					'label' => __( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input' => 'background-color: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'form_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_button_style',
				[
					'label' => esc_html__( 'Button', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'selector' => '{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]',
				]
			);
			$this->add_responsive_control(
				'btn_width',
				[
					'label' => esc_html__( 'Width', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'button_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'button_style' );
				$this->start_controls_tab(
					'button_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'button_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'button_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'button_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'button_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'button_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-newsletter .mc4wp-form-fields input[type="submit"]:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Newsletter widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$newsletter_style = !empty( $settings['newsletter_style'] ) ? $settings['newsletter_style'] : '';
		$form_id = !empty( $settings['form_id'] ) ? $settings['form_id'] : '';

		if ($newsletter_style === 'two') {
			$style_cls = ' newsletter-style-two';
		} else {
			$style_cls = '';
		}

		// Starts
			$output = '<div class="naedu-newsletter naedu-form'.$style_cls.'">
								  <div class="newsletter-info">'.do_shortcode($form_id).'</div>
							  </div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Newsletter() );

} // enable & disable