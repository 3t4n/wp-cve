<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_process_variation_vertical' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_process_variation_vertical( $variations ) {
		$variations['vertical'] = esc_html__( 'Vertical', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_process_layouts', 'qi_addons_for_elementor_add_process_variation_vertical' );
}

if ( ! function_exists( 'qi_addons_for_elementor_process_vertical_add_extra_options' ) ) {
	function qi_addons_for_elementor_process_vertical_add_extra_options( $extra_options ) {
		$vertical = array();

		$space_between = array(
			'field_type' => 'slider',
			'name'       => 'vertical_space_between',
			'title'      => esc_html__( 'Space Between Items', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-qi-process.qodef-item-layout--vertical .qodef-process-item:not(:last-child) .qodef-e-icon-holder' => 'padding-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'vertical',
						'default_value' => 'horizontal',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$vertical[] = $space_between;

		return array_merge( $extra_options, $vertical );
	}

	add_filter( 'qi_addons_for_elementor_filter_process_extra_options', 'qi_addons_for_elementor_process_vertical_add_extra_options' );
}
