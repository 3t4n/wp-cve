<?php
/**
 * Plugin Name:       Menu Caching
 * Description:       This plugin caches WordPress menus to improve page loading time.
 * Version:           1.1.2
 * Author:            Theo Gkitsos
 * Author URI:        https://theodorosgkitsos.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       menu-caching
 * Domain Path:       /languages
 * Requires at least: 5.3
 * Tested up to:      6.3
 * Requires PHP:      5.6
 * Stable tag:        1.1.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'WP_MENU_CACHING_VERSION', '1.1.2' );
define( 'WP_MENU_CACHING_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'WP_MENU_CACHING_BASE_FILE', 'menu-caching/menu-caching.php' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-menu-caching-activator.php
 */
function activate_dc_menu_caching() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-menu-caching-activator.php';
    Wp_Menu_Caching_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-menu-caching-deactivator.php
 */
function deactivate_dc_menu_caching() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-menu-caching-deactivator.php';
    Wp_Menu_Caching_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dc_menu_caching' );
register_deactivation_hook( __FILE__, 'deactivate_dc_menu_caching' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-menu-caching.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_dc_menu_caching() {
	$plugin = new Wp_Menu_Caching();
	$plugin->run();
}
run_dc_menu_caching();
