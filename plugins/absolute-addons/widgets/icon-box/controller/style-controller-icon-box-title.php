<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'title_section',
	[
		'label' => esc_html__( 'Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'title_typography',
		'label'    => esc_html__( 'Title Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-title,
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-content .icon-box-title',
	]
);

$this->add_control(
	'title_color',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-title' => 'color: {{VALUE}};',
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-title' => 'fill: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'title_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '!==',
					'value'    => 'eleven',
				],
			],
		],
	]
);

$this->add_responsive_control(
	'title_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '!==',
					'value'    => 'eleven',
				],
			],
		],
	]
);

$this->end_controls_section();
