<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'label_section',
	[
		'label'      => esc_html__( 'Label', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'three',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'eight',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'eleven',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'fifteen',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '==',
					'value'    => 'eighteen',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'label_typography',
		'label'    => esc_html__( 'Label Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label',
	]
);

$this->add_control(
	'label_color',
	[
		'label'     => esc_html__( 'Label Text Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'label_background',
		'label'          => esc_html__( 'Label Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Label Background',
			],
		],
		'selector'       => '
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label,
		{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label::before
		',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'label_border',
		'label'    => esc_html__( 'Label Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label',
	]
);

$this->add_responsive_control(
	'label_border_radius',
	[
		'label'      => esc_html__( 'Label  Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'label_box_shadow',
		'label'    => esc_html__( 'Label Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label',
	]
);

$this->add_responsive_control(
	'label_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'label_margin',
	[
		'label'      => esc_html__( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-inner .content-card-box-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
