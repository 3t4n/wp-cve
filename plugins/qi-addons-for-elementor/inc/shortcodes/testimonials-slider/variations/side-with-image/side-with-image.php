<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_slider_variation_side_with_image' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_slider_variation_side_with_image( $variations ) {
		$variations['side-with-image'] = esc_html__( 'Side With Image', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_testimonials_slider_layouts', 'qi_addons_for_elementor_add_testimonials_slider_variation_side_with_image' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_slider_side_with_image_options' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_slider_side_with_image_options( $options ) {
		$side_with_image = array();

		$side_width = array(
			'field_type' => 'slider',
			'name'       => 'side_with_image_side_width',
			'title'      => esc_html__( 'Side Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'range'      => array(
				'px' => array(
					'min' => 10,
					'max' => 500,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-side' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$image_border_radius = array(
			'field_type' => 'dimensions',
			'name'       => 'side_with_image_image_border_radius',
			'title'      => esc_html__( 'Image Border Radius', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-side .qodef-e-media-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$side_margin_right = array(
			'field_type' => 'slider',
			'name'       => 'side_with_image_side_margin_right',
			'title'      => esc_html__( 'Item Side Margin Right', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'range'      => array(
				'px' => array(
					'min' => 10,
					'max' => 300,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-side' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$author_position = array(
			'field_type' => 'slider',
			'name'       => 'side_with_image_author_position_margin_top',
			'title'      => esc_html__( 'Item Author Occupation Margin Top', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-author-job' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$quote_background_color = array(
			'field_type' => 'color',
			'name'       => 'side_with_image_quote_background_color',
			'title'      => esc_html__( 'Quote Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-quote' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Quote Style', 'qi-addons-for-elementor' ),
		);

		$quote_box_size = array(
			'field_type' => 'slider',
			'name'       => 'side_with_image_quote_box_size',
			'title'      => esc_html__( 'Quote Box Size', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'range'      => array(
				'px' => array(
					'min' => 1,
					'max' => 100,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-quote' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Quote Style', 'qi-addons-for-elementor' ),
		);

		$quote_border_radius = array(
			'field_type' => 'dimensions',
			'name'       => 'side_with_image_quote_border_radius',
			'title'      => esc_html__( 'Quote Border Radius', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-quote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Quote Style', 'qi-addons-for-elementor' ),
		);

		$quote_positions = array(
			'field_type'         => 'dimensions',
			'name'               => 'side_with_image_quote_position',
			'title'              => esc_html__( 'Quote Position', 'qi-addons-for-elementor' ),
			'size_units'         => array( 'px', '%', 'em' ),
			'allowed_dimensions' => array( 'top', 'right' ),
			'responsive'         => true,
			'selectors'          => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-quote' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}};',
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-inner' => 'padding-top: calc((-1)*{{TOP}}{{UNIT}}); padding-bottom: calc((-1)*{{TOP}}{{UNIT}});',
			),
			'dependency'         => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'              => esc_html__( 'Quote Style', 'qi-addons-for-elementor' ),
		);

		$item_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'side_with_image_item_padding',
			'title'      => esc_html__( 'Item Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-with-image .qodef-e-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-with-image',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$side_with_image[] = $side_width;
		$side_with_image[] = $image_border_radius;
		$side_with_image[] = $side_margin_right;
		$side_with_image[] = $author_position;
		$side_with_image[] = $quote_positions;
		$side_with_image[] = $quote_background_color;
		$side_with_image[] = $quote_box_size;
		$side_with_image[] = $quote_border_radius;
		$side_with_image[] = $item_padding;

		return array_merge( $options, $side_with_image );
	}

	add_filter( 'qi_addons_for_elementor_filter_testimonials_slider_extra_options', 'qi_addons_for_elementor_add_testimonials_slider_side_with_image_options' );
}
