<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_list_variation_info_below' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_list_variation_info_below( $variations ) {
		$variations['info-below'] = esc_html__( 'Info Below', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_list_layouts', 'qi_addons_for_elementor_add_product_list_variation_info_below' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_list_options_info_below' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_list_options_info_below( $options ) {
		$info_below_options = array();

		$title_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_title_margin_top',
			'title'      => esc_html__( 'Title Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below .qodef-e-product-title' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below',
						'default_value' => '',
					),
				),
			),
		);

		$price_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_price_margin_top',
			'title'      => esc_html__( 'Price Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below .qodef-woo-product-price' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below',
						'default_value' => '',
					),
				),
			),
		);

		$rating_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_rating_margin_top',
			'title'      => esc_html__( 'Rating Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below .qodef-e-ratings' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below',
						'default_value' => '',
					),
				),
			),
		);

		$button_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_button_margin_top',
			'title'      => esc_html__( 'Button Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below .qodef-e-product-content .qodef-qi-button' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below',
						'default_value' => '',
					),
				),
			),
		);

		$info_below_options[] = $title_margin_top;
		$info_below_options[] = $price_margin_top;
		$info_below_options[] = $rating_margin_top;
		$info_below_options[] = $button_margin_top;

		return array_merge( $options, $info_below_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_list_extra_options', 'qi_addons_for_elementor_add_product_list_options_info_below' );
}
