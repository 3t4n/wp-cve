<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'fun_fact_section_separator_five',
	[
		'label'     => esc_html__( 'Separator', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_fun_fact' => 'five',
		],
	]
);

$this->add_control(
	'fun_fact_separator_enable_five',
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
	'fun_fact_separator_color_five',
	array(
		'label'       => esc_html__( ' Separator Color', 'absolute-addons' ),
		'type'        => Controls_Manager::COLOR,
		'description' => esc_html__( 'Select Separator color.', 'absolute-addons' ),
		'conditions'  => [
			'terms' => [
				[
					'name'     => 'fun_fact_separator_enable_five',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
		'selectors'   => [
			'
			{{WRAPPER}} .absp-wrapper .absp-fun-fact-item .fun-fact-separate.fun-fact-separate-bottom,
			{{WRAPPER}} .absp-wrapper .absp-fun-fact-item .fun-fact-separate.fun-fact-separate-right
			' => 'border-color: {{VALUE}}',
		],
	)
);

$this->add_control(
	'fun_fact_separator_right_enable',
	array(
		'label'        => esc_html__( ' Right Separator', 'absolute-addons' ),

		'type'         => Controls_Manager::SWITCHER,
		'enable'       => esc_html__( 'Yes', 'absolute-addons' ),
		'disable'      => esc_html__( 'No', 'absolute-addons' ),
		'return_value' => 'enable',
		'default'      => 'enable',
		'conditions'   => [
			'terms' => [
				[
					'name'     => 'fun_fact_separator_enable_five',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	)
);

$this->add_control(
	'fun_fact_separator_bottom_enable',
	array(
		'label'        => esc_html__( ' Bottom Separator', 'absolute-addons' ),

		'type'         => Controls_Manager::SWITCHER,
		'enable'       => esc_html__( 'Yes', 'absolute-addons' ),
		'disable'      => esc_html__( 'No', 'absolute-addons' ),
		'return_value' => 'enable',
		'default'      => 'enable',
		'conditions'   => [
			'terms' => [
				[
					'name'     => 'fun_fact_separator_enable_five',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	)
);

$this->end_controls_section();

