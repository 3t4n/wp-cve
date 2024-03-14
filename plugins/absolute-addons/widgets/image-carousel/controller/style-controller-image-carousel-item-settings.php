<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'image_carousel_section_settings',
	[
		'label' => esc_html__( 'Settings', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'image_carousel_section_tabs' );

// Normal State Tab
$this->start_controls_tab(
	'image_carousel_section_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'image_carousel_body_section_background',
		'label'          => esc_html__( 'Body Section Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Body Section Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'image_carousel_body_border',
		'label'    => esc_html__( 'Body Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider',
	]
);

$this->add_responsive_control(
	'image_carousel_body_section_border_radius',
	[
		'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'image_carousel_body_section_box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider',
	]
);

$this->add_responsive_control(
	'image_carousel_body_section_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'image_carousel_body_section_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

// Hover State Tab
$this->start_controls_tab(
	'image_carousel_section_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'image_carousel_body_section_background_hover',
		'label'          => esc_html__( 'Body Section Hover Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Body Section Hover Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider:hover',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'image_carousel_body_border_hover',
		'label'    => esc_html__( 'Body Section Hover Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider:hover',
	]
);

$this->add_responsive_control(
	'image_carousel_body_section_border_radius_hover',
	[
		'label'      => esc_html__( 'Body Section Hover Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'image_carousel_body_section_box_shadow_hover',
		'label'    => esc_html__( 'Body Section Hover Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

