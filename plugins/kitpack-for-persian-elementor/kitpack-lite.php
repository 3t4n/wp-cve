<?php
/**
 * Plugin Name:       Kitpack Elementor Lite
 * Plugin URI:        https://kitpack.ir
 * Description:		  دسترسی به تمپلیت های آماده، تغییر فونت ظاهر المنتور، اضافه شدن فونت های فارسی به المنتور
 * Version:           2.1.1
 * Author:            کیت پک
 * Author URI:        https://kitpack.ir
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kitpack-lite
 * Domain Path:       /languages
 * Tested up to: 6.4.1
 * Tags:         فونت فارسی, فونت المنتور, افزودنی المنتور, صفحه ساز, ایران, rtl, farsi, parsian, iran, fa_IR,المنتور فارسی,شمسی,شمسی ساز
 * Elementor tested up to: 3.17.3
 * Elementor Pro tested up to: 3.17.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 2.1.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'KITPACK_LITE_VERSION', '2.1.1' );
define( 'KITPACK_URL', plugins_url( '/', __FILE__ ) );
define( 'KITPACK_PATH', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kitpack-lite-activator.php
 */
function activate_kitpack_lite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kitpack-lite-activator.php';
	Kitpack_Lite_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kitpack-lite-deactivator.php
 */
function deactivate_kitpack_lite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kitpack-lite-deactivator.php';
	Kitpack_Lite_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kitpack_lite' );
register_deactivation_hook( __FILE__, 'deactivate_kitpack_lite' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kitpack-lite.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kitpack_lite() {

	$plugin = new Kitpack_Lite();
	$plugin->run();
	

}
run_kitpack_lite();
