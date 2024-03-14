<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_item_showcase_variation_side_icon' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_item_showcase_variation_side_icon( $variations ) {
		$variations['side-icon'] = esc_html__( 'Side Icon', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_item_showcase_layouts', 'qi_addons_for_elementor_add_item_showcase_variation_side_icon' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_item_showcase_options_side_icon' ) ) {
	function qi_addons_for_elementor_add_item_showcase_options_side_icon( $options ) {
		$side_icon_options = array();

		$side_margin = array(
			'field_type' => 'dimensions',
			'name'       => 'side_icon_side_margin',
			'title'      => esc_html__( 'Side Margins', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--side-icon .qodef--right .qodef-e-side-holder' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .qodef-layout--side-icon .qodef--left .qodef-e-side-holder' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-icon',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$side_icon_options[] = $side_margin;

		return array_merge( $options, $side_icon_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_item_showcase_extra_options', 'qi_addons_for_elementor_add_item_showcase_options_side_icon' );
}
