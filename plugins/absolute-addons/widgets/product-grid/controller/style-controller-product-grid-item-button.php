<?php
defined( 'ABSPATH' ) || exit;

use AbsoluteAddons\Controls\Group_Control_ABSP_Background;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'product_grid_btn_section',
	[
		'label' => esc_html__( 'Product Button', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'product_grid_btn_tabs' );

// Normal State Tab
$this->start_controls_tab(
	'product_grid_btn_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Button Typography', 'absolute-addons' ),
		'name'     => 'product_grid_btn_typography',
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button
		',
	]
);

$this->add_control(
	'product_grid_btn_color',
	[
		'label'     => esc_html__( 'Button Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_ABSP_Background::get_type(),
	[
		'name'     => 'product_grid_btn_background',
		'label'    => esc_html__( 'Button Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button
		',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'product_grid_btn_border',
		'label'    => esc_html__( 'Button Border', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button
		',
	]
);

$this->add_responsive_control(
	'product_grid_btn_border_radius',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-carousel-item-cart-btn,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button
			' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'product_grid_btn_box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button
		',
	]
);

$this->add_responsive_control(
	'product_grid_btn_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button
			' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_btn_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button
			' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

// Hover State Tab
$this->start_controls_tab(
	'product_grid_btn_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'product_grid_btn_color_hover',
	[
		'label'     => esc_html__( 'Button Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button:hover::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple:hover::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped:hover::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external:hover::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable:hover::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward:hover::before,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button:hover
			' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_ABSP_Background::get_type(),
	[
		'name'     => 'product_grid_btn_background_hover',
		'label'    => esc_html__( 'Button Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button:hover

		',
	]
);

$this->add_control(
	'product_grid_btn_border_color_hover',
	[
		'label'     => esc_html__( 'Button Border Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button:hover
			' => 'border-color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'product_grid_btn_border_radius_hover',
	[
		'label'      => esc_html__( 'Button Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-carousel-item-cart-btn:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward:hover,
			{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button:hover
			' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'product_grid_btn_box_shadow_hover',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.add_to_cart_button:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_simple:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_grouped:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_external:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .button.product_type_variable:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .added_to_cart.wc-forward:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-button-inner .product-grid-item-cart-btn .button:hover
		',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
