<?php
/*
Plugin Name: WP Payhip Integration
Description: Integrates Payhip into WordPress. When a Payhip product page link is clicked, it will open a Payhip "Buy Now" box. There are no settings, it will automatically work on all links to Payhip product pages, both existing and new ones.
Version: 1.1
Author: Robin Phillips (Author Help)
Author URI: https://www.authorhelp.uk/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WP Payhip Integration is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Payhip Integration is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Payhip Integration. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

function wp_payhip_init () {
	// Register the JavaScript
	wp_register_script('payhip', 'https://payhip.com/payhip.js');
	wp_register_script('wp_payhip_js', plugin_dir_url(__FILE__) . 'wp-payhip.min.js', array('jquery'));

	// Enqueue the JavaScript
	wp_enqueue_script('payhip');
	wp_enqueue_script('wp_payhip_js');
}

// Enqueue the JavaScript - only in main area, not admin area
if (!is_admin())
	add_action('wp_enqueue_scripts', 'wp_payhip_init');
