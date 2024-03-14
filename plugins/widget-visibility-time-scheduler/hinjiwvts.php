<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wordpress.org/plugins/widget-visibility-time-scheduler
 * @since             1.0.0
 * @package           Hinjiwvts
 *
 * @wordpress-plugin
 * Plugin Name:       Widget Visibility Time Scheduler
 * Plugin URI:        http://wordpress.org/plugins/widget-visibility-time-scheduler
 * Description:       Control the visibility of each widget based on date, time and weekday easily.
 * Version:           5.3.13
 * Requires at least: 3.5
 * Requires PHP:      5.2
 * Author:            Kybernetik Services
 * Author URI:        https://www.kybernetik-services.com/?utm_source=wordpress_org&utm_medium=plugin&utm_campaign=widget-visibility-time-scheduler&utm_content=author
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hinjiwvts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'WVTS_ROOT', plugin_dir_path( __FILE__ ) );
define( 'WVTS_URL', plugin_dir_url( __FILE__ ) );

function wvts_autoloader( $class_name ) {
    if ( false !== strpos( $class_name, 'Hinjiwvts' ) ) {
        include WVTS_ROOT . 'includes/class-' . $class_name . '.php';
    }
}
spl_autoload_register('wvts_autoloader');

if( is_admin() ) {

    register_activation_hook( __FILE__, 'activate_hinjiwvts' );
    register_deactivation_hook( __FILE__, 'deactivate_hinjiwvts' );

    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-hinjiwvts-activator.php
     */
    function activate_hinjiwvts() {
        Hinjiwvts_Activator::activate();
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-hinjiwvts-deactivator.php
     */
    function deactivate_hinjiwvts() {
        Hinjiwvts_Deactivator::deactivate();
    }

}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hinjiwvts() {

    /**
     * The core plugin class that is used to define internationalization,
     * dashboard-specific hooks, and public-facing site hooks.
     */

    $plugin = new Hinjiwvts();
	$plugin->run();

}
run_hinjiwvts();
