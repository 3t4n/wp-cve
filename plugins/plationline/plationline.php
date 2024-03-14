<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://plati.online
 * @since             6.0.0
 * @package           PlatiOnlinePO6
 *
 * @wordpress-plugin
 * Plugin Name:         PlatiOnline Payments
 * Plugin URI:          https://plati.online
 * Description:         Online payment by card and Login with Plati.Online account
 * Version:             6.3.2
 * Author:              PlatiOnline
 * Author URI:          https://plati.online
 * License:             GPL-3.0
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:         plationline
 * Domain Path:         /languages
 * WC requires at least: 3.0.4
 */

namespace PlatiOnlinePO6;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die();
}

/**
 * Define Constants
 */

define(__NAMESPACE__ . '\NS', __NAMESPACE__ . '\\');
define(NS . 'PLUGIN_NAME', 'plationline');
define(NS . 'PLUGIN_VERSION', '6.3.2');
define(NS . 'PLUGIN_NAME_DIR', plugin_dir_path(__FILE__));
define(NS . 'PLUGIN_NAME_URL', plugin_dir_url(__FILE__));
define(NS . 'PLUGIN_BASENAME', plugin_basename(__FILE__));
define(NS . 'PLUGIN_TEXT_DOMAIN', 'plationline');

/**
 * Autoload Classes
 */

require_once(PLUGIN_NAME_DIR . 'inc/libraries/autoloader.php');

/**
 * Register Activation and Deactivation Hooks
 * This action is documented in inc/core/class-activator.php
 */
register_activation_hook(__FILE__, array(NS . 'Inc\Core\Activator', 'activate'));

/**
 * The code that runs during plugin deactivation.
 * This action is documented inc/core/class-deactivator.php
 */
register_deactivation_hook(__FILE__, array(NS . 'Inc\Core\Deactivator', 'deactivate'));

/**
 * Plugin Singleton Container
 *
 * Maintains a single copy of the plugin app object
 *
 * @since    1.0.0
 */
class PlatiOnlinePO6
{

    /**
     * The instance of the plugin.
     *
     * @since    1.0.0
     * @var      Init $init Instance of the plugin.
     */
    private static $init;

    /**
     * Loads the plugin
     *
     * @access    public
     */
    public static function init()
    {
        if (null === self::$init) {
            self::$init = new Inc\Core\Init();
            self::$init->run();
        }

        return self::$init;
    }
}

return PlatiOnlinePO6::init();
