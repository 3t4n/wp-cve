<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_variation_info_on_image' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_slider_variation_info_on_image( $variations ) {
		$variations['info-on-image'] = esc_html__( 'Info on Image', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layouts', 'qi_addons_for_elementor_add_blog_slider_variation_info_on_image' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_info_on_image_hide_options' ) ) {
	/**
	 * Function that adds layout on filter that hides excerpt options
	 *
	 * @param array $layouts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blog_slider_info_on_image_hide_options( $layouts ) {
		$layouts['info-on-image'] = 'info-on-image';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layout_hide_media', 'qi_addons_for_elementor_add_blog_slider_info_on_image_hide_options' );
	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layout_hide_excerpt', 'qi_addons_for_elementor_add_blog_slider_info_on_image_hide_options' );
	add_filter( 'qi_addons_for_elementor_filter_blog_slider_layout_hide_button', 'qi_addons_for_elementor_add_blog_slider_info_on_image_hide_options' );
}

if ( ! function_exists( 'qi_addons_for_elementor_load_blog_slider_variation_info_on_image_assets' ) ) {
	/**
	 * Function that return is global blog asses allowed for variation layout
	 *
	 * @param bool $is_enabled
	 * @param array $params
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_load_blog_slider_variation_info_on_image_assets( $is_enabled, $params ) {

		if ( 'info-on-image' === $params['layout'] ) {
			$is_enabled = true;
		}

		return $is_enabled;
	}

	add_filter( 'qi_addons_for_elementor_filter_load_blog_slider_assets', 'qi_addons_for_elementor_load_blog_slider_variation_info_on_image_assets', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_slider_info_on_image_scripts' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_slider_info_on_image_scripts( $scripts ) {

		$scripts['wp-mediaelement']    = array(
			'registered' => true,
		);
		$scripts['mediaelement-vimeo'] = array(
			'registered' => true,
		);

		return $scripts;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_register_scripts', 'qi_addons_for_elementor_register_blog_slider_info_on_image_scripts' );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_blog_slider_info_on_image_styles' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $styles
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_blog_slider_info_on_image_styles( $styles ) {

		$styles['wp-mediaelement'] = array(
			'registered' => true,
		);

		return $styles;
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_register_styles', 'qi_addons_for_elementor_register_blog_slider_info_on_image_styles' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_blog_slider_options_info_on_image' ) ) {
	function qi_addons_for_elementor_add_blog_slider_options_info_on_image( $options ) {
		$info_on_image_options = array();

		$full_height_slider = array(
			'field_type'           => 'checkbox',
			'name'                 => 'info_on_image_full_height',
			'title'                => esc_html__( 'Slider Full Height', 'qi-addons-for-elementor' ),
			'options'              => qi_addons_for_elementor_get_select_type_options_pool( 'yes_no', false ),
			'default_value'        => '',
			'selectors_dictionary' => array(
				'yes' => 'height: 100vh; object-fit: cover;',
				''    => 'height: auto;',
			),
			'selectors'            => array(
				'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-media-image img' => '{{VALUE}}',
			),
			'dependency'           => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-image',
						'default_value' => '',
					),
				),
			),
			'group'                => esc_html__( 'Layout', 'qi-addons-for-elementor' ),
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

		$info_on_image_options[] = $full_height_slider;
		$info_on_image_options[] = $content_padding;

		return array_merge( $options, $info_on_image_options );
	}

	add_filter( 'qi_addons_for_elementor_filter_blog_slider_extra_options', 'qi_addons_for_elementor_add_blog_slider_options_info_on_image' );
}
