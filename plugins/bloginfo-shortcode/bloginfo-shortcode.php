<?php
/*
Plugin Name: Bloginfo Shortcode
Plugin URI: http://w3prodigy.com/wordpress-plugins/bloginfo-shortcode/
Version: 1.1
Description: Displays information about your blog in a page or post. [bloginfo show="url"] where show can equal any values from http://codex.wordpress.org/Function_Reference/get_bloginfo 
Author: Jay Fortner
Author URI: http://w3prodigy.com
*/

new Bloginfo_Shortcode;

class Bloginfo_Shortcode {
	
	function Bloginfo_Shortcode()
	{
		add_shortcode('bloginfo', array(&$this,'bloginfo'));
	} // function
	
	function bloginfo($atts)
	{
		extract( shortcode_atts(array('show' => 'url'), $atts) );
		
		return get_bloginfo($show);
	} // function
	
} // class

?>