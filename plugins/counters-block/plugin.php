<?php
/**
 * Plugin Name: Counters Block
 * Description: Display Number as animated counter( start to end ).
 * Version: 1.1.0
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: counters-block
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'CTRB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.1.0' );
define( 'CTRB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'CTRB_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once CTRB_DIR_PATH . 'inc/block.php';