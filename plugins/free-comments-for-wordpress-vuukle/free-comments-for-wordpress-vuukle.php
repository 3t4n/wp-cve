<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @category PHP
 * @package  Free_Comments_For_Wordpress_Vuukle
 * @author   Vuukle <info@vuukle.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link     https://vuukle.com
 * @since    1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Vuukle: Analytics, Comments, Newsletters, Emojis and Sharing Tool
 * Plugin URI:        https://vuukle.com
 * Description:       Vuukle is the smartest commenting platform that offers AI-powered commenting, Unique Sharing tool bar, Emoji reaction widget and real time analytics with just one click. Customize all you want, make your pages load faster and experience user engagement like never before!
 * Version:           5.1.7
 * Author:            Vuukle
 * Author URI:        https://vuukle.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       free-comments-for-wordpress-vuukle
 * Domain Path:       /languages
 * Tested up to:	  6.1.1
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-free-comments-for-wordpress-vuukle-activator.php
 *
 * @return void
 */
function Activate_Free_Comments_For_Wordpress_vuukle() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-free-comments-for-wordpress-vuukle-activator.php';
	Free_Comments_For_Wordpress_Vuukle_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-free-comments-for-wordpress-vuukle-deactivator.php
 *
 * @return void
 */
function Deactivate_Free_Comments_For_Wordpress_vuukle() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-free-comments-for-wordpress-vuukle-deactivator.php';
	Free_Comments_For_Wordpress_Vuukle_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'Activate_Free_Comments_For_Wordpress_vuukle' );
register_deactivation_hook( __FILE__, 'Deactivate_Free_Comments_For_Wordpress_vuukle' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-free-comments-for-wordpress-vuukle.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @return void
 * @since  1.0.0
 */
function Run_Free_Comments_For_Wordpress_vuukle() {
	$plugin = new Free_Comments_For_Wordpress_Vuukle( '5.1.7', plugin_dir_path( __FILE__ ), plugin_dir_url( __FILE__ ), plugin_basename( __FILE__ ) );
	$plugin->run();
}

Run_Free_Comments_For_Wordpress_vuukle();
