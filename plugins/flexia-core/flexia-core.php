<?php

/**
 * @link              https://flexia.pro
 * @since             1.0.0
 * @package           Flexia_Core
 *
 * @wordpress-plugin
 * Plugin Name:       Flexia Core
 * Plugin URI:        https://flexia.pro
 * Description:       Core plugin for Flexia theme. Controls all the plugin territory functionalities.
 * Version:           1.4.2
 * Author:            WPDeveloper
 * Author URI:        https://www.wpdeveloper.net
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       flexia-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FLEXIA_CORE_VERSION', '1.4.2' );
define( 'FLEXIA_CORE_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flexia-core-activator.php
 */
function activate_flexia_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flexia-core-activator.php';
	Flexia_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flexia-core-deactivator.php
 */
function deactivate_flexia_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flexia-core-deactivator.php';
	Flexia_Core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flexia_core' );
register_deactivation_hook( __FILE__, 'deactivate_flexia_core' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flexia-core.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-wpdev-notices.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flexia_core() {

	$plugin = new Flexia_Core();
	$plugin->run();

}
run_flexia_core();


/**
 * Add separate flexia admin page through plugin
 * 
 * @since 1.0.4
 */
add_action('flexia_admin_menu', function($class) {
	add_menu_page(
		'Flexia',
		'Flexia',
		'manage_options',
		'flexia',
		array($class, 'flexia_dashboard_page'),
		get_template_directory_uri() . '/admin/img/flexia-logo-white.svg',
		199
	);
});

/**
 * Optional usage tracker
 */
if( ! class_exists( 'Flexia_Core_Plugin_Usage_Tracker') ) {
	require_once dirname( __FILE__ ) . '/includes/class-plugin-usage-tracker.php';
}
if( ! function_exists( 'flexia_core_start_plugin_tracking' ) ) {
	function flexia_core_start_plugin_tracking() {
		new Flexia_Core_Plugin_Usage_Tracker(
			__FILE__,
			'http://app.wpdeveloper.net',
			array(),
			true,
			true,
			1
		);
	}
	flexia_core_start_plugin_tracking();
}
