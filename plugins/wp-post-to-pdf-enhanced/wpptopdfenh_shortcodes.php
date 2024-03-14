<?php
/**
 * wpptopdfenh_shortcodes.php
 *
 * This file contains shortcode functions.
 *
 */

if ( ! class_exists( 'TCPDF' ) ) {
	require_once WPPTOPDFENH_PATH . '/tcpdf/tcpdf.php';
}

//Add possible shortcodes here
/**
 * [wpptopdfenh]
 *
 * Adds a simple PDF icon, ignorant of any other include/exclude settings.
 *
 */
function wpptopdfenh_shortcode_func( $atts ) {
	return wpptopdfenh_display_shortcode_icon();
}
add_shortcode( 'wpptopdfenh', 'wpptopdfenh_shortcode_func' );

/**
 * [wpptopdfenh_break]
 *
 * Adds a page break to the PDF. We already ignore the built-in pagination function.
 *
 */
function wpptopdfenh_break_shortcode_func( $atts ) {
	return wpptopdfenh_do_shortcode_break();
}
add_shortcode( 'wpptopdfenh_break', 'wpptopdfenh_break_shortcode_func' );
function wpptopdfenh_do_shortcode_break() {
	$content = '<tcpdf method="AddPage" />';
	return $content;
}

// Define these common things here.
define( 'PARAMS_RTL', 'TCPDF_STATIC::serializeTCPDFtagParameters( \'true\' )' );
define( 'PARAMS_LTR', 'TCPDF_STATIC::serializeTCPDFtagParameters( \'false\' )' );

/**
 * [wpptopdfenh_ltr]
 *
 * Temporarily shifts the text between the shortcodes to LTR.
 *
 */
function wpptopdfenh_ltr_shortcode_func( $atts, $content = null ) {
	$content = '<tcpdf method="setRTL" params="' . PARAMS_LTR . '" />' . $content . '<tcpdf method="setRTL" params="' . PARAMS_RTL . '" />';
	return $content;
}
add_shortcode( 'wpptopdfenh_ltr', 'wpptopdfenh_ltr_shortcode_func' );

/**
 * [wpptopdfenh_rtl]
 *
 * Temporarily shifts the text between the shortcodes to RTL.
 *
 */
function wpptopdfenh_rtl_shortcode_func( $atts, $content = null ) {
	$content = '<tcpdf method="setRTL" params="' . PARAMS_RTL . '" />' . $content . '<tcpdf method="setRTL" params="' . PARAMS_LTR . '" />';
	return $content;
}
add_shortcode( 'wpptopdfenh_rtl', 'wpptopdfenh_rtl_shortcode_func' );

/**
 * [wpptopdfenh_opts opts="TCPDF parameters"]
 *
 * Passes specified TCPDF parameters into content where entered.
 *
 */
function wpptopdfenh_opts_shortcode_func( $atts ) {
	extract( shortcode_atts( array(
				'opts' => '',
			), $atts ) );
	$content = '<tcpdf method="{$opts}">';
	return $content;
}
add_shortcode( 'wpptopdfenh_opts', 'wpptopdfenh_opts_shortcode_func' );
