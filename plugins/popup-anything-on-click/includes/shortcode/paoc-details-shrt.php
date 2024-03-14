<?php
/**
 * 'paoc_details' Shortcode
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function popupaoc_details_shrt( $atts, $content = null ) {

	global $current_user;

	// Shortcode Parameter
	$atts = shortcode_atts(array(
		'display'	=> '',
		'default'	=> '',
	), $atts, 'paoc_details');

	// Taking some variable
	$atts['display'] = ! empty( $atts['display'] )	? $atts['display']	: '';
	$atts['default'] = ! empty( $atts['default'] )	? $atts['default']	: '';

	extract( $atts );

	// Return if `display` key is not there
	if( empty( $display ) ) {
		return $content;
	}

	ob_start();

	// Display admin email address
	if( $display == 'admin_email' ) {

		$admin_email = get_option('admin_email');

		echo esc_html( $admin_email );

	} else if( $display == 'site_url' ) { // Display site URL

		$site_url = get_option('siteurl');

		echo wp_kses_post( $site_url );

	} else if( $display == 'site_name' ) { // Display site name

		$site_name = get_option('blogname');

		echo wp_kses_post( $site_name );

	} else if( $display == 'page_title' ) { // Display current page title

		$page_title = get_the_title();
		$page_title	= ! empty( $page_title ) ? $page_title : $default;

		echo wp_kses_post( $page_title );

	} else if( $display == 'post_excerpt' ) { // Display current page OR post `excerpt`

		$post_excerpt = get_the_excerpt();

		echo wp_kses_post( $post_excerpt );

	} else if( $display == 'site_logo' ) { // Display site logo

		$site_logo = get_custom_logo();

		echo wp_kses_post( $site_logo );

	} else if( $display == 'date_time' ) { // Display current date & time

		$default_date_format = get_option('date_format');
		$default_time_format = get_option('time_format');

		$date_format	= ! empty( $default_date_format ) ? $default_date_format : 'Y-m-d';
		$time_format	= ! empty( $default_time_format ) ? $default_time_format : 'H:i:s';
		$date_time		= date_i18n( $date_format.' '.$time_format );

		echo esc_html( $date_time );

	} else if( $display == 'date' ) { // Display current date

		$default_date_format	= get_option( 'date_format' );
		$date_format			= ! empty( $default_date_format ) ? $default_date_format : 'Y-m-d';
		$current_date			= date_i18n( $date_format );

		echo esc_html( $current_date );

	} else if( $display == 'year' ) { // Display current year

		echo esc_html( date_i18n('Y') );

	} else if( $display == 'user_name' ) { // Display user name

		// Taking some variable
		$user_name		= '';
		$display_name	= ! empty( $current_user->display_name )	? $current_user->display_name	: '';
		$last_name		= ! empty( $current_user->last_name )		? $current_user->last_name		: '';
		$first_name		= ! empty( $current_user->first_name )		? $current_user->first_name		: $display_name;

		// If user `First Name` Or `Last Name` is there
		if( ! empty( $first_name ) || ! empty( $last_name ) ) {

			$user_name = $first_name .' '. $last_name;

		} else if( $first_name ) {

			$user_name = $first_name;

		} else if( ! empty( $default ) ) {

			$user_name = $default;
		}

		// Display Username
		echo wp_kses_post( $user_name );

	} else if( $display == 'user_email' ) { // Display user Email Address

		// Taking some variable
		$user_email = isset( $current_user->user_email ) ? $current_user->user_email : '';

		echo wp_kses_post( $user_email );

	} else if( strpos( $display, 'key_' ) !== false ) { // Display query string value

		// Taking some variable
		$key			= str_replace( 'key_', '', $display );
		$string_value	= ! empty( $_GET[ $key ] ) ? popupaoc_clean( $_GET[ $key ] ) : $default;

		echo wp_kses_post( $string_value );
	}

	$content .= ob_get_clean();
	return $content;
}

// Make Shortcode for get `Details`
add_shortcode('paoc_details', 'popupaoc_details_shrt');