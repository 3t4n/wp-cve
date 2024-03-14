<?php 

function social_icon_widget_scripts(){
	wp_enqueue_style("font-awesome", plugins_url("assets/css/font-awesome.min.css", __FILE__), FALSE);	
	wp_enqueue_style("main-style", plugins_url("assets/css/style.css", __FILE__), FALSE);
}
add_action('wp_enqueue_scripts', 'social_icon_widget_scripts');