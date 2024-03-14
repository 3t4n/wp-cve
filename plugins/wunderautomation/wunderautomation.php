<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           WunderAutomation
 *
 * @wordpress-plugin
 * Plugin Name:       WunderAutomation
 * Plugin URI:        https://www.wundermatics.com/wunderautomation/
 * Description:       A wunderful automation tool for WordPress and WooCommerce
 * Version:           1.9.0
 * Author:            Wundermatics
 * Author URI:        https://wundermatics.com/about
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wunderauto
 * Domain Path:       /languages
 */

use WunderAuto\Activator;
use WunderAuto\Pimple\Container;

// If this file is called directly, abort.
// phpcs:disable
if (!defined('WPINC')) {
    die; // phpcs:ignore
}

$wunderautomation_version = '1.9.0';
$wunderautomation_db_version = '1.6.0';
$wunderautomation_is_pro     = false;
// phpcs:enable

if (!defined('WUNDERAUTO_BASE')) {
    define('WUNDERAUTO_BASE', __DIR__);
}
if (!defined('WUNDERAUTO_FILE')) {
    define('WUNDERAUTO_FILE', __FILE__);
}
if (!defined('WUNDERAUTO_URLBASE')) {
    define('WUNDERAUTO_URLBASE', plugin_dir_url(__FILE__));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-marketautowoo-activator.php
 *
 * @return void
 */
function activate_wunderautomation()
{
    require_once plugin_dir_path(__FILE__) . 'src/Activator.php';
    Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 *
 * @return void
 */
function deactivate_wunderautomation()
{
    require_once plugin_dir_path(__FILE__) . 'src/Activator.php';
    Activator::deactivate();
}

// phpcs:disable
if (isset($plugin)) {
    register_activation_hook($plugin, 'activate_wunderautomation');
    register_deactivation_hook($plugin, 'deactivate_wunderautomation');
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'vendor/autoload.php';
require_once(__DIR__ . '/src/functions.php');
require_once(__DIR__ . '/src/action-scheduler/action-scheduler.php');

/**
 * Initialize global variables
 */
wa_initialize_globals();

/**
 * Define the one global instance of the WunderAuto class & begins execution of the plugin
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
$app = new Container();
$app->register(
    new \WunderAuto\Provider(
        'wunderautomation',
        $wunderautomation_version,
        $wunderautomation_db_version,
        $wunderautomation_is_pro
    )
);

global $wunderAuto;
$wunderAuto = $app['wunderauto'];
// phpcs:enable
