<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_variation_standard' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_link_showcase_variation_standard( $variations ) {
		$variations['standard'] = esc_html__( 'Standard', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_link_showcase_layouts', 'qi_addons_for_elementor_add_interactive_link_showcase_variation_standard' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_options_standard' ) ) {
	function qi_addons_for_elementor_add_interactive_link_showcase_options_standard( $options ) {
		$standard_options = array();

		$images_on_top = array(
			'field_type'    => 'select',
			'name'          => 'standard_images_on_top',
			'title'         => esc_html__( 'Images on Top', 'qi-addons-for-elementor' ),
			'options'       => qi_addons_for_elementor_get_select_type_options_pool( 'no_yes' ),
			'default_value' => 'no',
			'prefix_class'  => 'qodef-standard-images-on-top--',
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => '',
					),
				),
			),
		);

		$hide_images = array(
			'field_type'    => 'select',
			'name'          => 'standard_hide_images_under',
			'title'         => esc_html__( 'Hide Images', 'qi-addons-for-elementor' ),
			'options'       => array(
				''    => esc_html__( 'Never', 'qi-addons-for-elementor' ),
				'768' => esc_html__( 'Under 768px', 'qi-addons-for-elementor' ),
				'680' => esc_html__( 'Under 680px', 'qi-addons-for-elementor' ),
				'480' => esc_html__( 'Under 480px', 'qi-addons-for-elementor' ),
			),
			'default_value' => '',
			'prefix_class'  => 'qodef-standard-hide-under--',
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => '',
					),
				),
			),
		);

		$images_width = array(
			'field_type' => 'slider',
			'name'       => 'standard_images_width',
			'title'      => esc_html__( 'Images Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'range'      => array(
				'px' => array(
					'min' => 100,
					'max' => 2000,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--standard .qodef-e-image.qodef-position--right' => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .qodef-layout--standard .qodef-e-image.qodef-position--left'  => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
		);

		$standard_options[] = $hide_images;
		$standard_options[] = $images_on_top;
		$standard_options[] = $images_width;

		return array_merge( $options, $standard_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_link_showcase_extra_options', 'qi_addons_for_elementor_add_interactive_link_showcase_options_standard' );
}
