<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;


$this->start_controls_section(
	'four_thumb_img_section_settings',
	[
		'label'      => esc_html__( 'Thumbnail Images', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_call_to_action',
					'operator' => '==',
					'value'    => 'four',
				],
			],
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'four_thumb_img_section_background',
		'label'          => esc_html__( 'Images Background', 'absolute-addons' ),
		'label_block'    => true,
		'types'          => [ 'classic', 'gradient' ],
		'fields_options' => [
			'background' => [
				'label' => 'Images Background',
			],
		],
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-call-to-action-item.element-four .c2a-box .c2a-box-inner .c2a-box-img .thubnail-img-inner img',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'four_thumb_img_border',
		'label'    => esc_html__( 'Images Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-call-to-action-item.element-four .c2a-box .c2a-box-inner .c2a-box-img .thubnail-img-inner img',
	]
);

$this->add_responsive_control(
	'four_thumb_img_section_border_radius',
	[
		'label'      => esc_html__( 'Images  Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item.element-four .c2a-box .c2a-box-inner .c2a-box-img .thubnail-img-inner img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'four_thumb_img_section_box_shadow',
		'label'    => esc_html__( 'Images Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-call-to-action-item.element-four .c2a-box .c2a-box-inner .c2a-box-img .thubnail-img-inner img',
	]
);

$this->add_responsive_control(
	'four_thumb_img_section_padding',
	[
		'label'      => esc_html__( 'Images Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item.element-four .c2a-box .c2a-box-inner .c2a-box-img .thubnail-img-inner img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'four_thumb_img_section_margin',
	[
		'label'      => esc_html__( 'Images Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-call-to-action-item.element-four .c2a-box .c2a-box-inner .c2a-box-img .thubnail-img-inner img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

