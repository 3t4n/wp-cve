<?php


// =============== Twenty twenty====================== .
 $current_theme = wp_get_theme();

if ( 'twentytwenty' == $current_theme->template ) {
	add_filter( 'body_class', 'ssb_add_body_class' );
}

/**
 * Add special class.
 *
 * @return void
 */
function ssb_add_body_class( $classes ) {

  $classes [] = 'ssb-twenty-twenty';

	return $classes;
}
