<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_pricing_table_variation_with_icon' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_pricing_table_variation_with_icon( $variations ) {

		$variations['with-icon'] = esc_html__( 'With Icon', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_pricing_table_layouts', 'qi_addons_for_elementor_add_pricing_table_variation_with_icon' );
}

if ( ! function_exists( 'qi_addons_for_elementor_pricing_table_with_icon_add_extra_options' ) ) {
	function qi_addons_for_elementor_pricing_table_with_icon_add_extra_options( $extra_options ) {
		$with_icon = array();

		$icon = array(
			'field_type' => 'icons',
			'name'       => 'with_icon_icon',
			'title'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'with-icon',
						'default_value' => 'standard',
					),
				),
			),
		);

		$icon_color = array(
			'field_type' => 'color',
			'name'       => 'with_icon_icon_color',
			'title'      => esc_html__( 'Icon Color', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-title-icon' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'with-icon',
						'default_value' => 'standard',
					),
				),
			),
		);

		$icon_size = array(
			'field_type' => 'slider',
			'name'       => 'with_icon_icon_size',
			'title'      => esc_html__( 'Icon Size', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-title-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'with-icon',
						'default_value' => 'standard',
					),
				),
			),
		);

		$top_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'with_icon_top_area_padding',
			'title'      => esc_html__( 'Top Area Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--with-icon .qodef-m-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'with-icon',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
		);

		$item_divider = array(
			'field_type' => 'divider',
			'name'       => 'item_style_divider',
			'title'      => esc_html__( 'Divider', 'qi-addons-for-elementor' ),
			'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
		);

		$item_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'with_icon_item_padding',
			'title'      => esc_html__( 'Item Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--with-icon .qodef-m-content .qodef-e-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'with-icon',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
		);

		$item_border_color = array(
			'field_type' => 'color',
			'name'       => 'with_icon_item_border_color',
			'title'      => esc_html__( 'Item Border Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--with-icon .qodef-m-content .qodef-e-item' => 'border-top-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'with-icon',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
		);

		$item_border_width = array(
			'field_type' => 'number',
			'name'       => 'with_icon_item_border_width',
			'title'      => esc_html__( 'Item Border Width (px)', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--with-icon .qodef-m-content .qodef-e-item' => 'border-top-width: {{VALUE}}px;',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'with-icon',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
		);

		$item_border_style = array(
			'field_type' => 'select',
			'name'       => 'with_icon_item_border_style',
			'title'      => esc_html__( 'Item Border Style', 'qi-addons-for-elementor' ),
			'options'    => qi_addons_for_elementor_get_select_type_options_pool( 'border_style' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-layout--with-icon .qodef-m-content .qodef-e-item' => 'border-top-style: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'with-icon',
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Table Style', 'qi-addons-for-elementor' ),
		);

		$with_icon[] = $icon;
		$with_icon[] = $icon_color;
		$with_icon[] = $icon_size;
		$with_icon[] = $top_padding;
		$with_icon[] = $item_divider;
		$with_icon[] = $item_padding;
		$with_icon[] = $item_border_color;
		$with_icon[] = $item_border_width;
		$with_icon[] = $item_border_style;

		return array_merge( $extra_options, $with_icon );
	}

	add_filter( 'qi_addons_for_elementor_filter_pricing_table_extra_options', 'qi_addons_for_elementor_pricing_table_with_icon_add_extra_options' );
}
