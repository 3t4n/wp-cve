<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'content_card_sale_price_section',
	[
		'label'      => esc_html__( 'Sale Price', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'seven',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'nine',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'content_card_sale_price_typography',
		'label'    => esc_html__( 'Sale Price Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-img .content-card-box-sale-price',
	]
);

$this->add_control(
	'content_card_sale_price_color',
	[
		'label'     => esc_html__( 'Sale Price Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-img .content-card-box-sale-price' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'                         => 'content_card_sale_price_background',
		'label'                        => esc_html__( 'Sale Price Background', 'absolute-addons' ),
		'content_card_sale_priceblock' => true,
		'types'                        => [ 'classic', 'gradient' ],
		'fields_options'               => [
			'background' => [
				'label' => 'Sale Price Background',
			],
		],
		'selector'                     => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-img .content-card-box-sale-price',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'content_card_sale_price_border',
		'label'    => esc_html__( 'Sale Price Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-img .content-card-box-sale-price',
	]
);

$this->add_responsive_control(
	'content_card_sale_price_border_radius',
	[
		'label'      => esc_html__( 'Sale Price  Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-img .content-card-box-sale-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'content_card_sale_price_box_shadow',
		'label'    => esc_html__( 'Sale Price Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-img .content-card-box-sale-price',
	]
);

$this->add_responsive_control(
	'content_card_sale_price_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-img .content-card-box-sale-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'content_card_sale_price_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-img .content-card-box-sale-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
