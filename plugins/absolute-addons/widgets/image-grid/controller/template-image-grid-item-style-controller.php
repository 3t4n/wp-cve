<?php
/*
 *  ABSP Image Grid One Controller
 */

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$this->start_controls_section(
	'image-grid-style-section',
	[
		'label' => __('Images', 'absolute-addons'),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_responsive_control(
	'image-padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-grid-wrapper .absp-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->render_controller( 'image-width' );

$this->render_controller( 'image-filters' );

$this->end_controls_section();

$this->start_controls_section(
		'gap_control_section',
		[
			'label' => __( 'Gap Control', 'absolute-addons' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
);

$this->add_control(
	'row-gap',
	[
		'label'      => __( 'Row Gap', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1000,
				'step' => 5,
			],

		],
		'default'    => [
			'unit' => 'px',
			'size' => 10,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-image-grid .absp-grid-wrapper' => 'row-gap: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'column-gap',
	[
		'label'      => __( 'Column Gap', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 1000,
				'step' => 5,
			],

		],
		'default'    => [
			'unit' => 'px',
			'size' => 10,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-image-grid .absp-grid-wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();
