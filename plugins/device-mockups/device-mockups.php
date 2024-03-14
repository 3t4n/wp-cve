<?php
/**
 * Plugin Name: Device Mockups
 * Plugin URI: https://wordpress.org/plugins/device-mockups/
 * Description: Show your work in high resolution, responsive device mockups using only shortcodes.
 * Version: 1.8.2
 * Author: Justin Peacock
 * Author URI: https://byjust.in/
 * Text Domain: device_mockups
 * Domain Path: /languages
 * License: GNU General Public License v2 or later
 * License URI: LICENSE
 *
 * @package Device_Mockups
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

// Useful global constants.
define( 'DEVICE_MOCKUPS_VERSION', '1.8.2' );
define( 'DEVICE_MOCKUPS_URL', plugin_dir_url( __FILE__ ) );
define( 'DEVICE_MOCKUPS_PATH', dirname( __FILE__ ) . '/' );
define( 'DEVICE_MOCKUPS_ADMIN', DEVICE_MOCKUPS_PATH . 'admin/' );
define( 'DEVICE_MOCKUPS_INC', DEVICE_MOCKUPS_PATH . 'includes/' );
define( 'DEVICE_MOCKUPS_DOCS', esc_url( 'https://devicemockupswp.com/' ) );

/**
 * Register stylesheet to be used within shortcodes.
 */
function device_mockups_register_style() {
	wp_register_style( 'device-mockups-styles', DEVICE_MOCKUPS_URL . 'css/device-mockups.css', array(), DEVICE_MOCKUPS_VERSION, false );
}

add_action( 'wp_enqueue_scripts', 'device_mockups_register_style' );

/**
 * Register script to be used within shortcodes.
 */
function device_mockups_register_script() {
	wp_register_script( 'device-mockups-scripts', DEVICE_MOCKUPS_URL . 'js/device-mockups.js', array( 'jquery' ), DEVICE_MOCKUPS_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'device_mockups_register_script' );

/**
 * Add documentation link.
 *
 * @param $links
 *
 * @return mixed
 */
function device_mockups_docs_link( $links ) {
	$settings_link = '<a href="' . esc_url( DEVICE_MOCKUPS_DOCS ) . '" target="_blank">' . esc_html__( 'Documentation' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}

$plugin = plugin_basename( __FILE__ );

add_filter( 'plugin_action_links_$plugin', 'device_mockups_docs_link' );

/**
 * Include functions
 */
require_once DEVICE_MOCKUPS_ADMIN . 'device-mockups.php';
require_once DEVICE_MOCKUPS_INC . 'device.php';
require_once DEVICE_MOCKUPS_INC . 'browser.php';

/**
 * Disables wp texturize on registered shortcodes
 *
 * @param $shortcodes
 *
 * @return array
 */
function device_mockups_shortcode_exclude( $shortcodes ) {
	$shortcodes[] = 'device';
	$shortcodes[] = 'browser';

	return $shortcodes;
}

add_filter( 'no_texturize_shortcodes', 'device_mockups_shortcode_exclude' );

/**
 * Filters shortcode to remove auto p and br tags
 *
 * @param $pee
 *
 * @return mixed
 */
function device_mockups_shortcode_unautop( $pee ) {
	global $shortcode_tags;

	if ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) {
		return $pee;
	}

	$tagregexp = join( '|', array_map( 'preg_quote', array_keys( $shortcode_tags ) ) );

	$pattern =
		'/'
		. '<p>'
		. '\\s*+'
		. '('
		. '\\[\\/?'
		. "($tagregexp)"
		. '(?![\\w-])'
		. '[^\\]\\/]*'
		. '(?:'
		. '\\/(?!\\])'
		. '[^\\]\\/]*'
		. ')*?'
		. '[\\w\\s="\']*'
		. '(?:'
		. '\\s*+'
		. '\\/\\]'
		. '|'
		. '\\]'
		. '(?:'
		. '(?!<\/p>)'
		. '[^\\[]*+'
		. '(?:'
		. '\\[(?!\\/\\2\\])'
		. '[^\\[]*+'
		. ')*+'
		. '\\[\\/\\2\\]'
		. ')?'
		. ')'
		. ')'
		. '\\s*+'
		. '<\\/p>'
		. '/s';

	return preg_replace( $pattern, '$1', $pee );
}

foreach ( array( 'device', 'browser' ) as $filter ) {
	remove_filter( $filter, 'shortcode_unautop' );
	add_filter( $filter, 'device_mockups_shortcode_unautop' );
}

remove_filter( 'the_content', 'shortcode_unautop' );
add_filter( 'the_content', 'device_mockups_shortcode_unautop' );
remove_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'device_mockups_shortcode_unautop' );
