<?php
/**
 * FDP Dummy Theme functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 *
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook when needed.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package FDP Dummy Theme
 * @subpackage Core
 * @since FDP Dummy Theme 1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Return an array of unnecessary actions.
$eos_unnecessary_actions = array(
	'rsd_link'                 => 'wp_head',
	'wp_generator'             => 'wp_head',
	'wlwmanifest_link'         => 'wp_head',
	'rest_output_link_wp_head' => 'wp_head',
	'rest_output_link_header'  => 'template_redirect',
);
// Remove unnecessary actions.
foreach ( $eos_unnecessary_actions as $callback => $action ) { // @codingStandardsIgnoreLine.
	remove_action( $callback, $action ); // @codingStandardsIgnoreLine.
}
// Register dummy menu.
register_nav_menus( array() );
