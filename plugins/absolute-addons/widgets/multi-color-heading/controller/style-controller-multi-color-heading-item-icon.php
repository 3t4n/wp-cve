<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'multi_color_heading_icon_section',
	[
		'label'     => __( 'Icon', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'style_variation'                  => 'one',
			'multi_color_heading_icon[value]!' => '',
		],
	]
);

$this->add_control(
	'multi_color_heading_icon_gradient_color_1',
	[
		'label'   => __( 'Icon Gradient Color 1', 'absolute-addons' ),
		'type'    => Controls_Manager::COLOR,
		'default' => '#FF5A64',
	]
);

$this->add_control(
	'multi_color_heading_icon_gradient_color_2',
	[
		'label'   => __( 'Icon Gradient Color 2', 'absolute-addons' ),
		'type'    => Controls_Manager::COLOR,
		'default' => '#8C5AFF',
	]
);

$this->add_responsive_control(
	'info_box_multi_color_heading_icon_size',
	[
		'label'     => __( 'Icon Size', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-svg-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_icon_margin',
	[
		'label'      => esc_html__( 'Icon Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-icon,
			{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-svg-icon
			' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
