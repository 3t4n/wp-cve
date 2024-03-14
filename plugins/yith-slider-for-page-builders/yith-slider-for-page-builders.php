<?php
/**
 * Plugin Name: YITH Slider for page builders
 * Plugin URI: https://wordpress.org/plugins/yith-slider-for-page-builders/
 * Description: This plugin will add cool block oriented sliders to your website. Works with Block Editor.
 * Author: YITH
 * Version: 1.0.11
 * Author URI: https://yithemes.com
 *
 * @package YITH Slider for page builders
 */

if ( ! defined( 'YITH_SLIDER_FOR_PAGE_BUILDERS' ) ) {
	define( 'YITH_SLIDER_FOR_PAGE_BUILDERS', 'YITH_SLIDER_FOR_PAGE_BUILDERS' );
}

if ( ! defined( 'YITH_SLIDER_FOR_PAGE_BUILDERS_VERSION' ) ) {
	define( 'YITH_SLIDER_FOR_PAGE_BUILDERS_VERSION', '1.0.11' );
}

if ( ! defined( 'YITH_SLIDER_FOR_PAGE_BUILDERS_PATH' ) ) {
	define( 'YITH_SLIDER_FOR_PAGE_BUILDERS_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_SLIDER_FOR_PAGE_BUILDERS_URL' ) ) {
	define( 'YITH_SLIDER_FOR_PAGE_BUILDERS_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Manage shortcodes
 */
require_once 'include/shortcodes.php';

/**
 * Manage sliders
 */
require_once 'include/sliders.php';

/**
 * Manage sliders metaboxes
 */
require_once 'include/class-yith-sliders-metabox.php';

/**
 * Gutenberg block
 */
require_once 'include/block/slider-block.php';

/**
 * Include CSS & JS files
 */
function yith_slider_for_page_builders_load_scripts() {
	wp_enqueue_script( 'yith-sliders-slick-script', YITH_SLIDER_FOR_PAGE_BUILDERS_URL . 'assets/third-party/slick.min.js', 'jquery', '1.8.1', true );
	wp_enqueue_style( 'yith-sliders-slick-style', YITH_SLIDER_FOR_PAGE_BUILDERS_URL . 'assets/third-party/slick.css', '', '1.8.1', 'screen' );
	wp_enqueue_style( 'yith-sliders-slider-style', YITH_SLIDER_FOR_PAGE_BUILDERS_URL . 'assets/slider-style.css', '', YITH_SLIDER_FOR_PAGE_BUILDERS_VERSION, 'screen' );
}

add_action( 'wp_enqueue_scripts', 'yith_slider_for_page_builders_load_scripts' );
