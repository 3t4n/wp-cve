<?php
/**
 * Plugin Name: HelloWoofy.com
 * Plugin URI: https://hellowoofy.com/hellowoofy-wordpress-plugin/
 * Description: Create marketing content automatically using data science.
 * Version: 1.1.5
 * Author: HelloWoofy.com
 * Author URI: https://hellowoofy.com
 * Text Domain: hellowoofy-com
 * Domain Path: /i18n/languages/
 * License: GPL v3
 *
 * @package HelloWoofy.com

HelloWoofy.com for WordPress
Copyright (C) 2020, HelloWoofy.com

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

/**
 * This is the main class of Hellowoofy Webstories.
 *
 * @package Max_web_story
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( ! defined( 'MWS_PLUGIN_VERSION' ) ) {
	define( 'MWS_PLUGIN_VERSION', '1.1.4' );
}

add_action( 'plugins_loaded', 'mws_load_main' );
/** Create the admin menu */
function mws_load_main() {
	require_once plugin_dir_path( __FILE__ ) . '/class-hellowoofy-com.php';
	if ( class_exists( 'Hellowoofy_Com' ) ) {
		new Hellowoofy_Com();
	}
}





































