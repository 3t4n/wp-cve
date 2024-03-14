<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_variation_inline' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_link_showcase_variation_inline( $variations ) {
		$variations['inline'] = esc_html__( 'Inline', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_link_showcase_layouts', 'qi_addons_for_elementor_add_interactive_link_showcase_variation_inline' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_options_inline' ) ) {
	function qi_addons_for_elementor_add_interactive_link_showcase_options_inline( $options ) {
		$inline_options = array();

		$separator = array(
			'field_type'    => 'text',
			'name'          => 'inline_separator',
			'title'         => esc_html__( 'Separator Character', 'qi-addons-for-elementor' ),
			'default_value' => '/',
			'selectors'     => array(
				'{{WRAPPER}} .qodef-layout--inline .qodef-e-title:before' => 'content: \'{{VALUE}}\'',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'inline',
						'default_value' => '',
					),
				),
			),
		);

		$hide_images = array(
			'field_type'    => 'select',
			'name'          => 'inline_hide_images_under',
			'title'         => esc_html__( 'Hide Images', 'qi-addons-for-elementor' ),
			'options'       => array(
				''    => esc_html__( 'Never', 'qi-addons-for-elementor' ),
				'680' => esc_html__( 'Under 680px', 'qi-addons-for-elementor' ),
				'480' => esc_html__( 'Under 480px', 'qi-addons-for-elementor' ),
			),
			'default_value' => '',
			'prefix_class'  => 'qodef-inline-hide-under--',
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'inline',
						'default_value' => '',
					),
				),
			),
		);

		$inline_options[] = $separator;
		$inline_options[] = $hide_images;

		return array_merge( $options, $inline_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_link_showcase_extra_options', 'qi_addons_for_elementor_add_interactive_link_showcase_options_inline' );
}
