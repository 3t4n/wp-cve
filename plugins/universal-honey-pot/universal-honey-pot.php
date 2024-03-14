<?php

/**
 *
 * @link              https://webdeclic.com
 * @since             1.0.0
 * @package           Universal_Honey_Pot
 *
 * @wordpress-plugin
 * Plugin Name:       Universal Honey Pot
 * Plugin URI:        https://webdeclic.com/
 * Description:       Universal Honey Pot is a powerful and user-friendly WordPress plugin that provides a plug-and-play solution for protecting your forms against unwanted spam. With Universal Honey Pot, there's no need for any configuration.
 * Version:           5.0.1
 * Author:            Webdeclic
 * Author URI:        https://webdeclic.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       universal-honey-pot
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check if your are in local or production environment
 */
$is_local = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1';

/**
 * If you are in local environment, you can use the version number as a timestamp for better cache management in your browser
 */
$version  = $is_local ? time() : '5.0.1';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'UNIVERSAL_HONEY_POT_VERSION', $version );

/**
 * You can use this const for check if you are in local environment
 */
define( 'UNIVERSAL_HONEY_POT_DEV_MOD', $is_local );

/**
 * Plugin Name text domain for internationalization.
 */
define( 'UNIVERSAL_HONEY_POT_TEXT_DOMAIN', 'universal-honey-pot' );

/**
 * Plugin Name Path for plugin includes.
 */
define( 'UNIVERSAL_HONEY_POT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin Name URL for plugin sources (css, js, images etc...).
 */
define( 'UNIVERSAL_HONEY_POT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-universal-honey-pot-activator.php
 */
function activate_universal_honey_pot() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-universal-honey-pot-activator.php';
	Universal_Honey_Pot_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-universal-honey-pot-deactivator.php
 */
function deactivate_universal_honey_pot() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-universal-honey-pot-deactivator.php';
	Universal_Honey_Pot_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_universal_honey_pot' );
register_deactivation_hook( __FILE__, 'deactivate_universal_honey_pot' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-universal-honey-pot.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_universal_honey_pot() {

	$plugin = new Universal_Honey_Pot();
	$plugin->run();

}
run_universal_honey_pot();
