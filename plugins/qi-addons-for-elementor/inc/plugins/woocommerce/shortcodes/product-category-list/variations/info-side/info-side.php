<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_product_category_list_variation_info_side' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_product_category_list_variation_info_side( $variations ) {
		$variations['info-side'] = esc_html__( 'Info Side with Button', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_product_category_list_layouts', 'qi_addons_for_elementor_add_product_category_list_variation_info_side' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_product_category_list_options_info_side' ) ) {
	function qi_addons_for_elementor_add_product_category_list_options_info_side( $options, $this_class ) {
		$info_side = array();

		$title_hover_color = array(
			'field_type' => 'color',
			'name'       => 'info_side_title_hover_color',
			'title'      => esc_html__( 'Title Hover Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-side .qodef-e-title > a:hover' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-side',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$content_horizontal_alignment = array(
			'field_type' => 'choose',
			'name'       => 'info_side_content_horizontal_alignment',
			'title'      => esc_html__( 'Content Horizontal Alignment', 'qi-addons-for-elementor' ),
			'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'alignment_icons', false ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-side .qodef-e-content' => 'text-align: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-side',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$content_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_side_content_padding',
			'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-side .qodef-e-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-side',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$image_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_side_image_padding',
			'title'      => esc_html__( 'Image Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-side .qodef-e-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-side',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$image_width = array(
			'field_type' => 'slider',
			'name'       => 'info_side_image_width',
			'title'      => esc_html__( 'Image Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-side .qodef-e-image' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-side',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$background_color = array(
			'field_type' => 'color',
			'name'       => 'info_side_background_color',
			'title'      => esc_html__( 'Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-side .qodef-e-holder-inner' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-side',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$content_vertical_alignment = array(
			'field_type' => 'select',
			'name'       => 'info_side_vertical_alignment',
			'title'      => esc_html__( 'Content Vertical Alignment', 'qi-addons-for-elementor' ),
			'options'    => array(
				'flex-start' => esc_html__( 'Top', 'qi-addons-for-elementor' ),
				'center'     => esc_html__( 'Middle', 'qi-addons-for-elementor' ),
				'flex-end'   => esc_html__( 'Bottom', 'qi-addons-for-elementor' ),
			),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-side .qodef-e-holder-inner' => 'align-items: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-side',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Style', 'qi-addons-for-elementor' ),
		);

		$button_top_margin = array(
			'field_type' => 'slider',
			'name'       => 'info_side_button_top_margin',
			'title'      => esc_html__( 'Button Top Margin', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-side .qodef-m-button' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-side',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$info_side[] = $title_hover_color;
		$info_side[] = $image_width;
		$info_side[] = $image_padding;
		$info_side[] = $content_horizontal_alignment;
		$info_side[] = $content_padding;
		$info_side[] = $background_color;
		$info_side[] = $content_vertical_alignment;
		$info_side[] = $button_top_margin;

		return array_merge( $options, $info_side );
	}

	add_filter( 'qi_addons_for_elementor_filter_product_category_list_extra_options', 'qi_addons_for_elementor_add_product_category_list_options_info_side', 10, 2 );
}
