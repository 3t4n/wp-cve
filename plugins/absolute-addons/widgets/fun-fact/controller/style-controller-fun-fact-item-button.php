<?php
defined( 'ABSPATH' ) || exit;

use AbsoluteAddons\Controls\Group_Control_ABSP_Background;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'fun_fact_button_section',
	[
		'label'      => esc_html__( 'Button', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_fun_fact',
					'operator' => '==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_fun_fact',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_fun_fact',
					'operator' => '==',
					'value'    => 'four',
				],
				[
					'name'     => 'absolute_fun_fact',
					'operator' => '==',
					'value'    => 'six',
				],
				[
					'name'     => 'absolute_fun_fact',
					'operator' => '==',
					'value'    => 'nine',
				],
				[
					'name'     => 'absolute_fun_fact',
					'operator' => '==',
					'value'    => 'ten',
				],
			],
		],
		'condition'  => [
			'enable_button' => 'yes',
		],
	]
);

$this->start_controls_tabs( 'fun_fact_button_tabs' );

// Normal State Tab
$this->start_controls_tab(
	'fun_fact_button_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Button Typography', 'absolute-addons' ),
		'name'     => 'fun_fact_button_typography',
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn',
	]
);

$this->add_control(
	'fun_fact_button_color',
	[
		'label'     => esc_html__( 'Button Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_ABSP_Background::get_type(),
	[
		'name'     => 'fun_fact_button_background',
		'label'    => esc_html__( 'Button Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'fun_fact_button_border',
		'label'    => esc_html__( 'Button Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn',
	]
);

$this->add_responsive_control(
	'fun_fact_button_border_radius',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'fun_fact_button_box_shadow',
		'label'    => esc_html__( 'Button Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn',
	]
);

$this->add_responsive_control(
	'fun_fact_button_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'fun_fact_button_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

// Hover State Tab
$this->start_controls_tab(
	'fun_fact_button_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'fun_fact_button_color_hover',
	[
		'label'     => esc_html__( 'Button Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_ABSP_Background::get_type(),
	[
		'name'     => 'fun_fact_button_background_hover',
		'label'    => esc_html__( 'Button Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn:hover',
	]
);

$this->add_control(
	'fun_fact_button_border_color_hover',
	[
		'label'     => esc_html__( 'Button Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn:hover' => 'border-color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'fun_fact_button_border_radius_hover',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'fun_fact_button_box_shadow_hover',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-btn:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

