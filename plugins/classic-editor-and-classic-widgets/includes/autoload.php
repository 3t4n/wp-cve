<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Language Files
if ( ! is_textdomain_loaded( 'classic-editor-and-classic-widgets' ) ) {
	load_plugin_textdomain( 'classic-editor-and-classic-widgets', false, 'classic-editor-and-classic-widgets/languages' );
}

function cew_init() {
	// Include
	require_once CEW_INCLUDES . '/helpers.php';
	require_once CEW_INCLUDES . '/vendor/Controller.php';
	require_once CEW_INCLUDES . '/Settings.php';
	require_once CEW_INCLUDES . '/Frontend.php';
	require_once CEW_INCLUDES . '/Gutenberg.php';

	// Init
	new GRIM_CEW\Frontend();
	new GRIM_CEW\Gutenberg();

	// WP Admin
	if ( is_admin() ) {
		require_once CEW_INCLUDES . '/vendor/Field.php';
		require_once CEW_INCLUDES . '/fields/Autocomplete.php';
		require_once CEW_INCLUDES . '/Dashboard.php';

		new GRIM_CEW\Fields\Autocomplete();
		new GRIM_CEW\Dashboard();
	}
}

add_action( 'plugins_loaded', 'cew_init', 10 );
