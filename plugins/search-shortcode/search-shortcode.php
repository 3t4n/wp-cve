<?php

/*
Plugin Name: Search shortcode
Plugin URI: http://eng.marksw.com/2013/02/12/a-search-short-code-plugin-for-wordpress/
Description: Provides a [search] shortcode to insert search form in content.
Author: Mark Kaplun
Version: 1.0
Author URI: http://eng.marksw.com
Tags: Search
License: GPLv3
*/

function mk_ser_shortcode( $args, $content, $tag ) {
	
	global $post;

	if (!is_singular()) {
	  return '[Search form]';
	}
	
	ob_start();
	get_search_form(true);
	$ret = ob_get_contents();
	ob_end_clean();
	
	return "<p>$ret</p>";
	
}

add_shortcode( 'search', 'mk_ser_shortcode' );


?>