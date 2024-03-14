<?php
/*
Plugin Name: 3CX Webinars
Plugin URI: https://www.3cx.com/phone-system/webinar-wordpress/
Description: The 3CX Webinars plugin provides free Webinars functionality to website visitors through 3CX WebMeeting.
Author: 3CX
Author URI: https://www.3cx.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: 3cx-webinar
Domain Path: /languages/
Version: 18.2.4

3CX Webinars Wordpress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
3CX Webinars Wordpress Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with 3CX Webinars Wordpress Plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

define( 'WP3CXW_VERSION', '18.2.4' );

define( 'WP3CXW_REQUIRED_WP_VERSION', '4.8' );

define( 'WP3CXW_PLUGIN', __FILE__ );

define( 'WP3CXW_PLUGIN_BASENAME', plugin_basename( WP3CXW_PLUGIN ) );

define( 'WP3CXW_PLUGIN_NAME', trim( dirname( WP3CXW_PLUGIN_BASENAME ), '/' ) );

define( 'WP3CXW_PLUGIN_DIR', untrailingslashit( dirname( WP3CXW_PLUGIN ) ) );

if ( ! defined( 'WP3CXW_ADMIN_READ_CAPABILITY' ) ) {
	define( 'WP3CXW_ADMIN_READ_CAPABILITY', 'edit_posts' );
}

if ( ! defined( 'WP3CXW_ADMIN_READ_WRITE_CAPABILITY' ) ) {
	define( 'WP3CXW_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );
}

require_once WP3CXW_PLUGIN_DIR . '/settings.php';
