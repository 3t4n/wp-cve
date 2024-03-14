<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'fun_fact_section_settings',
	[
		'label' => esc_html__( 'Settings', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'fun_fact_section_tabs' );

// Normal State Tab
$this->start_controls_tab(
	'fun_fact_section_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'fun_fact_body_section_background',
		'label'          => esc_html__( 'Body Section Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Body Section Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'fun_fact_body_border',
		'label'    => esc_html__( 'Body Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item',
	]
);

$this->add_responsive_control(
	'fun_fact_body_section_border_radius',
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'fun_fact_body_section_box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item',
	]
);

$this->add_responsive_control(
	'fun_fact_body_section_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'fun_fact_body_section_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

// State Tab
$this->start_controls_tab(
	'fun_fact_section_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'fun_fact_hover_animation',
	[
		'label'        => esc_html__( 'Body Section Animation', 'absolute-addons' ),
		'type'         => Controls_Manager::HOVER_ANIMATION,
		'prefix_class' => 'elementor-animation-',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'fun_fact_body_section_background_hover',
		'label'          => esc_html__( 'Body Section Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Body Section Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'fun_fact_body_border_hover',
		'label'    => esc_html__( 'Body Section Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover',
	]
);

$this->add_responsive_control(
	'fun_fact_body_section_border_radius_hover',
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'fun_fact_body_section_box_shadow_hover',
		'label'    => esc_html__( 'Body Section Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover',
	]
);

$this->add_control(
	'fun_fact_hover_counter',
	[
		'label'     => esc_html__( 'Counter Number Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover .fun-fact-number,
			{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover .fun-fact-number-suffix,
			{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover .fun-fact-icon
			' => 'color: {{VALUE}};',
		],
		'condition' => [
			'absolute_fun_fact' => 'four',
		],
	]
);

$this->add_control(
	'fun_fact_body_section_button_color_hover',
	[
		'label'     => esc_html__( 'Button Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover .fun-fact-btn' => 'color: {{VALUE}};',
		],
		'condition' => [
			'absolute_fun_fact' => 'four',
		],
	]
);

$this->add_group_control(
		Group_Control_Background::get_type(),
		[
			'name'           => 'fun_fact_body_section_button_background_hover',
			'label'          => esc_html__( 'Button Background', 'absolute-addons' ),
			'fields_options' => [
				'background' => [
					'label' => 'Button Background',
				],
			],
			'selector'       => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover .fun-fact-btn',
			'condition'      => [
				'absolute_fun_fact' => 'four',
			],
		]
);

$this->add_control(
	'fun_fact_body_section_button_border_color_hover',
	[
		'label'     => esc_html__( 'Button Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover .fun-fact-btn' => 'border-color: {{VALUE}};',
		],
		'condition' => [
			'absolute_fun_fact' => 'four',
		],
	]
);

$this->add_responsive_control(
	'fun_fact_body_section_button_border_radius_hover',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover .fun-fact-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_fun_fact' => 'four',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'      => 'fun_fact_body_section_button_box_shadow_hover',
		'label'     => esc_html__( 'Button Box Shadow', 'absolute-addons' ),
		'selector'  => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item:hover .fun-fact-btn',
		'condition' => [
			'absolute_fun_fact' => 'four',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

