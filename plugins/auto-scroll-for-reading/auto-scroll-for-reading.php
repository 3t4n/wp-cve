<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpglob.com/
 * @since             1.0.0
 * @package           Auto_Scroll_For_Reading
 *
 * @wordpress-plugin
 * Plugin Name:       Auto scroll for reading
 * Plugin URI:        https://wpglob.com/wordpress-autoscroll-plugin/
 * Description:       Let your readers easily scroll your content. Add automatic scrolling to your website.
 * Version:           1.1.2
 * Author:            WP Glob
 * Author URI:        https://wpglob.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       auto-scroll-for-reading
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AUTO_SCROLL_FOR_READING_VERSION', '1.1.2' );
define( 'AUTO_SCROLL_FOR_READING_NAME_VERSION', '1.0.0' );
define( 'AUTO_SCROLL_FOR_READING_NAME', 'auto-scroll-for-reading' );

if( ! defined( 'AUTO_SCROLL_FOR_READING_BASENAME' ) )
    define( 'AUTO_SCROLL_FOR_READING_BASENAME', plugin_basename( __FILE__ ) );

if( ! defined( 'AUTO_SCROLL_FOR_READING_DIR' ) )
    define( 'AUTO_SCROLL_FOR_READING_DIR', plugin_dir_path( __FILE__ ) );

if( ! defined( 'AUTO_SCROLL_FOR_READING_BASE_URL' ) )
    define( 'AUTO_SCROLL_FOR_READING_BASE_URL', plugin_dir_url(__FILE__ ) );

if( ! defined( 'AUTO_SCROLL_FOR_READING_ADMIN_PATH' ) )
    define( 'AUTO_SCROLL_FOR_READING_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin' );

if( ! defined( 'AUTO_SCROLL_FOR_READING_ADMIN_URL' ) )
    define( 'AUTO_SCROLL_FOR_READING_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin' );

if( ! defined( 'AUTO_SCROLL_FOR_READING_PUBLIC_PATH' ) )
    define( 'AUTO_SCROLL_FOR_READING_PUBLIC_PATH', plugin_dir_path( __FILE__ ) . 'public' );

if( ! defined( 'AUTO_SCROLL_FOR_READING_PUBLIC_URL' ) )
    define( 'AUTO_SCROLL_FOR_READING_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-auto-scroll-for-reading-activator.php
 */
function activate_auto_scroll_for_reading() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-scroll-for-reading-activator.php';
    Auto_Scroll_For_Reading_Activator::wpg_auto_scroll_update_db_check();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-auto-scroll-for-reading-deactivator.php
 */
function deactivate_auto_scroll_for_reading() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-scroll-for-reading-deactivator.php';
	Auto_Scroll_For_Reading_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_auto_scroll_for_reading' );
register_deactivation_hook( __FILE__, 'deactivate_auto_scroll_for_reading' );

add_action( 'plugins_loaded', 'activate_auto_scroll_for_reading' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-auto-scroll-for-reading.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_auto_scroll_for_reading() {

	$plugin = new Auto_Scroll_For_Reading();
	$plugin->run();

}
run_auto_scroll_for_reading();
