<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_variation_standard' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_slider_variation_standard( $variations ) {
		$variations['standard'] = esc_html__( 'Standard', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layouts', 'qi_addons_for_elementor_add_blog_slider_variation_standard' );
}

if ( ! function_exists( 'qi_addons_for_elementor_load_blog_slider_variation_standard_assets' ) ) {
	/**
	 * Function that return is global blog asses allowed for variation layout
	 *
	 * @param bool $is_enabled
	 * @param array $params
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_load_blog_slider_variation_standard_assets( $is_enabled, $params ) {

		if ( 'standard' === $params['layout'] ) {
			$is_enabled = true;
		}

		return $is_enabled;
	}

	add_filter( 'qi_addons_for_elementor_filter_load_blog_slider_assets', 'qi_addons_for_elementor_load_blog_slider_variation_standard_assets', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_slider_standard_scripts' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_slider_standard_scripts( $scripts ) {

		$scripts['wp-mediaelement']    = array(
			'registered' => true,
		);
		$scripts['mediaelement-vimeo'] = array(
			'registered' => true,
		);

		return $scripts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_register_scripts', 'qi_addons_for_elementor_register_blog_slider_standard_scripts' );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_slider_standard_styles' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $styles
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_slider_standard_styles( $styles ) {

		$styles['wp-mediaelement'] = array(
			'registered' => true,
		);

		return $styles;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_register_styles', 'qi_addons_for_elementor_register_blog_slider_standard_styles' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_standard_hide_options' ) ) {
	/**
	 * Function that adds layout on filter that hides excerpt options
	 *
	 * @param array $layouts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_slider_standard_hide_options( $layouts ) {
		$layouts['standard'] = 'standard';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layout_hide_button', 'qi_addons_for_elementor_add_blog_slider_standard_hide_options' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_options_standard' ) ) {
	function qi_addons_for_elementor_add_blog_slider_options_standard( $options ) {
		$standard_options = array();

		$image_margin = array(
			'field_type' => 'slider',
			'name'       => 'standard_image_margin_bottom',
			'title'      => esc_html__( 'Image Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--standard .qodef-e-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
		);

		$text_margin = array(
			'field_type' => 'slider',
			'name'       => 'standard_text_margin_bottom',
			'title'      => esc_html__( 'Text Margin Bottom', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--standard .qodef-e-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
		);

		$bottom_info_color = array(
			'field_type' => 'color',
			'name'       => 'standard_bottom_info_color',
			'title'      => esc_html__( 'Bottom Info Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--standard .qodef-e-info.qodef-info--bottom .qodef-e-info-item' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Info Style', 'qi-addons-for-elementor' ),
		);

		$bottom_info_hover_color = array(
			'field_type' => 'color',
			'name'       => 'standard_bottom_info_hover_color',
			'title'      => esc_html__( 'Bottom Info Hover Color', 'qi-addons-for-elementor' ),
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--standard .qodef-e-info.qodef-info--bottom .qodef-e-info-item a:hover' => 'color: {{VALUE}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Info Style', 'qi-addons-for-elementor' ),
		);

		$bottom_info_typography = array(
			'field_type' => 'typography',
			'name'       => 'standard_bottom_info_typography',
			'title'      => esc_html__( 'Bottom Info Typography', 'qi-addons-for-elementor' ),
			'selector'   => '{{WRAPPER}} .qodef-item-layout--standard .qodef-e-info.qodef-info--bottom .qodef-e-info-item',
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'standard',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Info Style', 'qi-addons-for-elementor' ),
		);

		$standard_options[] = $image_margin;
		$standard_options[] = $text_margin;
		$standard_options[] = $bottom_info_color;
		$standard_options[] = $bottom_info_hover_color;
		$standard_options[] = $bottom_info_typography;

		return array_merge( $options, $standard_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_extra_options', 'qi_addons_for_elementor_add_blog_slider_options_standard' );
}
