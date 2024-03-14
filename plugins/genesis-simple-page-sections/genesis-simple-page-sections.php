<?php

/*
 * Plugin Name:       Genesis Simple Page Sections
 * Plugin URI:        https://efficientwp.com/plugins/genesis-simple-page-sections
 * Description:       Easily make full width page sections in Genesis. Must be using the Genesis theme framework.
 * Version:           1.4.0
 * Author:            Doug Yuen
 * Author URI:        https://efficientwp.com
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl.html
 * Text Domain:       genesis-simple-page-sections
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Current plugin version. */
define( 'GSPS_VERSION', '1.4.0' );

/* The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks. */
require plugin_dir_path( __FILE__ ) . 'includes/class-genesis-simple-page-sections.php';

/* Begins execution of the plugin. */
function run_gsps() {
	$plugin = new GSPS();
	$plugin->run();
}
run_gsps();