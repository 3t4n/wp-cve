<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Use a standard function for the visual indicator for required form fields.
 * Function exists since WP 6.1.
 *
 * @return string Indicator glyph wrapped in a span tag.
 *
 * @since 4.5.0
 */
function gwolle_gb_wp_required_field_indicator() {

	if ( function_exists( 'wp_required_field_indicator' ) ) {
		return wp_required_field_indicator();
	}

	/* translators: Character to identify required form fields. */
	$glyph     = esc_html__( '*', 'gwolle-gb' );
	$indicator = '<span class="required">' . esc_html( $glyph ) . '</span>';

	return $indicator;

}
