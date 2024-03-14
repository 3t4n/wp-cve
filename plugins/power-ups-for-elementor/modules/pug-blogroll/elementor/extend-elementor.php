<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//define( 'ELEMENTOR_PANDO__FILE__', __FILE__ );

/**
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function elpugblogroll_load() {
	// Load localization file
	load_plugin_textdomain( 'elpug' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'elpugblogroll_fail_load' );
		return;
	}

	// Check required version
	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'elpugblogroll_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/plugin.php' );
}
add_action( 'plugins_loaded', 'elpugblogroll_load' );


function elpugblogroll_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . esc_html( 'Power-Ups for Elementor is not working because you are using an old version of Elementor.', 'elpug' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elpug' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}