<?php
/**
 * Plugin Name: Price Calculator - Block
 * Description: Calculate price of products based on quantity
 * Version: 1.1.0
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: price-calculator
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'PCLB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.1.0' );
define( 'PCLB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'PCLB_DIR_PATH',  plugin_dir_path( __FILE__ ) );

require_once 'inc/block.php';