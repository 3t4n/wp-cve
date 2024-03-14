<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    http://www.grafreak.net
 * @since   1.0.0
 * @package Pago_Redsys_Grafreak
 *
 * @wordpress-plugin
 * Plugin Name:       Pago por Redsys
 * Plugin URI:        http://www.grafreak.net
 * Description:       Plugin que permite colocar una pasarela de pago en tu web
 * Version:           1.0.9
 * Author:            Grafreak
 * Author URI:        http://www.grafreak.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pago-redsys-grafreak
 * Domain Path:       /languages
 */

if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

// If this file is called directly, abort.
if (! defined('WPINC') ) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pago-redsys-grafreak-activator.php
 */
function activate_pago_redsys_grafreak()
{
    include_once plugin_dir_path(__FILE__) . 'includes/class-pago-redsys-grafreak-activator.php';
    Pago_Redsys_Grafreak_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pago-redsys-grafreak-deactivator.php
 */
function deactivate_pago_redsys_grafreak()
{
    include_once plugin_dir_path(__FILE__) . 'includes/class-pago-redsys-grafreak-deactivator.php';
    Pago_Redsys_Grafreak_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_pago_redsys_grafreak');
register_deactivation_hook(__FILE__, 'deactivate_pago_redsys_grafreak');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-pago-redsys-grafreak.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_pago_redsys_grafreak()
{

    $plugin = new Pago_Redsys_Grafreak();
    $plugin->run();

}
run_pago_redsys_grafreak();
