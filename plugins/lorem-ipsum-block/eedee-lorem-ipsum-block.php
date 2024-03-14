<?php
/**
 * Plugin Name: Lorem Ipsum Block
 * Plugin URI: https://eedee.net/plugin-lorem-ipsum-block/
 * Description: Lorem Ipsum Block - Prototype your websites faster - With Gutenberg native support.
 * Author: eedee
 * Author URI: https://eedee.com
 * Version: 2.1.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: eedee-lorem-ipsum-block
 *
 * @package eedee-blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
