<?php
/*
Plugin Name: Magic Buttons for Elementor
Plugin URI: https://pwrplugins.com/magic-buttons-for-elementor-addon-and-widget-plugin-demo/
Description: This plugin extend Elementor by adding a new Magic Button widget, with awesome features and hover effects!
Author: rexdot
Version: 1.0
Author URI: https://pwrplugins.com
*/
function pwr_magic_buttons_load() {
	/*
	 * Elementor
	*/
	require ('elementor/extend-elementor.php');
	/*
	 * Shortcodes
	 */
	require ('magic_buttons_shortcodes.php');
}

pwr_magic_buttons_load();