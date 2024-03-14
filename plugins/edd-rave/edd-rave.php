<?php
/*
    Plugin Name:    Flutterwave Easy Digital Downloads Payment Gateway
    Plugin URL:     https://wordpress.org/plugins/edd-rave
    Description:    Flutterwave payment gateway for Easy Digital Downloads
    Version:        2.0.1
    Author:         Tunbosun Ayinla
    Author URI:     https://bosun.me
    License:        GPL-2.0+
    License URI:    http://www.gnu.org/licenses/gpl-2.0.txt
	Text Domain:    edd-rave
	Domain Path:    /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin Root File.
if ( ! defined( 'TBZ_EDD_RAVE_PLUGIN_FILE' ) ) {
	define( 'TBZ_EDD_RAVE_PLUGIN_FILE', __FILE__ );
}

// Plugin version.
if ( ! defined( 'TBZ_EDD_RAVE_VERSION' ) ) {
	define( 'TBZ_EDD_RAVE_VERSION', '2.0.1' );
}

// Plugin Folder Path.
if ( ! defined( 'TBZ_EDD_RAVE_PLUGIN_DIR' ) ) {
	define( 'TBZ_EDD_RAVE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL.
if ( ! defined( 'TBZ_EDD_RAVE_URL' ) ) {
	define( 'TBZ_EDD_RAVE_URL', plugin_dir_url( __FILE__ ) );
}

function tbz_edd_rave_loader() {

	// Bail if Easy Digital Downloads is not active.
	if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
		return;
	}

	require_once TBZ_EDD_RAVE_PLUGIN_DIR . 'includes/class-frontend.php';

	if ( is_admin() ) {
		require_once TBZ_EDD_RAVE_PLUGIN_DIR . 'includes/class-admin.php';
	}
}
add_action( 'plugins_loaded', 'tbz_edd_rave_loader', 100 );
