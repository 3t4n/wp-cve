<?php
/*
	Plugin Name: HTTP Basic Auth
	Plugin URI: https://wordpress.org/plugins/http-basic-auth
	Description: Basic Auth form login, admin or whole site
	Version: 1.1.0
	Author: grola
	Author URI: https://profiles.wordpress.org/grola#content-plugins
	Text Domain: http-basic-auth
	Domain Path: /lang/
	Tested up to: 5.9
    WC Tested up to: 5.9

	Copyright 2016 WP Desk Ltd.

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
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

$plugin_version = '1.1.0';
define( 'HTTP_BASIC_AUTH_VERSION', $plugin_version );

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( 'classes/wpdesk/class-plugin.php' );
require_once( 'classes/class-http-basic-auth-plugin.php' );

/**
 * @return HTTP_Basic_Auth_Plugin
 */
function http_basic_auth_plugin() {
	global $basic_auth_plugin;
	if ( !isset( $basic_auth_plugin ) ) {
		$basic_auth_plugin = new HTTP_Basic_Auth_Plugin( __FILE__, array() );
	}
	return $basic_auth_plugin;
}

$http_basic_auth_plugin = http_basic_auth_plugin();
if ( !defined( 'HTTP_BASIC_AUTH_TESTS' ) ) {
	$http_basic_auth_plugin->init_dependencies();
	$http_basic_auth_plugin->check_auth();
}
