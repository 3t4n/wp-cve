<?php
/*
Plugin Name: Team Addon for Elementor
Plugin URI: http://wppug.com
Description: 
Author: wppug
Version: 1.0
Author URI: http://wppug.com
*/
function elpug_team_module2() {
	/*
	 * Elementor
	*/
	require ('elementor/extend-elementor.php');

	/*
	 * Shortcodes
	 */
	require ('team_shortcodes.php');
}

elpug_team_module2();