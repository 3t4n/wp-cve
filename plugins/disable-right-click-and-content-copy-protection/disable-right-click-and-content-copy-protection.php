<?php
/*
Plugin Name: Disable Right Click and Content Copy Protection
Plugin URI: 
Description: This plugin is used to disable right click on website to prevent cut, copy, paste, save image etc. But when Administrator or Site Editor is logged in, he can access everything without any of the above restrictions.
Tags: prevent right click, disable right click, stop image saving with right click, copyright protection, no copy paste.
Author: Meet Makadia
Version: 1.4
Author URI: https://profiles.wordpress.org/immeet94/
*/
add_action('wp_enqueue_scripts', 'drcacc_js');

function drcacc_js()
{
	if( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
		//Silence Is Golden
	}else{
		wp_enqueue_script('jquery');
		wp_register_script('drcacc_disablejs',plugins_url( 'drcacc_disable.js' , __FILE__ ),array( 'jquery' ));
    	wp_enqueue_script('drcacc_disablejs');
    }
}
?>