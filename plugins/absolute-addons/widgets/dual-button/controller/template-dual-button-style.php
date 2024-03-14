<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'absp_dual',
	[
		'label' => __( 'Primary Button', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'absp_dual_tabs' );

$this->start_controls_tab(
	'absp_dual_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_dual_typography',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_dual_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary',
		'default'        => '',
	]
);

$this->add_control(
	'absp_dual_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_dual_border',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary',
	]
);

$this->add_responsive_control(
	'absp_dual_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_dual_shadow',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary',
	]
);

$this->add_responsive_control(
	'absp_dual_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_dual_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_dual_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_dual_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:hover',
		'default'        => '',
	]
);

$this->add_control(
	'absp_dual_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_dual_border_hover',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:hover',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_dual_shadow_hover',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:hover',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_dual_icon_tabs_focus',
	[
		'label' => __( 'Focus', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_dual_background_focus',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:focus',
		'default'        => '',
	]
);

$this->add_control(
	'absp_dual_focus_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:focus' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_dual_border_focus',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:focus',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_dual_shadow_focus',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:focus',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'absp_dual_icon',
	[
		'label'     => __( 'Primary Icon', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_dual_button_icons_switch' => 'yes',
		],
	]
);

$this->start_controls_tabs( 'absp_dual_icon_tabs' );

$this->start_controls_tab(
	'absp_dual_icon_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_dual_icon_typography',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i',
	]
);

$this->add_responsive_control(
	'absp_dual_icon_width',
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
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i' => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_dual_icon_height',
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
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_dual_icon_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i',
		'default'        => '',
	]
);

$this->add_control(
	'absp_dual_icon_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_dual_icon_border',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i',
	]
);

$this->add_responsive_control(
	'absp_dual_icon_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_absp_dual_icon_shadow',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i',
	]
);

$this->add_responsive_control(
	'absp_dual_icon_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_dual_icon_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_absp_dual_icon_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_dual_icon_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:hover i',
		'default'        => '',
	]
);

$this->add_control(
	'absp_dual_icon_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:hover i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_dual_icon_border_hover',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:hover i',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_dual_icon_shadow_hover',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary:hover i',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'absp_secondary_dual_btn',
	[
		'label' => __( 'Secondary Button', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'absp_secondary_dual_tabs' );

$this->start_controls_tab(
	'absp_secondary_dual_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_secondary_dual_typography',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_secondary_dual_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary',
		'default'        => '',
	]
);

$this->add_control(
	'absp_secondary_dual_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_secondary_dual_border',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary',
	]
);

$this->add_responsive_control(
	'absp_secondary_dual_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_secondary_dual_shadow',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary',
	]
);

$this->add_responsive_control(
	'absp_secondary_dual_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_secondary_dual_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_secondary_dual_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_secondary_dual_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:hover',
		'default'        => '',
	]
);

$this->add_control(
	'absp_secondary_dual_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_secondary_dual_border_hover',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:hover',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_secondary_dual_shadow_hover',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:hover',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_secondary_dual_icon_tabs_focus',
	[
		'label' => __( 'Focus', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_secondary_dual_background_focus',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:focus',
		'default'        => '',
	]
);

$this->add_control(
	'absp_secondary_dual_focus_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:focus' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_secondary_dual_border_focus',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:focus',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_secondary_dual_shadow_focus',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:focus',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'absp_secondary_dual_icon',
	[
		'label'     => __( 'Secondary Icon', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_dual_button_icons_switch_secondary' => 'yes',
		],
	]
);

$this->start_controls_tabs( 'absp_secondary_dual_icon_tabs' );

$this->start_controls_tab(
	'absp_secondary_dual_icon_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_secondary_dual_icon_typography',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i',
	]
);

$this->add_responsive_control(
	'absp_secondary_dual_icon_width',
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
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i' => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_secondary_dual_icon_height',
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
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_secondary_dual_icon_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i',
		'default'        => '',
	]
);

$this->add_control(
	'absp_secondary_dual_icon_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_secondary_dual_icon_border',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i',
	]
);

$this->add_responsive_control(
	'absp_secondary_dual_icon_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_secondary_dual_icon_shadow',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i',
	]
);

$this->add_responsive_control(
	'absp_secondary_dual_icon_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_secondary_dual_icon_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_secondary_dual_icon_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_secondary_dual_icon_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:hover i',
		'default'        => '',
	]
);

$this->add_control(
	'absp_secondary_dual_icon_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:hover i' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_secondary_dual_icon_border_hover',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:hover i',
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_secondary_dual_icon_shadow_hover',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary:hover i',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'absp_dual_btn_connector',
	[
		'label'     => __( 'Connector', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_dual_button_connector_switch!' => 'yes',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_dual_btn_connector_typography',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn-connector',
	]
);

$this->add_responsive_control(
	'absp_dual_btn_connector_width',
	[
		'label'      => esc_html__( 'Width', 'absolute-addons' ),
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
			'{{WRAPPER}} .absp-dual-button .absp-btn-connector' => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_dual_btn_connector_height',
	[
		'label'      => esc_html__( 'Height', 'absolute-addons' ),
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
			'{{WRAPPER}} .absp-dual-button .absp-btn-connector' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_dual_btn_connector_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-dual-button .absp-btn-connector',
		'default'        => '',
	]
);

$this->add_control(
	'absp_dual_btn_connector_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-dual-button .absp-btn-connector' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'absp_dual_btn_connector_border',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn-connector',
	]
);

$this->add_responsive_control(
	'absp_dual_btn_connector_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn-connector' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_dual_btn_connector_shadow',
		'selector' => '{{WRAPPER}} .absp-dual-button .absp-btn-connector',
	]
);

$this->add_responsive_control(
	'absp_dual_btn_connector_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-dual-button .absp-btn-connector' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

