<?php
/*
Plugin Name: Really Simple popup
Plugin URI: http://www.hotscot.net/
Description: Simple, easy to use, fancybox style popup
Version: 1.0.11
Author: Hotscot
Author URI: http://www.hotscot.net
License: GPL2
*/
////////////////////////////////////////////////////////////////////////////////
/*  Copyright 2012 Hotscot (email : support@hotscot.net)

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
////////////////////////////////////////////////////////////////////////////////

add_action('wp_enqueue_scripts', 'hs_rsp_enqueue_scripts_and_styles');

/**
 * Enques the relevent js and css
 *
 * @return void
 */
function hs_rsp_enqueue_scripts_and_styles(){
	//Register and enqueue the javascript
	wp_register_script('hs_rsp_popup_js',  plugin_dir_url( __FILE__ ) . "js/hs_rsp_popup.min.js", array('jquery'));
	wp_enqueue_script('hs_rsp_popup_js');

	//Register and enqueue the CSS
	wp_register_style('hs_rsp_popup_css', plugin_dir_url( __FILE__ ) . "css/hs_rsp_popup.min.css");
	wp_enqueue_style('hs_rsp_popup_css');
}
