<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$this->start_controls_section(
	'counter_section',
	[
		'label'     => esc_html__( 'General Section', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_counter!' => [ 'three' ],
		],
	]
);

$this->start_controls_tabs( 'counter_general_style' );

$this->start_controls_tab(
	'counter_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'counter_wrapper',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper',
	]
);

$this->add_responsive_control(
	'counter_wrapper_box',
	[
		'label'      => esc_html__( 'Counter Wrapper Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1500,
				'step' => 5,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
		],
		'separator'  => 'after',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'counter_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper',
		'default'  => '',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'counter_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper ',
	]
);

$this->add_responsive_control(
	'counter_border_radius',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'separator'  => 'after',
	]
);

$this->add_control(
	'transform_to',
	[
		'label'     => esc_html__( 'Transform Animated', 'absolute-addons' ),
		'type'      => Controls_Manager::SELECT,
		'options'   => [
			'rotate' => esc_html__( 'Rotate', 'absolute-addons' ),
			'scale'  => esc_html__( 'Scale', 'absolute-addons' ),
		],
		'default'   => 'scale',
		'separator' => 'before',
	]
);

$this->add_control(
	'transform_rotate',
	[
		'label'      => esc_html__( 'Transform Rotate', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'deg', '%' ],
		'range'      => [
			'deg' => [
				'min' => 0,
				'max' => 360,
			],
		],
		'default'    => [
			'unit' => 'deg',
			'size' => 0,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper' => 'transform: rotate({{SIZE}}{{UNIT}});',
		],
		'condition'  => [
			'transform_to' => 'rotate',
		],
	]
);

$this->add_control(
	'transform_scale',
	[
		'label'     => esc_html__( 'Transform Scale', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper' => 'transform: scale({{SIZE}});',
		],
		'condition' => [
			'transform_to' => 'scale',
		],
		'separator' => 'after',
	]
);

$this->add_responsive_control(
	'counter_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'counter_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'counter_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'counter_wrapper_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover',
	]
);

$this->add_control(
	'transform_to_hover',
	[
		'label'     => esc_html__( 'Transform Animated', 'absolute-addons' ),
		'type'      => Controls_Manager::SELECT,
		'options'   => [
			'rotate' => esc_html__( 'Rotate', 'absolute-addons' ),
			'skew'   => esc_html__( 'Skew', 'absolute-addons' ),
		],
		'default'   => 'scale',
		'separator' => 'before',
	]
);

$this->add_control(
	'transform_rotate_hover',
	[
		'label'      => esc_html__( 'Transform Rotate', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'deg', 'px' ],
		'range'      => [
			'deg' => [
				'min' => 0,
				'max' => 360,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover' => 'transform: rotate({{SIZE}}{{UNIT}});',
		],
		'condition'  => [
			'transform_to_hover' => 'rotate',
		],
	]
);

$this->add_control(
	'transform_scale_hover',
	[
		'label'     => esc_html__( 'Transform Scale', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover' => 'transform: scale({{SIZE}});',
		],
		'condition' => [
			'transform_to_hover' => 'scale',
		],
		'separator' => 'after',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'counter_box_shadow_hover',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover',
		'default'  => '',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'counter_border_hover',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover',
	]
);

$this->add_responsive_control(
	'counter_border_radius_hover',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'counter_shape_section',
	[
		'label'     => esc_html__( 'Background Shape Color', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_counter' => [ 'four' ],
		],
	]
);

$this->start_controls_tabs( 'counter_shape_tabs' );

$this->start_controls_tab(
	'counter_shape_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_control(
	'counter_shape_color_1',
	[
		'label'     => esc_html__( 'Shape Color 1', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .gradient_color_1' => 'stop-color: {{VALUE}}',
		],
		'default'   => '#fff',
	]
);

$this->add_control(
	'counter_shape_color_2',
	[
		'label'     => esc_html__( 'Shape Color 2', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .gradient_color_2' => 'stop-color: {{VALUE}}',
		],
		'default'   => '#fff',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'counter_shape_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'counter_shape_color_hover_1',
	[
		'label'     => esc_html__( 'Shape Color 1', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .gradient_color_1' => 'stop-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'counter_shape_color_hover_2',
	[
		'label'     => esc_html__( 'Shape Color 2', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .gradient_color_2' => 'stop-color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'counter_title_section',
	[
		'label' => esc_html__( 'Title Style', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'counter_title_tabs' );

$this->start_controls_tab(
	'counter_title_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Title Typography', 'absolute-addons' ),
		'name'     => 'counter_title',
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .count-title',
	]
);

$this->add_control(
	'counter_title_color',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .count-title' => 'color: {{VALUE}}',
			'{{WRAPPER}} .counter-item-four .counter-wrapper .count-title'          => 'fill: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'counter_title_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .count-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_counter' => 'four',
		],
	]
);

$this->add_responsive_control(
	'counter_title_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .count-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_counter' => 'four',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'counter_title_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Title Typography', 'absolute-addons' ),
		'name'     => 'counter_title_hover',
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .count-title',
	]
);

$this->add_control(
	'counter_title_color_hover',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .count-title' => 'color: {{VALUE}}; fill: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'counter_sub_title_section',
	[
		'label'     => esc_html__( 'Sub Title Style', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_counter' => 'four',
		],
	]
);

$this->start_controls_tabs( 'counter_sub_title_tabs' );

$this->start_controls_tab(
	'counter_sub_title_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Sub Title Typography', 'absolute-addons' ),
		'name'     => 'counter_sub_title',
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .counter-sub-title',
	]
);

$this->add_control(
	'counter_sub_title_color',
	[
		'label'     => esc_html__( 'Sub Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .counter-sub-title' => 'fill: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'counter_sub_title_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Sub Title Typography', 'absolute-addons' ),
		'name'     => 'counter_sub_title_hover',
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .counter-sub-title',
	]
);

$this->add_control(
	'counter_sub_title_color_hover',
	[
		'label'     => esc_html__( 'Sub Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .counter-sub-title' => 'fill: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'counter_number_section',
	[
		'label' => esc_html__( 'Counter Number', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'counter_number_tabs' );

$this->start_controls_tab(
	'counter_number_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'counter_number',
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .count-number, {{WRAPPER}} .absp-counter .counter-item .counter-wrapper .percentCount',
	]
);

$this->add_control(
	'counter_number_color',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-number, span)' => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .percentCount' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'counter_number_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .count-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_counter' => 'four',
		],
	]
);

$this->add_responsive_control(
	'counter_number_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .count-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_counter' => 'four',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'counter_number_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'counter_number_hover',
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .count-number',
	]
);

$this->add_control(
	'counter_number_color_hover',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover :is(.count-number, span)' => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .percentCount' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'counter_icon_section',
	[
		'label'     => esc_html__( 'Icon Section', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_counter'    => [ 'one', 'five', 'seven', 'eight', 'nine' ],
			'counter_icon_select' => 'true',
		],
	]
);

$this->start_controls_tabs( 'counter_icon_tabs' );

$this->start_controls_tab(
	'counter_icon_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'counter_icon',
		'selector' => '
		{{WRAPPER}} .absp-counter .counter-item .counter-wrapper
		:is(.count-title, .counter-box, .counter-top-content) i,
		{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i',
	]
);

$this->add_control(
	'counter_icon_width',
	[
		'label'      => esc_html__( 'Icon Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1500,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i' => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i' => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'counter_icon_height',
	[
		'label'      => esc_html__( 'Icon Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1500,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i' => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i' => 'height: {{SIZE}}{{UNIT}};',
		],
		'separator'  => 'after',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'counter_icon_bg',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i, {{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i',
	]
);

$this->add_control(
	'counter_icon_color',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i' => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i' => 'color: {{VALUE}}',
		],
		'separator' => 'after',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'counter_icon_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i, {{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i',
		'default'  => '',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'counter_icon_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i, {{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i',
	]
);

$this->add_responsive_control(
	'counter_icon_border_radius',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'separator'  => 'after',
	]
);

$this->add_responsive_control(
	'counter_icon_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'counter_icon_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper :is(.count-title, .counter-box, .counter-top-content) i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .icon-area  i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'counter_icon_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'counter_icon_bg_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover :is(.count-title, .counter-box, .counter-top-content) i, {{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .icon-area  i',
	]
);

$this->add_control(
	'counter_icon_color_hover',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover :is(.count-title, .counter-box, .counter-top-content) i' => 'color: {{VALUE}}',
			'{{WRAPPER}}{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .icon-area  i' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'counter_icon_box_shadow_hover',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover :is(.count-title, .counter-box,
		.counter-top-content) i,{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .icon-area  i',
		'default'  => '',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'counter_icon_border_hover',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover :is(.count-title, .counter-box, .counter-top-content) i,
		 {{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .icon-area  i',
	]
);

$this->add_responsive_control(
	'counter_icon_border_radius_hover',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover :is(.count-title, .counter-box, .counter-top-content) i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .icon-area  i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'counter_button_section',
	[
		'label'     => esc_html__( 'Button Section', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'counter_link_select!' => 'false',
			'absolute_counter'     => [ 'two', 'eight' ],
		],
	]
);

$this->start_controls_tabs( 'counter_button_tabs' );

$this->start_controls_tab(
	'counter_button_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'counter_button',
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'counter_button_bg',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary',
	]
);

$this->add_control(
	'counter_button_color',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary' => 'color: {{VALUE}}',
		],
		'separator' => 'after',
	]
);

$this->add_control(
	'transform_to_button',
	[
		'label'     => esc_html__( 'Button Animated', 'absolute-addons' ),
		'type'      => Controls_Manager::SELECT,
		'options'   => [
			'rotate' => esc_html__( 'Rotate', 'absolute-addons' ),
			'scale'  => esc_html__( 'Scale', 'absolute-addons' ),
		],
		'default'   => 'scale',
		'separator' => 'before',
	]
);

$this->add_control(
	'transform_button_rotate',
	[
		'label'      => esc_html__( 'Transform Rotate', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'deg', '%' ],
		'range'      => [
			'deg' => [
				'min' => 0,
				'max' => 360,
			],
		],
		'default'    => [
			'unit' => 'deg',
			'size' => 0,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary' => 'transform: rotate({{SIZE}}{{UNIT}});',
		],
		'condition'  => [
			'transform_to_button' => 'rotate',
		],
	]
);

$this->add_control(
	'transform_button_scale',
	[
		'label'     => esc_html__( 'Transform Scale', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary' => 'transform: scale({{SIZE}});',
		],
		'condition' => [
			'transform_to_button' => 'scale',
		],
		'separator' => 'after',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'counter_button_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary',
		'default'  => '',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'counter_button_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary',
	]
);

$this->add_responsive_control(
	'counter_button_border_radius',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'separator'  => 'after',
	]
);

$this->add_responsive_control(
	'counter_button_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'counter_button_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper .btn-primary' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'counter_button_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'counter_button_bg_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .btn-primary',
	]
);

$this->add_control(
	'counter_button_color_hover',
	[
		'label'     => esc_html__( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .btn-primary' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'counter_button_box_shadow_hover',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .btn-primary',
		'default'  => '',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'counter_button_border_hover',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .btn-primary',
	]
);

$this->add_responsive_control(
	'counter_button_border_radius_hover',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'transform_to_button_hover',
	[
		'label'     => esc_html__( 'Button Animated', 'absolute-addons' ),
		'type'      => Controls_Manager::SELECT,
		'options'   => [
			'rotate' => esc_html__( 'Rotate', 'absolute-addons' ),
			'scale'  => esc_html__( 'Scale', 'absolute-addons' ),
		],
		'default'   => 'scale',
		'separator' => 'before',
	]
);

$this->add_control(
	'transform_button_rotate_hover',
	[
		'label'      => esc_html__( 'Transform Rotate', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'deg', '%' ],
		'range'      => [
			'deg' => [
				'min' => 0,
				'max' => 360,
			],
		],
		'default'    => [
			'unit' => 'deg',
			'size' => 0,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .btn-primary' => 'transform: rotate({{SIZE}}{{UNIT}});',
		],
		'condition'  => [
			'transform_to_button_hover' => 'rotate',
		],
	]
);

$this->add_control(
	'transform_button_scale_hover',
	[
		'label'     => esc_html__( 'Transform Scale', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'selectors' => [
			'{{WRAPPER}} .absp-counter .counter-item .counter-wrapper:hover .btn-primary' => 'transform: scale({{SIZE}});',
		],
		'condition' => [
			'transform_to_button_hover' => 'scale',
		],
		'separator' => 'after',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

