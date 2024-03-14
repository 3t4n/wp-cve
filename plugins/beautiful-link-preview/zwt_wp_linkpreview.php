<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://zeitwesentech.com
 * @since             1.0.0
 * @package           Zwt_wp_linkpreview
 *
 * @wordpress-plugin
 * Plugin Name:       Beautiful Link Preview
 * Plugin URI:        https://zeitwesentech.com/go/wp-beautiful-link-preview
 * Description:       <strong>Beautiful Link Preview BETA</strong> by zeitwesentech | Creates previews of links with title, description and preview images similar to sharing links on social networks.
 * Version:           1.5.0
 * Author:            zeitwesentech
 * Author URI:        https://zeitwesentech.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       zwt_textdomain
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
define('ZWT_WP_LINKPREVIEWER_VERSION', '1.5.0');

require_once plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer-constants.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer-utils.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer-db.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer-urlfetcher.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer-rest-controller.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer-shortcode.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-zwt_wp_linkpreviewer-activator.php
 */
function activate_zwt_wp_linkpreviewer()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer-activator.php';
    Zwt_wp_linkpreviewer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-zwt_wp_linkpreviewer-deactivator.php
 */
function deactivate_zwt_wp_linkpreviewer()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer-deactivator.php';
    Zwt_wp_linkpreviewer_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_zwt_wp_linkpreviewer');
register_deactivation_hook(__FILE__, 'deactivate_zwt_wp_linkpreviewer');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-zwt_wp_linkpreviewer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_zwt_wp_linkpreviewer()
{

    $plugin = new Zwt_wp_linkpreviewer();
    $plugin->run();

}

run_zwt_wp_linkpreviewer();
