<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_list_variation_boxed' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_list_variation_boxed( $variations ) {
		$variations['boxed'] = esc_html__( 'Boxed', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_testimonials_list_layouts', 'qi_addons_for_elementor_add_testimonials_list_variation_boxed' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_testimonials_list_boxed_options' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_testimonials_list_boxed_options( $options ) {
		$boxed = array();

		$author_position = array(
			'field_type' => 'slider',
			'name'       => 'boxed_author_position_margin_top',
			'title'      => esc_html__( 'Item Author Occupation Margin Top', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-author-job' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'boxed',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$image_margin_bottom = array(
			'field_type' => 'slider',
			'name'       => 'boxed_image_margin_bottom',
			'title'      => esc_html__( 'Item Image Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-media-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'boxed',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$quote_positions = array(
			'field_type' => 'slider',
			'name'       => 'boxed_quote_position',
			'title'      => esc_html__( 'Quote Position', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'range'      => array(
				'px' => array(
					'min' => - 200,
					'max' => 200,
				),
				'%'  => array(
					'min' => - 100,
					'max' => 100,
				),
				'em' => array(
					'min' => - 3,
					'max' => 3,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-quote' => 'top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'boxed',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Quote Style', 'qi-addons-for-elementor' ),
		);

		$padding = array(
			'field_type' => 'dimensions',
			'name'       => 'boxed_padding',
			'title'      => esc_html__( 'Boxed Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'boxed',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$image_border_radius = array(
			'field_type' => 'dimensions',
			'name'       => 'boxed_image_border_radius',
			'title'      => esc_html__( 'Image Border Radius', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-media-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};;',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'boxed',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$image_background = array(
			'field_type' => 'background',
			'name'       => 'boxed_item_background',
			'title'      => esc_html__( 'Item Background', 'qi-addons-for-elementor' ),
			'types'      => array( 'classic', 'gradient', 'video' ),
			'selector'   => '{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-inner',
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'boxed',
						'default-value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$boxed[] = $author_position;
		$boxed[] = $image_margin_bottom;
		$boxed[] = $quote_positions;
		$boxed[] = $padding;
		$boxed[] = $image_border_radius;
		$boxed[] = $image_background;

		return array_merge( $options, $boxed );
	}

	add_filter( 'qi_addons_for_elementor_filter_testimonials_list_extra_options', 'qi_addons_for_elementor_add_testimonials_list_boxed_options' );
}
