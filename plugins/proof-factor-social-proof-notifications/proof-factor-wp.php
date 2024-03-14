<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://prooffactor.com
 * @since             1.0.0
 * @package           Proof_Factor_WP
 *
 * @wordpress-plugin
 * Plugin Name:       Proof Factor - Social Proof Notifications
 * Description:       Proof Factor displays recent user sign ups!
 * Version:           1.0.5
 * Author:            Proof Factor LLC
 * Author URI:        https://prooffactor.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       proof-factor-wp
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
define('PROOF_FACTOR_WP_PLUGIN_VERSION', '1.0.5');

require plugin_dir_path(__FILE__) . 'includes/class-proof-factor-wp-helper.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-proof-factor-wp-activator.php
 */
function activate_proof_factor_wp()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-proof-factor-wp-activator.php';
    Proof_Factor_WP_Activator::activate();
}

function uninstall_proof_factor_wp()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-proof-factor-wp-helper.php';
    Proof_Factor_WP_Helper::uninstall();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-proof-factor-wp-deactivator.php
 */
function deactivate_proof_factor_wp()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-proof-factor-wp-deactivator.php';
    Proof_Factor_WP_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_proof_factor_wp');
register_deactivation_hook(__FILE__, 'deactivate_proof_factor_wp');
register_uninstall_hook(__FILE__, 'uninstall_proof_factor_wp');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-proof-factor-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_proof_factor_wp()
{

    $plugin = new Proof_Factor_WP();
    $plugin->run();

}

run_proof_factor_wp();
