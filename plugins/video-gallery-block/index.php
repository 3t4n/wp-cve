<?php
/**
 * Plugin Name: Video Gallery Block
 * Description: Display your videos as gallery in a professional way.
 * Version: 1.0.7
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: video-gallery
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'VGB_PLUGIN_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.7' );
define( 'VGB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'VGB_ASSETS_DIR', VGB_DIR_URL . 'assets/' );
define( 'VGB_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once VGB_DIR_PATH . 'inc/block.php';