<?php
/**
 * Plugin generic functions file
 *
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get plugin default settings
 * 
 * Handles to return all settings value
 * 
 * @since 1.0.0
 */

/**
 * Update default settings
 * 
 * @since 1.0
 */
function apwpultimate_ultimate_get_default_settings() {

	$apwpultimate_ultimate_options = apply_filters( 'apwpultimate_ultimate_options_default_values', array(
										'theme_color'				=> '#ff6347',
										'playlist_bg_color'			=> '#f7f7f7',
										'playlist_font_color'		=> '#000000',
										'audio_title_font_color'	=> '#ffffff',
										'title_bg_color'			=> '#000000',
										'audio_title_font_size'		=> '22',
										'playlist_font_size'		=> '18',
										'custom_css'				=> '',
								) );

	return $apwpultimate_ultimate_options;
}

/**
 * Update default settings
 * 
 * @since 1.2.5
 */
function apwpultimate_ultimate_set_default_settings() {

	global $apwpultimate_ultimate_options;

	$apwpultimate_ultimate_options = apwpultimate_ultimate_get_default_settings();

	// Update default options
	update_option( 'apwpultimate_ultimate_options', $apwpultimate_ultimate_options );
}

/**
 * Get Settings From Option Page
 * 
 * Handles to return all settings value
 * 
 * @since 1.0
*/
function apwpultimate_ultimate_get_settings() {

	$options 	= get_option( 'apwpultimate_ultimate_options' );
	$settings 	= is_array( $options ) 	? $options : array();

	return $settings;
}

/**
 * Get an option
 * Looks to see if the specified setting exists, returns default if not
 * 
 * @since 1.0
 */
function apwpultimate_ultimate_get_option( $key = '', $default = false ) {
	global $apwpultimate_ultimate_options;

	$default_setting = apwpultimate_ultimate_get_default_settings();

	if( ! isset( $apwpultimate_ultimate_options[ $key ] ) && isset( $default_setting[ $key ] ) && ! $default ) {
		
		$value = $default_setting[ $key ];

	} else {

		$value = ! empty( $apwpultimate_ultimate_options[ $key ] ) ? $apwpultimate_ultimate_options[ $key ] : $default;
	}

	$value = apply_filters( 'apwpultimate_ultimate_get_option', $value, $key, $default );

	return apply_filters( 'apwpultimate_ultimate_get_option_' . $key, $value, $key, $default );
}

/**
 * Function to get unique value number
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0
 */
function apwpultimate_get_unique() {
	static $unique = 0;
	$unique++;
	return $unique;
}

/**
 * Sanitize Hex Color
 * 
 * @since 1.0.0
 */
function apwpultimate_ultimate_clean_color( $color, $fallback = null ) {

	if ( false === strpos( $color, 'rgba' ) ) {
		
		$data = sanitize_hex_color( $color );

	} else {

		$red	= 0;
		$green	= 0;
		$blue	= 0;
		$alpha	= 0.5;

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		$data = 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';
	}

	return ( empty( $data ) && $fallback ) ? $fallback : $data;
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @since 1.0
 */
function apwpultimate_ultimate_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'wpspw_pro_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash( $data );
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 1.0
 */
function apwpultimate_ultimate_clean_number( $var, $fallback = null, $type = 'int' ) {

	$var = trim( $var );
	$var = is_numeric( $var ) ? $var : 0;

	if ( $type == 'number' ) {
		$data = intval( $var );
	} else if ( $type == 'abs' ) {
		$data = abs( $var );
	} else if ( $type == 'float' ) {
		$data = (float)$var;
	} else {
		$data = absint( $var );
	}

	return ( empty( $data ) && isset( $fallback ) ) ? $fallback : $data;
}

/**
 * Sanitize URL
 * 
 * @since 1.0
 */
function apwpultimate_ultimate_clean_url( $url ) {
	return esc_url_raw( trim( $url ) );
}

/**
 * Function to get button style type
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
function apwpultimate_player_layout() {
	$player_layout = array(
						'layout-1'	=> __('Layout 1','audio-player-with-playlist-ultimate'),	
					);
	return apply_filters('apwpultimate_player_layout', $player_layout );
}