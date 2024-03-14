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
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '!==',
					'value'    => 'sixteen',
				],
				[
					'name'     => 'content_card_box_image[url]',
					'operator' => '!==',
					'value'    => '',
				],
			],
		],
	]
);

$this->add_responsive_control(
	'images_section_width',
	[
		'label'          => __( 'Image Width', 'absolute-addons' ),
		'type'           => Controls_Manager::SLIDER,
		'default'        => [
			'unit' => '%',
		],
		'tablet_default' => [
			'unit' => '%',
		],
		'mobile_default' => [
			'unit' => '%',
		],
		'size_units'     => [ '%', 'px', 'vw' ],
		'range'          => [
			'%'  => [
				'min' => 1,
				'max' => 100,
			],
			'px' => [
				'min' => 1,
				'max' => 1000,
			],
			'vw' => [
				'min' => 1,
				'max' => 100,
			],
		],
		'conditions'     => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '!=',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!=',
					'value'    => 'sixteen',
				],
			],
		],
		'selectors'      => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img img' => 'width: {{SIZE}}{{UNIT}};margin: 0 auto;',
		],
	]
);

$this->add_responsive_control(
	'images_section_space',
	[
		'label'          => __( 'Image Max Width', 'absolute-addons' ),
		'type'           => Controls_Manager::SLIDER,
		'default'        => [
			'unit' => '%',
		],
		'tablet_default' => [
			'unit' => '%',
		],
		'mobile_default' => [
			'unit' => '%',
		],
		'size_units'     => [ '%', 'px', 'vw' ],
		'range'          => [
			'%'  => [
				'min' => 1,
				'max' => 100,
			],
			'px' => [
				'min' => 1,
				'max' => 1000,
			],
			'vw' => [
				'min' => 1,
				'max' => 100,
			],
		],
		'conditions'     => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '!=',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!=',
					'value'    => 'sixteen',
				],
			],
		],
		'selectors'      => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img img' => 'max-width: {{SIZE}}{{UNIT}};margin: 0 auto;',
		],
	]
);

$this->add_responsive_control(
	'images_section_height',
	[
		'label'          => __( 'Image Height', 'absolute-addons' ),
		'type'           => Controls_Manager::SLIDER,
		'default'        => [
			'unit' => 'px',
		],
		'tablet_default' => [
			'unit' => 'px',
		],
		'mobile_default' => [
			'unit' => 'px',
		],
		'size_units'     => [ 'px', 'vh' ],
		'range'          => [
			'px' => [
				'min' => 1,
				'max' => 500,
			],
			'vh' => [
				'min' => 1,
				'max' => 100,
			],
		],
		'conditions'     => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'absolute_content_card',
					'operator' => '!=',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_content_card',
					'operator' => '!=',
					'value'    => 'sixteen',
				],
			],
		],
		'selectors'      => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img img' => 'height: {{SIZE}}{{UNIT}};',
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
		'selector'       => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'images_border',
		'label'    => esc_html__( 'Images Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img',
	]
);

$this->add_responsive_control(
	'images_section_border_radius',
	[
		'label'      => esc_html__( 'Images  Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'images_section_box_shadow',
		'label'    => esc_html__( 'Images Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img',
	]
);

$this->add_responsive_control(
	'images_section_padding',
	[
		'label'      => esc_html__( 'Images Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

