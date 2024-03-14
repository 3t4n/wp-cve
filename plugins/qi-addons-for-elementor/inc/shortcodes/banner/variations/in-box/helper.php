<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_banner_variation_in_box' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_banner_variation_in_box( $variations ) {
		$variations['in-box'] = esc_html__( 'In Box', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_banner_layouts', 'qi_addons_for_elementor_add_banner_variation_in_box' );
}

if ( ! function_exists( 'qi_addons_for_elementor_banner_in_box_add_extra_options' ) ) {
	function qi_addons_for_elementor_banner_in_box_add_extra_options( $extra_options ) {
		$in_box = array();

		$content_background_color = array(
			'field_type' => 'color',
			'name'       => 'content_background_color',
			'title'      => esc_html__( 'Content Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-content-inner' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'in-box',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$content_border_radius = array(
			'field_type' => 'dimensions',
			'name'       => 'content_border_radius',
			'title'      => esc_html__( 'Content Border Radius', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'in-box',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$content_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'content_padding',
			'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'in-box',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$content_width = array(
			'field_type' => 'slider',
			'name'       => 'content_width',
			'title'      => esc_html__( 'Content Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%' ),
			'range'      => array(
				'px' => array(
					'min' => 100,
					'max' => 300,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-m-content-inner' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'in-box',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$content_alignment = array(
			'field_type'    => 'select',
			'name'          => 'content_text_align',
			'title'         => esc_html__( 'Content Text Alignment', 'qi-addons-for-elementor' ),
			'options'       => array(
				''       => esc_html__( 'Default', 'qi-addons-for-elementor' ),
				'left'   => esc_html__( 'Left', 'qi-addons-for-elementor' ),
				'center' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
				'right'  => esc_html__( 'Right', 'qi-addons-for-elementor' ),
			),
			'default_value' => '',
			'selectors'     => array(
				'{{WRAPPER}} .qodef-m-content-inner' => 'text-align: {{VALUE}};',
			),
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'in-box',
						'default_value' => '',
					),
				),
			),
			'group'         => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$in_box[] = $content_background_color;
		$in_box[] = $content_border_radius;
		$in_box[] = $content_padding;
		$in_box[] = $content_width;
		$in_box[] = $content_alignment;

		return array_merge( $extra_options, $in_box );
	}

	add_filter( 'qi_addons_for_elementor_filter_banner_extra_options', 'qi_addons_for_elementor_banner_in_box_add_extra_options' );
}
