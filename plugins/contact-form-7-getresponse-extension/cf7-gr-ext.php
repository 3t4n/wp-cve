<?php
/**
 *
 * @link              http://wensolutions.com/
 * @since             1.0.0
 * @package           Cf7_Gr_Ext
 *
 * @wordpress-plugin
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
function activate_cf7_gr_ext() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-gr-ext-activator.php';
	Cf7_Gr_Ext_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_cf7_gr_ext() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cf7-gr-ext-deactivator.php';
	Cf7_Gr_Ext_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cf7_gr_ext' );
register_deactivation_hook( __FILE__, 'deactivate_cf7_gr_ext' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cf7-gr-ext.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_cf7_gr_ext() {

	$plugin = new Cf7_Gr_Ext();
	$plugin->run();

}

add_action( 'plugins_loaded', 'run_cf7_gr_ext' );
