<?php

/**
 * Plugin Name:       WP Disabler
 * Plugin URI:        http://themesocket.com/wpdisabler
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.2.1
 * Author:            Themesocket
 * Author URI:        http://themesocket.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-disbler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'WP_DISABLER_VERSION', '1.2.1' );
define( 'PLUGIN_BASENAME', plugin_basename(__FILE__));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_wp_disabler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-disabler-activator.php';
	Wp_Disabler_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_wp_disabler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-disabler-deactivator.php';
	Wp_Disabler_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_disabler' );
register_deactivation_hook( __FILE__, 'deactivate_wp_disabler' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-disabler.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_disabler() {

	$plugin = new Wp_Disabler();
	$plugin->run();

}
run_wp_disabler();
