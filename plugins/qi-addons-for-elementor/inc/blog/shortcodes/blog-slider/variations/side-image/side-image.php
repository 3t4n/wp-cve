<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_variation_side_image' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_slider_variation_side_image( $variations ) {
		$variations['side-image'] = esc_html__( 'Side Image', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layouts', 'qi_addons_for_elementor_add_blog_slider_variation_side_image' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_side_image_hide_excerpt' ) ) {
	/**
	 * Function that adds layout on filter that hides excerpt options
	 *
	 * @param array $layouts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_slider_side_image_hide_excerpt( $layouts ) {
		$layouts['side-image'] = 'side-image';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layout_hide_excerpt', 'qi_addons_for_elementor_add_blog_slider_side_image_hide_excerpt' );
}

if ( ! function_exists( 'qi_addons_for_elementor_load_blog_slider_variation_side_image_assets' ) ) {
	/**
	 * Function that return is global blog asses allowed for variation layout
	 *
	 * @param bool $is_enabled
	 * @param array $params
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_load_blog_slider_variation_side_image_assets( $is_enabled, $params ) {

		if ( 'side-image' === $params['layout'] ) {
			$is_enabled = true;
		}

		return $is_enabled;
	}

	add_filter( 'qi_addons_for_elementor_filter_load_blog_slider_assets', 'qi_addons_for_elementor_load_blog_slider_variation_side_image_assets', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_slider_side_image_scripts' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_slider_side_image_scripts( $scripts ) {

		$scripts['wp-mediaelement']    = array(
			'registered' => true,
		);
		$scripts['mediaelement-vimeo'] = array(
			'registered' => true,
		);

		return $scripts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_register_scripts', 'qi_addons_for_elementor_register_blog_slider_side_image_scripts' );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_slider_side_image_styles' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $styles
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_slider_side_image_styles( $styles ) {

		$styles['wp-mediaelement'] = array(
			'registered' => true,
		);

		return $styles;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_register_styles', 'qi_addons_for_elementor_register_blog_slider_side_image_styles' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_options_side_image' ) ) {
	function qi_addons_for_elementor_add_blog_slider_options_side_image( $options ) {
		$side_image_options = array();

		$image_margin = array(
			'field_type' => 'slider',
			'name'       => 'side_image_image_width',
			'title'      => esc_html__( 'Image Width', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'vw' ),
			'range'      => array(
				'px' => array(
					'min' => 1,
					'max' => 500,
				),
			),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-image .qodef-e-media' => 'width: {{SIZE}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Layout Spacing Style', 'qi-addons-for-elementor' ),
		);

		$content_padding = array(
			'field_type' => 'dimensions',
			'name'       => 'side_image_content_padding',
			'title'      => esc_html__( 'Content Padding', 'qi-addons-for-elementor' ),
			'size_units' => array( 'px', '%', 'em' ),
			'responsive' => true,
			'selectors'  => array(
				'{{WRAPPER}} .qodef-item-layout--side-image .qodef-e-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'side-image',
						'default_value' => '',
					),
				),
			),
			'group'      => esc_html__( 'Content Style', 'qi-addons-for-elementor' ),
		);

		$side_image_options[] = $image_margin;
		$side_image_options[] = $content_padding;

		return array_merge( $options, $side_image_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_extra_options', 'qi_addons_for_elementor_add_blog_slider_options_side_image' );
}
