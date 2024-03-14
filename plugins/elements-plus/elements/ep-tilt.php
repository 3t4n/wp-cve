<?php
add_action(
	'elementor/element/before_section_start',
	function( $element, $section_id, $args ) {

		$type = get_class( $element );

		switch ( $type ) {
			case 'Elementor\Element_Column':
			case 'Elementor\Element_Section':
				$show_before_id = 'section_advanced';
				break;
			default:
				// Section '_section_style' is common to all widgets, registered in Widget_Common::register_controls().
				$show_before_id = '_section_style';
		}

		if ( $show_before_id === $section_id ) {
			ep_tilt_controls( $element );
		}

	},
	10,
	3
);

function ep_tilt_controls( $element ) {
	$element->start_controls_section(
		'tilt',
		[
			'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			'label' => __( 'Tilt Effect', 'elements-plus' ),
		]
	);

	$element->add_control(
		'enable_tilt',
		[
			'label'        => __( 'Enable Tilt', 'elements-plus' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'default'      => '',
			'label_on'     => 'On',
			'label_off'    => 'Off',
			'return_value' => 'yes',
			'separator'    => 'none',
		]
	);

	$element->add_control(
		'reverse_tilt',
		[
			'label'        => __( 'Reverse Tilt Direction', 'elements-plus' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'default'      => false,
			'label_on'     => 'On',
			'label_off'    => 'Off',
			'return_value' => true,
			'separator'    => 'none',
			'condition'    => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->add_control(
		'tilt_max',
		[
			'label'      => __( 'Max tilt rotation', 'elements-plus' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 90,
					'step' => 1,
				],
			],
			'default'    => [
				'size' => 35,
			],
			'condition'  => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->add_control(
		'start_x',
		[
			'label'      => __( 'Starting tilt on the X axis', 'elements-plus' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 90,
					'step' => 1,
				],
			],
			'default'    => [
				'size' => 0,
			],
			'condition'  => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->add_control(
		'start_y',
		[
			'label'      => __( 'Starting tilt on the Y axis', 'elements-plus' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 90,
					'step' => 1,
				],
			],
			'default'    => [
				'size' => 0,
			],
			'condition'  => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->add_control(
		'perspective',
		[
			'label'      => __( 'Transform perspective, the lower the more extreme the tilt gets.', 'elements-plus' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 500,
					'max'  => 2000,
					'step' => 50,
				],
			],
			'default'    => [
				'size' => 1000,
			],
			'condition'  => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->add_control(
		'scale',
		[
			'label'      => __( 'Scale on hover.', 'elements-plus' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 50,
					'max'  => 200,
					'step' => 10,
				],
			],
			'default'    => [
				'size' => 100,
			],
			'condition'  => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->add_control(
		'ep_tilt_speed',
		[
			'label'      => __( 'Speed of the enter/exit transition.', 'elements-plus' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 100,
					'max'  => 1000,
					'step' => 50,
				],
			],
			'default'    => [
				'size' => 300,
			],
			'condition'  => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->add_control(
		'axis',
		[
			'label'     => __( 'Choose an axis to disable', 'elements-plus' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'default'   => '',
			'options'   => [
				''  => __( 'None', 'elements-plus' ),
				'y' => __( 'Disable X axis', 'elements-plus' ),
				'x' => __( 'Disable Y axis', 'elements-plus' ),
			],
			'condition' => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->add_control(
		'gyroscope',
		[
			'label'        => __( 'Enable/Disable device orientation detection', 'elements-plus' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'default'      => true,
			'label_on'     => 'On',
			'label_off'    => 'Off',
			'return_value' => true,
			'separator'    => 'none',
			'condition'    => [
				'enable_tilt' => 'yes',
			],
		]
	);

	$element->end_controls_section();
}

add_action( 'elementor/frontend/widget/before_render', 'ep_tilt_before_render' );
add_action( 'elementor/frontend/section/before_render', 'ep_tilt_before_render' );
add_action( 'elementor/frontend/column/before_render', 'ep_tilt_before_render' );

function ep_tilt_before_render( \Elementor\Element_Base $element ) {
	$settings = $element->get_settings();

	if ( 'yes' !== $settings['enable_tilt'] ) {
		return;
	}

	$scale = floatval( $settings['scale']['size'] / 100 );
	$element->add_render_attribute( '_wrapper', 'data-tilt' );
	$element->add_render_attribute( '_wrapper', 'data-tilt-reverse', $settings['reverse_tilt'] );
	$element->add_render_attribute( '_wrapper', 'data-tilt-max', $settings['tilt_max']['size'] );
	$element->add_render_attribute( '_wrapper', 'data-tilt-startX', $settings['start_x']['size'] );
	$element->add_render_attribute( '_wrapper', 'data-tilt-startY', $settings['start_y']['size'] );
	$element->add_render_attribute( '_wrapper', 'data-tilt-perspective', $settings['perspective']['size'] );
	$element->add_render_attribute( '_wrapper', 'data-tilt-scale', $scale );
	$element->add_render_attribute( '_wrapper', 'data-tilt-speed', $settings['ep_tilt_speed']['size'] );
	$element->add_render_attribute( '_wrapper', 'data-tilt-axis', $settings['axis'] );
	$element->add_render_attribute( '_wrapper', 'data-tilt-gyroscope', $settings['gyroscope'] );
}
