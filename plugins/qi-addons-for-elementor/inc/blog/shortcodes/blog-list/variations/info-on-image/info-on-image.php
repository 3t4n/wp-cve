<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_list_variation_info_on_image' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_list_variation_info_on_image( $variations ) {
		$variations['info-on-image'] = esc_html__( 'Info on Image', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_list_layouts', 'qi_addons_for_elementor_add_blog_list_variation_info_on_image' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_list_info_on_image_hide_options' ) ) {
	/**
	 * Function that adds layout on filter that hides excerpt options
	 *
	 * @param array $layouts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_list_info_on_image_hide_options( $layouts ) {
		$layouts['info-on-image'] = 'info-on-image';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_list_layout_hide_media', 'qi_addons_for_elementor_add_blog_list_info_on_image_hide_options' );
	add_filter( 'qi_addons_for_elementor_filter_blog_list_layout_hide_excerpt', 'qi_addons_for_elementor_add_blog_list_info_on_image_hide_options' );
	add_filter( 'qi_addons_for_elementor_filter_blog_list_layout_hide_button', 'qi_addons_for_elementor_add_blog_list_info_on_image_hide_options' );
}

if ( ! function_exists( 'qi_addons_for_elementor_load_blog_list_variation_info_on_image_assets' ) ) {
	/**
	 * Function that return is global blog asses allowed for variation layout
	 *
	 * @param bool $is_enabled
	 * @param array $params
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_load_blog_list_variation_info_on_image_assets( $is_enabled, $params ) {

		if ( 'info-on-image' === $params['layout'] ) {
			$is_enabled = true;
		}

		return $is_enabled;
	}

	add_filter( 'qi_addons_for_elementor_filter_load_blog_list_assets', 'qi_addons_for_elementor_load_blog_list_variation_info_on_image_assets', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_list_info_on_image_scripts' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_list_info_on_image_scripts( $scripts ) {

		$scripts['wp-mediaelement']    = array(
			'registered' => true,
		);
		$scripts['mediaelement-vimeo'] = array(
			'registered' => true,
		);

		return $scripts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_list_register_scripts', 'qi_addons_for_elementor_register_blog_list_info_on_image_scripts' );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_list_info_on_image_styles' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $styles
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_list_info_on_image_styles( $styles ) {

		$styles['wp-mediaelement'] = array(
			'registered' => true,
		);

		return $styles;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_list_register_styles', 'qi_addons_for_elementor_register_blog_list_info_on_image_styles' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_list_options_info_on_image' ) ) {
	function qi_addons_for_elementor_add_blog_list_options_info_on_image( $options ) {
		$info_on_image_options = array();

		$date_color = array(
			'field_type' => 'color',
			'name'       => 'info_on_image_date_color',
			'title'      => esc_html__( 'Date Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_hover_color = array(
			'field_type' => 'color',
			'name'       => 'info_on_image_date_hover_color',
			'title'      => esc_html__( 'Date Hover Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date a:hover' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_typography = array(
			'field_type' => 'typography',
			'name'       => 'info_on_image_date_typography',
			'title'      => esc_html__( 'Date Typography', 'qi-addons-for-elementor' ),
			'selector'   => '{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date',
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_background_color = array(
			'field_type' => 'color',
			'name'       => 'info_on_image_date_background_color',
			'title'      => esc_html__( 'Date Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_on_image_date_padding',
			'title'      => esc_html__( 'Date Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$content_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'info_on_image_content_padding',
			'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
		);

		$date_border_radius = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_date_border_radius',
			'title'      => esc_html__( 'Date Border Radius', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'border-radius: {{SIZE}}{{UNIT}};',
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_vertical_offset = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_date_vertical_offset',
			'title'      => esc_html__( 'Date Vertical Offset', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'top: {{SIZE}}{{UNIT}};',
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_horizontal_offset = array(
			'field_type' => 'slider',
			'name'       => 'info_on_image_date_horizontal_offset',
			'title'      => esc_html__( 'Date Horizontal Offset', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'right: {{SIZE}}{{UNIT}};',
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$info_on_image_options[] = $date_color;
		$info_on_image_options[] = $date_hover_color;
		$info_on_image_options[] = $date_typography;
		$info_on_image_options[] = $date_background_color;
		$info_on_image_options[] = $date_padding;
		$info_on_image_options[] = $content_padding;
		$info_on_image_options[] = $date_border_radius;
		$info_on_image_options[] = $date_vertical_offset;
		$info_on_image_options[] = $date_horizontal_offset;

		return array_merge( $options, $info_on_image_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_list_extra_options', 'qi_addons_for_elementor_add_blog_list_options_info_on_image' );
}
