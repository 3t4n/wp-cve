<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Classic editor related functions
 */

add_action( 'wp_enqueue_editor', 'siteseo_wp_tiny_mce' );
/**
 * Load extension for wpLink
 *
 * @param  string  $hook  Page hook name
 */
function siteseo_wp_tiny_mce( $hook ){
	$suffix = '';
	wp_enqueue_style( 'siteseo-classic', SITESEO_ASSETS_DIR . '/css/classic-editor' . $suffix . '.css' , [], SITESEO_VERSION );
	wp_enqueue_script( 'siteseo-classic', SITESEO_ASSETS_DIR . '/js/siteseo-classic-editor' . $suffix . '.js' , ['wplink'], SITESEO_VERSION, true );
	wp_localize_script( 'siteseo-classic', 'siteseoI18n', array(
		'sponsored' => __( 'Add <code>rel="sponsored"</code> attribute', 'siteseo' ),
		'nofollow'  => __( 'Add <code>rel="nofollow"</code> attribute', 'siteseo' ),
		'ugc'	   => __( 'Add <code>rel="UGC"</code> attribute', 'siteseo' ),
	) );
}
