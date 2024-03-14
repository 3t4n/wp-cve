<?php
/*
Plugin Name: Comment Validation
Plugin URI: http://bassistance.de/wordpress-plugin-comment-validation/
Description: Client-side validation for comments
Author: Jörn Zaefferer
Version: 0.4
Author URI: http://bassistance.de
*/

function load_comment_validation() {
	wp_enqueue_style( 'commentvalidation', WP_PLUGIN_URL . '/comment-validation/comment-validation.css');
	wp_enqueue_script('jqueryvalidate', WP_PLUGIN_URL . '/comment-validation/jquery.validate.pack.js', array('jquery'));
	wp_enqueue_script('commentvalidation', WP_PLUGIN_URL . '/comment-validation/comment-validation.js', array('jquery','jqueryvalidate'));
}

add_action('init', 'load_comment_validation');

?>