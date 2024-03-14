<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_list_variation_minimal' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_list_variation_minimal( $variations ) {
		$variations['minimal'] = esc_html__( 'Minimal', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_list_layouts', 'qi_addons_for_elementor_add_blog_list_variation_minimal' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_list_minimal_hide_options' ) ) {
	/**
	 * Function that adds layout on filter that hides excerpt options
	 *
	 * @param array $layouts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_list_minimal_hide_options( $layouts ) {
		$layouts['minimal'] = 'minimal';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_list_layout_hide_media', 'qi_addons_for_elementor_add_blog_list_minimal_hide_options' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_list_options_minimal' ) ) {
	function qi_addons_for_elementor_add_blog_list_options_minimal( $options ) {
		$minimal_options = array();

		$padding_top = array(
			'field_type' => 'slider',
			'name'       => 'minimal_item_padding_bottom',
			'title'      => esc_html__( 'Item Padding Top', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--minimal .qodef-e-inner' => 'padding-top: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .qodef-item-layout--minimal .qodef-grid-inner' => 'margin-top: -{{SIZE}}{{UNIT}} !important;',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'minimal',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
		);

		$button_margin_top = array(
			'field_type' => 'slider',
			'name'       => 'minimal_button_margin_top',
			'title'      => esc_html__( 'Button Margin Top', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--minimal .qodef-e-info.qodef-info--bottom' => 'margin-top: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'minimal',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
		);

		$border_top_color = array(
			'field_type' => 'color',
			'name'       => 'minimal_border_bottom_color',
			'title'      => esc_html__( 'Border Top Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--minimal .qodef-e-inner' => 'border-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'minimal',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$border_top_thickness = array(
			'field_type' => 'slider',
			'name'       => 'minimal_border_bottom_thickness',
			'title'      => esc_html__( 'Border Top Thickness', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--minimal .qodef-e-inner' => 'border-top-width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'minimal',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$border_top_style = array(
			'field_type' => 'select',
			'name'       => 'minimal_border_bottom_type',
			'title'      => esc_html__( 'Border Top Style', 'qi-addons-for-elementor' ),
			'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'border_style' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--minimal .qodef-e-inner' => 'border-top-style: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'minimal',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$minimal_options[] = $button_margin_top;
		$minimal_options[] = $padding_top;
		$minimal_options[] = $border_top_color;
		$minimal_options[] = $border_top_thickness;
		$minimal_options[] = $border_top_style;

		return array_merge( $options, $minimal_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_list_extra_options', 'qi_addons_for_elementor_add_blog_list_options_minimal' );
}
