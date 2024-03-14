<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://etracker.com
 * @since             1.0.0
 * @package           Etracker
 *
 * @wordpress-plugin
 * Plugin Name:       etracker Analytics
 * Plugin URI:        https://wordpress.org/plugins/etracker
 * Description:       Official etracker Analytics plugin for WordPress.
 * Version:           2.6.1
 * Requires PHP:      7.2
 * Requires at least: 5.5
 * Author:            etracker GmbH
 * Author URI:        https://etracker.com/?etcc_cmp=eA%20Plugin&etcc_med=Pluginstore&etcc_grp=wordpress&etcc_ctv=pluginheader
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       etracker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ETRACKER_VERSION', '2.6.1' );

if ( ! defined( 'ETRACKER_FILE' ) ) {
	define( 'ETRACKER_FILE', __FILE__ );
}

if ( ! defined( 'ETRACKER_PATH' ) ) {
	define( 'ETRACKER_PATH', plugin_dir_path( ETRACKER_FILE ) );
}

$etracker_autoload_file = ETRACKER_PATH . 'vendor/autoload.php';

if ( is_readable( $etracker_autoload_file ) ) {
	$etracker_autoloader = require $etracker_autoload_file;
}

use Etracker\Plugin\Activator as Etracker_Activator;
use Etracker\Plugin\Deactivator as Etracker_Deactivator;
use Etracker\Etracker_Main;

/**
 * The code that runs during plugin activation.
 */
function etracker_activate() {
	Etracker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function etracker_deactivate() {
	Etracker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'etracker_activate' );
register_deactivation_hook( __FILE__, 'etracker_deactivate' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function etracker_run() {
	$plugin = new Etracker_Main();
	$plugin->run();
}
etracker_run();
