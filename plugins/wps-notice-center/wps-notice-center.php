<?php
/*
Plugin Name: WPS Notice Center
Description:  WPS Notice Center permet de masquer l'ensemble des notices d'administration et les réunir dans une seul notice à déplier. Profitez pleinement de votre interface WordPress sans pollution visuel.
Donate Link: https://www.paypal.me/donateWPServeur
Version: 1.2.6
Author: WPServeur, NicolasKulka, wpformation, benoti
Author URI: https://wpserveur.net
Domain Path: languages
Tested up to: 6.3
Requires PHP: 7.0
Text Domain: wps-notice-center
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'WPS_NOTICE_CENTER_VERSION', '1.2.6' );
define( 'WPS_NOTICE_CENTER_FOLDER', 'wps-notice-center' );
define( 'WPS_NOTICE_CENTER_BASENAME', plugin_basename( __FILE__ ) );

define( 'WPS_NOTICE_CENTER_URL', plugin_dir_url( __FILE__ ) );
define( 'WPS_NOTICE_CENTER_DIR', plugin_dir_path( __FILE__ ) );

require_once WPS_NOTICE_CENTER_DIR . 'autoload.php';

add_action( 'plugins_loaded', 'plugins_loaded_wps_notice_center' );
function plugins_loaded_wps_notice_center() {
	\WPS\WPS_Notice_Center\Plugin::get_instance();

	load_plugin_textdomain( 'wps-notice-center', false, basename( rtrim( dirname( __FILE__ ), '/' ) ) . '/languages' );
}