<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_list_image_sizes' ) ) {
	/**
	 * Function that extended global image size options
	 *
	 * @param array $image_sizes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_list_image_sizes( $image_sizes ) {
		$image_sizes[] = array(
			'slug'           => 'qi_addons_for_elementor_image_size_square',
			'label'          => esc_html__( 'Qi Addons Square Size', 'qi-addons-for-elementor' ),
			'label_simple'   => esc_html__( 'Square', 'qi-addons-for-elementor' ),
			'default_crop'   => true,
			'default_width'  => 650,
			'default_height' => 650,
		);

		$image_sizes[] = array(
			'slug'           => 'qi_addons_for_elementor_image_size_landscape',
			'label'          => esc_html__( 'Qi Addons Landscape Size', 'qi-addons-for-elementor' ),
			'label_simple'   => esc_html__( 'Landscape', 'qi-addons-for-elementor' ),
			'default_crop'   => true,
			'default_width'  => 1300,
			'default_height' => 650,
		);

		$image_sizes[] = array(
			'slug'           => 'qi_addons_for_elementor_image_size_portrait',
			'label'          => esc_html__( 'Qi Addons Portrait Size', 'qi-addons-for-elementor' ),
			'label_simple'   => esc_html__( 'Portrait', 'qi-addons-for-elementor' ),
			'default_crop'   => true,
			'default_width'  => 650,
			'default_height' => 1300,
		);

		$image_sizes[] = array(
			'slug'           => 'qi_addons_for_elementor_image_size_huge-square',
			'label'          => esc_html__( 'Qi Addons Huge Square Size', 'qi-addons-for-elementor' ),
			'label_simple'   => esc_html__( 'Huge Square', 'qi-addons-for-elementor' ),
			'default_crop'   => true,
			'default_width'  => 1300,
			'default_height' => 1300,
		);

		return $image_sizes;
	}

	add_filter( 'qi_addons_for_elementor_filter_framework_populate_image_sizes', 'qi_addons_for_elementor_add_list_image_sizes' );
}

if ( ! function_exists( 'qi_addons_for_elementor_add_pool_masonry_list_image_sizes' ) ) {
	/**
	 * Function that add global masonry image size options
	 *
	 * @param array $options
	 * @param string $type
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_pool_masonry_list_image_sizes( $options, $type ) {
		if ( 'masonry_image_dimension' === $type ) {
			$options['qi_addons_for_elementor_image_size_square']      = esc_html__( 'Square', 'qi-addons-for-elementor' );
			$options['qi_addons_for_elementor_image_size_landscape']   = esc_html__( 'Landscape', 'qi-addons-for-elementor' );
			$options['qi_addons_for_elementor_image_size_portrait']    = esc_html__( 'Portrait', 'qi-addons-for-elementor' );
			$options['qi_addons_for_elementor_image_size_huge-square'] = esc_html__( 'Huge Square', 'qi-addons-for-elementor' );
		}

		return $options;
	}

	add_filter( 'qi_addons_for_elementor_filter_select_type_option', 'qi_addons_for_elementor_add_pool_masonry_list_image_sizes', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_get_custom_image_size_class_name' ) ) {
	/**
	 * Function that return custom image size class name
	 *
	 * @param string $image_size
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_get_custom_image_size_class_name( $image_size ) {
		return ! empty( $image_size ) ? 'qodef-item--' . str_replace( 'qi_addons_for_elementor_image_size_', '', $image_size ) : '';
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_custom_image_size_meta' ) ) {
	/**
	 * Function that return custom image size meta value
	 *
	 * @param string $type
	 * @param string $name
	 * @param int $post_id
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_get_custom_image_size_meta( $type, $name, $post_id ) {
		$image_size_meta = qi_addons_for_elementor_framework_get_option_value( '', $type, $name, '', $post_id );
		$image_size      = ! empty( $image_size_meta ) ? esc_attr( $image_size_meta ) : 'full';

		return array(
			'size'  => $image_size,
			'class' => qi_addons_for_elementor_get_custom_image_size_class_name( $image_size ),
		);
	}
}
