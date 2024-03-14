<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'product_grid_category_section',
	[
		'label'     => esc_html__( 'Product Category', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'product_grid_category' => 'yes',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'product_grid_category_typography',
		'label'    => esc_html__( 'Category Typography', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category li a,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category li:before
		',
	]
);

$this->add_control(
	'product_grid_category_color',
	[
		'label'     => esc_html__( 'Category Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category li a,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category li:before
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'product_grid_category_hover_color',
	[
		'label'     => esc_html__( 'Category Text Hover Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category li a:hover
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'product_grid_category_background',
	[
		'label'     => esc_html__( 'Category Background', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category
			' => 'background-color: {{VALUE}};',
		],

	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'product_grid_category_border',
		'label'    => esc_html__( 'Category Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category',
	]
);

$this->add_responsive_control(
	'product_grid_category_border_radius',
	[
		'label'      => esc_html__( 'Category  Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'product_grid_category_box_shadow',
		'label'    => esc_html__( 'Category Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category',
	]
);

$this->add_responsive_control(
	'product_grid_category_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_category_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
