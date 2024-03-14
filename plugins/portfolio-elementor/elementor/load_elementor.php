<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load the Elementor widgets after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function powerfolio_elementor_load() {

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		//add_action( 'admin_notices', 'powerfolio_elementor_fail_load' );
		return;
	}

	// Check required version
	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'powerfolio_elementor_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/Register_Powerfolio_Elementor_Widgets.php' );
}
add_action( 'plugins_loaded', 'powerfolio_elementor_load' );


function powerfolio_elementor_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . esc_html( 'Powerfolio + Elementor is not working because you are using an old version of Elementor.', 'powerfolio' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'powerfolio' ) ) . '</p>';

	echo '<div class="error">' . wp_kses_post($message) . '</div>';
}