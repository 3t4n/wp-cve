<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_list_variation_info_below_swap' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_list_variation_info_below_swap( $variations ) {
		$variations['info-below-swap'] = esc_html__( 'Info Below Swap', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_list_layouts', 'qi_addons_for_elementor_add_product_list_variation_info_below_swap' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_list_options_info_below_swap' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_list_options_info_below_swap( $options ) {
		$info_below_swap_options = array();

		$info_below_swap_alignment = array(
			'field_type'   => 'select',
			'name'         => 'info_below_swap_alignment',
			'title'        => esc_html__( 'Alignment', 'qi-addons-for-elementor' ),
			'group'        => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
			'options'      => array(
				'center' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
				'left'   => esc_html__( 'Left', 'qi-addons-for-elementor' ),
				'right'  => esc_html__( 'Right', 'qi-addons-for-elementor' ),
			),
			'prefix_class' => 'qodef-info-below-alignment--',
			'dependency'   => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
		);

		$title_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_swap_title_margin_top',
			'title'      => esc_html__( 'Title Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-e-product-title' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
		);

		$swap_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_swap_swap_margin_top',
			'title'      => esc_html__( 'Swap Holder Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-e-swap-holder' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
		);

		$price_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_swap_price_margin_top',
			'title'      => esc_html__( 'Price Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-woo-product-price' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
		);

		$rating_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_swap_rating_margin_top',
			'title'      => esc_html__( 'Rating Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-e-ratings' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
		);

		$button_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_swap_button_margin_top',
			'title'      => esc_html__( 'Button Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-e-product-content .qodef-qi-button' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
		);

		$info_below_swap_options[] = $info_below_swap_alignment;
		$info_below_swap_options[] = $title_margin_top;
		$info_below_swap_options[] = $swap_margin_top;
		$info_below_swap_options[] = $rating_margin_top;
//		$info_below_swap_options[] = $price_margin_top;
//		$info_below_swap_options[] = $button_margin_top; //add it if necessary

		return array_merge( $options, $info_below_swap_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_list_extra_options', 'qi_addons_for_elementor_add_product_list_options_info_below_swap' );
}
