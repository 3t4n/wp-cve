<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'shape_section_settings',
	[
		'label'     => esc_html__( 'Image BG Shape', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_content_card' => 'fifteen',
		],
	]
);

$this->add_control(
	'shape_section_background',
	[
		'label'     => esc_html__('Shape Background Color', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-border-shape .content-card-box-shape1-class1,
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-border-shape .content-card-box-shape2-class1,
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-content .content-card-box-sub-title b
		' => 'fill: {{VALUE}}; color: {{VALUE}}',
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-content .content-card-box-title:before' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'shape_section_border',
	[
		'label'     => esc_html__('Shape Border Color', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-border-shape .content-card-box-shape1-class2,
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-border-shape .content-card-box-shape2-class2
		' => 'fill: {{VALUE}}',
		],
	]
);

$this->end_controls_section();

