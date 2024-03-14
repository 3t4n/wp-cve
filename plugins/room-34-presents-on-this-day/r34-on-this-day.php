<?php
/*
Plugin Name: On This Day (by Room 34)
Plugin URI: https://blog.room34.com/archives/4841
Description: A very simple widget that displays a list of blog posts that were published on the same date in previous years. Title and "no posts" message are customizable.
Version: 3.2.1
Author: Room 34 Creative Services, LLC
Author URI: http://room34.com
License: GPL2
Text Domain: r34otd
Domain Path: /i18n/languages/
*/

/*  Copyright 2012-2021 Room 34 Creative Services, LLC (email: info@room34.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once(plugin_dir_path(__FILE__) . '/admin.php');
require_once(plugin_dir_path(__FILE__) . '/archive.php');
require_once(plugin_dir_path(__FILE__) . '/widget.php');

// Flush rewrite rules when plugin is activated
register_activation_hook(__FILE__, function() { flush_rewrite_rules(); });
