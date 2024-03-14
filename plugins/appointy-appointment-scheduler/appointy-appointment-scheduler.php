<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Appointy.com
 * @since             4.2.1
 * @package           Appointy_appointment_scheduler
 *
 * @wordpress-plugin
 * Plugin Name:       Appointy - Appointment Scheduler
 * Plugin URI:        https://www.appointy.com/wordpress/
 * Description:       This plugin shows your free time slot on your blog and allows you to book appointments with your clients 24x7x365. Very easy Ajax interface. Easy to setup and can be controlled completely from powerful admin area.
 * Version:           4.2.1
 * Author:            Appointy
 * Author URI:        https://www.appointy.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       appointy_appointment_scheduler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('APPOINTY_APPOINTMENT_SCHEDULER_VERSION', '4.2.1');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-appointy-appointment-scheduler.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    3.0.1
 */
function run_appointy_appointment_scheduler()
{

    $plugin = new Appointy_appointment_scheduler();
    $plugin->run();

}

run_appointy_appointment_scheduler();