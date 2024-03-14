<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;


$this->start_controls_section(
	'product_grid_1_section_settings',
	[
		'label'     => esc_html__( 'Product Body Section', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_product_grid' => 'one',
		],
	]
);

$this->start_controls_tabs( 'product_grid_1_section_tabs' );

// Normal State Tab
$this->start_controls_tab(
	'product_grid_1_section_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'product_grid_1_body_section_background',
		'label'          => esc_html__( 'Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Background',
			],
		],
		'selector'       => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-hover
		',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'product_grid_1_body_border',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item-hover
		',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'product_grid_1_body_section_box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item
		',
	]
);

$this->add_responsive_control(
	'product_grid_1_body_section_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

// Hover State Tab
$this->start_controls_tab(
	'product_grid_1_section_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'product_grid_1_body_section_background_hover',
		'label'          => esc_html__( 'Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Background',
			],
		],
		'selector'       => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner:hover .product-grid-item-hover
		',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'product_grid_1_body_border_hover',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item:hover,
		{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner:hover .product-grid-item-hover
		',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'product_grid_1_body_section_box_shadow_hover',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-product-grid-item .product-grid-item-inner .product-grid-item:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

