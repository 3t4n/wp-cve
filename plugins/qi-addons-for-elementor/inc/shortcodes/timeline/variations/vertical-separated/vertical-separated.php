<?php

if ( ! function_exists( 'qi_addons_for_elementor_filter_timeline_layouts_variation_vertical_separated' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_filter_timeline_layouts_variation_vertical_separated( $variations ) {
		$variations['vertical-separated'] = esc_html__( 'Vertical Separated', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_timeline_layouts', 'qi_addons_for_elementor_filter_timeline_layouts_variation_vertical_separated' );
}

if ( ! function_exists( 'qi_addons_for_elementor_filter_timeline_layouts_type_vertical_separated' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $layouts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_filter_timeline_layouts_type_vertical_separated( $layouts ) {

		$layouts['vertical-separated'] = 'vertical';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_timeline_layouts_type', 'qi_addons_for_elementor_filter_timeline_layouts_type_vertical_separated', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_filter_timeline_reverse_padding_vertical_separated' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param string $type
	 * @param string $layout
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_filter_timeline_reverse_padding_vertical_separated( $layouts ) {

		$layouts[] = 'vertical-separated';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_timeline_reverse_padding_layouts', 'qi_addons_for_elementor_filter_timeline_reverse_padding_vertical_separated', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_timeline_vertical_separated_options' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_timeline_vertical_separated_options( $options ) {
		$vertical_separated = array();

		$side_width = array(
			'field_type' => 'slider',
			'name'       => 'vertical_separated_side_width',
			'title'      => esc_html__( 'Sides Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'range'      => array(
				'px' => array(
					'min' => 100,
					'max' => 500,
				),
				'%'  => array(
					'min' => 1,
					'max' => 50,
				),
				'vw' => array(
					'min' => 1,
					'max' => 50,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-timeline-layout--vertical-separated .qodef-e-side-holder' => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .qodef-timeline-layout--vertical-separated .qodef-e-content-holder' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'vertical-separated',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$space_from_center = array(
			'field_type' => 'slider',
			'name'       => 'vertical_separated_space_from_center',
			'title'      => esc_html__( 'Space From Center', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 500,
				),
				'%'  => array(
					'min' => 1,
					'max' => 40,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-timeline-layout--vertical-separated .qodef-e-side-holder'                                => 'padding: 0 {{SIZE}}{{UNIT}} 0 0;',
				'{{WRAPPER}} .qodef-timeline-layout--vertical-separated .qodef-e-content-holder'                             => 'padding: 0 0 0 {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .qodef-timeline-layout--vertical-separated .qodef-e-item.qodef-reverse .qodef-e-side-holder'    => 'padding: 0 0 0 {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .qodef-timeline-layout--vertical-separated .qodef-e-item.qodef-reverse .qodef-e-content-holder' => 'padding: 0 {{SIZE}}{{UNIT}} 0 0;',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'vertical-separated',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$vertical_separated[] = $side_width;
		$vertical_separated[] = $space_from_center;

		return array_merge( $options, $vertical_separated );
	}

	add_filter( 'qi_addons_for_elementor_filter_timeline_extra_options', 'qi_addons_for_elementor_add_timeline_vertical_separated_options' );
}
