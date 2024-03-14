<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_banner_variation_revealing' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_banner_variation_revealing( $variations ) {
		$variations['revealing'] = esc_html__( 'Revealing', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_banner_layouts', 'qi_addons_for_elementor_add_interactive_banner_variation_revealing' );
}
if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_banner_options_revealing' ) ) {
	function qi_addons_for_elementor_add_interactive_banner_options_revealing( $options ) {
		$revealing_options = array();

		$margin_bottom = array(
			'field_type' => 'slider',
			'name'       => 'image_switch_text_margin_bottom',
			'title'      => esc_html__( 'Text Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--revealing .qodef-m-content-inner > .qodef-m-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'revealing',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$revealing_options[] = $margin_bottom;

		return array_merge( $options, $revealing_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_banner_extra_options', 'qi_addons_for_elementor_add_interactive_banner_options_revealing' );
}
