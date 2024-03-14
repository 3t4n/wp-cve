<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

$this->start_controls_section(
	'multi_color_heading_number_section',
	[
		'label' => __( 'Style Number', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'multi_color_heading_number_typography',
		'label'    => __( 'Number Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'info_box_multi_color_heading_number_circle_background',
		'label'          => esc_html__( 'Number Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Number Background',
			],
		],
		'selector'       => '
		{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number,
		{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number-bg
		',
		'condition'      => [
			'style_variation' => 'two',
		],
	]
);

$this->add_control(
	'multi_color_heading_number_color',
	[
		'label'     => __( 'Number Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number' => 'color: {{VALUE}};',
		],
		'condition' => [
			'style_variation' => 'two',
		],
	]
);

$this->add_responsive_control(
	'info_box_multi_color_heading_number_circle',
	[
		'label'     => __( 'Circle Width', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
		],
		'condition' => [
			'style_variation' => 'two',
		],
	]
);

$this->add_control(
	'multi_color_heading_number_circle_border_color',
	[
		'label'     => __( 'Circle Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number-circle-space::before,
			{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number-circle-space::after
			' => 'background-color: {{VALUE}};',
		],
		'condition' => [
			'style_variation' => 'two',
		],
	]
);

// $this->add_control(
// 	'multi_color_heading_number_circle_border_width',
// 	[
// 		'label'     => __( 'Circle Border Width', 'absolute-addons' ),
// 		'type' => Controls_Manager::SLIDER,
// 		'range' => [
// 			'px' => [
// 				'min' => 0,
// 				'max' => 100,
// 			],
// 		],
// 		'selectors' => [
// 			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number-bg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
// 		],
// 		'condition'=> [
// 			'style_variation' => 'two',
// 		]
// 	]
// );

$this->add_responsive_control(
	'info_box_multi_color_heading_number_circle_border_gap',
	[
		'label'     => __( 'Circle Border Gap', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'%' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number-circle-space::before' => 'margin-right: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number-circle-space::after' => 'margin-left: {{SIZE}}{{UNIT}};',
		],
		'condition' => [
			'style_variation' => 'two',
		],
	]
);

$this->add_control(
	'multi_color_heading_number_gradient_color_1',
	[
		'label'     => __( 'Style Number Gradient Color 1', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '#FF5A64',
		'condition' => [
			'style_variation' => 'one',
		],
	]
);

$this->add_control(
	'multi_color_heading_number_gradient_color_2',
	[
		'label'     => __( 'Style Number Gradient Color 2', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'default'   => '#8C5AFF',
		'condition' => [
			'style_variation' => 'one',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_number_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
