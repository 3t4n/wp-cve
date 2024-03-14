<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Add Scripts. USE SPECIAL FUNCTION NAME SO I WILL NOT ENGAGE OTHER PLUGINS
function w2a_add_scripts(){

//enter the css to the plugin
	wp_enqueue_style('w2a-main-style', plugins_url() . '/web2application/css/w2a-style.css');  
// enter the JS to the plugin
	wp_enqueue_script('w2a-main-script', plugins_url() . '/web2application/js/w2a-main.js');
	
}

// add the hook of the script that will enter it to the wordpress flow. there is many hooks and we can use it to enter scripts to the html flow to the right place
add_action('admin_enqueue_scripts', 'w2a_add_scripts');
