<?php
/*
 * Plugin Name: .htaccess Site Access Control
 *  Plugin URI: http://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/
 *  Description: This plugin lets you password protect your login page, admin page, and/or the whole site.
 *  Author: WPSOS
 *  Version: 1.0
 *  Author URI: http://www.wpsos.io/
 *  Licence: GPLv2 or later
 * 
 */
 
define('WPSOS_HP_FILE', __FILE__);
//Require the plugin files
require_once( __DIR__ . '/class.htaccess.php' );
require_once( __DIR__ . '/settings-page.php' );

//Create global object
global $WPSOS_HP;
$WPSOS_HP = new WPSOS_HP();

//Register installing/uninstalling functions
register_activation_hook( __FILE__, array( $WPSOS_HP, 'activate' ) );
register_deactivation_hook( __FILE__, array( $WPSOS_HP, 'deactivate' ) );

if( is_admin() ){
	//Register plugin scripts
	add_action( 'admin_enqueue_scripts', array( $WPSOS_HP, 'register_plugin_scripts' ) );
}

?>