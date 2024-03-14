<?php
/*
Plugin Name: Easy Twitter Feed Widget
Plugin URI: https://designorbital.com/easy-twitter-feed-widget/
Description: Add twitter feeds on your WordPress site by using the Easy Twitter Feed Widget plugin.
Author: DesignOrbital.com
Author URI: https://designorbital.com
Text Domain: do-etfw
Domain Path: /languages/
Version: 0.9
License: GPL v3

Easy Twitter Feed Widget Plugin
Copyright (C) 2012-2017, DesignOrbital.com

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
 * Constants
 */
if ( ! defined( 'DO_ETFW_VERSION' ) ) {
	define( 'DO_ETFW_VERSION', '0.8' );
}

if ( ! defined( 'DO_ETFW_BASENAME' ) ) {
	define( 'DO_ETFW_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'DO_ETFW_DIR_BASENAME' ) ) {
	define( 'DO_ETFW_DIR_BASENAME', trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );
}

if ( ! defined( 'DO_ETFW_DIR' ) ) {
	define( 'DO_ETFW_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'DO_ETFW_URI' ) ) {
	define( 'DO_ETFW_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

/**
 * Load plugin textdomain.
 */
function do_etfw_load_textdomain() {
  load_plugin_textdomain( 'do-etfw', false, DO_ETFW_DIR_BASENAME. 'languages/' );
}
add_action( 'plugins_loaded', 'do_etfw_load_textdomain' );

/**
 * Custom functions.
 */
require DO_ETFW_DIR . 'inc/extras.php';

/**
 * Implement the Custom widgets.
 */
require DO_ETFW_DIR . '/inc/widgets.php';

/**
 * Admin Page
 */
if ( is_admin() ) {
	require DO_ETFW_DIR . 'inc/admin.php';
}
