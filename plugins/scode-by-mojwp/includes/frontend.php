<?php

if (!function_exists('add_action')) {
	echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
	exit;
}

remove_filter('the_content', 'shortcode_unautop');
remove_filter('the_excerpt', 'shortcode_unautop');

$sc_shortcodes_array = array();

add_action('plugins_loaded', 'sc_shortcode_hook');
function sc_shortcode_hook() {
	$sc_shortcodes = scode_get_shortcodes();
	
	global $shortcode_tags;
	global $sc_shortcodes_array;
	
	foreach ($sc_shortcodes as $sc_shortcode) {
		$sc_shortcodes_array[$sc_shortcode['code']] = $sc_shortcode['value'];
		$shortcode_tags[$sc_shortcode['code']] = 'sc_shortcode_replace';
	}
}

function sc_shortcode_replace() {
	$args = func_get_args();
	global $sc_shortcodes_array;
	return stripslashes($sc_shortcodes_array[$args[2]]);
}

?>