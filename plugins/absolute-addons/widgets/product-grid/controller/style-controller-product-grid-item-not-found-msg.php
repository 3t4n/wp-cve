<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'product_grid_not_found_msg_section',
	[
		'label' => esc_html__( 'Product Not Found Message', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'product_grid_not_found_msg_typography',
		'label'    => esc_html__( 'Product Not Found Message Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-not-found-msg',
	]
);

$this->add_control(
	'product_grid_not_found_msg_color',
	[
		'label'     => esc_html__( 'Product Not Found Message Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-not-found-msg' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'product_grid_not_found_msg_background',
	[
		'label'     => esc_html__( 'Product Not Found Message Background', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-not-found-msg
			' => 'background-color: {{VALUE}};',
		],

	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'product_grid_not_found_msg_border',
		'label'    => esc_html__( 'Product Not Found Message Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-not-found-msg',
	]
);

$this->add_responsive_control(
	'product_grid_not_found_msg_border_radius',
	[
		'label'      => esc_html__( 'Product Not Found Message  Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-not-found-msg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'product_grid_not_found_msg_box_shadow',
		'label'    => esc_html__( 'Product Not Found Message Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-not-found-msg',
	]
);

$this->add_responsive_control(
	'product_grid_not_found_msg_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-not-found-msg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_not_found_msg_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-not-found-msg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
