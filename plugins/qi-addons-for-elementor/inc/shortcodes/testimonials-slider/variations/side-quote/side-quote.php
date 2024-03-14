<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_slider_variation_side_quote' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_slider_variation_side_quote( $variations ) {
		$variations['side-quote'] = esc_html__( 'Side Quote', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_testimonials_slider_layouts', 'qi_addons_for_elementor_add_testimonials_slider_variation_side_quote' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_slider_side_quote_options' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_slider_side_quote_options( $options ) {
		$side_quote = array();

		$image_margin_right = array(
			'field_type' => 'slider',
			'name'       => 'side_quote_image_margin_right',
			'title'      => esc_html__( 'Item Image Margin Right', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-quote .qodef-e-media-image' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-quote',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$quote_margin_right = array(
			'field_type' => 'slider',
			'name'       => 'side_quote_quote_margin_right',
			'title'      => esc_html__( 'Quote Margin Right', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-quote .qodef-e-quote' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-quote',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$side_quote[] = $image_margin_right;
		$side_quote[] = $quote_margin_right;

		return array_merge( $options, $side_quote );
	}

	add_filter( 'qi_addons_for_elementor_filter_testimonials_slider_extra_options', 'qi_addons_for_elementor_add_testimonials_slider_side_quote_options' );
}
