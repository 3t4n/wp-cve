<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'fun_fact_section_separator',
	[
		'label'      => esc_html__( 'Separator', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_fun_fact',
					'operator' => '==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_fun_fact',
					'operator' => '==',
					'value'    => 'ten',
				],
			],
		],
	]
);

$this->add_control(
	'fun_fact_separator_enable',
	array(
		'label'   => esc_html__( 'Enable Separator', 'absolute-addons' ),
		'type'    => Controls_Manager::SELECT,
		'options' => array(
			'true'  => esc_html__( 'Yes', 'absolute-addons' ),
			'false' => esc_html__( 'No', 'absolute-addons' ),

		),
		'default' => 'true',
	)
);

$this->add_control(
	'fun_fact_separator_color',
	array(
		'label'       => esc_html__( ' Separator Color', 'absolute-addons' ),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'
			{{WRAPPER}} .absp-fun-fact-item .fun-fact-item .fun-fact-separate
			' => 'border-color: {{VALUE}}',
		),
		'description' => esc_html__( 'Select Separator color.', 'absolute-addons' ),
		'conditions'  => [
			'terms' => [
				[
					'name'     => 'fun_fact_separator_enable',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	)
);

$this->end_controls_section();

