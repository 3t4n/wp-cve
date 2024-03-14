<?php
/**
 * Main plugin file
 *
 * @package Capitalize Titles and Headings
 */

/**
Plugin Name: Capitalize Titles and Headings
Plugin URI: https://wordpress.org/plugins/capitalize-post-title
Description: Properly capitalize your English post titles, page titles and heading blocks after you type it.
Author: Benjamin Intal, Stackable
Version: 1.0
Author URI: http://gambit.ph
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

if ( ! function_exists( 'capitalize_title_admin_head' ) ) {
    // Add action to load our script in the classic editor
    add_action( 'admin_enqueue_scripts', 'capitalize_title_admin_head' );
    function capitalize_title_admin_head() {
        if ( ! get_current_screen()->is_block_editor() ) {
            wp_enqueue_script( 'capitalize-title', plugins_url( 'dist/classic.js', __FILE__ ) );
        }
    }
}

if ( ! function_exists( 'capitalize_title_block_editor_assets' ) ) {
    // Add action to load our script in the block editor
    add_action( 'enqueue_block_editor_assets', 'capitalize_title_block_editor_assets' );
    function capitalize_title_block_editor_assets() {
        wp_enqueue_script( 'capitalize-title', plugins_url( 'dist/editor.js', __FILE__ ), array( 'wp-blocks', 'wp-plugins', 'wp-hooks', 'wp-data', 'wp-edit-post', 'wp-element', 'wp-compose' ) );
    }
}
