<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_shortcode( 'sf', 'wpfrank_sf_shortcode' );
function wpfrank_sf_shortcode( $sf_atts ) {
	ob_start();
	if ( isset( $sf_atts['id'] ) ) {
		$sf_slider_id = $sf_atts['id'];
	}
	if ( isset( $sf_atts['layout'] ) ) {
		$sf_slider_layout = $sf_atts['layout'];
	}

	// load slider settings
	$slider = get_option( 'sf_slider_' . $sf_slider_id );
	if ( isset( $slider['sf_slider_title'] ) ) {
		$sf_slider_title = $slider['sf_slider_title'];
	} else {
		$sf_slider_title = '';
	}
	if ( isset( $slider['sf_slider_desc'] ) ) {
		$sf_slider_desc = $slider['sf_slider_desc'];
	} else {
		$sf_slider_desc = '';
	}

	// print_r($slider);
	// echo "<hr>";

	// load slider start
	if ( $sf_slider_layout == 1 ) {
		require 'layouts/1.php';
	}
	if ( $sf_slider_layout == 2 ) {
		require 'layouts/2.php';
	}
	if ( $sf_slider_layout == 3 ) {
		require 'layouts/3.php';
	}
	if ( $sf_slider_layout == 4 ) {
		require 'layouts/4.php';
	}
	if ( $sf_slider_layout == 5 ) {
		require 'layouts/5.php';
	}
	if ( $sf_slider_layout == 6 ) {
		require 'layouts/6.php';
	}
	if ( $sf_slider_layout == 7 ) {
		require 'layouts/7.php';
	}
	if ( $sf_slider_layout == 8 ) {
		require 'layouts/8.php';
	}
	if ( $sf_slider_layout == 9 ) {
		require 'layouts/9.php';
	}
	if ( $sf_slider_layout == 10 ) {
		require 'layouts/10.php';
	}
	if ( $sf_slider_layout == 11 ) {
		include 'layouts/11.php';
	}
	if ( $sf_slider_layout == 12 ) {
		include 'layouts/12.php';
	}
	// load slider end

	return ob_get_clean();
}

