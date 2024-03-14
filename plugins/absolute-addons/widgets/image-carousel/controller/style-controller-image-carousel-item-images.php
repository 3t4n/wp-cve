<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'image_carousel_images_section_settings',
	[
		'label' => esc_html__( 'Images', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'image_carousel_images_section_background',
		'label'          => esc_html__( 'Images Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Images Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'image_carousel_images_border',
		'label'    => esc_html__( 'Images Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider',
	]
);

$this->add_responsive_control(
	'image_carousel_images_section_border_radius',
	[
		'label'      => esc_html__( 'Images  Border Radius', 'absolute-addons' ),
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
		'name'     => 'image_carousel_images_section_box_shadow',
		'label'    => esc_html__( 'Images Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider',
	]
);

$this->add_responsive_control(
	'image_carousel_images_section_padding',
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
	'image_carousel_images_section_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
