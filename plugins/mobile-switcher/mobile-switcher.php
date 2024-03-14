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
 * @package           Mobile Switcher
 *
 * @wordpress-plugin
 * Plugin Name:       Mobile Switcher
 * Description:       This plugin allow to set themes for mobile phones and tablets
 * Version:           1.0.0
 * Author:            Timur Khamitov
 * Author URI:        https://vk.com/timur.khamitov
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mobile-switcher
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

//Define main plugin path
define( 'MOBILE_SWITCHER_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mobile-switcher-activator.php
 */
function activate_mobile_switcher()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-mobile-switcher-activator.php';
    Mobile_Switcher_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mobile-switcher-deactivator.php
 */
function deactivate_mobile_switcher()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-mobile-switcher-deactivator.php';
    Mobile_Switcher_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mobile_switcher' );
register_deactivation_hook( __FILE__, 'deactivate_mobile_switcher' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mobile-switcher.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mobile_switcher()
{

    $plugin = new Mobile_Switcher();
    $plugin->run();
}

run_mobile_switcher();
