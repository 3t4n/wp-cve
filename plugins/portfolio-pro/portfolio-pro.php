<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://logichunt.com
 * @since             1.0.0
 * @package           Portfolio_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       Portfolio by LogicHunt.com
 * Plugin URI:         http://logichunt.com/product/wordpress-portfolio-gallery
 * Description:       Smart Portfolio Gallery for Professionals. Responsive Grid system with Filter and Masonry Based on Isotope.

 * Version:           1.2.2
 * Author:            LogicHunt
 * Author URI:        http://logichunt.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       portfolio-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//plugin definition specific constants
defined( 'LGXPP_PLUGIN_NAME' ) or define( 'LGXPP_PLUGIN_NAME', 'portfoliopro' ); //need to define in uninstall also
defined( 'LGXPP_PLUGIN_VERSION' ) or define( 'LGXPP_PLUGIN_VERSION', '1.2.2' ); //need to define in uninstall also
defined( 'LGXPP_PLUGIN_BASE_NAME' ) or define( 'LGXPP_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
defined( 'LGXPP_PLUGIN_ROOT_PATH' ) or define( 'LGXPP_PLUGIN_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'LGXPP_PLUGIN_ROOT_URL' ) or define( 'LGXPP_PLUGIN_ROOT_URL', plugin_dir_url( __FILE__ ) );





/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-portfolio-pro-activator.php
 */
function activate_portfolio_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-portfolio-pro-activator.php';
	Portfolio_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-portfolio-pro-deactivator.php
 */
function deactivate_portfolio_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-portfolio-pro-deactivator.php';
	Portfolio_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_portfolio_pro' );
register_deactivation_hook( __FILE__, 'deactivate_portfolio_pro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-portfolio-pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_portfolio_pro() {

	$plugin = new Portfolio_Pro();
	$plugin->run();

}
run_portfolio_pro();
