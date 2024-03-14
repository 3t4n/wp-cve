<?php
/**
 * My WP Translate
 *
 * @package    MY_WP_Translate
 *
 * @wordpress-plugin
 * Plugin Name:       My WP Translate
 * Plugin URI:        https://mythemeshop.com/plugins/my-wp-translate/
 * Description:       Simple yet powerful Translate plugin for WordPress. Can be used with most of the themes and plugins which support translation.
 * Version:           1.1
 * Author:            MyThemeShop
 * Author URI:        https://mythemeshop.com/
 * Text Domain:       my-wp-translate
 * Domain Path:       /languages
 *
 * MTS Product Type: Free
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-my-wp-translate-activator.php
 */
function activate_my_wp_translate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-my-wp-translate-activator.php';
	MY_WP_Translate_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-my-wp-translate-deactivator.php
 */
function deactivate_my_wp_translate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-my-wp-translate-deactivator.php';
	MY_WP_Translate_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_my_wp_translate' );
register_deactivation_hook( __FILE__, 'deactivate_my_wp_translate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-my-wp-translate.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_my_wp_translate() {

	$plugin = new MY_WP_Translate();
	$plugin->run();

}
run_my_wp_translate();
