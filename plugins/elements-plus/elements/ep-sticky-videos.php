<?php

add_action(
	'elementor/element/video/section_video/before_section_end',
	function( $element, $args ) {

		$element->add_control(
			'ep_sticky_video',
			[
				'label'              => __( 'Enable Sticky Videos', 'elements-plus' ),
				'type'               => Elementor\Controls_Manager::SWITCHER,
				'default'            => 'off',
				'return_value'       => 'on',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ep_sticky_video_position',
			[
				'label'     => __( 'Position', 'elements-plus' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'right',
				'options'   => [
					'left'  => __( 'Left', 'elements-plus' ),
					'right' => __( 'Right', 'elements-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ep-sticky-wrapper .stuck' => '{{OPTION}}: 20px;',
				],
				'condition' => [
					'ep_sticky_video' => 'on',
				],
			]
		);

		$element->add_control(
			'ep_sticky_video_edge',
			[
				'label'      => __( 'Distance from the edges', 'elements-plus' ),
				'type'       => Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ep-sticky-wrapper .stuck' => '{{ep_sticky_video_position.VALUE}}: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'ep_sticky_video' => 'on',
				],
			]
		);

		$element->add_control(
			'ep_sticky_video_bottom',
			[
				'label'      => __( 'Distance from the bottom', 'elements-plus' ),
				'type'       => Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ep-sticky-wrapper .stuck' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'ep_sticky_video' => 'on',
				],
			]
		);

		$element->add_control(
			'ep_sticky_video_width',
			[
				'label'      => __( 'Video width', 'elements-plus' ),
				'type'       => Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 100,
						'max'  => 720,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 350,
				],
				'selectors'  => [
					'{{WRAPPER}} .ep-sticky-wrapper .stuck' => 'width: {{SIZE}}px;',
				],
				'condition'  => [
					'ep_sticky_video' => 'on',
				],
			]
		);

		$element->add_control(
			'ep_sticky_video_height',
			[
				'label'      => __( 'Video height', 'elements-plus' ),
				'type'       => Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 100,
						'max'  => 720,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 196,
				],
				'selectors'  => [
					'{{WRAPPER}} .ep-sticky-wrapper .stuck' => 'height: {{SIZE}}px;',
				],
				'condition'  => [
					'ep_sticky_video' => 'on',
				],
			]
		);
	},
	10,
	3
);
