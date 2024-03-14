<?php
/**
 * Plugin Name: Empty P Tag
 * Description: This plugin removes empty p and br tag from the content. 
 * Version: 2.0.1
 * Author: Husain Ahmed
 * Author URI: https://husain25.wordpress.com
 * Author Email: husain.ahmed25@gmail.com
 * License: HAQV1
 */

   	remove_filter('the_excerpt', 'wpautop');
   	remove_filter('the_content', 'wpautop');
   	remove_filter('widget_text_content', 'wpautop');

   	
   	add_filter('the_content', 'haq_empty_p_tag', 20, 1);
	function haq_empty_p_tag($content){
       	$content = force_balance_tags($content);
       	return preg_replace('#<p>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content);
    }
   
?>