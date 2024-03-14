<?php

// No direct access to file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add submenu page for admin setting
 */
function smnwcrpl_add_sublevel_menu() {
	add_submenu_page(
		'options-general.php',
		__( 'Woocommerce regenerate product lookup table', 'smnwcrpl' ),
		__( 'Woocommerce regenerate product lookup table', 'smnwcrpl' ),
		'manage_options',
		'smnwcrpl',
		'smnwcrpl_display_settings_page'
	);
}

add_action( 'admin_menu', 'smnwcrpl_add_sublevel_menu' );