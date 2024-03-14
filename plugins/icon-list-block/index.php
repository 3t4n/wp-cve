<?php
/**
 * Plugin Name: Icon List Block
 * Description: Show your icon list in web.
 * Version: 1.0.8
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: icon-list
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'ILB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.8' );
define( 'ILB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'ILB_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once ILB_DIR_PATH . 'inc/block.php';