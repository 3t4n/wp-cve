<?php
/**
 *Plugin Name: Remove Wpautop
 *Plugin URI: http://datasolz.com/
 *Description: Disables the wpautop function from the_content and the_excerpt 
 *Version: 1.0
 *Author: Rahul Kumar singh 
 *Author URI: http://datasolz.com
 *License: GPL2
**/

// Remove the wpautop filter completely
   remove_filter('the_content', 'wpautop');
   remove_filter('the_excerpt', 'wpautop');
?>