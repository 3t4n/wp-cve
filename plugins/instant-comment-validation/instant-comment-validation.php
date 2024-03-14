<?php
/*
Plugin Name: Instant Comment Validation
Plugin URI: http://wordpress.org/plugins/instant-comment-validation/
Description: Add a instant validator for WordPress comment form, instead of sending users to default error page.
Author: Mrinal Kanti Roy
Version: 1.1.0
Author URI: http://profiles.wordpress.org/mkrdip/
License: GPLv2 or later
*/

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;
	
function instant_comment_validation_init() {
		
	//load files only in single posts & if comments are open
	if(is_singular() && comments_open() ) { 	
		
	//loading plugin assest (CSS & JS files)
	wp_enqueue_style( 'commentvalidation', WP_PLUGIN_URL . '/instant-comment-validation/assets/css/instant-comment-validation.css');
	wp_enqueue_script('jqueryvalidate', WP_PLUGIN_URL . '/instant-comment-validation/assets/js/jquery.validate.min.js', array('jquery'));
	wp_enqueue_script('commentvalidation', WP_PLUGIN_URL . '/instant-comment-validation/assets/js/instant-comment-validation.js', array('jquery','jqueryvalidate'));
	}
}
add_action('wp_footer', 'instant_comment_validation_init');
?>
