<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_variation_split' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_link_showcase_variation_split( $variations ) {
		$variations['split'] = esc_html__( 'Split', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_link_showcase_layouts', 'qi_addons_for_elementor_add_interactive_link_showcase_variation_split' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_link_showcase_options_split' ) ) {
	function qi_addons_for_elementor_add_interactive_link_showcase_options_split( $options ) {
		$split_options = array();

		$list_position = array(
			'field_type'    => 'select',
			'name'          => 'split_list_position',
			'title'         => esc_html__( 'List Position', 'qi-addons-for-elementor' ),
			'options'       => array(
				'row'         => esc_html__( 'Left', 'qi-addons-for-elementor' ),
				'row-reverse' => esc_html__( 'Right', 'qi-addons-for-elementor' ),
			),
			'default_value' => 'row',
			'selectors'     => array(
				'{{WRAPPER}} .qodef-qi-interactive-link-showcase.qodef-layout--split' => 'flex-direction: {{VALUE}};',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'split',
						'default_value' => '',
					),
				),
			),
			'group'         => esc_html__( 'List Style', 'qi-addons-for-elementor' ),
		);

		$image_stretch = array(
			'field_type' => 'checkbox',
			'name'       => 'split_stretch_image',
			'title'      => esc_html__( 'Stretch Image', 'qi-addons-for-elementor' ),
			'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'split',
						'default_value' => '',
					),
				),
			),
		);

		$hide_images = array(
			'field_type'    => 'select',
			'name'          => 'split_hide_images_under',
			'title'         => esc_html__( 'Hide Images', 'qi-addons-for-elementor' ),
			'options'       => array(
				''    => esc_html__( 'Never', 'qi-addons-for-elementor' ),
				'680' => esc_html__( 'Under 680px', 'qi-addons-for-elementor' ),
				'480' => esc_html__( 'Under 480px', 'qi-addons-for-elementor' ),
			),
			'default_value' => '',
			'prefix_class'  => 'qodef-split-hide-under--',
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'split',
						'default_value' => '',
					),
				),
			),
		);

		$split_options[] = $list_position;
		$split_options[] = $image_stretch;
		$split_options[] = $hide_images;

		return array_merge( $options, $split_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_link_showcase_extra_options', 'qi_addons_for_elementor_add_interactive_link_showcase_options_split' );
}
