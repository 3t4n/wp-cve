<?php
function spawp_premium_get_dynamic_css() {
	$option = wp_parse_args(  get_option( 'spawp_option', array() ), spawp_theme_default_data() );
	require_once SPAWP_INC_DIR . 'classes/class-frontend-css.php';
	$pro_css = new Spawp_Pro_CSS;

	// slider
	$pro_css->set_selector( '.slide.overlay::before' );
	$pro_css->add_property( 'background-color', esc_attr( $option['slider_overlay_color'] ) );
	$pro_css->set_selector( '.slide .slide_subtitle' );
	$pro_css->add_property( 'color', esc_attr( $option['slider_subtitle_color'] ) );
	$pro_css->set_selector( '.slide .slide_title' );
	$pro_css->add_property( 'color', esc_attr( $option['slider_title_color'] ) );
	$pro_css->set_selector( '.slide .slide_decription' );
	$pro_css->add_property( 'color', esc_attr( $option['slider_desc_color'] ) );

	return apply_filters( 'spawp_dynamic_css', $pro_css );
}

/**
 * Enqueue Premium CSS styling.
 */
function spawp_premium_enqueue_dynamic_css() {
	$pro_css = spawp_premium_get_dynamic_css();
	wp_register_style( 'spawp-premium-style', false );
	wp_enqueue_style( 'spawp-premium-style' );

	wp_add_inline_style( 'spawp-premium-style', $pro_css->css_output() );
}
add_action( 'wp_enqueue_scripts', 'spawp_premium_enqueue_dynamic_css', 50 );