<?php
/*
 * Elementor Medical Addon for Elementor Subscribe Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Subscribe extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_basic_subscribe';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Subscribe / Contact', 'medical-addon-for-elementor' );
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
		return ['namedical-basic-category'];
	}

	/**
	 * Register Medical Addon for Elementor Subscribe widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_subscribe',
			[
				'label' => esc_html__( 'Subscribe Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'subscribe_title',
			[
				'label' => esc_html__( 'Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Sign up to our newsletter', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'subscribe_content',
			[
				'label' => esc_html__( 'Content Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Sign up to receive news and updates. Each week well send you a summary of the latest articles. Keep an eye on your inbox!', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type content text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'subscribe_form',
			[
				'label' => esc_html__( 'Subscribe Form', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( '[mc4wp_form id="40"]', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Section Alignment', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Section
		$this->start_controls_section(
			'section_box_style',
			[
				'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'section_width',
			[
				'label' => esc_html__( 'Section Width', 'medical-addon-for-elementor' ),
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
					'{{WRAPPER}} .namep-form-wrap' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'section_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-form-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'section_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .namep-form-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'section_border_radius',
			[
				'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-form-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'section_box_border',
				'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .namep-form-wrap',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'section_box_shadow',
				'label' => esc_html__( 'Image Box Shadow', 'medical-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .namep-form-wrap',
			]
		);
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'sasstp_title_typography',
				'selector' => '{{WRAPPER}} .namep-subscribe h3',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe h3' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .namep-subscribe p',
			]
		);
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Form
		$this->start_controls_section(
			'section_form_style',
			[
				'label' => esc_html__( 'Form', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'form_section_width',
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
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe-form' => 'max-width:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_typography',
				'selector' => '{{WRAPPER}} .namep-subscribe input[type="text"],
				{{WRAPPER}} .namep-subscribe input[type="email"],
				{{WRAPPER}} .namep-subscribe input[type="date"],
				{{WRAPPER}} .namep-subscribe input[type="time"],
				{{WRAPPER}} .namep-subscribe input[type="number"],
				{{WRAPPER}} .namep-subscribe textarea,
				{{WRAPPER}} .namep-subscribe select,
				{{WRAPPER}} .namep-subscribe .form-control,
				{{WRAPPER}} .namep-subscribe .nice-select',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'form_border',
				'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .namep-subscribe input[type="text"],
				{{WRAPPER}} .namep-subscribe input[type="email"],
				{{WRAPPER}} .namep-subscribe input[type="date"],
				{{WRAPPER}} .namep-subscribe input[type="time"],
				{{WRAPPER}} .namep-subscribe input[type="number"],
				{{WRAPPER}} .namep-subscribe textarea,
				{{WRAPPER}} .namep-subscribe select,
				{{WRAPPER}} .namep-subscribe .form-control,
				{{WRAPPER}} .namep-subscribe .nice-select',
			]
		);
		$this->add_control(
			'placeholder_text_color',
			[
				'label' => __( 'Placeholder Text Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe input:not([type="submit"])::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .namep-subscribe input:not([type="submit"])::-moz-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .namep-subscribe input:not([type="submit"])::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .namep-subscribe input:not([type="submit"])::-o-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .namep-subscribe textarea::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .namep-subscribe textarea::-moz-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .namep-subscribe textarea::-ms-input-placeholder' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .namep-subscribe textarea::-o-placeholder' => 'color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe input[type="text"],
					{{WRAPPER}} .namep-subscribe input[type="email"],
					{{WRAPPER}} .namep-subscribe input[type="date"],
					{{WRAPPER}} .namep-subscribe input[type="time"],
					{{WRAPPER}} .namep-subscribe input[type="number"],
					{{WRAPPER}} .namep-subscribe textarea,
					{{WRAPPER}} .namep-subscribe select,
					{{WRAPPER}} .namep-subscribe .form-control,
					{{WRAPPER}} .namep-subscribe .nice-select' => 'color: {{VALUE}} !important;',
				],
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .namep-subscribe input[type="submit"]',
			]
		);
		$this->add_responsive_control(
			'btn_width',
			[
				'label' => esc_html__( 'Width', 'medical-addon-for-elementor' ),
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
					'{{WRAPPER}} .namep-subscribe input[type="submit"]' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'btn_margin',
			[
				'label' => __( 'Margin', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-subscribe input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'button_style' );
			$this->start_controls_tab(
				'button_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'button_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-subscribe input[type="submit"]' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-subscribe input[type="submit"]' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-subscribe input[type="submit"]',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'button_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'button_hover_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-subscribe input[type="submit"]:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-subscribe input[type="submit"]:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'button_hover_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-subscribe input[type="submit"]:hover',
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

		$title = $subscribe_title ? '<h3>'.esc_html($subscribe_title).'</h3>' : '';
		$content = $subscribe_content ? '<p>'.esc_html($subscribe_content).'</p>' : '';

		// Starts
		$output  = '<div class="namep-subscribe namep-form"><div class="namep-form-wrap">'.$title.$content.'<div class="namep-subscribe-form">';
		$output .= do_shortcode( $subscribe_form );
		$output .= '</div></div></div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Subscribe() );
