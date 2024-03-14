<?php
/*

Plugin Name: Add Post Thumbnail Shortcode
Plugin URI:  http://aahacreative.com/our-projects/wordpress-post-featured-image-shortcode/
Description: Adds a [post_thumbnail] shortcode for use with wordpress post thumbnails. Also accepts [post_thumbnail size=""]
Version: 1.2.2
Author: Aaron Harun
Author URI: http://aaron.md

*/

function post_thumbnail_shortcode($atts, $content='') {
	if(!function_exists('post_thumbnail_shortcode'))
		return;

	if(!$atts['size'])
		$atts['size'] = 'thumbnail';

	return '<span class="post_thumbnail '.$atts['class'].'">'.get_the_post_thumbnail(null,$atts['size']).'</span>';
}

function post_thumbnail($str){
	$args = wp_parse_args($str);
	echo post_thumbnail_shortcode($args);
}

add_shortcode('post_thumbnail', 'post_thumbnail_shortcode');


?>