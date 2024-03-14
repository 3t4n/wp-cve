<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_category_list_variation_info_on_image' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_category_list_variation_info_on_image( $variations ) {
		$variations['info-on-image'] = esc_html__( 'Info On Image', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_category_list_layouts', 'qi_addons_for_elementor_add_product_category_list_variation_info_on_image' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_category_list_options_info_on_image' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_category_list_options_info_on_image( $options ) {
		$info_on_image_options = array();

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

		$title_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_on_image_content_padding',
			'title'      => esc_html__( 'Title Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .woocommerce-loop-category__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$info_on_image_options[] = $content_position;
		$info_on_image_options[] = $title_padding;

		return array_merge( $options, $info_on_image_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_category_list_extra_options', 'qi_addons_for_elementor_add_product_category_list_options_info_on_image' );
}
