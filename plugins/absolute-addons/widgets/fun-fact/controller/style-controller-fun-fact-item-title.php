<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

$this->start_controls_section(
	'fun_fact_title_section',
	[
		'label' => esc_html__( 'Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'fun_fact_title_typography',
		'label'    => esc_html__( 'Title Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-title',
	]
);

$this->add_control(
	'fun_fact_title_color',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-title' => 'color: {{VALUE}};',
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-title::before' => 'border-color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'fun_fact_title_border_color',
	[
		'name'      => 'fun_fact_title_border',
		'label'     => esc_html__( 'Title Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-title' => 'border-color: {{VALUE}};',
		],
		'condition' => [
			'absolute_fun_fact' => 'ten',
		],
	]
);

$this->add_responsive_control(
	'fun_fact_title_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'fun_fact_title_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
