<?php
/**
 * Plugin Name: Portfolio Block
 * Description: Display interactive portfolio / project on the web.
 * Version: 1.0.3
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: portfolio-block
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'PFB_PLUGIN_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.3' );
define( 'PFB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'PFB_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once PFB_DIR_PATH . 'inc/block.php';