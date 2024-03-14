<?php
/**
 * Plugin Name:       Cab fare calculator
 * Plugin URI:        https://kanev.com/products/taxi-booking-for-wordpress
 * Description:       Taxi Booking for WordPress.
 * Version:           1.1.6
 * Author:            Marian Kanev
 * Author URI:        https://kanev.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TBLIGHT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'TBLIGHT_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

define( 'TBLIGHT_FILE', __FILE__ );
define( 'TBLIGHT_PATH', __DIR__ );
define( 'TBLIGHT_URL', plugins_url( '', TBLIGHT_FILE ) );
define( 'TBLIGHT_ASSETS', TBLIGHT_URL . '/assets' );
define( 'TBLIGHT_ASSETS_PATH', TBLIGHT_PATH . '/assets' );

$tblight_db_version = 1; // it will be increased if any change in DB
define( 'TBLIGHT_DB_VERSION', $tblight_db_version );

$tblight_plugin_version = '1.1.6';
define( 'TBLIGHT_PLUGIN_VERSION', $tblight_plugin_version );

register_activation_hook( __FILE__, 'tblight_activate' );

require_once TBLIGHT_PLUGIN_PATH . 'functions.php';

add_action( 'plugins_loaded', 'init_tblight' );

/**
 * Do stuff on plugin activation
 *
 * @return void
 */
function tblight_activate() {
	require_once TBLIGHT_PLUGIN_PATH . 'Installer.php';

	$installer = new TBLight_Installer();
	$installer->run();
}
