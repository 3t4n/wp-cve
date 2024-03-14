<?php
/*
Plugin Name: Disable WPAUTOP
Plugin URI: https://wordpress.org/plugins/disable-wpautop/
Description: Disables the wpautop function for the_content and the_excerpt.
Version: 1.1
Author: Nick Momrik
Author URI: http://nickmomrik.com/
*/

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );
