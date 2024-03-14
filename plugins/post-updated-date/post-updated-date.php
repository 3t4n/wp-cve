<?php
/*
Plugin Name: Posts Modified Date
Plugin URI: https://www.usefulblogging.com/add-last-updated-date-wordpress-blog-posts
Description: Add Last Updated Date in WordPress Blog Posts.
Version: 1.3
Author: Ataul Ghani
Author URI: https://www.usefulblogging.com
Requires at least: 5.5
Tested Up to: 6.1
Stable Tag: trunk
License: GPL v2
*/

function awd_post_modified($d = '') {
	        if ( '' == $d )
	                $the_time = get_post_modified_time(get_option('date_format'), null, null, true);
	        else
	                $the_time = get_post_modified_time($d, null, null, true);
	
	        /**
	         * Filter the date a post was last modified.
	         *
	         * @since 2.1.0
	         *
	         * @param string $the_time The formatted date.
	         * @param string $d        PHP date format. Defaults to value specified in
	         *                         'date_format' option.
	         */
	        return apply_filters( 'awd_post_modified', $the_time, $d );
	}
add_shortcode( 'post_modified', 'awd_post_modified' );

//* Add Post Modifed Date
add_filter('the_content', 'awd_modified_date');

function awd_modified_date($content) {

$awd_modified_date= '<span style="font-style:italic; font-weight:bold;text-align:center;">(Last Updated On: [post_modified])</span>';

if(is_single() && !is_home()) {
$content = $awd_modified_date.$content;
}
return $content;
}

?>