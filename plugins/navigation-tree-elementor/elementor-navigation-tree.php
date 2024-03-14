<?php
/*
Plugin Name: Navigation Tree Elementor
Plugin URI: https://wp-distillery.com
description: Adds a navigation tree displaying a list of active elements on the elementor editor screen.
Version: 1.0.1
Author: WP Distillery
Author URI:
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'naviTreeElementor__', __FILE__ );

/**
 * Load Elementor Navigation Tree
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function naviTreeElementor_load() {
	// Load localization file
	load_plugin_textdomain( 'elementor-navigation-tree' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'ent_fail_load' );
		return;
	}

	// Check required version
	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'naviTreeElementor_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/plugin.php' );
}
add_action( 'plugins_loaded', 'naviTreeElementor_load' );


function naviTreeElementor_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Elementor Elementor Navigation Tree is not working because you are using an old version of Elementor.', 'elementor-navigation-tree' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-navigation-tree' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}