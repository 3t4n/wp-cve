<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themepure.net
 * @since             1.1.9
 * @package           tpmeta
 *
 * @wordpress-plugin
 * Plugin Name:       Pure Metafields
 * Plugin URI:        https://themepure.net/plugins/puremetafields/files/pure-metafields.zip
 * Description:       Plugin For Custom Metabox To Attach To Any Post Types.
 * Version:           1.1.9
 * Author:            ThemePure
 * Author URI:        https://themepure.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pure-metafields
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.1.9 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TPMETA_VERSION', '1.1.9' );
define( 'TPMETA_PATH', plugin_dir_path(__FILE__) );
define( 'TPMETA_URL', plugin_dir_url(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pure-metafields-activator.php
 */
if(!function_exists('tpmeta_activate_tp_metabox')){
	function tpmeta_activate_tp_metabox() {
		require_once TPMETA_PATH . 'includes/class-pure-metafields-activator.php';
		tpmeta_activator::activate();
	}
	register_activation_hook( __FILE__, 'tpmeta_activate_tp_metabox' );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pure-metafields-deactivator.php
 */
if(!function_exists('tpmeta_deactivate_tp_metabox')){
	function tpmeta_deactivate_tp_metabox() {
		require_once TPMETA_PATH . 'includes/class-pure-metafields-deactivator.php';
		tpmeta_aeactivator::deactivate();
	}
	register_deactivation_hook( __FILE__, 'tpmeta_deactivate_tp_metabox' );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require TPMETA_PATH . 'includes/class-pure-metafields.php';



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.9
 */
if(!function_exists('tpmeta_kick')){
	function tpmeta_kick() {
		$plugin = new tpmeta();
		$plugin->run();
	}
	tpmeta_kick();
}

require_once TPMETA_PATH . 'metaboxes/functions.php';
require_once TPMETA_PATH . 'metaboxes/class-metabox.php';


