<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_banner_variation_from_bottom' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_banner_variation_from_bottom( $variations ) {
		$variations['from-bottom'] = esc_html__( 'From Bottom', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_banner_layouts', 'qi_addons_for_elementor_add_interactive_banner_variation_from_bottom' );
}
if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_banner_options_from_bottom' ) ) {
	function qi_addons_for_elementor_add_interactive_banner_options_from_bottom( $options ) {
		$from_bottom_options = array();

		$margin_bottom = array(
			'field_type' => 'slider',
			'name'       => 'image_switch_text_margin_bottom',
			'title'      => esc_html__( 'Text Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--from-bottom .qodef-m-content-inner > .qodef-m-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'from-bottom',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$from_bottom_options[] = $margin_bottom;

		return array_merge( $options, $from_bottom_options );
	}

	//add_filter( 'qi_addons_for_elementor_filter_interactive_banner_extra_options', 'qi_addons_for_elementor_add_interactive_banner_options_from_bottom' );
}
