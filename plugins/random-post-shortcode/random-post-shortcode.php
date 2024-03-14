<?php
/*
Plugin Name: Powie's Random Post shortcode
Plugin URI: https://powie.de/wordpress
Description: Shortcode <code>[random-post]</code> for displaying a random post, or use the Widget
Version: 1.0.5
License: GPLv2
Author: Thomas Ehrhardt
Author URI: https://powie.de
*/

//call widgets file
include('random-post-widget.php');

//Shortcode
add_shortcode('random-post', 'random_post_shortcode');
function random_post_shortcode( $atts ) {
	$return='';
	//var_Dump($atts);
	if ( !isset($atts['numberposts']) ) {
		$atts['numberposts'] = 1;
	}
	if ( !isset($atts['category_name']) ) {
		$atts['category_name'] = '';
	}
	/*
	extract( shortcode_atts( array(
		'foo' => 'something',
		'bar' => 'something else',
	), $atts ) );
	return "Hallo -> foo = {$foo}";
	*/
	$args = array(
	'numberposts'     => $atts['numberposts'],
    'category_name'  => $atts['category_name'],	
	'orderby'         => 'rand',
	'post_type'       => 'post',
	'post_status'     => 'publish');
	$post = get_posts($args);
	foreach ($post as $p) {
		$title = apply_filters('the_title', $p->post_title);
		$content =  apply_filters('the_content', $p->post_content);
		$return.='<h2>'.$title.'</h2>'.$content;
	}
	return $return;
}

//Hook for Activation
register_activation_hook( __FILE__, 'rp_activate' );
//Hook for Deactivation
register_deactivation_hook( __FILE__, 'rp_deactivate' );
//Activate
function rp_activate() {
	// do not generate any output here
	//add_option('postfield-rows',5);
}
//Deactivate
function rp_deactivate() {
	// do not generate any output here
}
?>