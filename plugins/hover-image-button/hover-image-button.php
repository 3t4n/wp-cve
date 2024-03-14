<?php
/**
 * Hover Image Button
 *
 * Create amazing buttons with hover effects for your WordPress website.
 *
 * @link https://github.com/igpremuo/hover-image-button
 *
 * @package   Hover_Image_Button
 * @author    Ignacio Pérez
 * @license   GPLv2 or later
 *
 * @wordpress
 * Plugin Name: Hover Image Button
 * Description: Create mobile responsive image buttons with pure CSS3 animated hover effects.
 * Version: 1.1.2
 * Author: Ignacio Pérez
 * Author URI: https://github.com/igpremuo
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hover-image-button
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Define the global plugin variable
if ( ! defined('HOVER_IMAGE_BUTTON')) {
    define( 'HOVER_IMAGE_BUTTON' , 'hover_image_button' );
}

/**
 * Include the plugin variables.
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/plugin-variables.php' );

/**
 * Include the shortcode handler.
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes-handler.php' );

/**
 * Include the settings page functions.
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );

/**
 * Check the options and add the default values.
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/plugin-defaults.php' );

/**
 * Register the main plugin actions and the custom links action.
 */
add_action('plugins_loaded', 'hover_image_button_loaded');
add_action( 'init', 'hover_image_button_init' );
add_action('wp_head', 'hover_image_button_header');
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'hover_image_button_action_links' );

/**
 * Load the translation file from the lang folder.
 */
function hover_image_button_loaded() {
    load_plugin_textdomain( 'hover-image-button', false,  plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Initialice the plugin adding the required files.
 */
function hover_image_button_init() {
    // Enqueue CSS files
    wp_register_style( 'hover-image-button-css', plugins_url( 'css/public.css', __FILE__ ) );
    wp_enqueue_style( 'hover-image-button-css' );
    // Enqueue JavaScript files
    //wp_register_script( 'hover-image-button-js', plugins_url( 'js/public.js', __FILE__ ) );
    //wp_enqueue_script( 'hover-image-button-js' );
}

/**
 * Add custom CSS code to the page header.
 */
function hover_image_button_header() {
    // Get options from settings
    $options = get_option( HOVER_IMAGE_BUTTON_SETTINGS );
    $color   = $options[HOVER_IMAGE_BUTTON_COLOR];
    $opacity = $options[HOVER_IMAGE_BUTTON_OPACITY];
    $css     = $options[HOVER_IMAGE_BUTTON_CSS];

    echo '
        <style type="text/css">
            /* Hover Image Button CSS */

            .hib-text-container {
                background-color: rgba(' . hex2rgb($color) . ', 0.0);
            }

            .hib-text-container:hover {
                background-color: rgba(' . hex2rgb($color) . ', ' . $opacity . ');
            }

            /* Custom CSS Code */
            ' . $css . '
        </style>
    ';
}

/**
 * Auxiliar method to convert hexadecimal code to RGB
 *
 * @link http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
 *
 * @param string $hex Hexadecimal color code
 * @return string RGB color separated by commas.
 */
function hex2rgb( $hex ) {
    $hex = str_replace( "#", "", $hex );

    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1) . substr( $hex, 0, 1) );
        $g = hexdec( substr( $hex, 1, 1) . substr( $hex, 1, 1) );
        $b = hexdec( substr( $hex, 2, 1) . substr( $hex, 2, 1) );
    } else if ( strlen( $hex ) == 6 ) {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    } else {
        $r = 'FF';
        $g = 'FF';
        $b = 'FF';
    }

    $rgb = array( $r, $g, $b );
    return implode(",", $rgb);
}
