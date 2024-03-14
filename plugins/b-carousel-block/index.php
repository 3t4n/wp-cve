<?php
/**
 * Plugin Name: B Carousel Block
 * Description:  Create stunning responsive carousels effortlessly.
 * Version: 1.0.3
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: carousel-block
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'BICB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.3' );
define( 'BICB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'BICB_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once BICB_DIR_PATH . 'inc/block.php';