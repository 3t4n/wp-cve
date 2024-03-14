<?php
/*
Plugin Name: Pug Blogroll
Plugin URI: http://wppug.com
Description: Adds blogroll Functionality for Elementor.
Author: WpPug
Version: 1.0
Author URI: http://wppug.com
*/

function elpug_blogroll_module() {
	/*
	 * Shortcodes
	 */
	//require ('blogroll_shortcodes.php');
	/*
	 * Elementor
	 */
	require ('elementor/extend-elementor.php');
}
elpug_blogroll_module();

function elpug_blogroll_get_excerpt_by_id($post_id,$excerpt_length){
    $the_post = get_post($post_id); //Gets post ID

    $the_excerpt = null;
    if ($the_post)
    {
        $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
    }

    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

     if(count($words) > $excerpt_length) :
         array_pop($words);
         array_push($words, '…');
         $the_excerpt = implode(' ', $words);
     endif;

     return $the_excerpt;
}