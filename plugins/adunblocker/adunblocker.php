<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://digitalapps.com
 * @since             1.0.0
 * @package           AdUnblocker
 *
 * @wordpress-plugin
 * Plugin Name:       AdUnblocker
 * Plugin URI:        https://digitalapps.com/adunblocker/
 * Description:       This plugin detects if Google AdSense ads are blocked by ad-blocker. A popup shows with a text requesting user to whitelist your website.
 * Version:           1.1.6
 * Author:            Digital Apps
 * Author URI:        https://digitalapps.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       adunblocker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'DAAU_PLUGIN_VERSION', '1.1.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-adunblocker-activator.php
 */
function activate_adunblocker() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-adunblocker-activator.php';
    AdUnblocker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-adunblocker-deactivator.php
 */
function deactivate_adunblocker() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-adunblocker-deactivator.php';
    AdUnblocker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_adunblocker' );
register_deactivation_hook( __FILE__, 'deactivate_adunblocker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-adunblocker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_adunblocker() {

    $plugin = new AdUnblocker();
    $plugin->run();

}
run_adunblocker();