<?php

/*
Plugin Name: Wordpress Connect
Plugin URI: http://wp-connect.tomasvorobjov.com
Description: Integrates Facebook Social Plugins with Wordpress
Version: 2.0.3
Author: Tomas Vorobjov
Author URI: htpt://www.tomasvorobjov.com
*/

/*  Copyright 2009-2011  SciBuff - Wordpress Connect

    This file is part of Wordpress Connect Wordpress Plugin.

    Wordpress Connect is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Wordpress Connect is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Wordpress Connect.  If not, see <http://www.gnu.org/licenses/>.

*/

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnect.php' );

register_activation_hook( __FILE__, 'activate_wordpress_connect' );
register_deactivation_hook( __FILE__, 'deactivate_wordpress_connect' );

global $wordpress_connect;

$wordpress_connect = new WordpressConnect();

if ( !function_exists( 'activate_wordpress_connect' ) ){
	/**
	 * This function is executed when this plugin is activated. The
	 * function simply calls the <code>active</code> function of the
	 * <code>WordpressConnect</code>'s object, which takes over the activation
	 * procedures.
	 *
	 * @since	1.0
	 */
	function activate_wordpress_connect(){
		global $wordpress_connect;
		$wordpress_connect->activate();
	}
}

if ( !function_exists( 'deactivate_wordpress_connect' ) ){
	/**
	 * This function is executed when this plugin is activated. The
	 * function simply calls the <code>active</code> function of the
	 * <code>WordpressConnect</code>'s object, which takes over the activation
	 * procedures.
	 *
	 * @since	1.0
	 */
	function deactivate_wordpress_connect(){
		global $wordpress_connect;
		$wordpress_connect->deactivate();
	}
}


// add functions for plugin/theme developers
require_once( WP_PLUGIN_DIR . '/wordpress-connect/wordpress-connect-functions.php' );



?>