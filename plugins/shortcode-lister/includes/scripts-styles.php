<?php
/**
 * Enqueue Scripts for Shortcode Lister
 * 
 * @package Shortcode_Lister
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, you are not allowed to access this page directly.' );
}
add_action( 'admin_enqueue_scripts', 'shortcode_lister_scripts' );

/**
 * Shortcode Lister Scripts
 * Enqueues Plugin Scripts
 * 
 * @return void
 */
function shortcode_lister_scripts() {
	wp_enqueue_script( 'shortcode-select', SHORTCODE_LISTER_PLUGIN_URL . '/includes/js/shortcode-lister.js', array(), SHORTCODE_LISTER_VERSION, true );
}
