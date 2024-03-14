<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_variation_background' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_link_showcase_variation_background( $variations ) {
		$variations['background'] = esc_html__( 'Background', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_link_showcase_layouts', 'qi_addons_for_elementor_add_interactive_link_showcase_variation_background' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_options_background' ) ) {
	function qi_addons_for_elementor_add_interactive_link_showcase_options_background( $options ) {
		$background_options = array();

		$list_position = array(
			'field_type'    => 'select',
			'name'          => 'background_list_position',
			'title'         => esc_html__( 'List Position', 'qi-addons-for-elementor' ),
			'options'       => array(
				'flex-start' => esc_html__( 'Left', 'qi-addons-for-elementor' ),
				'center'     => esc_html__( 'Center', 'qi-addons-for-elementor' ),
				'flex-end'   => esc_html__( 'Right', 'qi-addons-for-elementor' ),
			),
			'responsive'    => true,
			'default_value' => 'flex-start',
			'selectors'     => array(
				'{{WRAPPER}} .qodef-qi-interactive-link-showcase.qodef-layout--background' => 'justify-content: {{VALUE}};',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'background',
						'default_value' => '',
					),
				),
			),
			'group'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
		);

		$background_options[] = $list_position;

		return array_merge( $options, $background_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_link_showcase_extra_options', 'qi_addons_for_elementor_add_interactive_link_showcase_options_background' );
}

