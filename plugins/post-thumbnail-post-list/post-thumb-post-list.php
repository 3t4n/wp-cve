<?php
/*
Plugin Name: Post Thumbnail & Post List
Plugin URI: https://www.themelamp.com/downloads/wp-post-thumbnail-post-list-free-wordpress-plugins/
Description: post thumbnail and post list plugin will help you to Show your recent published wordpress post with thumbnail and lists style. Show your post with individual category. No coding required, Mobile friendly and fast performance. You can use this plugin any of your wordpress sidebar widget.
Author: ThemeLamp
Author URI: https://themelamp.com
textdomain: wp-post-thumb-list
Version: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

/*
 Copyright (C) 2020  @ThemeLamp
*/
	
//Exit If accessed directly
if ( ! defined(	'ABSPATH' ) ) {
	exit;
}

//	Defines
	define( 'WP_POST_THUMB_POST_LIST', plugin_dir_path ( __FILE__ ) );

//	Loading Plugin Files
	require_once( WP_POST_THUMB_POST_LIST . 'includes/widget.php' );
	require_once (WP_POST_THUMB_POST_LIST . 'includes/wp-post-thumb-list-main.php');



//	Registering Plugin Files
function wp_post_thumb_list_files(){
	wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . '/assets/font-awesome.css', array(), '4.7.0' );
	wp_enqueue_style( 'post-list-widget', plugin_dir_url( __FILE__ ) . '/assets/wp-post-thumb-list.css', array(), '1.0.0' );
}
add_action('wp_enqueue_scripts', 'wp_post_thumb_list_files');