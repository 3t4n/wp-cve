<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'absp_btn',
	[
		'label' => __( 'Button', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'absp_btn_tabs' );

$this->start_controls_tab(
	'absp_btn_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_btn_typography',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_btn_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-button .absp-btn',
		'default'        => '',
	]
);

$this->add_control(
	'absp_btn_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-button .absp-btn' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_btn_border',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn',
	]
);

$this->add_responsive_control(
	'absp_btn_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-button .absp-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_btn_shadow',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn',
	]
);

$this->add_responsive_control(
	'absp_btn_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-button .absp-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_btn_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-button .absp-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_btn_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_btn_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-button .absp-btn:hover',
		'default'        => '',
	]
);

$this->add_control(
	'absp_btn_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-button .absp-btn:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_btn_border_hover',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn:hover',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_btn_shadow_hover',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn:hover',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_btn_tabs_focus',
	[
		'label' => __( 'Focus', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_background_focus',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-button .absp-btn:focus',
		'default'        => '',
	]
);

$this->add_control(
	'absp_focus_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-button .absp-btn:focus' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_border_focus',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn:focus',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_shadow_focus',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn:focus',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'absp_btn_icon',
	[
		'label'     => __( 'Icon Style', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_button_icons_switch' => 'yes',
		],
	]
);

$this->start_controls_tabs( 'absp_btn_icon_tabs' );

$this->start_controls_tab(
	'absp_btn_icon_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_btn_icon_typography',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn i',
	]
);

$this->add_responsive_control(
	'absp_btn_icon_width',
	[
		'label'      => esc_html__( 'Icon Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', '%' ],
		'range'      => [
			'px'  => [
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			],
			'rem' => [
				'min' => 0,
				'max' => 10,
			],
			'%'   => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-button .absp-btn i' => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_btn_icon_height',
	[
		'label'      => esc_html__( 'Icon Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', '%' ],
		'range'      => [
			'px'  => [
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			],
			'rem' => [
				'min' => 0,
				'max' => 10,
			],
			'%'   => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-button .absp-btn i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_btn_icon_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-button .absp-btn i',
		'default'        => '',
	]
);

$this->add_control(
	'absp_btn_icon_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-button .absp-btn i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_btn_icon_border',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn i',
	]
);

$this->add_responsive_control(
	'absp_btn_icon_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-button .absp-btn i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_btn_icon_shadow',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn i',
	]
);

$this->add_responsive_control(
	'absp_btn_icon_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-button .absp-btn i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_btn_icon_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-button .absp-btn i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_btn_icon_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_btn_icon_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-button .absp-btn:hover i',
		'default'        => '',
	]
);

$this->add_control(
	'absp_btn_icon_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-button .absp-btn:hover i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_btn_icon_border_hover',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn:hover i',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_btn_icon_shadow_hover',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn:hover i',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_btn_icon_focus',
	[
		'label' => __( 'Focus', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_btn_icon_background_focus',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-button .absp-btn:focus i',
		'default'        => '',
	]
);

$this->add_control(
	'absp_btn_icon_focus_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-button .absp-btn:focus i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_btn_icon_border_focus',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn:focus i',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_btn_icon_shadow_focus',
		'selector' => '{{WRAPPER}} .absp-button .absp-btn:focus i',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
