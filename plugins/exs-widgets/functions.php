<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//assets
if ( ! function_exists( 'exs_widgets_enqueue_static' ) ) :
	function exs_widgets_enqueue_static() {
		wp_register_style( 'exs-widgets-style', plugins_url( '/assets/css/exs-widgets.css', __FILE__ ), array(), '0.0.1' );
		wp_enqueue_style( 'exs-widgets-style' );
	}
endif;
add_action( 'wp_enqueue_scripts', 'exs_widgets_enqueue_static' );

//no icon for other themes as we use 'get_template_part' for SVG icons
if ( ! function_exists( 'exs_icon' ) ) :
	function exs_icon( $exs_name, $exs_return = false, $exs_container_css_class = 'svg-icon' ) {
		return '';
	}
endif;

//no icon for other themes as we use 'get_template_part' for SVG icons
if ( ! function_exists( 'exs_post_format_icon' ) ) :
	function exs_post_format_icon( $exs_post_format = '' ) {
		return '';
	}
endif;
