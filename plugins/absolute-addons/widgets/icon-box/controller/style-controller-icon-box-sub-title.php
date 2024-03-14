<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'sub_title_section',
	[
		'label'      => esc_html__( 'Sub Title', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'five',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'twelve',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'fifteen',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'eighteen',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'eleven',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'sub_title_typography',
		'label'    => esc_html__( 'Sub Title Typography', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-sub-title,
		{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-sub-title',
	]
);

$this->add_control(
	'sub_title_color',
	[
		'label'     => esc_html__( 'Sub Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-sub-title' => 'color: {{VALUE}};',
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-sub-title' => 'fill: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'sub_title_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	'sub_title_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
