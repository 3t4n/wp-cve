<?php
/**
 * Main php file
 * Register activation and deactivation hooks and run main class
 * php version 7.2
 *
 * @category Plugin
 * @package  Wp_Custom_Cursors
 * @author   Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 * @license  GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link     https://hamidrezasepehr.com/
 * @since    1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       WP Custom Cursors
 * Plugin URI:        https://wordpress.org/plugins/wp-custom-cursors/
 * Description:       Creative custom cursors for your website.
 * Version:           3.3
 * Requires at least: 6.2
 * Requires PHP:      7.2
 * Author:            Hamid Reza Sepehr
 * Author URI:        https://hamidrezasepehr.com/
 * License:           GPLv2 or later
 * License URI:       (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * Text Domain:       wpcustom-cursors
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

if ( ! defined( 'WP_CUSTOM_CURSORS_PLUGIN_BASE' ) ) {
	define( 'WP_CUSTOM_CURSORS_PLUGIN_BASE', plugin_basename( __FILE__ ) );
}


define( 'WP_CUSTOM_CURSORS_VERSION', '3.3' );

/**
 * Register activation hooks
 *
 * @since  1.0.0
 * @return void
 */
function wp_custom_cursors_activate() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-cursors-activator.php';
	Wp_Custom_Cursors_Activator::activate();
}

/**
 * Register deactivation hooks
 *
 * @since  1.0.0
 * @return void
 */
function wp_custom_cursors_deactivate() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-cursors-deactivator.php';
	Wp_Custom_Cursors_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wp_custom_cursors_activate' );
register_deactivation_hook( __FILE__, 'wp_custom_cursors_deactivate' );

require plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-cursors.php';

/**
 * Run main class
 *
 * @since  1.0.0
 * @return void
 */
function wp_custom_cursors_run() {
	$plugin = new Wp_custom_cursors();
	$plugin->run();
}

wp_custom_cursors_run();
