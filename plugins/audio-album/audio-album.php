<?php
/*
Plugin Name: Audio Album
Plugin URI: https://cubecolour.co.uk/audio-album
Description: Provides shortcodes to format native WordPress audio players as an album of tracks with additional info
Author: cubecolour
Version: 1.5.0
Text Domain: audio-album
Domain Path: /languages/
Author URI: https://cubecolour.co.uk/
License: GPLv2

  Copyright 2013-2023 Michael Atkins

  michael@cubecolour.co.uk

  Licenced under the GNU GPL:

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/


if ( ! defined( 'ABSPATH' ) ) exit;


function cc_audioalbum_load_textdomain() {
	load_plugin_textdomain( 'audio-album', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'cc_audioalbum_load_textdomain' );


/**
* Set Constant: Plugin Version
*
*/
define( 'CC_AUDIOALBUM_VERSION', '1.5.0' );


/**
* Add Links in Plugins Table
*
*/
add_filter( 'plugin_row_meta', 'cc_audioalbum_meta_links', 10, 2 );
function cc_audioalbum_meta_links( $links, $file ) {

	$plugin = plugin_basename(__FILE__);

	//* create the links
	if ( $file == $plugin ) {

		$supportlink	= 'https://wordpress.org/support/plugin/audio-album';
		$donatelink		= 'https://cubecolour.co.uk/wp';
		$reviewlink		= 'https://wordpress.org/support/view/plugin-reviews/audio-album#postform';
		$twitterlink	= 'https://twitter.com/cubecolour';
		$iconstyle		= 'style="-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;"';

		$supporttext	= __( 'Audio Album Support', 'audio-album' );
		$twittertext	= __( 'Cubecolour on Twitter', 'audio-album' );
		$reviewtext		= __( 'Review Audio Album', 'audio-album' );
		$donatetext		= __( 'Donate', 'audio-album' );

		return array_merge( $links, array(
			'<a href="' . $supportlink . '"> <span class="dashicons dashicons-lightbulb" ' . $iconstyle . 'title="' . $supporttext . '"></span></a>',
			'<a href="' . $twitterlink . '"><span class="dashicons dashicons-twitter" ' . $iconstyle . 'title="' . $twittertext . '"></span></a>',
			'<a href="' . $reviewlink . '"><span class="dashicons dashicons-star-filled"' . $iconstyle . 'title="' . $reviewtext . '"></span></a>',
			'<a href="' . $donatelink . '"><span class="dashicons dashicons-heart"' . $iconstyle . 'title="' . $donatetext . '"></span></a>',
		) );
	}

	return $links;
}


/**
* Function to add the plugin's options as an array
* called:
*  - on installation
*  - when the option does not exist
*
*/
function cc_audioalbum_add_option() {

	$settings = array(
	'version'	=> CC_AUDIOALBUM_VERSION,
	'manualcss'	=> '',
	'bgcol'		=> '#434a54',
	'playr'		=> '#2c3138',
	'tvcol'		=> '#ffffff',
	'txtbt'		=> '#ffffff',
	);

	add_option( 'cc_audioalbum', $settings, '' );
}


/**
* Activation
* call the function to add the plugin's option
*
*/
function cc_audioalbum_activate(){
	cc_audioalbum_add_option();
}

register_activation_hook( __FILE__, 'cc_audioalbum_activate' );


/**
* Create or update the plugin's version number in the options array
*
*/
if ( false == get_option( 'cc_audioalbum' ) ) {
	//*If the plugin's option does not exist, add it
	cc_audioalbum_add_option();

} else {
	$settings = get_option( 'cc_audioalbum' );
	//* Update the version number
	//* additional values in the options array can also be added here in future versions if required
	if ( $settings['version'] != CC_AUDIOALBUM_VERSION ) {
		$settings['version'] = CC_AUDIOALBUM_VERSION;
		update_option( 'cc_audioalbum', $settings );
	}
}


/**
* Register the scripts
*
*/
function cc_audioalbum_scripts() {
	wp_register_script( 'audioalbum', plugins_url( '/js/audiotrackpopup.js' , __FILE__ ), '', CC_AUDIOALBUM_VERSION, true );

	$audioalbumscript = 'window.addEventListener("load", function() {document.querySelectorAll(".albumtrack, .track").forEach(function(element) {element.style.visibility = "visible";element.style.backgroundImage = "none";});});';
	wp_add_inline_script( 'audioalbum', $audioalbumscript );
}
add_action('wp_enqueue_scripts', 'cc_audioalbum_scripts');


/**
* Add stylesheet & custom styles
*
*/
function cc_audioalbum_css() {

	$defaults = array(
		'bgcol'		=> '#434a54',
		'playr'		=> '#2c3138',
		'tvcol'		=> '#ffffff',
		'txtbt'		=> '#ffffff',
	);

	//* get the settings array
	$settings = get_option( 'cc_audioalbum', $defaults );

	//* If the option for manual CSS has been set, return without doing anything.
	if ( $settings[ 'manualcss' ] == 1 ) {
		return;
	}

	wp_register_style( 'audioalbum', plugin_dir_url(__FILE__) . 'css/audioalbum.css', array( 'dashicons' ), CC_AUDIOALBUM_VERSION );

	//* sanitize the settings
	$settings[ 'bgcol' ]	= cc_sanitize_hex_color( $settings[ 'bgcol' ], '#434a54' );
	$settings[ 'playr' ]	= cc_sanitize_hex_color( $settings[ 'playr' ], '#2c3138' );
	$settings[ 'tvcol' ]	= cc_sanitize_hex_color( $settings[ 'tvcol' ], '#ffffff' );
	$settings[ 'txtbt' ]	= cc_sanitize_hex_color( $settings[ 'txtbt' ], '#ffffff' );

	//* Output the inline style
	$audioalbum_custom_css = '
		.audioalbum,
		.audioheading,
		.track {
			background-color: ' . $settings[ 'bgcol' ] . ';
		}

		.track .wp-audio-shortcode.mejs-audio .mejs-inner > .mejs-controls,
		.track .audiobutton a {
			background-color: ' . $settings[ 'playr' ] . ';
		}

		.track .audiobutton a:hover {
			background-color: ' . $settings[ 'txtbt' ] . ';
			color: ' . $settings[ 'bgcol' ] . ';
		}

		.track .mejs-time-rail .mejs-time-total .mejs-time-current,
		.track .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current {
			background-color: ' . $settings[ 'tvcol' ] . ';
		}

		.track .mejs-controls .mejs-time-rail .mejs-time-total,
		.track .mejs-time-rail .mejs-time-total .mejs-time-loaded,
		.track .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total {
			background-color: ' . $settings[ 'playr' ] . ';
		}

		h1.audioheading,
		p.audioheading,
		h2.audioalbum,
		p.audioalbum,
		.track .songtitle,
		.track .songwriter,
		.track .mejs-controls > .mejs-button button,
		.track .audiobutton a,
		.track .mejs-currenttime,
		.track .mejs-duration {
			color: ' . $settings[ 'txtbt' ] . ';
		}

		.track .mejs-controls > .mejs-button button:hover {
			color: ' . $settings[ 'tvcol' ] . ';
		}';


	//* shrink css (not quite minimised)
	$audioalbum_custom_css = ( str_replace(array("\r", "\n", "\t"), '', $audioalbum_custom_css ) );

	wp_add_inline_style( 'audioalbum', $audioalbum_custom_css );
}

add_action('wp_enqueue_scripts', 'cc_audioalbum_css' );


/**
* Shortcode to add Album Title
* ( Optional )
*
*/
function cc_audioheading_shortcode( $atts, $content = null ) {

	wp_enqueue_style( 'audioalbum' );

	$args =  shortcode_atts( array(

		'title'		=> '',
		'label'		=> '',
		'catalog'	=> '',
		'bgcolor'	=> '',

	), $atts, 'audioheading' );

	// sanitize the args
	$args['title'] = sanitize_text_field( $args['title'] );
	$args['label'] = sanitize_text_field( $args['label'] );
	$args['catalog'] = sanitize_text_field( $args['catalog'] );

	//* initialize vars for bgcol
	$inlinestyle = '';	//* string to add inline style (or not)
	$bgcolset = '';		//* class to enable js to find out that a colour has been set in the shortcode

	//* If a bgcolor is specified it will be added as an inline style and add bgcolset class so it can be ignored by the customizer preview
	if ( $args['bgcolor'] ) {
		$inlinestyle = ' style="background: ' . cc_sanitize_hex_color( $args['bgcolor'], '#434a54' ) . '"';
		$bgcolset = ' bgcolset';
	}

	$output = '<h1 class="audioheading' . $bgcolset . '"' . $inlinestyle . '>' . $args['title'] . '</h1><p class="audioheading' . $bgcolset . '"' . $inlinestyle . '>' . $args['label'] . ' <span>' . $args['catalog'] . '</span></p>';

	return $output;
}

add_shortcode( 'audioheading', 'cc_audioheading_shortcode' );


/**
* shortcode to add Album Header info
* (optional)
*/
function cc_audioalbum_shortcode( $atts, $content = null ) {

	$args =  shortcode_atts( array(

		'title'		=> '',
		'detail'	=> '',
		'date'		=> '',
		'bgcolor'	=> '',

	), $atts, 'audioalbum' );

	// sanitize the args
	$args['title'] = sanitize_text_field( $args['title'] );
	$args['detail'] = sanitize_text_field( $args['detail'] );
	$args['date'] = sanitize_text_field( $args['date'] );

	//* initialize vars for bgcol
	$inlinestyle = '';	//* string to add inline style (or not)
	$bgcolset = '';		//* class to enable js to find out that a colour has been set in the shortcode

	//* If a bgcolor is specified it will be added as an inline style and add bgcolset class so it can be ignored by the customizer preview
	if ( $args['bgcolor'] ) {
		$inlinestyle = ' style="background: ' . cc_sanitize_hex_color( $args['bgcolor'], '#434a54' ) . '"';
		$bgcolset = ' bgcolset';
	}

	$output = '<h2 class="audioalbum' . $bgcolset . '"' . $inlinestyle . '>' . $args['title'] . '</h2>'
	. '<p class="audioalbum' . $bgcolset . '"' . $inlinestyle . '>' . $args['detail'] . '<span>' . $args['date'] .'</span>' . do_shortcode($content) . '</p>';

	return $output;
}

add_shortcode( 'audioalbum', 'cc_audioalbum_shortcode' );


/**
* Shortcode to add each audio track inside the album
*
*/
function cc_audiotrack_shortcode( $atts, $content = null ) {

	wp_enqueue_script( 'audioalbum' );
	wp_enqueue_style( 'audioalbum' );
	wp_enqueue_style( 'dashicons' );

	$lyricslink= '';
	$popupbutton = '';
	$cc_siteurl = get_bloginfo('url');

	$args =  shortcode_atts( array(

		'title'			=> '',
		'width'			=> '520',
		'height'		=> '400',
		'songwriter'	=> '',
		'buttontext'	=> 'lyrics',
		'buttonlink'	=> '#',
		'preload'		=> 'metadata',
		'src'			=> '',
		'mp3'			=> '',
		'ogg'			=> '',
		'wma'			=> '',
		'm4a'			=> '',
		'wav'			=> '',
		'loop'			=> '',
		'autoplay'		=> '',

	), $atts, 'audiotrack' );

	$wpaudioshortcode = 'audio';

	$args['title'] = esc_attr( $args['title'] );
	$args['buttontext'] = esc_attr( $args['buttontext'] );

	if ( $args['songwriter']  !== '') {
		$args['songwriter'] = '<span class="songwriter">(' . $args['songwriter'] . ')</span>';
	}

	if ( $args['src'] !== ''){
		$wpaudioshortcode .= ' src="' . esc_url( $args['src'] ) . '"';
	}

	if ( $args['mp3'] !== ''){
		$wpaudioshortcode .= ' mp3="' . esc_url( $args['mp3'] ) . '"';
	}

	if ( $args['ogg'] !== ''){
		$wpaudioshortcode .= ' ogg="' . esc_url( $args['ogg'] ) . '"';
	}

	if ( $args['wma'] !== ''){
		$wpaudioshortcode .= ' wma="' . esc_url( $args['wma'] ) . '"';
	}

	if ( $args['m4a'] !== ''){
		$wpaudioshortcode .= ' m4a="' . esc_url( $args['m4a'] ) . '"';
	}

	if ( $args['wav'] !== ''){
		$wpaudioshortcode .= ' wav="' . esc_url( $args['wav'] ) . '"';
	}

	if ( $args['loop'] !== ''){
		$wpaudioshortcode .= ' loop="' . esc_attr( $args['loop']) . '"';
	}

	if ( $args['autoplay'] !== ''){
		$wpaudioshortcode .= ' autoplay="' . esc_attr( $args['autoplay'] ) . '"';
	}

	if ( $args['preload'] !== 'none'){
		$wpaudioshortcode .= ' preload="' . esc_attr( $args['preload'] ) . '"';
	}

	if ( $args['buttonlink']  !== '#') {
		$popupbutton = '<a href="'. $cc_siteurl .'/?p=' . esc_attr( $args['buttonlink'] ) . '&pop=yes" class="info-popup" data-width="' . esc_attr( $args['width'] ) . '" data-height="' . esc_attr( $args['height']) . '">' . esc_attr( $args['buttontext']) . '</a>';
	}

	$audiotrack = '<span class="songtitle">' . $args['title'] . '</span>' . $args['songwriter'] . '<span class="audiobutton">' . $popupbutton . '</span>';

	//* Shortcode Inception! - call the native WP audio shortcode and pass the attributes
	$output = '<div class="track">' . $audiotrack . '<div class="albumtrack" style="visibility:hidden;">' . do_shortcode('[' . $wpaudioshortcode . ']') . '</div></div>';

	return $output;
}

add_shortcode( 'audiotrack', 'cc_audiotrack_shortcode' );


/**
* A small bonus for Genesis theme users
* Use a template for the popup when Genesis is the active parent theme
*
*/
if ( basename( get_template_directory() ) == 'genesis' ) {
	add_filter( 'template_include', 'cc_popup_audioalbum_template' );
}

function cc_popup_audioalbum_template( $template ) {
	if( isset( $_GET['pop']) && 'yes' == $_GET['pop'] )
		$template = plugin_dir_path( __FILE__ ) . 'templates/genesis-audioalbum-popup.php';

	return $template;
}


/**
* Sanitize Functions
*
*/
require_once(plugin_dir_path( __FILE__ ) . 'includes/sanitize.php');


/**
* Customiser
*
*/
require_once(plugin_dir_path( __FILE__ ) . 'includes/customizer.php');