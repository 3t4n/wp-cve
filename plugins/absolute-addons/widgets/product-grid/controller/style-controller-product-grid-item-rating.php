<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'product_grid_rating_section',
	[
		'label'     => esc_html__( 'Product Rating', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'product_grid_rating' => 'yes',
		],
	]
);

$this->add_control(
	'product_grid_rating_color',
	[
		'label'     => esc_html__( 'Rating Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .star-rating span::before' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'product_grid_blank_rating_color',
	[
		'label'     => esc_html__( 'Blank Rating Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .star-rating::before' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'product_grid_rating_count_color',
	[
		'label'     => esc_html__( 'Rating Count Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-ratting-count' => 'color: {{VALUE}};',
		],
		'condition' => [
			'product_grid_rating_count' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'product_grid_rating_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-ratting' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_rating_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-ratting' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
