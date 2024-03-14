<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

$this->start_controls_section(
	'icon_box_section_separator',
	[
		'label'      => esc_html__( 'Separator', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'twelve',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'fourteen',
				],

			],
		],
	]
);

$this->add_control(
	'icon_box_separator_enable',
	array(
		'label'        => esc_html__( 'Enable Separator ?', 'absolute-addons' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => __( 'Yes', 'absolute-addons' ),
		'label_off'    => __( 'No', 'absolute-addons' ),
		'return_value' => 'true',
		'default'      => 'true',
	)
);

$this->add_control(
	'icon_box_separator_color',
	array(
		'label'       => esc_html__( ' Separator Color', 'absolute-addons' ),
		'type'        => Controls_Manager::COLOR,
		'selectors'   => array(
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-separator' => 'background-color: {{VALUE}}',
		),
		'description' => esc_html__( 'Select Separator color.', 'absolute-addons' ),
		'conditions'  => [
			'terms' => [
				[
					'name'     => 'icon_box_separator_enable',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	)
);

$this->add_control(
	'icon_box_separator_width',
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
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-separator' => 'width: {{SIZE}}{{UNIT}};',
		],
		'conditions' => [
			'terms' => [
				[
					'name'     => 'icon_box_separator_enable',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	]
);

$this->add_control(
	'icon_box_separator_height',
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
			'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-separator' => 'height: {{SIZE}}{{UNIT}};',
		],
		'conditions' => [
			'terms' => [
				[
					'name'     => 'icon_box_separator_enable',
					'operator' => '==',
					'value'    => 'true',
				],
			],
		],
	]
);

$this->end_controls_section();
