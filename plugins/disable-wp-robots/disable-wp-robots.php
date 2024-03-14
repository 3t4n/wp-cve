<?php 
/*
	Plugin Name: Disable WP Robots
	Plugin URI: https://perishablepress.com/wordpress-disable-wp-robots/
	Description: Activate this plugin to disable the WP robots meta tag.
	Tags: robots, meta, disable
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 5.7
	Tested up to: 6.5
	Stable tag: 1.7
	Version:    1.7
	Requires PHP: 5.6.20
	Text Domain: disable-wp-robots
	Domain Path: /languages
	License: GPL v2 or later
*/

/*
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

if (!defined('ABSPATH')) die();

remove_filter('wp_robots', 'wp_robots_max_image_preview_large');