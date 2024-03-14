<?php
/*
Plugin Name: Speedcheck Internet Speed Test
Plugin URI: http://www.speedcheck.org/speedcheck-basic/
Description: The Speedcheck plugin lets you embed the internet speed test on your website via a shortcode.
Version: 1.0.0
Author: Etrality GmbH
Author URI: https://www.speedcheck.org/
License: GPLv2 or later
Text Domain: speedcheck
*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

function speedcheck_shortcode( $atts ) {

	// Set default attributes and overwrite them with shortcode attributes if any are set
	$atts = shortcode_atts(
		array(
			'language' => 'en',
			'center' => false,
			'border' => false,
			'link' => false
		), $atts
	);

	// If language is set, localize url
	$localeUrl = ''; 
	$languages = array('ar', 'de', 'en', 'es', 'fr', 'id', 'it', 'ja', 'ko', 'nl', 'pl', 'pt', 'ru', 'sv' );

	if ( in_array( $atts['language'], $languages) ) {
		if ( $atts['language'] == 'ar' ) {
			$localeUrl = 'ar/';
		}
		if ( $atts['language'] == 'de' ) {
			$localeUrl = 'de/';
		}
		if ( $atts['language'] == 'en' ) {
			$localeUrl = '';
		}
		if ( $atts['language'] == 'es' ) {
			$localeUrl = 'es/';
		}
		if ( $atts['language'] == 'fr' ) {
			$localeUrl = 'fr/';
		}
		if ( $atts['language'] == 'id' ) {
			$localeUrl = 'id/';
		}
		if ( $atts['language'] == 'it' ) {
			$localeUrl = 'it/';
		}
		if ( $atts['language'] == 'ja' ) {
			$localeUrl = 'ja/';
		}
		if ( $atts['language'] == 'ko' ) {
			$localeUrl = 'ko/';
		}
		if ( $atts['language'] == 'nl' ) {
			$localeUrl = 'nl/';
		}
		if ( $atts['language'] == 'pl' ) {
			$localeUrl = 'pl/';
		}
		if ( $atts['language'] == 'pt' ) {
			$localeUrl = 'pt/';
		}
		if ( $atts['language'] == 'ru' ) {
			$localeUrl = 'ru/';
		}
		if ( $atts['language'] == 'sv' ) {
			$localeUrl = 'sv/';
		}
	}

	// If center and/or border is to true, set correct container styles
	$style = ' style="';
	$styleEnd = '"';

	if ( $atts['center'] == true ) {
		$style = $style . 'margin: 0 auto;';
	}
	if ( $atts['border'] == true ) {
		$style = $style . 'border: 1px solid gray;';
	}

	// Set nofollow default for link
	$follow = ' rel="nofollow"';
	// If user chooses to attribute, remove nofollow
	if ( $atts['link'] == true ) {
		$follow = '';
	}

	// Build and return completed html code
	$html = '<div id="sc-container"' . $style . '><div id="sc-branding" class="sc-bb"><a target="_blank" href="https://www.speedcheck.org/' . $localeUrl . '"' . $follow . '><img src="https://cdn.speedcheck.org/branding/speedcheck-logo-18.png" alt="Speedcheck"/></a></div></div><script src="https://cdn.speedcheck.org/basic/scbjs.min.js" async></script>';

	return $html;
}

add_shortcode( 'speedcheck', 'speedcheck_shortcode' );

?>