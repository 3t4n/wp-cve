<?php
/**
 * Function to add the shortcodes for wp-forecast.
 *
 * @author Hans Matzen
 * @copyright 2009-2024
 * @since 2.4
 * @description wp-forecast using WordPress shortcode for more features
 * @Docs http://codex.wordpress.org/Shortcode_API
 *
 * @package wp-forecast
 */

if ( ! function_exists( 'wpforecast' ) ) {
	/**
	 * Function which adds the wp-forecast weather widget as shortcode.
	 *
	 * @param array $atts Parameters for the widget.
	 */
	function wpforecast( $atts ) {
		$lang   = '';
		$width  = '';
		$height = '';
		$id     = '';

		// parameter einlesen.
		if ( is_array( $atts ) ) {
			$lang   = ( isset( $atts['lang'] ) ? sanitize_key( $atts['lang'] ) : '' );
			$width  = ( isset( $atts['width'] ) ? intval( $atts['width'] ) : '' );
			$height = ( isset( $atts['height'] ) ? intval( $atts['height'] ) : '' );
			$id     = ( isset( $atts['id'] ) ? sanitize_key( $atts['id'] ) : 'A' );
		}

		// iframe tag zusammen bauen.
		$res = '<iframe class="wpf-iframe" src="' . site_url( 'wp-forecast-direct?wpfcid=' . $id . '&amp;header=1', __FILE__ );

		// falls eine sprache angegeben wurde haengen wir sie hinten dran.
		if ( '' != $lang ) {
			$res .= '&amp;language_override=' . $lang;
		}

		$res .= '" ';

		// falls eine breite angegeben wurde haengen wir sie hinten dran.
		if ( '' != $width && 0 != $width ) {
			$res .= "width='$width' ";
		}

		// falls eine sprache angegeben wurde haengen wir sie hinten dran.
		if ( '' != $height && 0 != $height ) {
			$res .= "height='$height' ";
		}

		$res .= '>' . __( 'wp-forecast shortcode: This browser does not support iframes.', 'wp-forecast' ) . '</iframe>';

		return $res;
	}
}
// shortcode bei wp anmelden.
if ( function_exists( 'add_shortcode' ) ) {
	add_shortcode( 'wpforecast', 'wpforecast' );
	add_filter( 'widget_text', 'do_shortcode' );
}


