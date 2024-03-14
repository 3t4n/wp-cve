<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Process the AJAX request to install and activate plugins.
add_action( 'wp_ajax_extras_plugin_install', 'csmm_extras_install_plugin' );
add_action( 'wp_ajax_extras_plugin_update', 'csmm_extras_update_plugin' );
add_action( 'wp_ajax_extras_plugin_activate', 'csmm_extras_activate_plugin' );

// Process the AJAX request to install, update, and activate themes.
add_action( 'wp_ajax_extras_theme_install', 'csmm_extras_install_theme' );
add_action( 'wp_ajax_extras_theme_activate', 'csmm_extras_activate_theme' );
add_action( 'wp_ajax_extras_theme_update', 'csmm_extras_update_theme' );

function csmm_extras_install_plugin() {
	// Verify the nonce for install action.
	$extnonce = $_POST['extnonce'];
	if ( ! wp_verify_nonce( $extnonce, 'csmm-extra-nonce' ) ) {
		wp_send_json_error( 'Invalid nonce.' );
	}

	// Retrieve the plugin slug.
	$extplugin_slug = $_POST['slug'];

	// Include the necessary files.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	// Get plugin information.
	$get_plugin_info = plugins_api( 'plugin_information', array( 'slug' => sanitize_key( wp_unslash( $extplugin_slug ) ) ) );
	// Create the plugin upgrader instance.
	$upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin( compact( 'title', 'url', 'nonce', 'plugin', 'api' ) ) );

	// Install the plugin.
	$result = $upgrader->install( $get_plugin_info->download_link );

	// Check the installation result.
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( 'Plugin installation failed.' );
	}

	// Send response.
	csmm_extras_activate_plugin();
	wp_send_json_success( 'Plugin installed successfully.' );
}

function csmm_extras_update_plugin() {
	// Verify the nonce for update action.
	$nonce = $_POST['extnonce'];
	if ( ! wp_verify_nonce( $nonce, 'csmm-extra-nonce' ) ) {
		wp_send_json_error( 'Invalid nonce.' );
	}

	// Retrieve the plugin slug.
	$extplugin_slug = $_POST['slug'];

	// Include the necessary files.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	// Get plugin information.
	$get_plugin_info = plugins_api( 'plugin_information', array( 'slug' => sanitize_key( wp_unslash( $extplugin_slug ) ) ) );
	// Create the plugin upgrader instance.
	$upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin( compact( 'title', 'url', 'nonce', 'plugin', 'api' ) ) );

	// Update the plugin.
	$result = $upgrader->upgrade( $get_plugin_info->download_link );

	// Check the update result.
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( 'Plugin update failed.' );
	}

	// Send response.
	wp_send_json_success( 'Plugin updated successfully.' );
}

function csmm_extras_activate_plugin() {
	// Verify the nonce for activate action.
	$nonce = $_POST['extnonce'];
	if ( ! wp_verify_nonce( $nonce, 'csmm-extra-nonce' ) ) {
		wp_send_json_error( 'Invalid nonce.' );
	}

	// Retrieve the plugin slug.
	$plugin_slug = $_POST['slug'];

	// Include the necessary files.
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	// Activate the plugin.
	$activate_result = activate_plugin( $plugin_slug . '/' . $plugin_slug . '.php' );

	// Check the activation result.
	if ( is_wp_error( $activate_result ) ) {
		wp_send_json_error( 'Plugin activation failed.' );
	}

	// Send response.
	wp_send_json_success( 'Plugin activated successfully.' );
}

// Theme functions.
function csmm_extras_install_theme() {
	// Verify the nonce for install action.
	$extnonce = $_POST['extnonce'];
	if ( ! wp_verify_nonce( $extnonce, 'csmm-extra-nonce' ) ) {
		wp_send_json_error( 'Invalid nonce.' );
	}

	// Retrieve the theme slug.
	$exttheme_slug = $_POST['slug'];

	// Include the necessary files.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/theme-install.php';

	// Get theme information.
	$get_theme_info = themes_api( 'theme_information', array( 'slug' => sanitize_key( wp_unslash( $exttheme_slug ) ) ) );

	// Create the theme upgrader instance.
	$upgrader = new Theme_Upgrader( new Theme_Upgrader_Skin( compact( 'title', 'url', 'nonce', 'theme' ) ) );

	// Install the theme.
	$result = $upgrader->install( $get_theme_info->download_link );

	// Check the installation result.
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( 'Theme installation failed.' );
	}

	// Send response.
	wp_send_json_success( 'Theme installed successfully.' );
}

function csmm_extras_update_theme() {
	// Verify the nonce for update action.
	$nonce = $_POST['extnonce'];
	if ( ! wp_verify_nonce( $nonce, 'csmm-extra-nonce' ) ) {
		wp_send_json_error( 'Invalid nonce.' );
	}

	// Retrieve the theme slug.
	$theme_slug = $_POST['slug'];

	// Include the necessary files.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/theme-install.php';

	// Get theme information.
	$get_theme_info = themes_api( 'theme_information', array( 'slug' => sanitize_key( wp_unslash( $theme_slug ) ) ) );

	// Create the theme upgrader instance.
	$upgrader = new Theme_Upgrader( new Theme_Upgrader_Skin( compact( 'title', 'url', 'nonce', 'theme' ) ) );

	// Update the theme.
	$result = $upgrader->upgrade( $get_theme_info->download_link );

	// Check the update result.
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( 'Theme update failed.' );
	}

	// Send response.
	wp_send_json_success( 'Theme updated successfully.' );
}
