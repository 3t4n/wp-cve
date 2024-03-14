<?php
/**
 * @link              http://imdpen.com
 * @since             1.0.0
 * @package           Inactive Logout
 *
 * Plugin Name:       Inactive Logout
 * Plugin URI:        https://inactive-logout.com/
 * Description:       Automatically logout idle user sessions, even if they are on the front end! Fully configurable & easy to use.
 * Version:           3.2.5
 * Author:            Deepen Bajracharya
 * Author URI:        https://inactive-logout.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       inactive-logout
 * Domain Path:       /lang
 * Requires at least: 5.8
 * Requires PHP:      7.1
 **/

// Not Permission to agree more or less then given.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

defined( 'INACTIVE_LOGOUT_ABS_NAME' ) || define( 'INACTIVE_LOGOUT_ABS_NAME', plugin_basename( __FILE__ ) );
defined( 'INACTIVE_LOGOUT_REQUIRED_PHP' ) || define( 'INACTIVE_LOGOUT_REQUIRED_PHP', '7.1' );
defined( 'INACTIVE_LOGOUT_SLUG' ) || define( 'INACTIVE_LOGOUT_SLUG', 'inactive-logout' );
defined( 'INACTIVE_LOGOUT_DIR_PATH' ) || define( 'INACTIVE_LOGOUT_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
defined( 'INACTIVE_LOGOUT_DIR_URI' ) || define( 'INACTIVE_LOGOUT_DIR_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
defined( 'INACTIVE_LOGOUT_BUILD_URI' ) || define( 'INACTIVE_LOGOUT_BUILD_URI', INACTIVE_LOGOUT_DIR_URI . 'build' );
defined( 'INACTIVE_LOGOUT_VIEWS' ) || define( 'INACTIVE_LOGOUT_VIEWS', INACTIVE_LOGOUT_DIR_PATH . 'views' );
defined( 'INACTIVE_LOGOUT_VERSION' ) || define( 'INACTIVE_LOGOUT_VERSION', '3.2.5' );

//Legacy support
require_once dirname( __FILE__ ) . '/legacy/class-inactive-logout-helpers.php';

//require autoload
if ( file_exists( INACTIVE_LOGOUT_DIR_PATH . 'vendor/autoload.php' ) ) {
	require INACTIVE_LOGOUT_DIR_PATH . 'vendor/autoload.php';
}

require INACTIVE_LOGOUT_DIR_PATH . 'core/Bootstrap.php';

register_activation_hook( __FILE__, 'Codemanas\InactiveLogout\Bootstrap::activate' );
register_deactivation_hook( __FILE__, 'Codemanas\InactiveLogout\Bootstrap::deactivate' );
