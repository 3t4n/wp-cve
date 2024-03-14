<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_separator_variation_border_image' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_separator_variation_border_image( $variations ) {
		$variations['border-image'] = esc_html__( 'Border Image', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_separator_layouts', 'qi_addons_for_elementor_add_separator_variation_border_image' );
}

if ( ! function_exists( 'qi_addons_for_elementor_separator_border_image_add_extra_options' ) ) {
	function qi_addons_for_elementor_separator_border_image_add_extra_options( $extra_options ) {
		$border_image = array();

		$border_image_src = array(
			'field_type'    => 'image',
			'name'          => 'separator_border_image',
			'title'         => esc_html__( 'Border Image', 'qi-addons-for-elementor' ),
			'default_value' => array(),
			'dependency'    => array(
				'show' => array(
					'separator_layout' => array(
						'values'        => array( 'border-image' ),
						'default_value' => 'standard',
					),
				),
			),
			'group'         => esc_html__( 'Border Image', 'qi-addons-for-elementor' ),
		);

		$border_image_size = array(
			'field_type' => 'select',
			'name'       => 'separator_border_image_size',
			'title'      => esc_html__( 'Border Image Size', 'qi-addons-for-elementor' ),
			'options'    => array(
				'auto'    => esc_html__( 'Auto', 'qi-addons-for-elementor' ),
				'cover'   => esc_html__( 'Cover', 'qi-addons-for-elementor' ),
				'contain' => esc_html__( 'Contain', 'qi-addons-for-elementor' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-line' => 'background-size: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'separator_layout' => array(
						'values'        => array( 'border-image' ),
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Border Image', 'qi-addons-for-elementor' ),
		);

		$border_image_position = array(
			'field_type' => 'select',
			'name'       => 'separator_border_image_position',
			'title'      => esc_html__( 'Border Image Position', 'qi-addons-for-elementor' ),
			'options'    => array(
				'left'   => esc_html__( 'Left', 'qi-addons-for-elementor' ),
				'center' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
				'right'  => esc_html__( 'Right', 'qi-addons-for-elementor' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-line' => 'background-position: top {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'separator_layout' => array(
						'values'        => array( 'border-image' ),
						'default_value' => 'standard',
					),
					'separator_border_image_size' => array(
						'values'        => array( 'auto', 'contain' ),
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Border Image', 'qi-addons-for-elementor' ),
		);

		$border_image_repeat = array(
			'field_type' => 'select',
			'name'       => 'separator_border_image_select',
			'title'      => esc_html__( 'Border Image Repeat', 'qi-addons-for-elementor' ),
			'options'    => array(
				'round'     => esc_html__( 'Round', 'qi-addons-for-elementor' ),
				'repeat'    => esc_html__( 'Repeat', 'qi-addons-for-elementor' ),
				'space'     => esc_html__( 'Space', 'qi-addons-for-elementor' ),
				'no-repeat' => esc_html__( 'None', 'qi-addons-for-elementor' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-line' => 'background-repeat: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'separator_layout' => array(
						'values'        => array( 'border-image' ),
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Border Image', 'qi-addons-for-elementor' ),
		);

		$border_image[] = $border_image_src;
		$border_image[] = $border_image_size;
		$border_image[] = $border_image_position;
		$border_image[] = $border_image_repeat;

		return array_merge( $extra_options, $border_image );
	}

	add_filter( 'qi_addons_for_elementor_filter_separator_extra_options', 'qi_addons_for_elementor_separator_border_image_add_extra_options' );
}
