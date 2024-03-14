<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_list_variation_info_below_hover_inset' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_list_variation_info_below_hover_inset( $variations ) {
		$variations['info-below-hover-inset'] = esc_html__( 'Info Below Hover Inset', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_list_layouts', 'qi_addons_for_elementor_add_product_list_variation_info_below_hover_inset' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_list_options_info_below_hover_inset' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_list_options_info_below_hover_inset( $options ) {
		$info_below_hover_inset_options = array();

		$image_hover_background_color = array(
			'field_type' => 'color',
			'name'       => 'info_below_hover_inset_image_hover_background_color',
			'title'      => esc_html__( 'Image Hover Background Color', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-hover-inset .qodef-e-product-image-inner' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-hover-inset',
						'default_value' => '',
					),
				),
			),
		);

		$image_overlay_inner_offset = array(
			'field_type' => 'slider',
			'name'       => 'info_below_hover_inset_image_overlay_inner_offset',
			'title'      => esc_html__( 'Image Overlay Inner Offset', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-hover-inset .qodef-e-product-inner:hover .qodef-e-product-image-inner' => 'clip-path: inset({{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}});',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-hover-inset',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$category_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_hover_inset_category_margin_top',
			'title'      => esc_html__( 'Category Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-hover-inset .qodef-e-product-categories' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-hover-inset',
						'default_value' => '',
					),
				),
			),
		);

		$rating_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_below_hover_inset_rating_margin_top',
			'title'      => esc_html__( 'Rating Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-below-hover-inset .qodef-e-ratings' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-below-hover-inset',
						'default_value' => '',
					),
				),
			),
		);

		$info_below_hover_inset_options[] = $image_hover_background_color;
		$info_below_hover_inset_options[] = $image_overlay_inner_offset;
		$info_below_hover_inset_options[] = $category_margin_top;
		$info_below_hover_inset_options[] = $rating_margin_top;

		return array_merge( $options, $info_below_hover_inset_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_list_extra_options', 'qi_addons_for_elementor_add_product_list_options_info_below_hover_inset' );
}
