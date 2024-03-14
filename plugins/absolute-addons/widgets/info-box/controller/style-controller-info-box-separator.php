<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'info_box_section_separator',
	[
		'label'      => esc_html__( 'Separator', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'eleven',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'twelve',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'fourteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'fifteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'thirteen',
				],
				[
					'name'     => 'absolute_info_box',
					'operator' => '==',
					'value'    => 'twenty-four',
				],

			],
		],
	]
);

$this->add_control(
	'info_box_separator_enable',
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
	'info_box_separator_color',
	array(
		'label'       => esc_html__( ' Separator Color', 'absolute-addons' ),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item .info-box-separator' => 'background-color: {{VALUE}}',
		),
		'description' => esc_html__( 'Select Separator color.', 'absolute-addons' ),
		'conditions'  => [
			'terms' => [
				[
					'name'     => 'info_box_separator_enable',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	)
);

$this->add_control(
	'info_box_separator_width',
	[
		'label'      => esc_html__( 'Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min' => 0,
				'max' => 350,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item .info-box-separator' => 'width: {{SIZE}}{{UNIT}};',
		],
		'conditions' => [
			'terms' => [
				[
					'name'     => 'info_box_separator_enable',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	]
);

$this->add_control(
	'info_box_separator_height',
	[
		'label'      => esc_html__( 'Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ '%' ],
		'range'      => [
			'%' => [
				'min' => 1,
				'max' => 100,
			],
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-wrapper .absp-info-box-item .info-box-separator' => 'height: {{SIZE}}{{UNIT}};',
		],
		'conditions' => [
			'terms' => [
				[
					'name'     => 'info_box_separator_enable',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	]
);

$this->end_controls_section();
