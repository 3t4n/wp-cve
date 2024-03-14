<?php

/**
 * Plugin Name: Columns and Sections Link for Elementor
 * Description: Make Elementor columns and sections clickable with this simple and lightweight plugin. It does not require any configuration, just install it and you will be able to make the columns and sections of Elementor linkable.
 * Plugin URI: https://seolog.net/columns-and-sections-link-for-elementor/
 * Author: Giulio Gualtieri
 * Version: 1.0.0
 * Author URI: https://seolog.net/chi-sono
 * Elementor tested up to: 3.6
 * Elementor Pro tested up to: 3.6.4
 * Text Domain: ecsl
 */

/*====================
DEFINE
=====================*/
define( 'ECSL_FILE', __FILE__ );
define( 'ECSL_URL', plugins_url( '/', __FILE__ ) );
define( 'ECSL_PATH', plugin_dir_path( __FILE__ ) );
define( 'ECSL_SCRIPT_SUFFIX', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
define( 'ECSL_VERSION', '1.0.0' );



/*====================
CHECK for elementor
=====================*/
if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path         = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}


if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}


function elementor_is_active() {
	if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
		return;
	}
	\Elementor\Plugin::$instance->files_manager->clear_cache();
}
register_activation_hook( __FILE__, 'elementor_is_active' );


/*====================
REQUIRE
=====================*/
require_once 'inc/main_core.php';







//END
