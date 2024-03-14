<?php
/* 
Plugin Name: Acurax On Click PopUnder
Plugin URI: http://www.acurax.com/Products/acurax-click-pop-plugin-wordpress/
Description: The Best Pop Under Plugin which helps you to show pop under on visitors browser on click.Plugin helps you to configure multiple URL'S. Plugin will set cookie on visitors browser when popunder appear and so it will show only once. You can also configure the cookie timeout in plugin settings.
Author: Acurax 
Version: 3.0
Author URI: http://www.acurax.com 
License: GPLv2 or later
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/ 
define("ACURAX_POPUNDER_VERSION_P","3.0");
define("ACX_OCPU_BASE_LOCATION",plugin_dir_url( __FILE__ ));
define("ACX_OCPU_WP_SLUG","acurax-on-click-pop-under");

//*************** Admin function ***************
include_once(plugin_dir_path( __FILE__ ).'function.php');
include_once(plugin_dir_path( __FILE__ ).'includes/hooks.php');
include_once(plugin_dir_path( __FILE__ ).'includes/hook_functions.php');
include_once(plugin_dir_path( __FILE__ ).'includes/option_fields.php');
include_once(plugin_dir_path( __FILE__ ).'includes/acx-onclick_popunder-licence-activation.php');
function acx_onclick_popunder_admin() {
	include_once(plugin_dir_path( __FILE__ ).'includes/acx_onclick_popunder_admin.php');
}
function acx_onclick_popunder_misc() 
{
	include_once(plugin_dir_path( __FILE__ ).'includes/acx_onclick_popunder_misc.php');
}
function acx_onclick_popunder_addons_page() 
{
	include(plugin_dir_path( __FILE__ ).'includes/acx_onclick_popunder_addons.php');
}
function acx_onclick_popunder_admin_actions()
{
	add_menu_page(  __('PopUnder','acurax-on-click-pop-under'), __('PopUnder','acurax-on-click-pop-under'),'manage_options', 'Acurax-onclick-popunder-Settings','acx_onclick_popunder_admin',plugin_dir_url( __FILE__ ).'/images/admin.png' ); // manage_options for admin
	
	add_submenu_page('Acurax-onclick-popunder-Settings',  __('Acurax Onclick Popunder Misc Settings','acurax-on-click-pop-under'),  __('Misc','acurax-on-click-pop-under'), 'manage_options', 'Acurax-Onclick-Popunder-Misc' ,'acx_onclick_popunder_misc');
	
	add_submenu_page('Acurax-onclick-popunder-Settings', __('Acurax Onclick Popunder Available Add-ons','acurax-on-click-pop-under'), __('Add-ons','acurax-on-click-pop-under'), 'manage_options', 'Acurax-Onclick-Popunder-Add-ons' ,'acx_onclick_popunder_addons_page');
	
}
if ( is_admin() )
{
	add_action('admin_menu', 'acx_onclick_popunder_admin_actions');
}
?>