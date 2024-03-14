<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'subscription_section',
	[
		'label'      => esc_html__( 'Subcription Form', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'condition'  => [
			'enable_subs_form' => 'yes',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'three',
				],
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'four',
				],
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'eight',
				],
				[
					'name'     => 'absolute_countdown',
					'operator' => '!==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'form_title_typography',
		'label'    => esc_html__( 'Form Title Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-title',
	]
);

$this->add_control(
	'subscription_form_title_color',
	[
		'label'     => esc_html__( 'Form Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-title' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'subscription_form_box_shadow',
		'label'    => esc_html__( 'Input Form Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-inline-form',
	]
);

$this->add_control(
	'subscriptions_submit_button',
	[
		'label'     => esc_html__( 'Submit Button', 'absolute-addons' ),
		'type'      => Controls_Manager::HEADING,
		'separator' => 'before',
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'subscription_btn_typography',
		'label'    => esc_html__( 'Submit Button Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-submit-btn',
	]
);

$this->add_control(
	'subscription_btn_color',
	[
		'label'     => esc_html__( 'Submit Button Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-submit-btn' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'subscription_btn_background',
		'label'          => esc_html__( 'Submit Button Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Submit Button Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-submit-btn',
	]
);

$this->add_control(
	'subscription_btn_color_hover',
	[
		'label'     => esc_html__( 'Hover Submit Button Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-submit-btn:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'subscription_btn_background_hover',
		'label'          => esc_html__( 'Hover Submit Button Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Hover Submit Button Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-submit-btn:hover',
	]
);

$this->add_control(
	'subscriptions_input_option',
	[
		'label'     => esc_html__( 'Input Field', 'absolute-addons' ),
		'type'      => Controls_Manager::HEADING,
		'separator' => 'before',
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'subscription_input_field_typography',
		'label'    => esc_html__( 'Input Field Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-email-input-field',
	]
);

$this->add_control(
	'subscription_input_field_color',
	[
		'label'     => esc_html__( 'Input Field Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-email-input-field
			' => 'color: {{VALUE}} !important;',
		],
	]
);

$this->add_control(
	'subscription_input_field_placeholder_color',
	[
		'label'     => esc_html__( 'Input Field Placeholder Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-email-input-field::placeholder
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'subscription_input_field_background',
	[
		'label'     => esc_html__( 'Input Field Background', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-email-input-field
			' => 'background-color: {{VALUE}} !important;',
		],
	]
);

$this->add_responsive_control(
	'subscription_input_field_border_radius',
	[
		'label'      => esc_html__( 'Input Field Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-email-input-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'subscriptions_input_width',
	[
		'label'      => __( 'Input Field Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 250,
				'max' => 1000,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-subscribe-email-input-field' => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'subscription_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'separator'  => 'before',
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-email-subscribe' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'subscription_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-email-subscribe' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

