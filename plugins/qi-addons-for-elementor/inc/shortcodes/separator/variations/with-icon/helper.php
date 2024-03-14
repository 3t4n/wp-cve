<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_separator_variation_with_icon' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_separator_variation_with_icon( $variations ) {
		$variations['with-icon'] = esc_html__( 'With Icon', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_separator_layouts', 'qi_addons_for_elementor_add_separator_variation_with_icon' );
}

if ( ! function_exists( 'qi_addons_for_elementor_separator_with_icon_add_extra_options' ) ) {
	function qi_addons_for_elementor_separator_with_icon_add_extra_options( $extra_options ) {
		$with_icon = array();

		$icon_field = array(
			'field_type' => 'icons',
			'name'       => 'separator_icon',
			'title'      => esc_html__( 'Separator Icon', 'qi-addons-for-elementor' ),
			'dependency' => array(
				'show' => array(
					'separator_layout' => array(
						'values'        => array( 'with-icon' ),
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Icon', 'qi-addons-for-elementor' ),
		);

		$icon_color = array(
			'field_type' => 'color',
			'name'       => 'separator_icon_color',
			'title'      => esc_html__( 'icon Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-separator-icon' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'separator_layout' => array(
						'values'        => array( 'with-icon' ),
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
		);

		$icon_font_size = array(
			'field_type' => 'slider',
			'name'       => 'separator_icon_font_size',
			'title'      => esc_html__( 'Icon Font Size', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-separator-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'separator_layout' => array(
						'values'        => array( 'with-icon' ),
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
		);

		$icon_margin = array(
			'field_type' => 'dimensions',
			'name'       => 'separator_icon_margin',
			'title'      => esc_html__( 'Icon Margin', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-separator-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

			),
			'dependency' => array(
				'show' => array(
					'separator_layout' => array(
						'values'        => array( 'with-icon' ),
						'default_value' => 'standard',
					),
				),
			),
			'group'      => esc_html__( 'Icon Style', 'qi-addons-for-elementor' ),
		);

		$with_icon[] = $icon_field;
		$with_icon[] = $icon_color;
		$with_icon[] = $icon_font_size;
		$with_icon[] = $icon_margin;

		return array_merge( $extra_options, $with_icon );
	}

	add_filter( 'qi_addons_for_elementor_filter_separator_extra_options', 'qi_addons_for_elementor_separator_with_icon_add_extra_options' );
}
