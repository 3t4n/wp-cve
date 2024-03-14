<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'svg_shape_section_settings',
	[
		'label'      => esc_html__( 'Shape Settings', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'eleven',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'seventeen',
				],
			],
		],
	]
);

$this->add_control(
	'gradient_color_1',
	[
		'label' => esc_html__( 'Gradient Background Color One', 'absolute-addons' ),
		'type'  => Controls_Manager::COLOR,
	]
);

$this->add_control(
	'gradient_color_2',
	[
		'label' => esc_html__( 'Gradient Background Color Two', 'absolute-addons' ),
		'type'  => Controls_Manager::COLOR,
	]
);

$this->end_controls_section();

