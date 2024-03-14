<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.buymeacoffee.com
 * @since             1.0.0
 * @package           Buy_Me_A_Coffee
 *
 * @wordpress-plugin
 * Plugin Name:       Buy Me a Coffee - Button and Widget
 * Plugin URI:        https://www.buymeacoffee.com/
 * Description:       Accept donations in a fast and friendly way. Instant payments via Stripe and direct bank transfer using Standard Payouts.
 * Version:           4.0
 * Author:            Buy Me a Coffee
 * Author URI:        https://www.buymeacoffee.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       buy-me-a-coffee
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLUGIN_NAME_VERSION', '4.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-buy-me-a-coffee-activator.php
 */
function activate_buy_me_a_coffee()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-buy-me-a-coffee-activator.php';
    Buy_Me_A_Coffee_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-buy-me-a-coffee-deactivator.php
 */
function deactivate_buy_me_a_coffee()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-buy-me-a-coffee-deactivator.php';
    Buy_Me_A_Coffee_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_buy_me_a_coffee');
register_deactivation_hook(__FILE__, 'deactivate_buy_me_a_coffee');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-buy-me-a-coffee.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_buy_me_a_coffee()
{

    $plugin = new Buy_Me_A_Coffee();
    $plugin->run();
}
run_buy_me_a_coffee();
