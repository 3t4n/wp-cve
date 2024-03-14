<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_icon_with_text_variation_before_content' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_icon_with_text_variation_before_content( $variations ) {
		$variations['before-content'] = esc_html__( 'Before Content', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_icon_with_text_layouts', 'qi_addons_for_elementor_add_icon_with_text_variation_before_content' );
}


if ( ! function_exists( 'qi_addons_for_elementor_add_icon_with_text_before_content_options' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_icon_with_text_before_content_options( $options, $default_layout ) {
		$icon_with_text_options = array();

		$responsive_option = array(
			'field_type'    => 'select',
			'name'          => 'before_content_column_responsive',
			'title'         => esc_html__( 'Enable Column Responsive', 'qi-addons-for-elementor' ),
			'options'       => array(
				'never' => esc_html__( 'Never', 'qi-addons-for-elementor' ),
				'768'   => esc_html__( 'Under 768px', 'qi-addons-for-elementor' ),
				'680'   => esc_html__( 'Under 680px', 'qi-addons-for-elementor' ),
				'480'   => esc_html__( 'Under 480px', 'qi-addons-for-elementor' ),
			),
			'default_value' => 'never',
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'before-content',
						'default_value' => $default_layout,
					),
				),
			),
		);

		$icon_with_text_options[] = $responsive_option;

		return array_merge( $options, $icon_with_text_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_icon_with_text_extra_options', 'qi_addons_for_elementor_add_icon_with_text_before_content_options', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_icon_with_text_classes_before_content_responsive' ) ) {
	/**
	 * Function that return additional holder classes for this module
	 *
	 * @param array $holder_classes
	 * @param array $atts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_icon_with_text_classes_before_content_responsive( $holder_classes, $atts ) {

		if ( 'before-content' === $atts['layout'] ) {
			$holder_classes[] = ! empty( $atts['before_content_column_responsive'] ) ? 'qodef-column-responsive--' . $atts['before_content_column_responsive'] : 'qodef-column-responsive--never';
		}

		return $holder_classes;
	}

	add_filter( 'qi_addons_for_elementor_filter_icon_with_text_variation_classes', 'qi_addons_for_elementor_add_icon_with_text_classes_before_content_responsive', 10, 2 );
}
