<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_slider_variation_info_below_swap' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_slider_variation_info_below_swap( $variations ) {
		$variations['info-below-swap'] = esc_html__( 'Info Below Swap', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_slider_layouts', 'qi_addons_for_elementor_add_product_slider_variation_info_below_swap' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_slider_options_info_below_swap' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_slider_options_info_below_swap( $options ) {
		$info_below_swap_options = array();

		$item_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_below_swap_item_padding',
			'title'      => esc_html__( 'Item Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-e-product-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$background_color = array(
			'field_type' => 'color',
			'name'       => 'info_below_swap_background_color',
			'title'      => esc_html__( 'Item Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-e-product-inner' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$background_hover_color = array(
			'field_type' => 'color',
			'name'       => 'info_below_swap_background_hover_color',
			'title'      => esc_html__( 'Item Background Hover Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-e-product-inner:hover' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-swap',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$category_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_swap_category_margin_top',
			'title'      => esc_html__( 'Category Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-swap .qodef-e-product-categories' => 'margin-top: {{SIZE}}{{UNIT}};',
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

		$info_below_swap_options[] = $item_padding;
		$info_below_swap_options[] = $background_color;
		$info_below_swap_options[] = $background_hover_color;
		$info_below_swap_options[] = $category_margin_top;
		$info_below_swap_options[] = $swap_margin_top;
		$info_below_swap_options[] = $rating_margin_top;
//		$info_below_swap_options[] = $price_margin_top;
//		$info_below_swap_options[] = $button_margin_top; //add it if necessary

		return array_merge( $options, $info_below_swap_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_slider_extra_options', 'qi_addons_for_elementor_add_product_slider_options_info_below_swap' );
}
