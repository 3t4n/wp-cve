<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://boomdevs.com
 * @since             1.0.0
 * @package           Boomdevs_Swiss_Toolkit
 *
 * @wordpress-plugin
 * Plugin Name:       Swiss Toolkit For WP
 * Plugin URI:        https://boomdevs.com
 * Description:       Say Goodbye to Plugin Overload - WP Swiss Toolkit Has It All
 * Version:           1.0.4
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            BoomDevs
 * Author URI:        https://boomdevs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       swiss-toolkit-for-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    exit;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('BDSTFW_SWISS_TOOLKIT_VERSION', '1.0.4');
define('BDSTFW_SWISS_TOOLKIT_PATH', plugin_dir_path(__FILE__));
define('BDSTFW_SWISS_TOOLKIT_URL', plugin_dir_url(__FILE__));
define('BDSTFW_SWISS_TOOLKIT_NAME', 'swiss-toolkit-for-wp');
define('BDSTFW_SWISS_TOOLKIT_FULL_NAME', 'Swiss Toolkit');
define('BDSTFW_SWISS_TOOLKIT_BASE_NAME', plugin_basename(__FILE__));

require __DIR__ . '/vendor/autoload.php';

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
if (!function_exists('BDSTFW_appsero_init_tracker_swiss_toolkit_for_wp')) {
    function BDSTFW_appsero_init_tracker_swiss_toolkit_for_wp()
    {

        if ( ! class_exists( 'Appsero\Client' ) ) {
            require_once __DIR__ . '/appsero/src/Client.php';
        }

        $client = new Appsero\Client( '378537ce-0d4c-4848-a50b-08a9e0c02c8a', 'Swiss Toolkit For WP', __FILE__ );

        // Active insights
        $client->insights()->init();
    }

    BDSTFW_appsero_init_tracker_swiss_toolkit_for_wp();
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-boomdevs-swiss-toolkit-activator.php
 */
if (!function_exists('BDSTFW_Swiss_Toolkit_activate')) {
    function BDSTFW_Swiss_Toolkit_activate()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/class-boomdevs-swiss-toolkit-activator.php';
        BDSTFW_Swiss_Toolkit_Activator::activate();
    }

    register_activation_hook(__FILE__, 'BDSTFW_Swiss_Toolkit_activate');
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-boomdevs-swiss-toolkit-deactivator.php
 */
if (!function_exists('BDSTFW_Swiss_Toolkit_deactivate')) {
    function BDSTFW_Swiss_Toolkit_deactivate()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/class-boomdevs-swiss-toolkit-deactivator.php';
        BDSTFW_Swiss_Toolkit_Deactivator::deactivate();
    }

    register_deactivation_hook(__FILE__, 'BDSTFW_Swiss_Toolkit_deactivate');
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-boomdevs-swiss-toolkit.php';

do_action('wp_swiss_toolkit_pro/loaded');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if (!function_exists('BDSTFW_run_Swiss_Toolkit')) {
    function BDSTFW_run_Swiss_Toolkit()
    {
        $plugin = new BDSTFW_Swiss_Toolkit();
        $plugin->run();
    }

    add_action('plugins_loaded', 'BDSTFW_run_Swiss_Toolkit', 2);
}