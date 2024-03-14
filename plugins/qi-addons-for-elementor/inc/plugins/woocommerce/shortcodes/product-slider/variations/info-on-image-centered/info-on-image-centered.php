<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_slider_variation_info_on_image_centered' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_slider_variation_info_on_image_centered( $variations ) {
		$variations['info-on-image-centered'] = esc_html__( 'Info On Image Centered', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_slider_layouts', 'qi_addons_for_elementor_add_product_slider_variation_info_on_image_centered' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_slider_options_info_on_image_centered' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_slider_options_info_on_image_centered( $options ) {
		$info_on_image_centered_options = array();

		$image_hover_background_color = array(
			'field_type' => 'color',
			'name'       => 'info_on_image_centered_image_hover_background_color',
			'title'      => esc_html__( 'Image Hover Background Color', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-centered .qodef-e-product-image-inner' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-centered',
						'default_value' => '',
					),
				),
			),
		);

		$title_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_centered_title_margin_top',
			'title'      => esc_html__( 'Title Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-centered .qodef-e-product-title' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-centered',
						'default_value' => '',
					),
				),
			),
		);

		$price_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_centered_price_margin_top',
			'title'      => esc_html__( 'Price Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-centered .qodef-woo-product-price' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-centered',
						'default_value' => '',
					),
				),
			),
		);

		$rating_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_centered_rating_margin_top',
			'title'      => esc_html__( 'Rating Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-centered .qodef-e-ratings' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-centered',
						'default_value' => '',
					),
				),
			),
		);

		$button_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_centered_button_margin_top',
			'title'      => esc_html__( 'Button Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-centered .qodef-qi-button' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-centered',
						'default_value' => '',
					),
				),
			),
		);

		$info_on_image_centered_options[] = $image_hover_background_color;
		$info_on_image_centered_options[] = $title_margin_top;
		$info_on_image_centered_options[] = $price_margin_top;
		$info_on_image_centered_options[] = $rating_margin_top;
		$info_on_image_centered_options[] = $button_margin_top;

		return array_merge( $options, $info_on_image_centered_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_slider_extra_options', 'qi_addons_for_elementor_add_product_slider_options_info_on_image_centered' );
}
