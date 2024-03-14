<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

$this->start_controls_section(
	'image_carousel_img_overlay_settings',
	[
		'label' => esc_html__( 'Image Overlay', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'image_carousel_img_overlay_background',
		'label'          => esc_html__( 'Image Overlay Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Image Overlay Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider:before',
	]
);

$this->add_control(
	'image_carousel_img_opacity',
	[
		'label'     => esc_html__( 'Opacity', 'absolute-addons' ),
		'type'      => Controls_Manager::SLIDER,
		'range'     => [
			'px' => [
				'max'  => 1,
				'min'  => 0.10,
				'step' => 0.01,
			],
		],
		'default'   => [
			'size' => 0.5,
		],
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-image-carousel-item .absp-image-carousel-slider:before' => 'opacity: {{SIZE}};',
		],
	]
);

$this->end_controls_section();


