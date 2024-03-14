<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              www.mydirtyhobby.com/registrationplugin
 * @since             1.0.0
 * @package           Mdh_Promote
 *
 * @wordpress-plugin
 * Plugin Name:       Mydirtyhobby Affiliate Sign up
 * Plugin URI:        www.mydirtyhobby.com/registrationplugin
 * Description:       When users click on register button they will be directed to mydirtyhobby registration form and from that point on they will be linked to your affiliate account.
 * Version:           1.0.0
 * Author:            MindGeek
 * Requires PHP:      5.6
 * Author URI:        www.mindgeek.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       www.mydirtyhobby.com
 * Domain Path:       /registrationplugin
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
define( 'MDH_PROMOTE_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mdh-promote-activator.php
 */
function activate_mdh_promote() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mdh-promote-activator.php';
	Mdh_Promote_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mdh-promote-deactivator.php
 */
function deactivate_mdh_promote() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mdh-promote-deactivator.php';
	Mdh_Promote_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mdh_promote' );
register_deactivation_hook( __FILE__, 'deactivate_mdh_promote' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mdh-promote.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mdh_promote() {

	$plugin = new Mdh_Promote();
	$plugin->run();

}
run_mdh_promote();
