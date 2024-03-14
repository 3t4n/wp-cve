<?php
/**
 * Plugin Name: ThemeRain Core
 * Description: Core functionalities for ThemeRain's themes.
 * Version: 1.1.3
 * Author: ThemeRain
 * Author URI: http://themeforest.net/user/themerain
 * License: GPLv3 or later
 * Text Domain: themerain
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup plugin constants.
 */
define( 'TRC_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'TRC_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'TRC_ASSETS_URL', TRC_URL . 'assets' );

// Portfolio
require_once __DIR__ . '/includes/portfolio/portfolio.php';

// Meta boxes
require_once __DIR__ . '/includes/meta-boxes/meta-boxes.php';

// Customizer
require_once __DIR__ . '/includes/customizer/customizer.php';

// Blocks
require_once __DIR__ . '/includes/blocks/blocks.php';

// Custom fonts
require_once __DIR__ . '/includes/fonts/fonts.php';
require_once __DIR__ . '/includes/fonts/fonts-admin.php';

// Custom CSS
require_once __DIR__ . '/includes/custom-css/custom-css.php';

// Enqueue scripts and styles
function trc_enqueue_scripts() {
	wp_enqueue_style( 'swiper', plugins_url( 'assets/css/swiper.min.css', __FILE__ ) );
	wp_enqueue_style( 'fancybox', plugins_url( 'assets/css/fancybox.min.css', __FILE__ ) );
	wp_enqueue_style( 'trc-main', plugins_url( 'assets/css/main.css', __FILE__ ) );
	wp_enqueue_script( 'swiper', plugins_url( 'assets/js/swiper.min.js', __FILE__ ), array(), null, true );
	wp_enqueue_script( 'fancybox', plugins_url( 'assets/js/fancybox.min.js', __FILE__ ), array(), null, true );
	wp_enqueue_script( 'trc-main', plugins_url( 'assets/js/main.js', __FILE__ ), array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'trc_enqueue_scripts' );

// Enqueue admin scripts and styles
function trc_enqueue_admin_scripts() {
	wp_enqueue_style( 'swiper', plugins_url( 'assets/css/swiper.min.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'trc_enqueue_admin_scripts' );
