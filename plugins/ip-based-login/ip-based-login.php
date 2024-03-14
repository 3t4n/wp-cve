<?php

/**
 * @package ip-based-login
 * @version 2.3.10
 */
/*
Plugin Name: IP Based Login
Plugin URI: http://wordpress.org/extend/plugins/ip-based-login/
Description: IP Based Login is a plugin which allows you to directly login from an allowed IP. You can create ranges and define the IP range which can get access to a particular user. So if you want to allow someone to login but you do not want to share the login details just add their IP using IP Based Login.
Version: 2.3.10
Text Domain: ip-based-login
Domain Path: /languages/
Author: Brijesh Kothari
Author URI: https://profiles.wordpress.org/brijeshk89/
License: GPLv3 or later
*/

/*
Copyright (C) 2013  Brijesh Kothari (email : admin@wp-inspired.com)
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

function ip_based_login_load_plugin_textdomain(){
    load_plugin_textdomain( 'ip-based-login', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'ip_based_login_load_plugin_textdomain' );

$_ltmp_plugins = get_option('active_plugins');

// Is the premium plugin loaded ?
if(in_array('ip-based-login-pro/ip-based-login-pro.php', $_ltmp_plugins)){
	return;
}

// Is the premium plugin active ?
if(defined('ipbl_version')){
	return;
}

$plugin_ipbl = plugin_basename(__FILE__);
define('IPBL_FILE', __FILE__);
define('IPBL_API', 'https://api.wp-inspired.com/');
define('IPBL_UPDATES_API', 'https://wp-inspired.com/wp-content/uploads/packages/');

include_once(dirname(__FILE__).'/init.php');