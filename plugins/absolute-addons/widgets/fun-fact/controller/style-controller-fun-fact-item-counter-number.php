<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'fun_fact_counter_number_section',
	[
		'label' => esc_html__( 'Counter Number', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'fun_fact_counter_number_typography',
		'label'    => esc_html__( 'Counter Number Typography', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-number,
		{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-number-suffix
		',
	]
);

$this->add_control(
	'fun_fact_counter_number_color',
	[
		'label'     => esc_html__( 'Counter Number Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-number,
			{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-number-suffix' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'fun_fact_counter_number_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-number-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'fun_fact_counter_number_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-number-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

