<?php
/**
 * @package Activate-Update-Services
 * @author David M&aring;rtensson
 * @version 1.0.7
 */
/*
Plugin Name: Activate Update Services
Plugin URI: http://www.feedmeastraycat.net/projects/random-wordpress-plugins/activate-update-services/
Description: For some reason WordPress removes the Update Services ability (under Settings - Writing) when you create a network (aka enables WordPress MU/Multisite). Activate this plugin to get it back.
Author: David M&aring;rtensson
Version: 1.0.7
Author URI: http://www.feedmeastraycat.net/
*/


/**
 * Init plugin
 */
function ActivateUpdateServices_init() {
	
	// If multisite - Add filter to show update services.
	// Set prio 11 to run after the official. Thanks to @westi
	// QUOTE: 
	//		@DMRsweden The best thing to do is to use the add_filter but with priority 11 
	//		that way it will run after the default filter and win
	if (MULTISITE) {
		
		add_filter('enable_update_services_configuration', '__return_true', 11);
		
		add_filter('whitelist_options', 'ActivateUpdateServices_filter__whitelist_options', 11);
		
	}
	
}
add_action('init', 'ActivateUpdateServices_init');


/**
 * Filter for Whitelist options. Adds ping_sites as a white list so it will be updated and
 * saved into the database.
 */
function ActivateUpdateServices_filter__whitelist_options($input) {
	$input['writing'][] = "ping_sites";
	return $input;
}


/**
 * Make sure __return_true() exists
 */
if (!function_exists('__return_true')) {
	function __return_true() {
		return true;
	}
}








?>