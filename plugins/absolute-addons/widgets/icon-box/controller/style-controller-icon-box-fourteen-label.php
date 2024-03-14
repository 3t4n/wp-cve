<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'label_section',
	[
		'label'      => esc_html__( 'label', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'fourteen',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'     => 'label_typography',
		'label'    => esc_html__( 'label Typography', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-label-text',
	]
);

$this->add_control(
	'label_color',
	[
		'label'     => esc_html__( 'label Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-label-text' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'label_background',
		'label'          => esc_html__( 'label Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'label Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-label-text',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'label_border',
		'label'    => esc_html__( 'label Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-label-text',
	]
);

$this->add_responsive_control(
	'label_border_radius',
	[
		'label'      => esc_html__( 'label Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-label-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'label_box_shadow',
		'label'    => esc_html__( 'label Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-label-text',
	]
);

$this->add_responsive_control(
	'label_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-label-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box .icon-box-label-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
