<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'multi_color_heading_title_section',
	[
		'label' => __( 'Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'multi_color_heading_title_typography',
		'label'    => __( 'Title Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-title',
	]
);

$this->add_control(
	'multi_color_heading_title_color',
	[
		'label'     => __( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-title' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'multi_color_heading_title_border_color',
	[
		'label'     => __( 'Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-title-border' => 'background-color: {{VALUE}};',
		],
		'condition' => [
			'style_variation' => 'one',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_title_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'style_variation' => 'one',
		],
	]
);

$this->add_responsive_control(
	'multi_color_heading_title_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'style_variation' => 'one',
		],
	]
);

$this->end_controls_section();
