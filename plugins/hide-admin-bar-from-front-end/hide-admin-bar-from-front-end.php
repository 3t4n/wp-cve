<?php
/**
 * Plugin Name: Hide Admin Bar From Front End
 * Plugin URI: https://aftabhusain.wordpress.com/
 * Description: This plugin provides  feature to hide/show admin bar from front end.
 * Version: 1.0.0
 * Author: Aftab Husain
 * Author URI: https://aftabhusain.wordpress.com/
 * Author Email: amu02.aftab@gmail.com
 * License: GPLv2
 */

define('HSABFFE_REGISTRATION_PAGE_DIRECTORY', plugin_dir_path(__FILE__).'includes/');

// New menu submenu for plugin options in Settings menu
add_action('admin_menu', 'hsabffe_options_menu');
function hsabffe_options_menu() {
	add_options_page('Hide/Show Admin Bar', 'Hide/Show Admin Bar', 'manage_options', 'hide_admin_bar_from_front_end', 'hsabffe_plugin_pages');
	
}

function hsabffe_plugin_pages() {

   $itm = HSABFFE_REGISTRATION_PAGE_DIRECTORY.$_GET["page"].'.php';
   include($itm);
}

//code to hide_admin_bar start

$hsabffe_hide_admin_bar= get_option('hsabffe_hide_admin_bar');

if($hsabffe_hide_admin_bar == 'hide'){
 add_filter('show_admin_bar', '__return_false');
}

//code to hide_admin_bar end
?>
