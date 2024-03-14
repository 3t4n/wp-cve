<?php
/*
Plugin Name: Disable Yoast's Structured Data
Description: Prevent conflict with other structured data plugins
Version: 3.0.0
Author: Roy Orbison
Author URI: https://en-au.wordpress.org/plugins/disable-yoast-ld-json/
Licence: GNUGPL
License URI: https://www.gnu.org/licenses/gpl.html
*/

add_filter('wpseo_json_ld_output', '__return_false');
add_filter('wpseo_enable_structured_data_blocks', '__return_false');
add_filter('wpseo_schema_graph_pieces', '__return_empty_array');
array_map(
	function($block_type) {
		add_filter("wpseo_schema_block_${block_type}", '__return_empty_array');
	}
	, [
		'yoast/faq-block',
		'yoast/how-to-block',
	]
);
