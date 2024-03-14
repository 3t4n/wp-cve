<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://iqonic.design/
 * @since             1.7.2.2
 * @package           Marvy_Animation_Addons
 *
 * @wordpress-plugin
 * Plugin Name:       Marvy - Ultimate Elementor Animation addons
 * Plugin URI:        https://iqonicthemes.com
 * Description:       Marvy is the best solution for users who need beautiful animations for creative and professional projects.
 * Version:           1.7.2.2
 * Author:            Iqonic Design
 * Author URI:        https://iqonic.design/
 * Text Domain:       marvy-animation-addons-for-elementor-lite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.7.2 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MARVY_ANIMATION_ADDONS_VERSION', '1.7.2.2' );
define( 'MARVY_ANIMATION_ADDONS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MARVY_ANIMATION_ADDONS_PLUGIN_URL', plugins_url( '/', __FILE__ ) );


// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
} else {
	die( 'Something went wrong' );
}
marvy_check_pro();

$GLOBALS['marvy_config'] = require_once MARVY_ANIMATION_ADDONS_PLUGIN_PATH . 'config.php';
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-marvy-animation-addons-activator.php
 */
function marvy_animation_addons_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-marvy-animation-addons-activator.php';
	Marvy_Animation_Addons_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-marvy-animation-addons-deactivator.php
 */
function marvy_animation_addons_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-marvy-animation-addons-deactivator.php';
	Marvy_Animation_Addons_Deactivator::deactivate();
}

if ( function_exists( 'register_activation_hook' ) ) {
	register_activation_hook( __FILE__, 'marvy_animation_addons_activate' );
}
if ( function_exists( 'register_deactivation_hook' ) ) {
	register_deactivation_hook( __FILE__, 'marvy_animation_addons_deactivate' );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-marvy-animation-addons.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.7.2
 */
function marvy_animation_addons_run() {
	$plugin = new Marvy_Animation_Addons();
	$plugin->run();
}

marvy_animation_addons_run();

/*****
 * @param $links
 *
 * @return mixed
 */
function marvy_plugin_settings_link( $links ) {
	$links[] = sprintf( '<a href="admin.php?page=marvy-animation">' . __( 'Settings', 'marvy-animation-addons-for-elementor-lite' ) . '</a>' );
	$links[] = sprintf( '<a href="https://iqonic.design/docs/product/marvy-documentation/getting-started/"  target="_blank">' . __( 'Docs', 'marvy-animation-addons-for-elementor-lite' ) . '</a>' );
	if ( ! isMarvyProInstall() ) {
		$links[] = sprintf( '<a href="https://codecanyon.net/item/marvy-background-animations-for-elementor/28285063" target="_blank" style="font-weight: bold; color: #93003c;">' . __( 'Get Pro', 'marvy-animation-addons-for-elementor-lite' ) . '</a>' );
	}

	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'marvy_plugin_settings_link' );
