<?php
/*
Plugin Name: Gravity Forms Placeholder Add-On
Description: Adds HTML5 placeholder support to Gravity Forms as well as a Placeholder like function to select boxes.
Version: 1.1.0
Author: Able Engine, William Wilkerson
Author URI: http://www.ableengine.com

Instructions:
Just add a class name of  "gf-add-placeholder" to the fields that you want the placeholder applied to. 
If you want this applied to the entire form then just add the same class to the entire form
*/

if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'wp-login.php' )
	return;

add_action('wp_footer', 'gf_placeholder_addon_script_enqueue');

function gf_placeholder_addon_script_enqueue() {
	$plugin_url = plugins_url( basename(dirname(__FILE__)) );
	echo "<script>var jquery_placeholder_url = '" . $plugin_url . "/jquery.placeholder-1.0.1.js';</script>";
	wp_enqueue_script('gf_placeholder_add_on', $plugin_url . '/gfplaceholderaddon.js', array('jquery'), '1.0' );
}

