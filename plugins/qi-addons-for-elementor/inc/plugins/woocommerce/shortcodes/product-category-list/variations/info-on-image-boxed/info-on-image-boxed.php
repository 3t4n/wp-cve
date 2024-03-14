<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_category_list_variation_info_on_image_boxed' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_category_list_variation_info_on_image_boxed( $variations ) {
		$variations['info-on-image-boxed'] = esc_html__( 'Info On Image Boxed', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_category_list_layouts', 'qi_addons_for_elementor_add_product_category_list_variation_info_on_image_boxed' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_category_list_options_info_on_image_boxed' ) ) {
	function qi_addons_for_elementor_add_product_category_list_options_info_on_image_boxed( $options ) {
		$info_on_image_boxed = array();

		$title_box_width = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_boxed_title_box_width',
			'title'      => esc_html__( 'Title Box Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'range'      => array(
				'px' => array(
					'min' => 100,
					'max' => 500,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-boxed .woocommerce-loop-category__title' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$title_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_on_image_boxed_title_padding',
			'title'      => esc_html__( 'Title Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-boxed .woocommerce-loop-category__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$title_background_color = array(
			'field_type' => 'color',
			'name'       => 'info_on_image_boxed_title_background_color',
			'title'      => esc_html__( 'Title Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-boxed .woocommerce-loop-category__title' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$title_bottom_offset = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_boxed_title_bottom_offset',
			'title'      => esc_html__( 'Title Bottom Offset', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 300,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image-boxed .woocommerce-loop-category__title' => 'bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$info_on_image_boxed[] = $title_box_width;
		$info_on_image_boxed[] = $title_padding;
		$info_on_image_boxed[] = $title_background_color;
		$info_on_image_boxed[] = $title_bottom_offset;

		return array_merge( $options, $info_on_image_boxed );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_category_list_extra_options', 'qi_addons_for_elementor_add_product_category_list_options_info_on_image_boxed' );
}
