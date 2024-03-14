<?php

/*
Plugin Name: Global Settings
Author: Victor4g, vishaljp
Author URI: http://vishaljp.wordpress.com
Version: 1.1
Description: Global Setting allows you to add, update and delete the global variables for your blog.

This file is part of Global Settings.

Global Setting is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License.

Global Setting is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Global Setting. If not, see .

*/

function gbs_main(){
	include('gbs-option.php');
}

function gbs_custom_options()
{
	add_menu_page('Global Settings', 'Global Settings','administrator', 'gbs', '', plugins_url('global-settings/download.png'));
	add_submenu_page( 'gbs', 'Manage', 'Manage', 'administrator', 'gbs', 'gbs_main' );
}
	add_action('admin_menu', 'gbs_custom_options');
	add_shortcode('global', 'gbs_global_option');
	function gbs_global_option($attr)
{
	return get_option('gbs_'.$attr[0]);
	
}	

?>