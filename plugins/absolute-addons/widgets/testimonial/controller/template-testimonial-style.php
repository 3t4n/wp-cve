<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$this->start_controls_section(
	'section_style_testimonial_general',
	[
		'label'     => esc_html__( 'General', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_testimonial!' => [
				'eight',
			],
		],
	]
);

$this->start_controls_tabs( 'testimonial_style_normal' );

$this->start_controls_tab(
	'testimonial_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_testimonial_body_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient', 'video' ],
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item, {{WRAPPER}} .testimonial-style-one, {{WRAPPER}} .testimonial-style-two .testimonial-top-content, {{WRAPPER}} .testimonial-style-two .testimonial-top-content::before, {{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item, .testimonial-item)',
		'default'  => '',
	]
);

$this->add_responsive_control(
	'testimonial_item_box',
	[
		'label'      => esc_html__( 'Testimonial Item Box Width', 'absolute-addons' ),
		'name'       => 'testimonial_image',
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1500,
				'step' => 5,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item'                                          => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one, {{WRAPPER}} .testimonial-style-two .testimonial-top-content' => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item, .testimonial-item)'                    => 'width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item, {{WRAPPER}} .testimonial-style-one, {{WRAPPER}} .testimonial-style-two .testimonial-top-content, {{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item, .testimonial-item)',
		'default'  =>
			[
				'horizontal' => 2,
				'vertical'   => 3,
				'blur'       => 2,
				'spread'     => 2,
				'color'      => 'rgba(0,0,0,0.05)',
			],

	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'section_style_testimonial_general',
		'label'    => esc_html__( 'Testimonial Item Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item, {{WRAPPER}} .testimonial-style-one, {{WRAPPER}} .testimonial-style-two .testimonial-top-content, {{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item, .testimonial-item)',
	]
);

$this->add_responsive_control(
	'body_section_border_radius',
	[
		'label'      => esc_html__( 'Testimonial Item Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-top-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item, .testimonial-item)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_padding',
	[
		'label'      => esc_html__( 'Section Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-top-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item, .testimonial-item)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_margin',
	[
		'label'      => esc_html__( 'Section margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-top-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item, .testimonial-item)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'testimonial_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_testimonial_item_background_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient', 'video' ],
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item:hover, {{WRAPPER}} .testimonial-style-one:hover, {{WRAPPER}} .testimonial-style-two .testimonial-top-content:hover, {{WRAPPER}} .testimonial-style-two .testimonial-top-content:hover::before, {{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item:hover, .testimonial-item:hover)',
		'default'  => '',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'box_shadow_hover',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item:hover, {{WRAPPER}} .testimonial-style-one:hover, {{WRAPPER}} .testimonial-style-two .testimonial-top-content:hover, {{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item:hover, .testimonial-item:hover)',
		'default'  =>
			[
				'horizontal' => 2,
				'vertical'   => 3,
				'blur'       => 2,
				'spread'     => 2,
				'color'      => 'rgba(0,0,0,0.05)',
			],

	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'section_style_testimonial_general_hover',
		'label'    => esc_html__( 'Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item:hover, {{WRAPPER}} .testimonial-style-one:hover:hover, {{WRAPPER}} .testimonial-style-two .testimonial-top-content:hover, {{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item:hover, .testimonial-item:hover)',
	]
);

$this->add_responsive_control(
	'body_section_border_radius_hover',
	[
		'label'      => esc_html__( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-top-content:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial :is(.absp-testimonial-item:hover, .testimonial-item:hover)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'testimonial_section_title',
	[
		'label'     => esc_html__( 'Testimonial Section Title', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_testimonial' => [
				'nine',
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Section Title Typography', 'absolute-addons' ),
		'name'     => 'testimonial_section_title_typography',
		'selector' => '{{WRAPPER}} .testimonial-style-nine .testimonial-left-section .section-title .testimonial-section-title',
	]
);

$this->add_control(
	'testimonial_section_title_color',
	[
		'label'     => esc_html__( 'Section Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .testimonial-style-nine .testimonial-left-section .section-title .testimonial-section-title' => 'color: {{VALUE}}',
			'{{WRAPPER}} .testimonial-style-nine .testimonial-left-section::before'                                   => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Section Content Typography', 'absolute-addons' ),
		'name'     => 'testimonial_section_content_typography',
		'selector' => '{{WRAPPER}} .testimonial-style-nine .testimonial-left-section .section-description',
	]
);

$this->add_control(
	'testimonial_section_content_color',
	[
		'label'     => esc_html__( 'Section Content Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .testimonial-style-nine .testimonial-left-section .section-description' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_style_testimonial_image',
	[
		'label'     => esc_html__( 'Testimonial Image', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_testimonial!' => [
				'seven',
				'eight',
			],
		],
	]
);

$this->add_responsive_control(
	'testimonial_image_width',
	[
		'label'      => esc_html__( 'Testimonial Image Width', 'absolute-addons' ),
		'name'       => 'testimonial_image',
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1000,
				'step' => 5,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image'                                          => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image img'                                      => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one .testimonial-img img'                                                     => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-img img'                                                     => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image'                                     => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image img'                                 => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-nine .testimonial-right-section .testimonial-item-wrapper .testimonial-image' => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-six .testimonial-right-content .testimonial-image'                            => 'max-width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'testimonial_image_height',
	[
		'label'      => esc_html__( 'Testimonial Image Height', 'absolute-addons' ),
		'name'       => 'testimonial_image',
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1000,
				'step' => 5,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image'                                          => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image img'                                      => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one .testimonial-img img'                                                     => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-img img'                                                     => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image'                                     => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image img'                                 => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-six .testimonial-right-content .testimonial-image'                            => 'max-height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-nine .testimonial-right-section .testimonial-item-wrapper .testimonial-image' => 'height: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'image_box_shadow',
		'label'    => __( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image, {{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image img, {{WRAPPER}} .testimonial-style-one .testimonial-img img, {{WRAPPER}} .testimonial-style-two .testimonial-img img, {{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image, {{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image img, {{WRAPPER}} .testimonial-style-six .testimonial-right-content .testimonial-image, {{WRAPPER}} .testimonial-style-nine .testimonial-right-section .testimonial-item-wrapper .testimonial-image',
		'default'  =>
			[
				'horizontal' => 2,
				'vertical'   => 3,
				'blur'       => 2,
				'spread'     => 2,
				'color'      => 'rgba(0,0,0,0.05)',
			],

	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'testimonial_image_border',
		'label'    => esc_html__( 'Testimonial Item Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image, {{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image img, {{WRAPPER}} .testimonial-style-one .testimonial-img img, {{WRAPPER}} .testimonial-style-two .testimonial-img img, {{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image, {{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image img, {{WRAPPER}} .testimonial-style-six .testimonial-right-content .testimonial-image, {{WRAPPER}} .testimonial-style-nine .testimonial-right-section .testimonial-item-wrapper .testimonial-image',
	]
);

$this->add_responsive_control(
	'testimonial_image_border_radius',
	[
		'label'      => esc_html__( 'Testimonial Item Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image'                                          => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-image img'                                      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one .testimonial-img img'                                                     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-img img'                                                     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image'                                     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image img'                                 => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-nine .testimonial-right-section .testimonial-item-wrapper .testimonial-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'testimonial_image_masking',
	[
		'label'      => esc_html__( 'Image Masking Size', 'absolute-addons' ),
		'name'       => 'testimonial_image',
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', '%' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1000,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-image' => 'mask-size: {{SIZE}}{{UNIT}}; -webkit-mask-size:{{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_testimonial' => [ 'three', 'four', 'five' ],
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_style_testimonial_content',
	[
		'label' => esc_html__( 'Content', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Content Typography', 'absolute-addons' ),
		'name'     => 'testimonial_content',
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-content, {{WRAPPER}} .testimonial-style-one .testimonial-content, {{WRAPPER}} .testimonial-style-two .testimonial-top-content .testimonial-content, {{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-content, {{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-content',
	]
);

$this->add_control(
	'testimonial_content_color',
	[
		'label'     => esc_html__( 'Content Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-content' => 'color: {{VALUE}}',
			'{{WRAPPER}} .testimonial-style-one .testimonial-content'                     => 'color: {{VALUE}}',
			'{{WRAPPER}} .testimonial-style-two .testimonial-content'                     => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-content'   => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-content'        => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'content_responsive_padding',
	[
		'label'      => esc_html__( 'Content Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one .testimonial-content'                     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-content'                     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-content'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-content'        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'content_responsive_margin',
	[
		'label'      => esc_html__( 'Content Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one .testimonial-content'                     => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-content'                     => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-content'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-content'        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_style_testimonial_title',
	[
		'label' => esc_html__( 'Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Title Typography', 'absolute-addons' ),
		'name'     => 'testimonial_title',
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-designation .testimonial-title, {{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-title, {{WRAPPER}} .testimonial-style-one .testimonial-name .testimonial-title, {{WRAPPER}} .testimonial-style-two .testimonial-name h3, {{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-title, {{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-title',
	]
);

$this->add_control(
	'testimonial_title_color',
	[
		'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-designation .testimonial-title' => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-title'                          => 'color: {{VALUE}}',
			'{{WRAPPER}} .testimonial-style-one .testimonial-name .testimonial-title'                            => 'color: {{VALUE}}',
			'{{WRAPPER}} .testimonial-style-two .testimonial-name .testimonial-title'                            => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-title'                            => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-title'                                 => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'title_padding',
	[
		'label'      => esc_html__( 'Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-designation .testimonial-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-title'                          => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one .testimonial-name .testimonial-title'                            => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-name .testimonial-title'                            => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-title'                            => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-title'                                 => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'title_margin',
	[
		'label'      => esc_html__( 'Title Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-designation .testimonial-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-title'                          => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-one .testimonial-name .testimonial-title'                            => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-name .testimonial-title'                            => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-title'                            => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-title'                                 => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'section_style_testimonial_designation',
	[
		'label' => esc_html__( 'Designation', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Designation Typography', 'absolute-addons' ),
		'name'     => 'testimonial_degi',
		'selector' => '{{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-designation .testimonial-desig, {{WRAPPER}} .absp-testimonial-slider .testimonial-item .testimonial-desig, {{WRAPPER}} .testimonial-style-one .testimonial-name .testimonial-desig, {{WRAPPER}} .testimonial-style-two .testimonial-name .testimonial-desig, {{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-desig, {{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-desig, {{WRAPPER}} .testimonial-style-eight .testimonial-item:is(.image-position-left,.image-position-right) .testimonial-right-content .testimonial-designation',
	]
);

$this->add_control(
	'testimonial_desig_color',
	[
		'label'     => esc_html__( 'Designation Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .testimonial-style-one .testimonial-name .testimonial-desig'                                                                                   => 'color: {{VALUE}}',
			'{{WRAPPER}} .testimonial-style-two .testimonial-name .testimonial-desig'                                                                                   => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-desig'                                                                                   => 'color: {{VALUE}}',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-desig'                                                                                        => 'color: {{VALUE}}',
			'{{WRAPPER}} .testimonial-style-eight .testimonial-item:is(.image-position-left,.image-position-right) .testimonial-right-content .testimonial-designation' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'testimonial_desig_padding',
	[
		'label'      => esc_html__( 'Designation Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .testimonial-style-one .testimonial-name .testimonial-desig'                                                                                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-name .testimonial-desig'                                                                                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-desig'                                                                                   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-desig'                                                                                        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-eight .testimonial-item:is(.image-position-left,.image-position-right) .testimonial-right-content .testimonial-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'testimonial_desig_margin',
	[
		'label'      => esc_html__( 'Designation Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .testimonial-style-one .testimonial-name .testimonial-desig'                                                                                   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-two .testimonial-name .testimonial-desig'                                                                                   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .absp-testimonial-item .testimonial-desig'                                                                                   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .absp-testimonial .testimonial-item .testimonial-desig'                                                                                        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .testimonial-style-eight .testimonial-item:is(.image-position-left,.image-position-right) .testimonial-right-content .testimonial-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
