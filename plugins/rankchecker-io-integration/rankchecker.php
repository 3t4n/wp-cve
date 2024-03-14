<?php

/**
 * Plugin Name:       Rankchecker.io Integration
 * Plugin URI:        https://rankchecker.io
 * Description:       Simple integration plugin for Rankchecker.io
 * Version:           1.0.9
 * Author:            Rankchecker.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rankchecker-io-integration
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
define( 'RANKCHECKER_VERSION', '1.0.9' );

define( 'RANKCHECKER_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rankchecker-activator.php
 */
function activate_rankchecker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rankchecker-activator.php';
	Rankchecker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rankchecker-deactivator.php
 */
function deactivate_rankchecker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rankchecker-deactivator.php';
	Rankchecker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rankchecker' );
register_deactivation_hook( __FILE__, 'deactivate_rankchecker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rankchecker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rankchecker() {

	$plugin = new Rankchecker();
	$plugin->run();

}
run_rankchecker();
