<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'multi_color_heading_section_settings',
	[
		'label'     => __( 'Settings', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'style_variation' => 'two',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_section_separator_border_height',
	[
		'label'     => __( 'Section Separator Border Height', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item.element-two .multi-color-heading-item::before,
			{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item.element-two .multi-color-heading-item::after
			' => 'height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_section_separator_border_gap',
	[
		'label'     => __( 'Section Separator Border Gap', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'%' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-item::before' => 'margin-right: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-item::after' => 'margin-left: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'multi_color_heading_section_separator_border_color',
	[
		'label'     => __( 'Section Separator Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item.element-two .multi-color-heading-item::before,
			{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item.element-two .multi-color-heading-item::after
			' => 'background-color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'multi_color_heading_body_section_background',
		'label'          => esc_html__( 'Body Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Body Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-flex-wrapper',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'multi_color_heading_body_border',
		'label'    => __( 'Body Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-flex-wrapper',
	]
);

$this->add_responsive_control(
	'multi_color_heading_body_section_border_radius',
	[
		'label'      => esc_html__( 'Body Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-flex-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'multi_color_heading_body_section_box_shadow',
		'label'    => __( 'Body Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-flex-wrapper',
	]
);

$this->add_responsive_control(
	'multi_color_heading_body_section_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-flex-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_body_section_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-flex-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

