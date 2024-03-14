<?php
/**
 * Plugin Name: Animated Text Block
 * Description: Apply animation on any text.
 * Version: 1.0.6
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: animated-text
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

define( 'ATB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.6' );
define( 'ATB_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'ATB_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once ATB_DIR_PATH . 'inc/block.php';