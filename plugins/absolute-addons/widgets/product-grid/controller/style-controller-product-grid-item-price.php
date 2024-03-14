<?php
defined('ABSPATH') || exit;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
$this->start_controls_section(
	'product_grid_price_tab_settings',
	[
		'label'     => esc_html__( 'Product Price', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'product_grid_price' => 'yes',
		],
	]
);

$this->start_controls_tabs(
	'product_grid_price_tabs'
);

//Product Carousel Regular Price tab
$this->start_controls_tab(
	'product_grid_regular_price_tab',
	[
		'label' => esc_html__( 'Reqular price', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'product_grid_regular_price_typography',
		'label'    => esc_html__( 'Regular Price Typography', 'absolute-addons' ),
		'selector' => '
        {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price del .woocommerce-Price-amount bdi,
        {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price del
        ',
	]
);

$this->add_control(
	'product_grid_regular_price_color',
	[
		'label'     => esc_html__( 'Regular Price Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price del .woocommerce-Price-amount bdi,
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price del
            ' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_regular_price_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price del .woocommerce-Price-amount bdi,
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price > .woocommerce-Price-amount bdi
            ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_regular_price_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price del .woocommerce-Price-amount bdi,
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price > .woocommerce-Price-amount bdi
            ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

//Product Carousel Sale Price tab
$this->start_controls_tab(
	'product_grid_sale_price_tab',
	[
		'label' => esc_html__( 'Sale price', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'product_grid_sale_price_typography',
		'label'    => esc_html__( 'Sale Price Typography', 'absolute-addons' ),
		'selector' => '
        {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price ins .woocommerce-Price-amount bdi,
        {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price > .woocommerce-Price-amount bdi
        ',
	]
);

$this->add_control(
	'product_grid_sale_price_color',
	[
		'label'     => esc_html__( 'Sale Price Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price ins .woocommerce-Price-amount bdi,
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price > .woocommerce-Price-amount bdi
            ' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_sale_price_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price ins .woocommerce-Price-amount bdi,
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price > .woocommerce-Price-amount bdi
            ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_sale_price_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price ins .woocommerce-Price-amount bdi,
            {{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-product-price > .woocommerce-Price-amount bdi
            ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
