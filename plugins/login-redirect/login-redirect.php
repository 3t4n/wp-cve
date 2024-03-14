<?php
/*

**************************************************************************

Plugin Name:  Login Redirect
Plugin URI:   http://www.arefly.com/login-redirect/
Description:  Redirect to a link after login. 登入後跳轉至特定鏈接
Version:      1.0.5
Author:       Arefly
Author URI:   http://www.arefly.com/
Text Domain:  login-redirect
Domain Path:  /lang/

**************************************************************************

	Copyright 2014  Arefly  (email : eflyjason@gmail.com)

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

**************************************************************************/

define("LOGIN_REDIRECT_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("LOGIN_REDIRECT_FULL_DIR", plugin_dir_path( __FILE__ ));
define("LOGIN_REDIRECT_TEXT_DOMAIN", "login-redirect");

/* Plugin Localize */
function login_redirect_load_plugin_textdomain() {
	load_plugin_textdomain(LOGIN_REDIRECT_TEXT_DOMAIN, false, dirname(plugin_basename( __FILE__ )).'/lang/');
}
add_action('plugins_loaded', 'login_redirect_load_plugin_textdomain');

include_once LOGIN_REDIRECT_FULL_DIR."options.php";

/* Add Links to Plugins Management Page */
function login_redirect_action_links($links){
	$links[] = '<a href="'.get_admin_url(null, 'options-general.php?page='.LOGIN_REDIRECT_TEXT_DOMAIN.'-options').'">'.__("Settings", LOGIN_REDIRECT_TEXT_DOMAIN).'</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'login_redirect_action_links');

function login_redirect() {     
	global $redirect_to;
	if(!isset($_GET['redirect_to'])){
		if(get_option("login_redirect_type") == "customise"){
			$redirect_to = get_option("login_redirect_customise_url");
		}else{
			$redirect_to = admin_url();
		}
	}
}
add_action('login_form', 'login_redirect'); 
