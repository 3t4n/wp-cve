<?php
/*
	Plugin Name: Disable Responsive Images Complete
	Plugin URI: https://perishablepress.com/disable-wordpress-responsive-images/
	Description: Completely disables WP responsive images
	Tags: responsive, images, responsive images, disable, srcset
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 4.6
	Tested up to: 6.5
	Stable tag: 2.6.2
	Version:    2.6.2
	Requires PHP: 5.6.20
	License: GPL v2 or later

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 
	2 of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/
	
	Copyright 2024 Monzilla Media. All rights reserved.
	
*/

if (!defined('ABSPATH')) exit;

// disable srcset on frontend
function disable_wp_responsive_images() {
	
	return 1;
	
}
add_filter('max_srcset_image_width', 'disable_wp_responsive_images');

// disable 768px image generation
function disable_wp_responsive_image_sizes($sizes) {
	
	unset($sizes['medium_large']);
	
	return $sizes;
	
}
add_filter('intermediate_image_sizes_advanced', 'disable_wp_responsive_image_sizes');