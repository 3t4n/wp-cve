<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'content_section',
	[
		'label'      => esc_html__( 'Content', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_call_to_action',
					'operator' => '!=',
					'value'    => 'twelve',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'content_typography',
		'label'    => esc_html__( 'Content Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-call-to-action-item .c2a-box .c2a-box-content p',
	]
);

$this->add_control(
	'content_color',
	[
		'label'     => esc_html__( 'Content Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item .c2a-box .c2a-box-content' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'content_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item .c2a-box .c2a-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'content_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item .c2a-box .c2a-box-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

