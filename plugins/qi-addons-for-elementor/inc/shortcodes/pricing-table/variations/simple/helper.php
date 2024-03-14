<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_pricing_table_variation_simple' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_pricing_table_variation_simple( $variations ) {

		$variations['simple'] = esc_html__( 'Simple', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_pricing_table_layouts', 'qi_addons_for_elementor_add_pricing_table_variation_simple' );
}

if ( ! function_exists( 'qi_addons_for_elementor_pricing_table_simple_add_extra_options' ) ) {
	function qi_addons_for_elementor_pricing_table_simple_add_extra_options( $extra_options ) {
		$simple = array();

		$wrapper_alignment = array(
			'field_type'    => 'choose',
			'name'          => 'title_wrapper_alignment',
			'title'         => esc_html__( 'Title Wrapper Alignment', 'qi-addons-for-elementor' ),
			'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false, array( 'right' ) ),
			'selectors'     => array(
				'{{WRAPPER}} .qodef-m-title-wrapper' => 'text-align: {{VALUE}};',
				'{{WRAPPER}} .qodef-m-title-wrapper .qodef-m-price' => 'justify-content: {{VALUE}};',
				'{{WRAPPER}} .qodef-period--bottom .qodef-m-title-wrapper .qodef-m-price' => 'align-items: {{VALUE}};',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'simple',
						'default_value' => 'standard',
					),
				),
			),
			'default_value' => 'center',
			'group'         => esc_html__( 'Table Title Style', 'qi-addons-for-elementor' ),
		);

		$background_image = array(
			'field_type' => 'background',
			'name'       => 'title_background_image',
			'title'      => esc_html__( 'Title Wrapper Background Image', 'qi-addons-for-elementor' ),
			'types'      => array( 'classic', 'gradient', 'video' ),
			'selector'   => '{{WRAPPER}} .qodef-m-title-wrapper',
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'simple',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Table Title Style', 'qi-addons-for-elementor' ),
		);

		$wrapper_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'title_wrapper_padding',
			'title'      => esc_html__( 'Title Wrapper Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-title-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'simple',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Table Title Style', 'qi-addons-for-elementor' ),
		);

		$simple[] = $wrapper_alignment;
		$simple[] = $background_image;
		$simple[] = $wrapper_padding;

		return array_merge( $extra_options, $simple );
	}

	add_filter( 'qi_addons_for_elementor_filter_pricing_table_extra_options', 'qi_addons_for_elementor_pricing_table_simple_add_extra_options' );
}
