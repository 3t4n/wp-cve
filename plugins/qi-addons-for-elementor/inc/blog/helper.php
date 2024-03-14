<?php

if ( ! function_exists( 'qi_addons_for_elementor_include_blog_shortcodes' ) ) {
	/**
	 * Function that includes shortcodes
	 */
	function qi_addons_for_elementor_include_blog_shortcodes() {
		foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_INC_PATH . '/blog/shortcodes/*/include.php' ) as $shortcode ) {
			include_once $shortcode;
		}
	}

	add_action( 'qi_addons_for_elementor_action_framework_before_shortcodes_register', 'qi_addons_for_elementor_include_blog_shortcodes' );
}

if ( ! function_exists( 'qi_addons_for_elementor_include_blog_shortcodes_widget' ) ) {
	/**
	 * Function that includes widgets
	 */
	function qi_addons_for_elementor_include_blog_shortcodes_widget() {
		foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_INC_PATH . '/blog/shortcodes/*/widget/include.php' ) as $widget ) {
			include_once $widget;
		}
	}

	add_action( 'qi_addons_for_elementor_action_framework_before_widgets_register', 'qi_addons_for_elementor_include_blog_shortcodes_widget' );
}

if ( ! function_exists( 'qi_addons_for_elementor_enable_posts_order' ) ) {
	/**
	 * Function that enable page attributes options for blog single page
	 */
	function qi_addons_for_elementor_enable_posts_order() {
		add_post_type_support( 'post', 'page-attributes' );
	}

	add_action( 'admin_init', 'qi_addons_for_elementor_enable_posts_order' );
}

if ( ! function_exists( 'qi_addons_for_elementor_get_blog_list_excerpt_length' ) ) {
	/**
	 * Function that return number of characters for excerpt on blog list page
	 *
	 * @return int
	 */
	function qi_addons_for_elementor_get_blog_list_excerpt_length() {
		$length = apply_filters( 'qi_addons_for_elementor_filter_post_excerpt_length', 180 );

		return intval( $length );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_post_image' ) ) {

	function qi_addons_for_elementor_get_post_image( $post_id, $images_proportion, $custom_image_width, $custom_image_height ) {

		$image_id = apply_filters( 'qi_addons_for_elementor_filter_get_post_image_id', get_post_thumbnail_id(), $post_id );

		$image = qi_addons_for_elementor_get_list_shortcode_item_image( $images_proportion, $image_id, intval( $custom_image_width ), intval( $custom_image_height ) );

		return $image;
	}
}
