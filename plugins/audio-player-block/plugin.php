<?php
/**
 * Plugin Name: Audio Player Block
 * Description: Listen Music on the Web.
 * Version: 1.1.0
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: mp3player-block
 */


// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

define( 'BPMP_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.1.0' );
define( 'BPMP_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'BPMP_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once BPMP_DIR_PATH . 'inc/block.php';