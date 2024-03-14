<?php
defined( 'ABSPATH' ) || exit;
/**
 * Google Analytics
 *
 * @package YAHMAN Add-ons
 */


function yahman_addons_ga_gtag($custom = ''){

	wp_register_script( 'googletagmanager-js', '', array(), null, true );
	$ga_gtag = '';
	//$ga_gtag = '<link rel="preconnect dns-prefetch" href="//www.googletagmanager.com"><link rel="preconnect dns-prefetch" href="//www.google-analytics.com">';
	//$ga_gtag .= '<script async src="https://www.googletagmanager.com/gtag/js?id='. esc_attr(YA_GA_GTAG) .'"></script>';
	$ga_gtag .= '<script>';
	$ga_gtag .= 'window.dataLayer = window.dataLayer || [];';
	$ga_gtag .= 'function gtag(){dataLayer.push(arguments);}';
	$ga_gtag .= "gtag('js', new Date());";
	$ga_gtag .= "gtag('config', '". esc_attr(YA_GA_GTAG) ."');";
	$ga_gtag .= $custom;
	$ga_gtag .= "</script>";

	return $ga_gtag;
}
add_filter('yahman_addons_gtag_custom', 'yahman_addons_ga_gtag', 10, 1);
add_action('template_redirect', 'yahman_addons_ga_gtag_start');


function yahman_addons_ga_gtag_noscript(){

	return '<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id='. esc_attr(YA_GA_GTAG) .'&visitorType=returning" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<!-- Global Site Tag (gtag.js) - Google Analytics -->';
}

if ( function_exists( 'wp_body_open' ) ){
	add_action( 'wp_body_open', function() {
		echo yahman_addons_ga_gtag_noscript();
	});
}

add_action('wp_head', 'yahman_addons_ga_gtag_finish');
function yahman_addons_ga_gtag_start() {
	ob_start('yahman_addons_ga_gtag_callback');
}


function yahman_addons_ga_gtag_finish() {
	ob_end_flush();
}
function yahman_addons_ga_gtag_callback ( $buffer ) {
	$ga_gtag = apply_filters('yahman_addons_gtag_custom', '');
	$buffer =  str_replace('<head>', '<head>'.$ga_gtag, $buffer);

	return $buffer;
}
