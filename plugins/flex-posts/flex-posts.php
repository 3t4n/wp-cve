<?php
/**
 * Plugin Name: Flex Posts - Widget and Gutenberg Block
 * Plugin URI:  https://tajam.id/flex-posts/
 * Description: A widget to display posts with thumbnails in various layouts for any widget area.
 * Version:     1.9.0
 * Author:      Tajam
 * Author URI:  https://tajam.id/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: flex-posts
 * Domain Path: /languages
 *
 * Flex Posts is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Flex Posts is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flex Posts. If not, see http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Flex Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current plugin version.
 */
define( 'FLEX_POSTS_VERSION', '1.9.0' );

/**
 * Plugin directory and url
 */
define( 'FLEX_POSTS_DIR', plugin_dir_path( __FILE__ ) );
define( 'FLEX_POSTS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include functions & widget classes
 */
require FLEX_POSTS_DIR . 'includes/functions.php';
require FLEX_POSTS_DIR . 'includes/form-helpers.php';
require FLEX_POSTS_DIR . 'includes/class-flex-posts-widget.php';
require FLEX_POSTS_DIR . 'includes/class-flex-posts-list.php';

/**
 * Include template functions in `after_setup_theme` hook
 * to make the functions pluggable
 * (can be overridden from theme or other plugin)
 */
function flex_posts_after_setup_theme() {
	require FLEX_POSTS_DIR . 'includes/template-tags.php';
}
add_action( 'after_setup_theme', 'flex_posts_after_setup_theme' );

/**
 * Include block
 */
if ( function_exists( 'register_block_type' ) ) {
	require FLEX_POSTS_DIR . 'blocks/list/block.php';
}

/**
 * Register custom widget
 */
function flex_posts_register_widgets() {
	register_widget( 'Flex_Posts_List' );
}
add_action( 'widgets_init', 'flex_posts_register_widgets' );

/**
 * Load the text domain for translation.
 */
function flex_posts_load_textdomain() {
	load_plugin_textdomain(
		'flex-posts',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'flex_posts_load_textdomain' );

/**
 * Register a new image size
 */
function flex_posts_init() {
	add_image_size( '400x250-crop', 400, 250, true );
}
add_action( 'init', 'flex_posts_init' );
