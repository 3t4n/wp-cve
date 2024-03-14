<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

$this->start_controls_section(
	'images_section_settings',
	[
		'label'      => esc_html__( 'Images', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'eight',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'seventeen',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'eighteen',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'nineteen',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'twenty',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'twenty-one',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'images_section_background',
		'label'          => esc_html__( 'Images Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Images Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-img',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'images_border',
		'label'    => esc_html__( 'Images Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-img',
	]
);

$this->add_responsive_control(
	'images_section_border_radius',
	[
		'label'      => esc_html__( 'Images  Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'images_section_box_shadow',
		'label'    => esc_html__( 'Images Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-img',
	]
);

$this->add_responsive_control(
	'images_section_padding',
	[
		'label'      => esc_html__( 'Images Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'images_section_margin',
	[
		'label'      => esc_html__( 'Images Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

