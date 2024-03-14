<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_list_variation_info_on_image' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_list_variation_info_on_image( $variations ) {
		$variations['info-on-image'] = esc_html__( 'Info On Image', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_list_layouts', 'qi_addons_for_elementor_add_product_list_variation_info_on_image' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_list_options_info_on_image' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_list_options_info_on_image( $options ) {
		$info_on_image_options = array();

		$image_hover_background_color = array(
			'field_type' => 'color',
			'name'       => 'info_on_image_image_hover_background_color',
			'title'      => esc_html__( 'Image Hover Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-product-image-inner' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$content_position = array(
			'field_type'   => 'select',
			'name'         => 'info_on_image_content_position',
			'title'        => esc_html__( 'Content Position', 'qi-addons-for-elementor' ),
			'options'      => array(
				'center'      => esc_html__( 'Center', 'qi-addons-for-elementor' ),
				'bottom-left' => esc_html__( 'Bottom Left', 'qi-addons-for-elementor' ),
			),
			'prefix_class' => 'qodef-position--',
			'dependency'   => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'        => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$title_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_title_margin_top',
			'title'      => esc_html__( 'Title Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-product-title' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
		);

		$price_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_price_margin_top',
			'title'      => esc_html__( 'Price Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-woo-product-price' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
		);

		$rating_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_rating_margin_top',
			'title'      => esc_html__( 'Rating Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-ratings' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
		);

		$button_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_button_margin_top',
			'title'      => esc_html__( 'Button Margin Top', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-qi-button' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
		);

		$info_on_image_options[] = $image_hover_background_color;
		$info_on_image_options[] = $content_position;
		$info_on_image_options[] = $title_margin_top;
		$info_on_image_options[] = $price_margin_top;
		$info_on_image_options[] = $rating_margin_top;
		$info_on_image_options[] = $button_margin_top;

		return array_merge( $options, $info_on_image_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_list_extra_options', 'qi_addons_for_elementor_add_product_list_options_info_on_image' );
}
