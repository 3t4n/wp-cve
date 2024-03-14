<?php
/*
Plugin Name: Align RSS Images
Plugin URI: http://w-shadow.com/blog/2008/12/31/aligning-images-in-rss-feeds/
Description: Makes image alignment work in RSS feeds.  
Version: 1.3.4
Author: Janis Elsts
Author URI: http://w-shadow.com/blog/
*/

/*
Created by Janis Elsts (email : whiteshadow@w-shadow.com) 
It's LGPL.
*/

function ws_add_alignment_styles($matches) {
	static $explicit_alignment_styles = array(
		'alignleft'         => 'float: left;',
		'alignright'        => 'float: right;',
		'aligncenter'       => 'display: block; margin-right: auto; margin-left: auto;',
		'centered'          => 'display: block; margin-right: auto; margin-left: auto;',
		'img.alignleft'     => 'padding: 4px; margin: 0 7px 2px 0;',
		'img.alignright'    => 'padding: 4px; margin: 0 0 2px 7px;',
		'wp-caption'        => 'border: 1px solid #dddddd; background-color: #f3f3f3; padding: 4px; margin: 10px; text-align:center;',
		'p.wp-caption-text' => 'padding: 0 4px 5px; margin: 0;',
	);

	$tag = strtolower($matches[1]);

	//Find the class attribute
	if ( preg_match('/class=(["\'])(.+?)\1/i', $matches[0], $classdata) ) {
		$classes = preg_split('/\s+/', $classdata[2]);
	} else {
		return $matches[0];
	}

	//Generate the appropriate inline style
	$style = "";
	foreach ($classes as $class) {
		if ( isset($explicit_alignment_styles[$class]) ) {
			$style .= " " . $explicit_alignment_styles[$class];
		}
		if ( isset($explicit_alignment_styles[$tag . '.' . $class]) ) {
			$style .= " " . $explicit_alignment_styles[$tag . '.' . $class];
		}
	}

	if ( empty($style) ) {
		return $matches[0];
	}

	//Find the original style attribute, if any
	if ( preg_match('/style=(["\'])(.*?)\1/i', $matches[0], $styledata) ) {
		//Add the style info to the attribute
		$result = str_replace($styledata[0], "style={$styledata[1]}{$styledata[2]}; {$style}{$styledata[1]}", $matches[0]);
	} else {
		//Insert a new style attribute
		$result = str_replace('<' . $matches[1], "<$tag style='$style' ", $matches[0]);
	}

	return $result;

}

function ws_align_rss_images($content) {
	if ( is_feed() ) {
		$content = preg_replace_callback('/<(div|img|p|span|figure).*?' . '>/i', 'ws_add_alignment_styles', $content);
	}
	return $content;
}

add_filter('the_content', 'ws_align_rss_images', 10000);