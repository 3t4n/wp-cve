<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://shapedplugin.com
 * @package           WP_Team
 *
 * Plugin Name:       WP Team
 * Plugin URI:        https://getwpteam.com/?ref=1
 * Description:       The most versatile and industry-leading WordPress team showcase plugin built to create and manage team members showcases with excellent design and multiple options.
 * Version:           3.0.0
 * Author:            ShapedPlugin LLC
 * Author URI:        https://shapedplugin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       team-free
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Currently plugin version.
 */
define( 'SPT_PLUGIN_NAME', 'WP Team' );
define( 'SPT_PLUGIN_SLUG', 'team-free' );
define( 'SPT_PLUGIN_FILE', __FILE__ );
define( 'SPT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPT_PLUGIN_VERSION', '3.0.0' );
define( 'SPT_PLUGIN_ROOT', plugin_dir_url( __FILE__ ) );
define( 'SPT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'activate_wp_team' ) ) {
	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-wp-team-activator.php
	 */
	function activate_wp_team() {
		require_once plugin_dir_path( __FILE__ ) . 'src/Includes/class-wp-team-activator.php';
		WP_Team_Activator::activate();
	}
}

if ( ! function_exists( 'deactivate_wp_team' ) ) {
	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-wp-team-deactivator.php
	 */
	function deactivate_wp_team() {
		require_once plugin_dir_path( __FILE__ ) . 'src/Includes/class-wp-team-deactivator.php';
		WP_Team_Deactivator::deactivate();
	}
}

register_activation_hook( __FILE__, 'activate_wp_team' );
register_deactivation_hook( __FILE__, 'deactivate_wp_team' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_wp_team() {
	$plugin = new ShapedPlugin\WPTeam\Includes\Team();
	$plugin->run();
}

// Don't run if the Premium version is active.
require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( ! ( is_plugin_active( 'wp-team-pro/wp-team-pro.php' ) || is_plugin_active_for_network( 'wp-team-pro/wp-team-pro.php' ) ) ) {
	run_wp_team();
}
