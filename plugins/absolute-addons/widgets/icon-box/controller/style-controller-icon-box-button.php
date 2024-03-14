<?php
defined( 'ABSPATH' ) || exit;

use AbsoluteAddons\Controls\Group_Control_ABSP_Background;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'button_section',
	[
		'label'      => esc_html__( 'Button', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'four',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'seven',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'eight',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'nine',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'sixteen',
				],
			],
		],
		'condition'  => [
			'enable_button' => 'yes',
		],
	]
);

$this->start_controls_tabs( 'button_tabs' );

// Normal State Tab
$this->start_controls_tab(
	'icon_box_btn_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Button Typography', 'absolute-addons' ),
		'name'     => 'button_typography',
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn',
	]
);

$this->add_control(
	'button_color',
	[
		'label'     => esc_html__( 'Button Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn.button-icon:after' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_ABSP_Background::get_type(),
	[
		'name'     => 'button_background',
		'label'    => esc_html__( 'Button Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'button_border',
		'label'    => esc_html__( 'Button Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn',
	]
);

$this->add_responsive_control(
	'button_border_radius',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'button_box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn',
	]
);

$this->add_responsive_control(
	'button_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'button_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

// Hover State Tab
$this->start_controls_tab(
	'icon_box_btn_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'button_color_hover',
	[
		'label'     => esc_html__( 'Button Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-one:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-three:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-three:hover .icon-box .icon-box-content .icon-box-btn.button-icon:after,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-seven:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn:hover.button-icon:after,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_ABSP_Background::get_type(),
	[
		'name'     => 'button_background_hover',
		'label'    => esc_html__( 'Button Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-one:hover .icon-box .icon-box-content .icon-box-btn,
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-three:hover .icon-box .icon-box-content .icon-box-btn,
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-seven:hover .icon-box .icon-box-content .icon-box-btn,
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn:hover',
	]
);

$this->add_control(
	'button_border_color_hover',
	[
		'label'     => esc_html__( 'Button Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-one:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-three:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-seven:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn:hover' => 'border-color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'button_border_radius_hover',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-one:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-three:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-seven:hover .icon-box .icon-box-content .icon-box-btn,
			{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'button_box_shadow_hover',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-one:hover .icon-box .icon-box-content .icon-box-btn,
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-three:hover .icon-box .icon-box-content .icon-box-btn,
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item.element-seven:hover .icon-box .icon-box-content .icon-box-btn,
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-btn:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
