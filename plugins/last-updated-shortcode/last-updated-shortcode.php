<?php
/*
Plugin Name: Last Updated Shortcode
Plugin URI: http://shinraholdings.com/plugins/last-updated-shortcode
Description: Creates a shortcode to display the date/time when a post/page was last updated (with optional formatting).
Version: 1.0.1
Author: bitacre
Author URI: http://shinraholdings.com
License: GPLv2 
	Copyright 2016 Shinra Web Holdings (plugins@shinraholdings.com)
	
Shortcodes: 
	[lastupdate] or [lastupdated]
	
Optional Arguments:
	format=""
		If this argument is omitted, the plugin just uses the format "January 11, 2012 at 7:02 pm".
		You can use any of the special date variables listed at http://codex.wordpress.org/Formatting_Date_and_Time.
		If you want to insert a variable letter as plain text (like the word "AT") escape them with a backslashs, \A\T
		
	before=""
		String you would like to appear before the date/time is displayed.
		Can contain html tag elements.
		A space is always inserted between the before string and the date/time.
		If you don't specify a before, the plugin just uses "Last updated:"
		
	after=""
		String you would like to appear after the date/time is displayed.
		Mostly useful for closing tags opened using before=""
		No space is inserted between the date/time and the after string.
		If you don't specify an after, the plugin doesn't insert anything after.
		
Examples:
	[lastupdated format="Y"] 					=> 		2012 
	[lastupdated format="l, F j, Y"] 			=>		Friday, January 11, 2012
	[lastupdated format="G:i a (T)"] 			=>		7:02 pm (EST)
	[lastupdated format="Y-m-d_h:i:s"] 			=> 		2012-01-11_19:02:44
	[lastupdated format="l, F j, Y \a\t G:i A"	=>		Friday, January 11, 2012 at 7:02 PM
	
	[lastupdated before="Last update:"]			=>		Last update: Jan-11-2012
	
	[lastupdated format="l, F j, Y" before="<span>This page hasn't been updated since" after="!</span>"] 	=>
		<span>This page hasn't been updated since Friday, January 11, 2012!</span>
*/

// variables
	$comment_tag = '
<!-- 
Plugin: Last Updated Shortcode
Plugin URI: http://shinraholdings.com/plugins/last-updated-shortcode
-->
';


// shortcode echo function
function last_updated_shortcode( $atts ) {
	extract( shortcode_atts( array( 'format' => 'F j, Y \a\t G:i a', 'before' => 'Last updated:', 'after' => '' ), $atts ) ); // extract optional format argument
	
	return $comment_tag . the_modified_date( $format, $before . ' ', $after, 0 );
}

// hooks and filters
$shortcodes = array( 'lastupdate', 'lastupdated' ); // add shortcode triggers to array
foreach( $shortcodes as $shortcode ) add_shortcode( $shortcode, 'last_updated_shortcode' ); // create shortcode for each item in $shortcodes
?>
