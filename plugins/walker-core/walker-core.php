<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://walkerwp.com/
 * @since             1.0.0
 * @package           Walker_Core
 *
 * @wordpress-plugin
 * Plugin Name: Walker Core
 * Plugin URI:        https://walkerwp.com/walker-core/
 * Description:       Walker Core is the companion plugin for WalkerWP Themes, which provides core functionality and custom post type for the themes. 
 * Version:           1.3.10
 * Author:            WalkerWp
 * Author URI:        https://walkerwp.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       walker-core
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!function_exists('wc_fs')) {
    // Create a helper function for easy SDK access.
    function wc_fs()
    {
        global  $wc_fs;

        if (!isset($wc_fs)) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';
            $wc_fs = fs_dynamic_init(array(
                'id'             => '8355',
                'slug'           => 'walker-core',
                'premium_slug'   => 'walker-core-premium',
                'type'           => 'plugin',
                'public_key'     => 'pk_4775e58ec84b129f9f0cbbd937044',
                'is_premium'     => true,
                'premium_suffix' => 'Premium',
                'has_premium_version' => true,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                    'slug' => 'walker-core',
                ),
                'is_live'        => true,
            ));
        }

        return $wc_fs;
    }

    // Init Freemius.
    wc_fs();
    // Signal that SDK was initiated.
    do_action('wc_fs_loaded');
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WALKER_CORE_VERSION', '1.3.10');
define('WALKER_CORE_PATH', plugin_dir_path(__FILE__));
define('WALKER_CORE_URL', plugin_dir_url(__FILE__));
define('WALKER_CORE_SETUP_TEMPLATE_URL', WALKER_CORE_URL . 'includes/demo-data/');
define('WALKER_CORE_SETUP_SCRIPT_PREFIX', (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : ''));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-walker-core-activator.php
 */
function activate_walker_core()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-walker-core-activator.php';
    Walker_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-walker-core-deactivator.php
 */
function deactivate_walker_core()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-walker-core-deactivator.php';
    Walker_Core_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_walker_core');
register_deactivation_hook(__FILE__, 'deactivate_walker_core');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-walker-core.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_walker_core()
{
    $plugin = new Walker_Core();
    $plugin->run();
}

run_walker_core();
