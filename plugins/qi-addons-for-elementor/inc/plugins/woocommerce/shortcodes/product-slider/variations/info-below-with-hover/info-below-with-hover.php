<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_slider_variation_info_below_with_hover' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_slider_variation_info_below_with_hover( $variations ) {
		$variations['info-below-with-hover'] = esc_html__( 'Info Below With Hover', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_slider_layouts', 'qi_addons_for_elementor_add_product_slider_variation_info_below_with_hover' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_slider_options_info_below_with_hover' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_slider_options_info_below_with_hover( $options ) {
		$info_below_with_hover_options = array();

		$category_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_with_hover_category_margin_top',
			'title'      => esc_html__( 'Category Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-with-hover .qodef-e-product-categories' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-with-hover',
						'default_value' => '',
					),
				),
			),
		);

		$rating_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_with_hover_rating_margin_top',
			'title'      => esc_html__( 'Rating Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-with-hover .qodef-e-ratings' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-with-hover',
						'default_value' => '',
					),
				),
			),
		);

		$info_below_with_hover_options[] = $category_margin_top;
		$info_below_with_hover_options[] = $rating_margin_top;

		return array_merge( $options, $info_below_with_hover_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_slider_extra_options', 'qi_addons_for_elementor_add_product_slider_options_info_below_with_hover' );
}
