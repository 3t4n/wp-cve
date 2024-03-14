<?php
/*
    Plugin Name: Disable Blocks Widget Sidebar
    Plugin URI: http://devdatastudio.com/
    Description: Disables Gutenberg Block widgets Sidebar and restores the Classic widgets Sidebar  .
    Tags: widgets, classic widgets, block widgets, block-widgets, gutenberg, disable, blocks, posts, post types
    Author: Echo Coder
    Author URI: https://profiles.wordpress.org/echocoder/
    Contributors: echocoder
    Requires at least: 5.8
    Tested up to: 5.8
    Version: 1.0.0
    Requires PHP: 5.6.20
    Text Domain: disable-gutenberg-widgets
    License: GPL v2 or later
*/


if ( ! function_exists( 'disable_gutenberg_blocks_widget' ) ) :
    function disable_gutenberg_blocks_widget() {
        add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
        add_filter( 'use_widgets_block_editor', '__return_false' );
    }
endif;                                                                  

add_action( 'init', 'disable_gutenberg_blocks_widget' );          