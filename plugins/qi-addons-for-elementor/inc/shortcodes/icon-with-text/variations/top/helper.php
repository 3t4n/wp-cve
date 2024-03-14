<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_icon_with_text_variation_top' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_icon_with_text_variation_top( $variations ) {
		$variations['top'] = esc_html__( 'Top', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_icon_with_text_layouts', 'qi_addons_for_elementor_add_icon_with_text_variation_top' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_icon_with_text_top_options' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_icon_with_text_top_options( $options, $default_layout ) {
		$icon_with_text_options = array();

		$alignment_option = array(
			'field_type'    => 'choose',
			'name'          => 'content_alignment',
			'title'         => esc_html__( 'Content Alignment', 'qi-addons-for-elementor' ),
			'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
			'default_value' => 'center',
			'responsive'    => true,
			'selectors'     => array(
				'{{WRAPPER}} .qodef-qi-icon-with-text.qodef-layout--top' => 'text-align: {{VALUE}};',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'top',
						'default_value' => $default_layout,
					),
				),
			),
			'group'         => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$icon_with_text_options[] = $alignment_option;

		return array_merge( $options, $icon_with_text_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_icon_with_text_extra_options', 'qi_addons_for_elementor_add_icon_with_text_top_options', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_icon_with_text_classes_alignment' ) ) {
	/**
	 * Function that return additional holder classes for this module
	 *
	 * @param array $holder_classes
	 * @param array $atts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_icon_with_text_classes_alignment( $holder_classes, $atts ) {

		if ( 'top' === $atts['layout'] ) {
			$holder_classes[] = ! empty( $atts['content_alignment'] ) ? 'qodef-alignment--' . $atts['content_alignment'] : 'qodef-alignment--center';
		}

		return $holder_classes;
	}

	add_filter( 'qi_addons_for_elementor_filter_icon_with_text_variation_classes', 'qi_addons_for_elementor_add_icon_with_text_classes_alignment', 10, 2 );
}
