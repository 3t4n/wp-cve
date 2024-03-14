<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_banner_variation_image_switch' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_banner_variation_image_switch( $variations ) {
		$variations['image-switch'] = esc_html__( 'Image Switch', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_banner_layouts', 'qi_addons_for_elementor_add_interactive_banner_variation_image_switch' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_banner_options_image_switch' ) ) {
	function qi_addons_for_elementor_add_interactive_banner_options_image_switch( $options ) {
		$image_switch_options = array();

		$background = array(
			'field_type' => 'background',
			'name'       => 'image_switch_background',
			'title'      => esc_html__( 'Background', 'qi-addons-for-elementor' ),
			'types'      => array( 'classic', 'gradient' ),
			'selector'   => '{{WRAPPER}} .qodef-layout--image-switch',
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'image-switch',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$image_width = array(
			'field_type' => 'slider',
			'name'       => 'image_switch_image_width',
			'title'      => esc_html__( 'Image Side Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--image-switch .qodef-m-image-holder' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'image-switch',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$margin_right = array(
			'field_type' => 'slider',
			'name'       => 'image_switch_content_margin_right',
			'title'      => esc_html__( 'Content Margin Right', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--image-switch .qodef-m-content-inner' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'image-switch',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$hover_image = array(
			'field_type' => 'image',
			'name'       => 'image_switch_hover_image',
			'title'      => esc_html__( 'Hover Image', 'qi-addons-for-elementor' ),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'image-switch',
						'default_value' => '',
					),
				),
			),
		);

		$image_switch_options[] = $background;
		$image_switch_options[] = $hover_image;
		$image_switch_options[] = $image_width;
		$image_switch_options[] = $margin_right;

		return array_merge( $options, $image_switch_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_banner_extra_options', 'qi_addons_for_elementor_add_interactive_banner_options_image_switch' );
}
