<?php
/*
Plugin Name: WP-PostRatings-Cheater
Plugin URI: http://sites.google.com/site/manfred.fettinger/
Description: If you use the famous wp-postratings plugin, you can now rate your articles like you want
Version: 1.5
Author: Manfred Fettinger
Author URI: http://sites.google.com/site/manfred.fettinger/
*/

/*  Copyright 2012  Manfred Fettinger  (email : manfred.fettinger@gmail.com)

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

### Cheater Logs Table Name
global $wpdb;



### Function: Administration Menu
add_action('admin_menu', 'prcheater_menu');
function prcheater_menu() {
	if (function_exists('add_menu_page')) 
		add_menu_page('WP-PostRatings Cheater', 'Ratings Cheater', 'role_prcheater', 'wp-postratings-cheater/wp-postratings-cheater-settings.php');
		
	if (function_exists('add_submenu_page')) {
		add_submenu_page('wp-postratings-cheater-settings.php', 'WP-Post',  'Settings',  'role_prcheater', 'wp-postratings-cheater/wp-postratings-cheater-settings.php');
	}	
}

add_action('activate_wp-postratings-cheater/wp-postratings-cheater.php', 'wppostratingscheaterinit');
function wppostratingscheaterinit() {
	// Set 'manage_ratings' Capabilities To Administrator	
	$role = get_role('administrator');
	if(!$role->has_cap('role_prcheater')) {
		$role->add_cap('role_prcheater');
	}
}
?>