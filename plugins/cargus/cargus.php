<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://cargus.ro/
 * @since             1.0.0
 * @package           Cargus
 *
 * @wordpress-plugin
 * Plugin Name:       Cargus
 * Plugin URI:        http://woocommerce.demo.cargus.ro/
 * Description:       Metoda de livrare Cargus pentru WooCommerce.
 * Version:           1.4.2
 * Author:            Cargus
 * Author URI:        https://cargus.ro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cargus
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'CARGUS_VERSION', '1.4.2' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cargus.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cargus() {

	$plugin = new Cargus();
	$plugin->run();

}
run_cargus();
