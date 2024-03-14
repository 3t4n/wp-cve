<?php

require_once(plugin_dir_path(__FILE__) . 'lib/autoload.php');

use PargoWp\Includes\Pargo;
use PargoWp\Includes\Pargo_Activator;
use PargoWp\Includes\Pargo_Deactivator;

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              pargo.co.za
 * @since             1.0.0
 * @package           Pargo
 *
 * @wordpress-plugin
 * Plugin Name:       Pargo Shipping
 * Plugin URI:        pargo.co.za
 * Description:       Pargo is a convenient logistics solution that lets you collect and return parcels at Pargo parcel points throughout the country when it suits you best.
 * Version:           3.4.5
 * Author:            Pargo
 * Author URI:        pargo.co.za
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       pargo
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

define('PARGO_VERSION', '3.4.5');
define('PARGO_PLUGIN_PATH', plugin_dir_url(__FILE__));
/**
 * The code that runs during plugin activation.
 * This action is documented in Includes/Pargo_Activator.php
 */
function activate_pargo()
{
    Pargo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in Includes/Pargo_Deactivator.php
 */
function deactivate_pargo()
{
    Pargo_Deactivator::deactivate();
}

/**
 * The code that runs during plugin uninstallation.
 * This action is documented in Includes/Pargo_Deactivator.php
 */
function uninstall_pargo()
{
    Pargo_Deactivator::uninstall();
}



register_activation_hook(__FILE__, 'activate_pargo');
register_deactivation_hook(__FILE__, 'deactivate_pargo');
register_uninstall_hook(__FILE__, 'uninstall_pargo');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pargo()
{
    $plugin = new Pargo();
    $plugin->run();
}

run_pargo();
