<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://alttext.ai
 * @since             1.0.0
 * @package           Atai
 *
 * @wordpress-plugin
 * Plugin Name:       AltText.ai
 * Plugin URI:        https://alttext.ai/product
 * Description:       Automatically generate image alt text with AltText.ai.
 * Version:           1.2.8
 * Author:            AltText.ai
 * Author URI:        https://alttext.ai
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       alttext-ai
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'ATAI_VERSION', '1.2.8' );

/**
 * Constant to save the value of the plugin path.
 */
define ( 'ATAI_PLUGIN_FILE', __FILE__ );

/**
* Constant for database table name of asset data
*/
define ( 'ATAI_DB_ASSET_TABLE', 'atai_assets' );

/**
 * Constant to save the length of the CSV line.
 */
if ( ! defined( 'ATAI_CSV_LINE_LENGTH' ) ) {
  define( 'ATAI_CSV_LINE_LENGTH', 2048 );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-atai-activator.php
 */
function activate_atai( $plugin ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-atai-activator.php';
	ATAI_Activator::activate( $plugin );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-atai-deactivator.php
 */
function deactivate_atai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-atai-deactivator.php';
	ATAI_Deactivator::deactivate();
}

add_action( 'activated_plugin', 'activate_atai', 10, 1 );
// register_deactivation_hook( __FILE__, 'deactivate_atai' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-atai.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_atai() {
	$plugin = new ATAI();
	$plugin->run();
}

run_atai();
