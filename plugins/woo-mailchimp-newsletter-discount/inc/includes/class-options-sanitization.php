<?php
/**
 * @package   WCMND_Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 */

/* Text */

add_filter( 'wcmnd_of_sanitize_text', 'sanitize_text_field' );


/* Password */

add_filter( 'wcmnd_of_sanitize_password', 'sanitize_text_field' );

/* Number */

add_filter( 'wcmnd_of_sanitize_number', 'sanitize_text_field' );

/* Icon Field */

add_filter( 'wcmnd_of_sanitize_icon', 'sanitize_text_field' );

/* Textarea */

function wcmnd_of_sanitize_textarea(  $input) {
	global $allowedposttags;
	$output = wp_kses( $input, $allowedposttags);
	return $output;
}

add_filter( 'wcmnd_of_sanitize_textarea', 'wcmnd_of_sanitize_textarea' );

/* CS Editor */
add_filter( 'wcmnd_of_sanitize_cseditor', 'sanitize_text_field' );

/* Select */

add_filter( 'wcmnd_of_sanitize_select', 'wcmnd_of_sanitize_enum', 10, 2 );


add_filter( 'wcmnd_of_sanitize_selectcustom', 'wcmnd_of_sanitize_select_enum', 10, 2 );


/* Radio */

add_filter( 'wcmnd_of_sanitize_radio', 'wcmnd_of_sanitize_enum', 10, 2 );

/* Images */

add_filter( 'wcmnd_of_sanitize_images', 'wcmnd_of_sanitize_enum', 10, 2 );

/* Checkbox */

function wcmnd_of_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = 'yes';
	} else {
		$output = false;
	}
	return $output;
}
add_filter( 'wcmnd_of_sanitize_checkbox', 'wcmnd_of_sanitize_checkbox' );


/* On/Off */
function wcmnd_of_sanitize_onoff( $input ) {
	if ( $input ) {
		$output = 'yes';
	} else {
		$output = false;
	}
	return $output;
}
add_filter( 'wcmnd_of_sanitize_onoff', 'wcmnd_of_sanitize_onoff' );

/* Multicheck */

function wcmnd_of_sanitize_multicheck( $input, $option ) {
	$output = array();
	if ( is_array( $input ) ) {
		foreach( $option['options'] as $key => $value ) {
			$output[$key] = false;
		}
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = '1';
			}
		}
	}
	return $output;
}
add_filter( 'wcmnd_of_sanitize_multicheck', 'wcmnd_of_sanitize_multicheck', 10, 2 );


function wcmnd_of_sanitize_productselect2( $input, $option ) {
  return $input;
}

add_filter( 'wcmnd_of_sanitize_productselect2', 'wcmnd_of_sanitize_productselect2', 10, 2 );

add_filter( 'wcmnd_of_sanitize_listid', 'wcmnd_of_sanitize_listid' );
function wcmnd_of_sanitize_listid( $input, $option ) {
  return $input;
}

function wcmnd_of_sanitize_excludeproductselect22( $input, $option ) {
  return $input;
}

add_filter( 'wcmnd_of_sanitize_excludeproductselect2', 'wcmnd_of_sanitize_excludeproductselect2' );

function wcmnd_of_sanitize_catselect2( $input, $option ) {

	return $input;
}

add_filter( 'wcmnd_of_sanitize_catselect2', 'wcmnd_of_sanitize_catselect2', 10, 2 );


/* Color Picker */

add_filter( 'wcmnd_of_sanitize_color', 'wcmnd_of_sanitize_hex' );

/* Uploader */

function wcmnd_of_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype( $input );
	if ( $filetype["ext"] ) {
		$output = esc_url( $input );
	}
	return $output;
}
add_filter( 'wcmnd_of_sanitize_upload', 'wcmnd_of_sanitize_upload' );

/* Editor */

function wcmnd_of_sanitize_editor( $input ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	}
	else {
		global $allowedtags;
		$output = wpautop(wp_kses( $input, $allowedtags));
	}
	return $output;
}
add_filter( 'wcmnd_of_sanitize_editor', 'wcmnd_of_sanitize_editor' );

/* Allowed Tags */

function wcmnd_of_sanitize_allowedtags( $input ) {
	global $allowedtags;
	$output = wpautop( wp_kses( $input, $allowedtags ) );
	return $output;
}

/* Allowed Post Tags */

function wcmnd_of_sanitize_allowedposttags( $input ) {
	global $allowedposttags;
	$output = wpautop( wp_kses( $input, $allowedposttags) );
	return $output;
}
add_filter( 'wcmnd_of_sanitize_info', 'wcmnd_of_sanitize_allowedposttags' );

/* Check that the key value sent is valid */

function wcmnd_of_sanitize_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}

function wcmnd_of_sanitize_select_enum( $input, $option ) {
	return $input;
}

/* Background */

function wcmnd_of_sanitize_background( $input ) {
	$output = wp_parse_args( $input, array(
		'color' => '',
		'image'  => '',
		'repeat'  => 'repeat',
		'position' => 'top center',
		'attachment' => 'scroll'
	) );

	$output['color'] = apply_filters( 'wcmnd_of_sanitize_hex', $input['color'] );
	$output['image'] = apply_filters( 'wcmnd_of_sanitize_upload', $input['image'] );
	$output['repeat'] = apply_filters( 'wcmnd_of_background_repeat', $input['repeat'] );
	$output['position'] = apply_filters( 'wcmnd_of_background_position', $input['position'] );
	$output['attachment'] = apply_filters( 'wcmnd_of_background_attachment', $input['attachment'] );

	return $output;
}
add_filter( 'wcmnd_of_sanitize_background', 'wcmnd_of_sanitize_background' );

function wcmnd_of_sanitize_background_repeat( $value ) {
	$recognized = wcmnd_of_recognized_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wcmnd_of_default_background_repeat', current( $recognized ) );
}
add_filter( 'wcmnd_of_background_repeat', 'wcmnd_of_sanitize_background_repeat' );

function wcmnd_of_sanitize_background_position( $value ) {
	$recognized = wcmnd_of_recognized_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wcmnd_of_default_background_position', current( $recognized ) );
}
add_filter( 'wcmnd_of_background_position', 'wcmnd_of_sanitize_background_position' );

function wcmnd_of_sanitize_background_attachment( $value ) {
	$recognized = wcmnd_of_recognized_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wcmnd_of_default_background_attachment', current( $recognized ) );
}
add_filter( 'wcmnd_of_background_attachment', 'wcmnd_of_sanitize_background_attachment' );


/* Typography */

function wcmnd_of_sanitize_typography( $input, $option ) {

	$output = wp_parse_args( $input, array(
		'size'  => '',
		'face'  => '',
		'style' => '',
		'color' => ''
	) );

	if ( isset( $option['options']['faces'] ) && isset( $input['face'] ) ) {
		if ( !( array_key_exists( $input['face'], $option['options']['faces'] ) ) ) {
			$output['face'] = '';
		}
	}
	else {
		$output['face']  = apply_filters( 'wcmnd_of_font_face', $output['face'] );
	}

	$output['size']  = apply_filters( 'wcmnd_of_font_size', $output['size'] );
	$output['style'] = apply_filters( 'wcmnd_of_font_style', $output['style'] );
	$output['color'] = apply_filters( 'wcmnd_of_sanitize_color', $output['color'] );
	return $output;
}
add_filter( 'wcmnd_of_sanitize_typography', 'wcmnd_of_sanitize_typography', 10, 2 );

function wcmnd_of_sanitize_font_size( $value ) {
	$recognized = wcmnd_of_recognized_font_sizes();
	$value_check = preg_replace('/px/','', $value);
	if ( in_array( (int) $value_check, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wcmnd_of_default_font_size', $recognized );
}
add_filter( 'wcmnd_of_font_size', 'wcmnd_of_sanitize_font_size' );


function wcmnd_of_sanitize_font_style( $value ) {
	$recognized = wcmnd_of_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wcmnd_of_default_font_style', current( $recognized ) );
}
add_filter( 'wcmnd_of_font_style', 'wcmnd_of_sanitize_font_style' );


function wcmnd_of_sanitize_font_face( $value ) {
	$recognized = wcmnd_of_recognized_font_faces();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wcmnd_of_default_font_face', current( $recognized ) );
}
add_filter( 'wcmnd_of_font_face', 'wcmnd_of_sanitize_font_face' );

/**
 * Get recognized background repeat settings
 *
 * @return   array
 *
 */
function wcmnd_of_recognized_background_repeat() {
	$default = array(
		'no-repeat' => __( 'No Repeat', 'textdomain' ),
		'repeat-x'  => __( 'Repeat Horizontally', 'textdomain' ),
		'repeat-y'  => __( 'Repeat Vertically', 'textdomain' ),
		'repeat'    => __( 'Repeat All', 'textdomain' ),
		);
	return apply_filters( 'wcmnd_of_recognized_background_repeat', $default );
}

/**
 * Get recognized background positions
 *
 * @return   array
 *
 */
function wcmnd_of_recognized_background_position() {
	$default = array(
		'top left'      => __( 'Top Left', 'textdomain' ),
		'top center'    => __( 'Top Center', 'textdomain' ),
		'top right'     => __( 'Top Right', 'textdomain' ),
		'center left'   => __( 'Middle Left', 'textdomain' ),
		'center center' => __( 'Middle Center', 'textdomain' ),
		'center right'  => __( 'Middle Right', 'textdomain' ),
		'bottom left'   => __( 'Bottom Left', 'textdomain' ),
		'bottom center' => __( 'Bottom Center', 'textdomain' ),
		'bottom right'  => __( 'Bottom Right', 'textdomain')
		);
	return apply_filters( 'wcmnd_of_recognized_background_position', $default );
}

/**
 * Get recognized background attachment
 *
 * @return   array
 *
 */
function wcmnd_of_recognized_background_attachment() {
	$default = array(
		'scroll' => __( 'Scroll Normally', 'textdomain' ),
		'fixed'  => __( 'Fixed in Place', 'textdomain')
		);
	return apply_filters( 'wcmnd_of_recognized_background_attachment', $default );
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param    string    The value that this function should return if it cannot be recognized as a color.
 * @return   string
 *
 */

function wcmnd_of_sanitize_hex( $hex, $default = '' ) {
	if ( wcmnd_of_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}

/**
 * Get recognized font sizes.
 *
 * Returns an indexed array of all recognized font sizes.
 * Values are integers and represent a range of sizes from
 * smallest to largest.
 *
 * @return   array
 */

function wcmnd_of_recognized_font_sizes() {
	$sizes = range( 9, 71 );
	$sizes = apply_filters( 'wcmnd_of_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

/**
 * Get recognized font faces.
 *
 * Returns an array of all recognized font faces.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function wcmnd_of_recognized_font_faces() {
	$default = array(
		'arial'     => 'Arial',
		'verdana'   => 'Verdana, Geneva',
		'trebuchet' => 'Trebuchet',
		'georgia'   => 'Georgia',
		'times'     => 'Times New Roman',
		'tahoma'    => 'Tahoma, Geneva',
		'palatino'  => 'Palatino',
		'helvetica' => 'Helvetica*'
		);
	return apply_filters( 'wcmnd_of_recognized_font_faces', $default );
}

/**
 * Get recognized font styles.
 *
 * Returns an array of all recognized font styles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function wcmnd_of_recognized_font_styles() {
	$default = array(
		'normal'      => __( 'Normal', 'textdomain' ),
		'italic'      => __( 'Italic', 'textdomain' ),
		'bold'        => __( 'Bold', 'textdomain' ),
		'bold italic' => __( 'Bold Italic', 'textdomain' )
		);
	return apply_filters( 'wcmnd_of_recognized_font_styles', $default );
}

/**
 * Is a given string a color formatted in hexidecimal notation?
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @return   bool
 *
 */

function wcmnd_of_validate_hex( $hex ) {
	$hex = trim( $hex );
	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}
