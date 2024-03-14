<?php
/*
   Plugin Name: Inkedjoy POD-Dropshipping
   Plugin URI: http://wordpress.org/extend/plugins/eprolo_pod/
   Version: 1.3
   Author: Inkedjoy/EPROLO
   Description: The best Etsy dropshipping suppliers,etsy print on demand,EPROLO POD
   Text Domain: Inkedjoy POD/EPROLO POD
   Author URI:   https://www.inkedjoy.com
  */

//PHP minimum required version
$eprolo_pod_minimalRequiredPhpVersion = '5.6';



/**
 * Prompt after PHP version error
 */
function eprolo_pod_noticePhpVersionWrong() {
	global $eprolo_pod_minimalRequiredPhpVersion;
	echo '<div class="updated fade">Inkedjoy POD requires a newer version of PHP to be running </div>';
}

/**
 * Check version
 */
function eprolo_pod_PhpVersionCheck() {
	global $eprolo_pod_minimalRequiredPhpVersion;
	if ( version_compare( phpversion(), $eprolo_pod_minimalRequiredPhpVersion ) < 0 ) {
		add_action( 'admin_notices', 'eprolo_pod_noticePhpVersionWrong' );
		return false;
	}
	return true;
}

/**
 *  Initialize the internationalization of this plugin (i18n). Different voices, none, default English
 *
 * @return void
 */
function eprolo_pod_i18n_init() {
	$pluginDir = dirname( plugin_basename( __FILE__ ) );
	load_plugin_textdomain( 'eprolo_pod', false, $pluginDir . '/languages/' );
}


// Adding method
add_action( 'plugins_loadedi', 'eprolo_pod_i18n_init' );


//Check PHP version
if ( !eprolo_pod_PhpVersionCheck() ) {
	// Only load and run the init function if we know PHP version can parse it
	return;
}

define('EPROLO_POD_ORIGIN', 'https://inkedjoy.com/');


include_once 'eprolo_pod_init.php';
eprolo_pod_init( __FILE__ );

//Define external AJAX interface
require_once 'Eprolo_pod_ajax.php';
function eprolo_pod_disconnect_init() {
	$aPlugin = new Eprolo_Pod_Ajax();
	$aPlugin->eprolo_pod_disconnect();
}
function eprolo_pod_connect_key_init() {
	 $aPlugin = new Eprolo_Pod_Ajax();
	$aPlugin->eprolo_pod_connect_key();
}
function eprolo_pod_reflsh_init() {
	$aPlugin = new Eprolo_Pod_Ajax();
	$aPlugin->eprolo_pod_reflsh();
}
// Interface Join Action
add_action( 'wp_ajax_eprolo_pod_disconnect', 'eprolo_pod_disconnect_init' );
add_action( 'wp_ajax_eprolo_pod_connect_key', 'eprolo_pod_connect_key_init' );
add_action( 'wp_ajax_eprolo_pod_reflsh', 'eprolo_pod_reflsh_init' );

