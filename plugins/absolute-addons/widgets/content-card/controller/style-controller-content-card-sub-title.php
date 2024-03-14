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
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'seven',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'eight',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'nine',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'eleven',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'twelve',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'thirteen',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'fourteen',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'sixteen',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'seventeen',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'eighteen',
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
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sub-title',
	]
);

$this->add_control(
	'sub_title_color',
	[
		'label'     => esc_html__( 'Sub Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sub-title
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'sub_title_highlighter_color',
	[
		'label'      => esc_html__( 'Sub Title Highlighter Color', 'absolute-addons' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sub-title u,
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sub-title b
			' => 'color: {{VALUE}};',
		],
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'fifteen',
				],
			],
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
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

