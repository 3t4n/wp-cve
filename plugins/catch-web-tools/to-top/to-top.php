<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-to-top.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function catchwebtools_run_to_top() {

	$plugin = new Catchwebtools_To_Top();
	$plugin->run();

}
catchwebtools_run_to_top();