<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}

function adsense_inpost_ads_init(){
	add_shortcode('AdSense-A', 'adsense_inpost_ads_a');
	add_shortcode('AdSense-B', 'adsense_inpost_ads_b');
	add_shortcode('AdSense-C', 'adsense_inpost_ads_c');
	if( function_exists( 'has_blocks' ) ){
		register_block_type( 'adsense-in-post-ads-by-oizuled/adsense-ad-a', array(
			'editor_script'		=> 'adsense-ad-block-a',
			'render_callback'	=> 'adsense_inpost_ads_a'
		));
		register_block_type( 'adsense-in-post-ads-by-oizuled/adsense-ad-b', array(
			'editor_script'		=> 'adsense-ad-block-b',
			'render_callback'	=> 'adsense_inpost_ads_b'
		));
		register_block_type( 'adsense-in-post-ads-by-oizuled/adsense-ad-c', array(
			'editor_script'		=> 'adsense-ad-block-c',
			'render_callback'	=> 'adsense_inpost_ads_c'
		));
	}
}
add_action( 'init', 'adsense_inpost_ads_init' );

/* Set Shortcodes */
function adsense_inpost_ads_a() {
	global $aip_options;
	return do_shortcode( $aip_options['unit_a'] );
}

function adsense_inpost_ads_b() {
	global $aip_options;
	return do_shortcode( $aip_options['unit_b'] );
}

function adsense_inpost_ads_c() {
	global $aip_options;
	return do_shortcode( $aip_options['unit_c'] );
}