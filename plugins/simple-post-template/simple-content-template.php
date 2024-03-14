<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://objectiv.co
 * @since             1.0.0
 * @package           Simple_Content_Templates
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Content Templates for Blog Posts & Pages
 * Plugin URI:        https://www.advancedcontenttemplates.com/
 * Description:       A simple to use content template system. Create similarly structured posts & pages with ease.
 * Version:           2.2.5
 * Author:            Clifton Griffin
 * Author URI:        https://objectiv.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-content-templates
 * Domain Path:       /languages
 * Tested up to:      6.3.1
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

define('CGD_SCT_VERSION', '2.2.5');
define('CGD_SCT_NAME', 'Simple Content Templates');

/**
 * Composer Include
 */
require dirname(__FILE__) . '/vendor/autoload.php';

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-advanced-content-templates.php';

$Simple_Content_Templates = new Simple_Content_Templates();
$Simple_Content_Templates->run();

register_activation_hook(__FILE__, array( $Simple_Content_Templates, 'activate' ));
register_deactivation_hook(__FILE__, array( $Simple_Content_Templates, 'deactivate' ));
