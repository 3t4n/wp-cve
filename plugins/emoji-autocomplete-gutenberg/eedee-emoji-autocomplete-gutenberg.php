<?php
/**
 * Plugin Name: Emoji Autocomplete Gutenberg
 * Plugin URI: https://eedee.net/emoji-autocomplete-gutenberg-plugin/
 * Description: Adds an emoji autocomplete to your gutenberg editor that lets you insert and search emojis faster than ever. Just type `:` to get started.`
 * Author: eedee
 * Author URI: https://eedee.net
 * Version: 1.1.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: eedee-emoji-autocomplete-gutenberg
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
