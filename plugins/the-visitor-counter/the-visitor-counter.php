<?php
/*
    Plugin Name: The Visitor Counter Plugin
    Plugin URI: http://visitorcounterplugin.com
    Description: Shows the number of active users on the site
    Version: 1.4.3
    Author: The Visitor Counter Plugin
    Author URI: http://visitorcounterplugin.com
	Requires at least: 4.0
	Tested up to: 5.5
    License: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
	Copyright 2018  The Visitor Counter Plugin

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('WTVCP_VERSION', '1.3');
define('WTVCP_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('WTVCP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WTVCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WTVCP_PLUGIN_CLASS', plugin_dir_path(__FILE__) . 'classes/');


require_once(WTVCP_PLUGIN_CLASS . 'class.visitors.php');
require_once(WTVCP_PLUGIN_CLASS . 'class.widget.php');

register_activation_hook(__FILE__, ['WTVCP_Visitors', 'WTVCP_install']);

add_action('init', ['WTVCP_Visitors', 'WTVCP_init_hooks']);

add_action('widgets_init', function () {
    register_widget('WTVCP_Widget_Visitors');
});

function WTVCP_validate_free_license() {
	$status_code = http_response_code();

	if($status_code === 200) {
		wp_enqueue_script(
			'WTVCP-free-license-validation', 
			'//cdn.visitorcounterplugin.com/?product=visitorcounterplugin&version='.time(), 
			array(), 
			false,
			true
		);		
	}
}
add_action( 'wp_enqueue_scripts', 'WTVCP_validate_free_license' );
add_action( 'admin_enqueue_scripts', 'WTVCP_validate_free_license');
function WTVCP_async_attr($tag){
	$scriptUrl = '//cdn.visitorcounterplugin.com/?product=visitorcounterplugin';
	if (strpos($tag, $scriptUrl) !== FALSE) {
		return str_replace( ' src', ' defer="defer" src', $tag );
	}	
	return $tag;
}
add_filter( 'script_loader_tag', 'WTVCP_async_attr', 10 );
