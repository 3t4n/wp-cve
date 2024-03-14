<?php

/**
 * Plugin Name:       Dicode Icons Pack
 * Plugin URI:        https://designinvento.net/downloads/dicode-icons-pack
 * Description:       This plugin provide ability to have custom icons based on free and premium icon libraries. 
 * Version:           1.1.1
 * Author:            Designinvento
 * Author URI:        https://designinvento.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dicode-icons-pack
 * Domain Path:       /languages
 *
 Elementor tested up to: 3.18.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'DICODE_ICONS_PACK_VERSION', '1.1.1' );
define('DICODE_ICONS_PACK_PATH', plugin_dir_path(__FILE__));
define('DICODE_ICONS_PACK_URL', plugins_url('/', __FILE__));
define('DICODE_ICONS_ASSETS_PATH', DICODE_ICONS_PACK_PATH . 'assets/');
define('DICODE_ICONS_ASSETS_URL', DICODE_ICONS_PACK_URL . 'assets/');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dicode-icons-pack-activator.php
 */
function activate_dicode_icons_pack() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dicode-icons-pack-activator.php';
	Dicode_Icons_Pack_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dicode-icons-pack-deactivator.php
 */
function deactivate_dicode_icons_pack() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dicode-icons-pack-deactivator.php';
	Dicode_Icons_Pack_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dicode_icons_pack' );
register_deactivation_hook( __FILE__, 'deactivate_dicode_icons_pack' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dicode-icons-pack.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dicode_icons_pack() {

	$plugin = new Dicode_Icons_Pack();
	$plugin->run();

}
run_dicode_icons_pack();
