<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.mailmunch.com
 * @since             2.0.0
 * @package           Mailmunch
 *
 * @wordpress-plugin
 * Plugin Name:       MailMunch - Grow Your Email List
 * Plugin URI:        http://www.mailmunch.com
 * Description:       The best free plugin to get more email subscribers. Beautiful signup forms and landing pages that integrate with MailChimp, Constant Contact, AWeber, Campaign Monitor and more.
 * Version:           3.1.6
 * Author:            MailMunch
 * Author URI:        http://www.mailmunch.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mailmunch
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mailmunch-activator.php
 */
function activate_mailmunch() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-mailmunch-activator.php';
  Mailmunch_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mailmunch-deactivator.php
 */
function deactivate_mailmunch() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-mailmunch-deactivator.php';
  Mailmunch_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mailmunch' );
register_deactivation_hook( __FILE__, 'deactivate_mailmunch' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mailmunch.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_mailmunch() {

  $plugin = new Mailmunch();
  $plugin->run();

}
run_mailmunch();
