<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_variation_date_boxed' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_slider_variation_date_boxed( $variations ) {
		$variations['date-boxed'] = esc_html__( 'Date Boxed', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layouts', 'qi_addons_for_elementor_add_blog_slider_variation_date_boxed' );
}

if ( ! function_exists( 'qi_addons_for_elementor_load_blog_slider_variation_date_boxed_assets' ) ) {
	/**
	 * Function that return is global blog asses allowed for variation layout
	 *
	 * @param bool $is_enabled
	 * @param array $params
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_load_blog_slider_variation_date_boxed_assets( $is_enabled, $params ) {

		if ( 'date-boxed' === $params['layout'] ) {
			$is_enabled = true;
		}

		return $is_enabled;
	}

	add_filter( 'qi_addons_for_elementor_filter_load_blog_slider_assets', 'qi_addons_for_elementor_load_blog_slider_variation_date_boxed_assets', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_slider_date_boxed_scripts' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_slider_date_boxed_scripts( $scripts ) {

		$scripts['wp-mediaelement']    = array(
			'registered' => true,
		);
		$scripts['mediaelement-vimeo'] = array(
			'registered' => true,
		);

		return $scripts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_register_scripts', 'qi_addons_for_elementor_register_blog_slider_date_boxed_scripts' );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_slider_date_boxed_styles' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $styles
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_slider_date_boxed_styles( $styles ) {

		$styles['wp-mediaelement'] = array(
			'registered' => true,
		);

		return $styles;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_register_styles', 'qi_addons_for_elementor_register_blog_slider_date_boxed_styles' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_options_date_boxed' ) ) {
	function qi_addons_for_elementor_add_blog_slider_options_date_boxed( $options ) {
		$date_boxed_options = array();

		$image_margin = array(
			'field_type' => 'slider',
			'name'       => 'date_boxed_image_margin_bottom',
			'title'      => esc_html__( 'Image Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'date-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
		);

		$text_margin = array(
			'field_type' => 'slider',
			'name'       => 'date_boxed_text_margin_bottom',
			'title'      => esc_html__( 'Text Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'date-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
		);

		$date_color = array(
			'field_type' => 'color',
			'name'       => 'date_boxed_date_color',
			'title'      => esc_html__( 'Date Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'date-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_hover_color = array(
			'field_type' => 'color',
			'name'       => 'date_boxed_date_hover_color',
			'title'      => esc_html__( 'Date Hover Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date a:hover' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'date-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_typography = array(
			'field_type' => 'typography',
			'name'       => 'date_boxed_date_typography',
			'title'      => esc_html__( 'Date Typography', 'qi-addons-for-elementor' ),
			'selector'   => '{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date',
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'date-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_background_color = array(
			'field_type' => 'color',
			'name'       => 'date_boxed_date_background_color',
			'title'      => esc_html__( 'Date Background Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date' => 'background-color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'date-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'date_boxed_date_padding',
			'title'      => esc_html__( 'Date Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'date-boxed',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Date Style', 'qi-addons-for-elementor' ),
		);

		$date_boxed_options[] = $image_margin;
		$date_boxed_options[] = $text_margin;
		$date_boxed_options[] = $date_color;
		$date_boxed_options[] = $date_hover_color;
		$date_boxed_options[] = $date_typography;
		$date_boxed_options[] = $date_background_color;
		$date_boxed_options[] = $date_padding;

		return array_merge( $options, $date_boxed_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_extra_options', 'qi_addons_for_elementor_add_blog_slider_options_date_boxed' );
}
