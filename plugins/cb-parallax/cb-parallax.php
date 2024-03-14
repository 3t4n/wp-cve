<?php
namespace CbParallax;

use CbParallax\Includes;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The plugin bootstrap file
 *
 * This is a plugin for custom backgrounds on single posts, products and pages, with a parallax effect.
 * The parallax effect requires an image with a width of at least 1920px and a height of at least 1200px for vertical parallax effect
 * Have Fun!
 * in memoriam of Bender ( 1999 to 2013 )
 * Built with Tom McFarlin's WordPress Plugin Boilerplate in mind -
 * which is now maintained by Devin Vinson.
 * https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 *
 * @wordpress-plugin
 * @package:          cbParallax
 * @since             0.1.0
 * @package           cb_parallax
 * @author            Demis Patti
 * @link:             https://wordpress.org/plugins/cb-parallax/
 * Plugin URI:        https://wordpress.org/plugins/cb-parallax/
 * Plugin Name:       cbParallax
 * Description:       Lets you add <a href="http://codex.wordpress.org/Custom_Backgrounds" target="_blank">custom background</a> - with or without vertical or horizontal parallax effect - for single posts, pages and products. It requires your theme to support the WordPress <code>custom-background</code> feature. It also requires you to set your theme's layout to "boxed" and / or to add a transparency to the container that holds the content in order to make the background image visible / shine trough.
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cb-parallax
 * Domain Path:       /languages
 * Version:           0.9.7
 * Stable tag:        0.9.7
 * Requires at least: 5.5
 * Tested up to:      6.1.1
 * Requires PHP:      5.6
 * Max. PHP version:  7.4.21
 */

/**
 * Define the constants for the plugin paths.
 */
if ( ! defined( 'CBPARALLAX_ROOT_DIR' ) ) {
	define( 'CBPARALLAX_ROOT_DIR',  plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CBPARALLAX_ROOT_URL' ) ) {
	define( 'CBPARALLAX_ROOT_URL',  plugin_dir_url( __FILE__ ) );
}

/**
 * Require the initial classes.
 */
if ( ! class_exists( 'Includes\cb_parallax_activator' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'includes/class-activator.php';
}
if ( ! class_exists( 'Includes\cb_parallax_deactivator' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'includes/class-deactivator.php';
}
if ( ! class_exists( 'CbParallax\cb_parallax' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'includes/class-cb-parallax.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cb-parallax-activator.php
 *
 * @since    0.1.0
 * @return   void
 */
function activate_plugin() {
	
	Includes\cb_parallax_activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cb-parallax-deactivator.php
 *
 * @since    0.1.0
 * @return   void
 */
function deactivate_plugin() {
	
	Includes\cb_parallax_deactivator::deactivate();
}

/**
 * Register the activation and deactivation functionality of the plugin.
 *
 * @since    0.1.0
 */
register_activation_hook( __FILE__, 'CbParallax\activate_plugin' );
register_deactivation_hook( __FILE__, 'CbParallax\deactivate_plugin' );

/**
 * Runs the plugin.
 *
 * @since    0.1.0
 * @return   void
 */
function run_cb_parallax() {
	
	$plugin = new Includes\cb_parallax();
	$plugin->run();
}

add_action( 'plugins_loaded', 'CbParallax\run_cb_parallax' );
