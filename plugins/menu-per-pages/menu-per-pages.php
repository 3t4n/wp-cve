<?php  
/* 
* Plugin Name: Menu Per Pages
* Plugin URI: http://www.ddisofttech.com
* Description: Wp Menu Per Pages Plugin let you provide to access a menu on your selected page.
* Version: 5.0
* Author: Gopal Sharma
* Author URI: http://www.ddisofttech.com
* Author Email: ddisofttech@gmail.com
* License: A "Slug" license name e.g. GPL2
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

$siteurl = get_option('siteurl');
define('DDI_FOLDER', dirname(plugin_basename(__FILE__)));
define('DDI_URL', $siteurl.'/wp-content/plugins/' . DDI_FOLDER);
define('DDI_FILE_PATH', dirname(__FILE__));
define('DDI_DIR_NAME', basename(DDI_FILE_PATH));

global $wpdb;

include('includes/ddi_functions.php');

register_activation_hook(__FILE__,'ddi_install');
register_deactivation_hook(__FILE__ , 'ddi_uninstall' );

add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
add_action( 'add_meta_boxes', 'meta_box_menu_per_page' );
add_action( 'save_post', 'meta_selected_menu_save' );


/*
*
* This Plugin function is useed to install the plugin
*
*/	
function ddi_install()
{
    global $wpdb;
				
}
/*
*
* This Plugin function is useed to uninstall the plugin
*
*/	
function ddi_uninstall()
{
    global $wpdb;  	
	
} 
?>