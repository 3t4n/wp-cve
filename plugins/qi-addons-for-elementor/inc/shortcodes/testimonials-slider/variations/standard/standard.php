<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_slider_variation_standard' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_slider_variation_standard( $variations ) {
		$variations['standard'] = esc_html__( 'Standard', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_testimonials_slider_layouts', 'qi_addons_for_elementor_add_testimonials_slider_variation_standard' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_slider_standard_options' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_slider_standard_options( $options ) {
		$standard = array();

		$quote_margin_bottom = array(
			'field_type' => 'slider',
			'name'       => 'standard_quote_margin_bottom',
			'title'      => esc_html__( 'Quote Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--standard .qodef-e-quote' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$image_margin_bottom = array(
			'field_type' => 'slider',
			'name'       => 'standard_image_margin_right',
			'title'      => esc_html__( 'Item Image Margin Right', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--standard .qodef-e-media-image' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$author_position = array(
			'field_type' => 'slider',
			'name'       => 'standard_author_position_margin_top',
			'title'      => esc_html__( 'Item Author Occupation Margin Top', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--standard .qodef-e-author-job' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$standard[] = $author_position;
		$standard[] = $image_margin_bottom;
		$standard[] = $quote_margin_bottom;

		return array_merge( $options, $standard );
	}

	add_filter( 'qi_addons_for_elementor_filter_testimonials_slider_extra_options', 'qi_addons_for_elementor_add_testimonials_slider_standard_options' );
}
