<?php
/*
Plugin Name: HTML in Category Descriptions
Version: 1.2.3
Plugin URI: http://wordpress.org/extend/plugins/allow-html-in-category-descriptions/
Description: Allows you to add HTML code in category descriptions
Author: Arno Esterhuizen
Author URI: arno.esterhuizen@gmail.com
Text Domain: allow-html-in-category-descriptions
*/

add_action('init','disable_kses_if_allowed');

function disable_kses_if_allowed() {
	if (current_user_can('unfiltered_html')) {
		// Disables Kses only for textarea saves
		foreach (array('pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description') as $filter) {
			remove_filter($filter, 'wp_filter_kses');
		}
	}

	// Disables Kses only for textarea admin displays
	foreach (array('term_description', 'link_description', 'link_notes', 'user_description') as $filter) {
		remove_filter($filter, 'wp_kses_data');
	}
}

//Additional links on the plugin page
add_filter('plugin_row_meta', 'RegisterPluginLinks', 10, 2);

function RegisterPluginLinks ($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$links[] = '<a href="http://wordpress.org/support/plugin/allow-html-in-category-descriptions">Support</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SGS5KSM9N4D3Y">Donate</a>';
	}
	return $links;
}	
