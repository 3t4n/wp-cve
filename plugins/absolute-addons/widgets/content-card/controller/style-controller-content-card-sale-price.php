<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'sale_price_section',
	[
		'label'      => esc_html__( 'Sale Price', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'four',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'seven',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'nine',
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
		'name'     => 'sale_price_typography',
		'label'    => esc_html__( 'Sale Price Typography', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price,
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price-title
		',
	]
);

$this->add_control(
	'sale_price_color',
	[
		'label'     => esc_html__( 'Sale Price Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price,
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price,
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price-title
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'sale_price_separator_color',
	[
		'label'     => esc_html__( 'Sale Price Separator Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price::before,
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price::after
			' => 'background-color: {{VALUE}};',
		],
		'condition' => [
			'absolute_content_card' => 'six',
		],
	]
);

$this->add_responsive_control(
	'sale_price_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price,
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price-title
			' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'sale_price_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price,
			{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box span.content-card-box-sale-price-title
			' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

