<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'img_seven_section',
	[
		'label'      => esc_html__( 'Image', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_call_to_action',
					'operator' => '==',
					'value'    => 'seven',
				],

			],
		],
	]
);

$this->add_responsive_control(
	'opacity',
	[
		'label'     => __( 'Image Opacity', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'max'  => 1,
				'min'  => 0.10,
				'step' => 0.01,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item .c2a-box .c2a-box-inner .c2a-box-img img' => 'opacity: {{SIZE}};',
		],
	]
);

$this->add_control(
	'img_seven_color',
	[
		'label'     => esc_html__( 'Image Overlay Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item .c2a-box .c2a-box-inner .c2a-box-img:before' => 'background-color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'img_seven_opacity',
	[
		'label'     => __( 'Overlay Color Opacity', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'max'  => 1,
				'min'  => 0.10,
				'step' => 0.01,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item .c2a-box .c2a-box-inner .c2a-box-img:before' => 'opacity: {{SIZE}};',
		],
	]
);

$this->end_controls_section();

