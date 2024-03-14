<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://atikul99.github.io/atikul
 * @since             1.0.0
 * @package           Ai_Preloader
 *
 * @wordpress-plugin
 * Plugin Name:       AI Preloader
 * Plugin URI:        https://wordpress.org/plugins/ai-preloader
 * Description:       AI Preloader is a static picture or animated css loaders displayed on-screen while the website is loading in the background.
 * Version:           1.0.2
 * Author:            Atikul Islam
 * Author URI:        https://atikul99.github.io/atikul
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ai-preloader
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AI_PRELOADER_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ai-preloader-activator.php
 */
function activate_ai_preloader() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-preloader-activator.php';
	Ai_Preloader_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ai-preloader-deactivator.php
 */
function deactivate_ai_preloader() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ai-preloader-deactivator.php';
	Ai_Preloader_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ai_preloader' );
register_deactivation_hook( __FILE__, 'deactivate_ai_preloader' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ai-preloader.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ai_preloader() {

	$plugin = new Ai_Preloader();
	$plugin->run();

}
run_ai_preloader();

// Settings Link

function ai_admin_settings_link( $actions, $plugin_file ){

    static $plugin;

    if ( !isset($plugin) ){
        $plugin = plugin_basename(__FILE__);
    }

    if ($plugin == $plugin_file) {

        if ( is_ssl() ) {
            $settings_link = '<a href="'.admin_url( 'admin.php?page=ai-menu-page', 'https' ).'">Settings</a>';
        }else{
            $settings_link = '<a href="'.admin_url( 'admin.php?page=ai-menu-page', 'http' ).'">Settings</a>';
        }
        
        $settings = array($settings_link);
        
        $actions = array_merge($settings, $actions);

    }
    
    return $actions;

}
add_filter( 'plugin_action_links', 'ai_admin_settings_link', 10, 5 );
