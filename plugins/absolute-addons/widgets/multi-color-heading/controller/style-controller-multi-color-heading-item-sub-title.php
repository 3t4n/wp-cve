<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'multi_color_heading_sub_title_section',
	[
		'label' => __( 'Sub Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'multi_color_heading_sub_title_typography',
		'label'    => __( 'Sub Title Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-sub-title',
	]
);

$this->add_control(
	'multi_color_heading_sub_title_color',
	[
		'label'     => __( 'Sub Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-sub-title' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'multi_color_heading_sub_title_border_color',
	[
		'label'     => __( 'Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-sub-title-border' => 'background-color: {{VALUE}};',
		],
		'condition' => [
			'style_variation' => 'one',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_sub_title_border_width',
	[
		'label'           => __( 'Border Width', 'absolute-addons' ),
		'type'            => Controls_Manager::SLIDER,
		'size_units'      => [ '%', 'px' ],
		'range'           => [
			'px' => [
				'max' => 1000,
			],
		],
		'default'         => [
			'size' => 85,
			'unit' => '%',
		],
		'tablet_default'  => [
			'unit' => '%',
		],
		'mobile_default'  => [
			'unit' => '%',
		],
		'selectors'       => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-sub-title-border' => 'width: {{SIZE}}{{UNIT}};',
		],
		'style_variation' => 'one',
	]
);

$this->add_responsive_control(
	'multi_color_heading_sub_title_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'style_variation' => 'one',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_sub_title_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'style_variation' => 'one',
		],
	]
);

$this->end_controls_section();
