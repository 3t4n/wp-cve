<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'shape_section_settings',
	[
		'label'      => esc_html__( 'Background Shape', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'five',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'fourteen',
				],
			],
		],
	]
);

$this->add_control(
	'shape_section_background',
	array(
		'label'      => esc_html__('Shape Background', 'absolute-addons'),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item .info-box-bg-shape-svg' => 'fill: {{VALUE}}',
		),
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'five',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'fourteen',
				],
			],
		],
	)
);

$this->end_controls_section();

$this->start_controls_section(
	'icon_shape_section_settings',
	[
		'label'     => esc_html__( 'Icon Shape', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_info_box' => 'five',
		],
	]
);

$this->add_control(
	'icon_shape_section_background',
	array(
		'label'     => esc_html__('Icon Shape Top Circle Background', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item .info-box-icon .shape-circle1' => 'background-color: {{VALUE}}',
		),
		'condition' => [
			'absolute_info_box' => 'five',
		],

	)
);

$this->add_control(
	'icon_shape2_section_background',
	array(
		'label'     => esc_html__('Icon Shape Bottom Circle Background', 'absolute-addons'),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item .info-box-icon .shape-circle2' => 'background-color: {{VALUE}}',
		),
		'condition' => [
			'absolute_info_box' => 'five',
		],
	)
);

$this->end_controls_section();



