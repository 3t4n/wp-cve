<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_pricing_table_variation_cascading' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_pricing_table_variation_cascading( $variations ) {

		$variations['cascading'] = esc_html__( 'Cascading', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_pricing_table_layouts', 'qi_addons_for_elementor_add_pricing_table_variation_cascading' );
}

if ( ! function_exists( 'qi_addons_for_elementor_pricing_table_cascading_add_extra_options' ) ) {
	function qi_addons_for_elementor_pricing_table_cascading_add_extra_options( $extra_options, $this_shortcode ) {
		$cascading = array();

		$title_background_color = array(
			'field_type' => 'color',
			'name'       => 'title_background_color',
			'title'      => esc_html__( 'Title Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-title' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'cascading',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
		);

		$title_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'title_padding',
			'title'      => esc_html__( 'Title Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'cascading',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Title Style', 'qi-addons-for-elementor' ),
		);

		$cascading[] = $title_background_color;
		$cascading[] = $title_padding;

		return array_merge( $extra_options, $cascading );
	}

	add_filter( 'qi_addons_for_elementor_filter_pricing_table_extra_options', 'qi_addons_for_elementor_pricing_table_cascading_add_extra_options', 10, 2 );
}
