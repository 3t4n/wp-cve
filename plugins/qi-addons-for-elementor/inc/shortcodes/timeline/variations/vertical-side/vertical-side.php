<?php

if ( ! function_exists( 'qi_addons_for_elementor_filter_timeline_layouts_variation_vertical_side' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_filter_timeline_layouts_variation_vertical_side( $variations ) {
		$variations['vertical-side'] = esc_html__( 'Vertical Side', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_timeline_layouts', 'qi_addons_for_elementor_filter_timeline_layouts_variation_vertical_side' );
}

if ( ! function_exists( 'qi_addons_for_elementor_filter_timeline_layouts_type_vertical_side' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param string $type
	 * @param string $layout
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_filter_timeline_layouts_type_vertical_side( $layouts ) {

		$layouts['vertical-side'] = 'vertical';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_timeline_layouts_type', 'qi_addons_for_elementor_filter_timeline_layouts_type_vertical_side', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_timeline_vertical_side_options' ) ) {
	/**
	 * Function that add additional options for variation layout
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_timeline_vertical_side_options( $options ) {
		$vertical_side = array();

		$side_width = array(
			'field_type' => 'slider',
			'name'       => 'vertical_side_side_width',
			'title'      => esc_html__( 'Side Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 300,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-timeline-layout--vertical-side .qodef-e-side-holder' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'vertical-side',
						'default-value' => 'vertical-side',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$content_width = array(
			'field_type' => 'slider',
			'name'       => 'vertical_side_content_width',
			'title'      => esc_html__( 'Content Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 300,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-timeline-layout--vertical-side .qodef-e-content-holder' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'vertical-side',
						'default-value' => 'vertical-side',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$item_side_padding = array(
			'field_type' => 'slider',
			'name'       => 'vertical_side_item_side_padding',
			'title'      => esc_html__( 'Item Side Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-timeline-layout--vertical-side .qodef-e-item-inner' => 'padding: 0 0 0 {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .qodef-timeline-layout--vertical-side .qodef-e-item.qodef-reverse .qodef-e-item-inner' => 'padding: 0 {{SIZE}}{{UNIT}} 0 0;',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'vertical-side',
						'default-value' => 'vertical-side',
					),
				),
			),
			'group'      => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$image_margin_right = array(
			'field_type'  => 'dimensions',
			'name'        => 'vertical_side_image_margins',
			'title'       => esc_html__( 'Image Margins', 'qi-addons-for-elementor' ),
			'description' => esc_html__( 'Left/right margins will be mirrored for items on the right', 'qi-addons-for-elementor' ),
			'size_units'  => array( 'px', '%', 'em' ),
			'responsive'  => true,
			'selectors'   => array(
				'{{WRAPPER}} .qodef-timeline-layout--vertical-side .qodef-e-side-holder'                             => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'{{WRAPPER}} .qodef-timeline-layout--vertical-side .qodef-e-item.qodef-reverse .qodef-e-side-holder' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency'  => array(
				'show' => array(
					'layout' => array(
						'values'        => 'vertical-side',
						'default-value' => 'vertical-side',
					),
				),
			),
			'group'       => esc_html__( 'Spacing Style', 'qi-addons-for-elementor' ),
		);

		$vertical_side[] = $side_width;
		$vertical_side[] = $content_width;
		$vertical_side[] = $item_side_padding;
		$vertical_side[] = $image_margin_right;

		return array_merge( $options, $vertical_side );
	}

	add_filter( 'qi_addons_for_elementor_filter_timeline_extra_options', 'qi_addons_for_elementor_add_timeline_vertical_side_options' );
}
