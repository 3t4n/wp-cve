<?php

/*********************
SCRIPTS
*********************/

function sb_load_scripts() {
	if(!is_admin()) {
	wp_enqueue_style('sb-styles', plugins_url( '/css/style.css' , __FILE__ ));
	wp_register_script('sb-tabs-js', plugins_url( '/js/tabs.js' , __FILE__ ), array('jquery'), 1.0, true);
	wp_enqueue_script('sb-tabs-js');
	}
}
add_action('wp_enqueue_scripts', 'sb_load_scripts');

?>