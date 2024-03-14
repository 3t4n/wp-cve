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
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'five',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'thirteen',
				],
			],
		],
	]
);

$this->add_control(
	'shape_section_background',
	array(
		'label'      => esc_html__( 'Shape Background', 'absolute-addons' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-bg-shape-svg' => 'fill: {{VALUE}}',
		),
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'five',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'thirteen',
				],
			],
		],
	)
);

$this->end_controls_section();

