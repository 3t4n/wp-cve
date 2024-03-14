<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'product_grid_label_section',
	[
		'label'     => esc_html__( 'Product Label', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'product_grid_label' => 'yes',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'product_grid_label_typography',
		'label'    => esc_html__( 'Label Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label',
	]
);

$this->add_control(
	'product_grid_label_color',
	[
		'label'     => esc_html__( 'Label Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'product_grid_label_background',
	[
		'label'     => esc_html__( 'Label Background', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label' => 'background-color: {{VALUE}};',
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label.left::before' => 'border-top-color: {{VALUE}};',
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label.left::after' => 'border-bottom-color: {{VALUE}};',
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label.right::before' => 'border-top-color: {{VALUE}};',
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label.right::after' => 'border-bottom-color: {{VALUE}};',
		],

	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'product_grid_label_border',
		'label'    => esc_html__( 'Label Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label',
	]
);

$this->add_responsive_control(
	'product_grid_label_border_radius',
	[
		'label'      => esc_html__( 'Label  Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'product_grid_label_box_shadow',
		'label'    => esc_html__( 'Label Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label',
	]
);

$this->add_responsive_control(
	'product_grid_label_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_label_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
