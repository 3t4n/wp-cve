<?php
/**
 * Plugin Name: Enable Shortcodes inside Widgets,Comments and Experts
 * Plugin URI: https://aftabhusain.wordpress.com/
 * Description: Very Simple plugin to enable shortcods inside Widget , Comments, Experts, Category, Tag, and Taxonomy Descriptions.
 * Version: 1.0.0
 * Author: Aftab Husain
 * Author URI: https://aftabhusain.wordpress.com/
 * Author Email: amu02.aftab@gmail.com
 * License: GPLv2
 */
 
	//Add shortcodes in Text Widgets 
	add_filter( 'widget_text', 'shortcode_unautop');
	add_filter( 'widget_text', 'do_shortcode');

	// Add shortcodes in Comments 
	add_filter( 'comment_text', 'shortcode_unautop');
	add_filter( 'comment_text', 'do_shortcode' );


	// Add shortcodes in  Excerpts 
	add_filter( 'the_excerpt', 'shortcode_unautop');
	add_filter( 'the_excerpt', 'do_shortcode');

	//Add shortcodes in Category, Tag, and Taxonomy Descriptions 
	add_filter( 'term_description', 'shortcode_unautop');
	add_filter( 'term_description', 'do_shortcode' );
?>
