<?php
/**
 * Plugin Name: Animated Headline Elementor
 * Description: Animated headline support plugin for elementor.
 * Plugin URI:  https://github.com/habibur899/animated-headline-elementor
 * Version:     1.0.0
 * Author:      Habibur Rahaman
 * Author URI:  https://github.com/habibur899
 * Text Domain: animated-headline-elementor
 *
 * Elementor tested up to: 3.8.0
 * Elementor Pro tested up to: 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function animated_headline_elementor_addon() {

	// Load plugin file
	require_once( __DIR__ . '/includes/plugin.php' );

	// Run the plugin
	\Elementor_Animated_Headline_Addon\Elementor_Animated_Headline::instance();

}

add_action( 'plugins_loaded', 'animated_headline_elementor_addon' );
