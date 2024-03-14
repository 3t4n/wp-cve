<?php

// prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// add the wp-image-borders class to the post container if the post_checkbox is checked
function bs_wib_add_post_class($classes) {

	// access the plugin options
	$options = get_option( 'wp_image_borders_options' );

	// if the checkbox is not empty and is checked, add the wp-image-borders class
	if ( 1 == $options['bs_wib_post_checkbox'] ) {
		$classes[] = "wp-image-borders";
	}
	return $classes;
}
add_filter('post_class', 'bs_wib_add_post_class');

function bs_wib_output_styles() {

	// access plugin options
	$options = get_option( 'wp_image_borders_options' );

	// create $css variable
	$css = '';

	// access the plugin options
	$options = get_option( 'wp_image_borders_options' );

	// if the classes section isn't empty
	if( $options['bs_wib_classes'] ) {

		// add the classes to the beginning of the output
		$css .= $options['bs_wib_classes'] . ',';
	}

	wp_enqueue_style( 'wp-image-borders-styles', BS_WIB_PRO_URL . 'wp-image-borders.css' );

	// add all the selectors need to precisely select all images
	$css .= '
		.wp-image-borders .alignright,
		.wp-image-borders .alignleft,
		.wp-image-borders .aligncenter,
		.wp-image-borders .alignnone,
		.wp-image-borders .size-auto,
		.wp-image-borders .size-full,
		.wp-image-borders .size-large,
		.wp-image-borders .size-medium,
		.wp-image-borders .size-thumbnail,
		.wp-image-borders .alignright img,
		.wp-image-borders .alignleft img,
		.wp-image-borders .aligncenter img,
		.wp-image-borders .alignnone img,
		.wp-image-borders .size-auto img,
		.wp-image-borders .size-full img,
		.wp-image-borders .size-large img,
		.wp-image-borders .size-medium img,
		.wp-image-borders .size-thumbnail img';
	$css .= ' {
	   border-style: ' . $options["bs_wib_border_style"] . ' !important;
	   border-width: ' . $options["bs_wib_border_width"] . 'px !important;
	   border-radius: ' . $options["bs_wib_border_radius"] . 'px !important;
	   border-color: ' . $options["bs_wib_border_color"] . ' !important;
	   -moz-box-shadow: ' . $options["bs_wib_box_shadow_horizontal"] . 'px ' . $options["bs_wib_box_shadow_vertical"] . 'px ' . $options["bs_wib_box_shadow_blur"] . 'px ' . $options["bs_wib_box_shadow_spread"] . 'px ' . $options["bs_wib_box_shadow_color"] . ' !important;
	   -webkit-box-shadow: ' . $options["bs_wib_box_shadow_horizontal"] . 'px ' . $options["bs_wib_box_shadow_vertical"] . 'px ' . $options["bs_wib_box_shadow_blur"] . 'px ' . $options["bs_wib_box_shadow_spread"] . 'px ' . $options["bs_wib_box_shadow_color"] . ' !important;
	   box-shadow: ' . $options["bs_wib_box_shadow_horizontal"] . 'px ' . $options["bs_wib_box_shadow_vertical"] . 'px ' . $options["bs_wib_box_shadow_blur"] . 'px ' . $options["bs_wib_box_shadow_spread"] . 'px ' . $options["bs_wib_box_shadow_color"] . ' !important;
   }';

	wp_add_inline_style('wp-image-borders-styles',$css);
}
add_action('wp_enqueue_scripts','bs_wib_output_styles');
