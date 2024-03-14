<?php

/**
 * Equal Height Columns
 *
 * @since             1.0.0
 * @package           Equal_Height_Columns
 *
 * @wordpress-plugin
 * Plugin Name:       Equal Height Columns
 * Description:       Apply equal heights to uneven columns and elements.
 * Version:           1.2.0
 * Author:            MIGHTYminnow, Mickey Kay, Braad Martin
 * Author URI:        http://mightyminnow.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       equal-height-columns
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-equal-height-columns-activator.php
 */
function activate_equal_height_columns() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-equal-height-columns-activator.php';
	Equal_Height_Columns_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-equal-height-columns-deactivator.php
 */
function deactivate_equal_height_columns() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-equal-height-columns-deactivator.php';
	Equal_Height_Columns_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_equal_height_columns' );
register_deactivation_hook( __FILE__, 'deactivate_equal_height_columns' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-equal-height-columns.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_equal_height_columns() {

	$plugin = new Equal_Height_Columns();
	$plugin->run();

}
run_equal_height_columns();
