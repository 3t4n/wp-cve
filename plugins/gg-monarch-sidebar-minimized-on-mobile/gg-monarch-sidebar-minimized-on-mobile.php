<?php

/*
*
* @link              https://deeppresentation.com/
* @since             1.0.0
* @package           Monarch_Sidebar_Minimized_on_Mobile
*
* @wordpress-plugin
* Plugin Name:       Monarch Sidebar Minimized on Mobile
* Plugin URI:        https://deeppresentation.com/plugins
* Description:       Plugin adjusts behavior of monarch plugin (elegantthemes) for mobile phones. It sets initial state of social share sidebar as nice minimized button (instead of annoying sticky bottom bar with text Share This).
* Version:           1.2.5
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Tomáš Groulík <deeppresentation>
* Author URI:        https://deeppresentation.com/
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       gg-monarch-sidebar-minimized-on-mobile
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$pluginDirPath = plugin_dir_path( __FILE__ );
require_once $pluginDirPath . 'vendor/autoload.php';
require_once $pluginDirPath . '/dp-build-type.php';
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gg-monarch-sidebar-minimized-on-mobile-activator.php
 */
function activate_gg_monarch_sidebar_minimized_on_mobile() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gg-monarch-sidebar-minimized-on-mobile-activator.php';
	GG_Monarch_Sidebar_Minimized_On_Mobile_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gg-monarch-sidebar-minimized-on-mobile-deactivator.php
 */
function deactivate_gg_monarch_sidebar_minimized_on_mobile() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gg-monarch-sidebar-minimized-on-mobile-deactivator.php';
	GG_Monarch_Sidebar_Minimized_On_Mobile_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gg_monarch_sidebar_minimized_on_mobile' );
register_deactivation_hook( __FILE__, 'deactivate_gg_monarch_sidebar_minimized_on_mobile' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gg-monarch-sidebar-minimized-on-mobile.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_gg_monarch_sidebar_minimized_on_mobile() {

    if( is_plugin_active('monarch/monarch.php')){
        // wpackio
        $enquier = new \MSMoMWPackio\Enqueue(
            // Name of the project, same as `appName` in wpackio.project.js
            'ggMonarchSidebarMinimizedOnMobile',
            // Output directory, same as `outputPath` in wpackio.project.js
            'dist',
            // Version of your plugin
            GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_VERSION,
            // Type of your project, same as `type` in wpackio.project.js
            'plugin',
            // Plugin location, pass false in case of theme.
            __FILE__
        );
	    $plugin = new GG_Monarch_Sidebar_Minimized_On_Mobile($enquier);
        $plugin->run();
    }

}
run_gg_monarch_sidebar_minimized_on_mobile();
