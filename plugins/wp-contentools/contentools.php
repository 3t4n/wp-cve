<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package Contentools
 * @link    https://growthhackers.com/workflow
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       WP Contentools
 * Plugin URI:        https://wordpress.org/plugins/wp-contentools/
 * Description:       This plugin enables the integration between the Contentools Platform and Wordpress.
 * Version:           3.1.1
 * Author:            Contentools
 * Author URI:        https://growthhackers.com/workflow
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       contentools
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLUGIN_CONTENTOOLS_VERSION', '3.1.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-contentools-activator.php
 */
function activate_contentools()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-contentools-activator.php';
    Contentools_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-contentools-deactivator.php
 */
function deactivate_contentools()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-contentools-deactivator.php';
    Contentools_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_contentools');
register_deactivation_hook(__FILE__, 'deactivate_contentools');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-contentools.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_contentools()
{

    $plugin = new Contentools();
    $plugin->run();

}
run_contentools();
