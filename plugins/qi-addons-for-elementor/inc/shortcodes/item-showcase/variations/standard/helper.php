<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_item_showcase_variation_standard' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_item_showcase_variation_standard( $variations ) {
		$variations['standard'] = esc_html__( 'Standard', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_item_showcase_layouts', 'qi_addons_for_elementor_add_item_showcase_variation_standard' );
}


if ( ! function_exists( 'qi_addons_for_elementor_add_item_showcase_options_standard' ) ) {
	function qi_addons_for_elementor_add_item_showcase_options_standard( $options ) {
		$standard_options = array();

		$icon_margin = array(
			'field_type' => 'dimensions',
			'name'       => 'standard_icon_margin',
			'title'      => esc_html__( 'Icon/Number Margin', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--standard .qodef-e-icon-holder' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .qodef-layout--standard .qodef-e-number'      => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$standard_options[] = $icon_margin;

		return array_merge( $options, $standard_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_item_showcase_extra_options', 'qi_addons_for_elementor_add_item_showcase_options_standard' );
}
