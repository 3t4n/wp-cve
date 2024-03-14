<?php

/**
 *
 * @link              http://www.twomandarins.com
 * @since             1.0.0
 * @package           Wp_Revisions_Limit
 *
 * @wordpress-plugin
 * Plugin Name:       WP Revisions Limit
 * Plugin URI:        http://www.twomandarins.com
 * Description:       Limit number of revisions stored.
 * Version:           1.3
 * Author:            Roger Rodrigo (TwoMandarins)
 * Author URI:        http://www.twomandarins.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-revisions-limit
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-revisions-limit-activator.php
 */
function activate_wp_revisions_limit() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-revisions-limit-activator.php';
	Wp_Revisions_Limit_Activator::activate();
	
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-revisions-limit-deactivator.php
 */
function deactivate_wp_revisions_limit() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-revisions-limit-deactivator.php';
	Wp_Revisions_Limit_Deactivator::deactivate();

}

register_activation_hook( __FILE__, 'activate_wp_revisions_limit' );
register_deactivation_hook( __FILE__, 'deactivate_wp_revisions_limit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-revisions-limit.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_revisions_limit() {

	$plugin = new Wp_Revisions_Limit();
	$plugin->run();

}
run_wp_revisions_limit();
