<?php

/**
 *
 * @link              platycorp.com
 * @since             1.0.0
 * @package           Platy_Syncer_Etsy
 *
 * @wordpress-plugin
 * Plugin Name:       PlatyCorp Etsy Syncer
 * Plugin URI:        platy-syncer-etsy
 * Description:       Syncs between Woocommerce and Etsy.
 * Version:           6.2.4
 * Author:            PlatyCorp
 * Author URI:        platycorp.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       platy-syncer-etsy
 * Domain Path:       /languages
 * 
 * WC requires at least: 4.0.0
 * WC tested up to:  8.6
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
define( 'PLATY_SYNCER_ETSY_VERSION', '6.2.4' );
define( 'PLATY_SYNCER_ETSY_DIR_URL', plugin_dir_url(__FILE__) );
define( 'PLATY_SYNCER_ETSY_DIR_PATH', plugin_dir_path(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-platy-syncer-etsy-activator.php
 */
function activate_platy_syncer_etsy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-platy-syncer-etsy-activator.php';
	Platy_Syncer_Etsy_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-platy-syncer-etsy-deactivator.php
 */
function deactivate_platy_syncer_etsy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-platy-syncer-etsy-deactivator.php';
	Platy_Syncer_Etsy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_platy_syncer_etsy' );
register_deactivation_hook( __FILE__, 'deactivate_platy_syncer_etsy' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-platy-syncer-etsy.php';

function update_platy_syncer_etsy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-platy-syncer-etsy-activator.php';
	$db_version = get_option( "platy_syncer_etsy_version", "0.0.0" );
	$current_version = PLATY_SYNCER_ETSY_VERSION;
    if (version_compare($db_version, $current_version) != 0) {
        Platy_Syncer_Etsy_Activator::update($current_version);
    }
}

add_action( 'plugins_loaded', 'update_platy_syncer_etsy' );
add_action( 'platy_etsy_clean_logs', 'platy\\etsy\\logs\\PlatyLogger::clean_logs' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_platy_syncer_etsy() {

	$plugin = new Platy_Syncer_Etsy();
	$plugin->run();

}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {

		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

run_platy_syncer_etsy();
