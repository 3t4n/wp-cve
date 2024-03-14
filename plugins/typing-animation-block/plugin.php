<?php
/**
 * Plugin Name: typing-animation-block
 * Plugin URI: https://github.com/middle-way-approach/gutenberg-typing-animation-block
 * Description: A Gutenberg block to create a typing animation.
 * Author: middle-way-approach
 * Author URI: https://github.com/middle-way-approach
 * Version: 1.2.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
