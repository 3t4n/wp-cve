<?php
/*
 * Elementor Charity Addon for Elementor Subscribe Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Charity_Elementor_Addon_Subscribe extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'nacharity_basic_subscribe';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Subscribe / Contact', 'charity-addon-for-elementor' );
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
		return ['nacharity-basic-category'];
	}

	/**
	 * Register Charity Addon for Elementor Subscribe widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_subscribe',
			[
				'label' => esc_html__( 'Subscribe Options', 'charity-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'subscribe_title',
			[
				'label' => esc_html__( 'Title Text', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Sign up to our newsletter', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'subscribe_content',
			[
				'label' => esc_html__( 'Content Text', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Sign up to receive news and updates. Each week well send you a summary of the latest articles. Keep an eye on your inbox!', 'charity-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type content text here', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'subscribe_form',
			[
				'label' => esc_html__( 'Subscribe Form', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( '[mc4wp_form id="40"]', 'charity-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Section Alignment', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-subscribe' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Section
		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'section_width',
			[
				'label' => esc_html__( 'Section Width', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-form-wrap' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'section_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-form-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'section_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-form-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'section_border_radius',
			[
				'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-form-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'section_box_border',
				'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .nacep-form-wrap',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'section_box_shadow',
				'label' => esc_html__( 'Image Box Shadow', 'charity-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .nacep-form-wrap',
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
					'{{WRAPPER}} .nacep-subscribe h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'sasstp_title_typography',
				'selector' => '{{WRAPPER}} .nacep-subscribe h3',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-subscribe h3' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .nacep-subscribe p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .nacep-subscribe p',
			]
		);
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-subscribe p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Form
		$this->start_controls_section(
			'section_form_style',
			[
				'label' => esc_html__( 'Form', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'form_section_width',
			[
				'label' => esc_html__( 'Form Width', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-subscribe-form' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_typography',
				'selector' => '{{WRAPPER}} .nacep-subscribe input[type="text"],
				{{WRAPPER}} .nacep-subscribe input[type="email"],
				{{WRAPPER}} .nacep-subscribe input[type="date"],
				{{WRAPPER}} .nacep-subscribe input[type="time"],
				{{WRAPPER}} .nacep-subscribe input[type="number"],
				{{WRAPPER}} .nacep-subscribe textarea,
				{{WRAPPER}} .nacep-subscribe select,
				{{WRAPPER}} .nacep-subscribe .form-control,
				{{WRAPPER}} .nacep-subscribe .nice-select',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'form_border',
				'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .nacep-subscribe input[type="text"],
				{{WRAPPER}} .nacep-subscribe input[type="email"],
				{{WRAPPER}} .nacep-subscribe input[type="date"],
				{{WRAPPER}} .nacep-subscribe input[type="time"],
				{{WRAPPER}} .nacep-subscribe input[type="number"],
				{{WRAPPER}} .nacep-subscribe textarea,
				{{WRAPPER}} .nacep-subscribe select,
				{{WRAPPER}} .nacep-subscribe .form-control,
				{{WRAPPER}} .nacep-subscribe .nice-select',
			]
		);
		$this->add_control(
			'placeholder_text_color',
			[
				'label' => __( 'Placeholder Text Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-subscribe input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .nacep-subscribe input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .nacep-subscribe input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .nacep-subscribe input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .nacep-subscribe textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .nacep-subscribe textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .nacep-subscribe textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .nacep-subscribe textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .nacep-subscribe input[type="text"],
					{{WRAPPER}} .nacep-subscribe input[type="email"],
					{{WRAPPER}} .nacep-subscribe input[type="date"],
					{{WRAPPER}} .nacep-subscribe input[type="time"],
					{{WRAPPER}} .nacep-subscribe input[type="number"],
					{{WRAPPER}} .nacep-subscribe textarea,
					{{WRAPPER}} .nacep-subscribe select,
					{{WRAPPER}} .nacep-subscribe .form-control,
					{{WRAPPER}} .nacep-subscribe .nice-select' => 'color: {{VALUE}} !important;',
				],
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .nacep-subscribe input[type="submit"]',
			]
		);
		$this->add_responsive_control(
			'btn_width',
			[
				'label' => esc_html__( 'Width', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-subscribe input[type="submit"]' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'btn_margin',
			[
				'label' => __( 'Margin', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-subscribe input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-subscribe input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'button_style' );
			$this->start_controls_tab(
				'button_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'button_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-subscribe input[type="submit"]' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-subscribe input[type="submit"]' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-subscribe input[type="submit"]',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'button_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'button_hover_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-subscribe input[type="submit"]:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-subscribe input[type="submit"]:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_hover_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-subscribe input[type="submit"]:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Subscribe widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$subscribe_title = !empty( $settings['subscribe_title'] ) ? $settings['subscribe_title'] : '';
		$subscribe_content = !empty( $settings['subscribe_content'] ) ? $settings['subscribe_content'] : '';
		$subscribe_form = !empty( $settings['subscribe_form'] ) ? $settings['subscribe_form'] : '';

		$title = $subscribe_title ? '<h3 class="subscribe-title">'.esc_html($subscribe_title).'</h3>' : '';
		$content = $subscribe_content ? '<p>'.esc_html($subscribe_content).'</p>' : '';

		// Starts
		$output  = '<div class="nacep-subscribe nacep-form"><div class="nacep-form-wrap">'.$title.$content.'<div class="nacep-subscribe-form">';
		$output .= do_shortcode( $subscribe_form );
		$output .= '</div></div></div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Subscribe() );
