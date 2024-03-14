<?php
/*
Plugin Name: BAW Multipass for Protected Pages
Plugin URI: http://www.boiteaweb.fr/mpp
Description: Adds a textarea field under the actual password field to add more than 1 password per protected page.
Version: 1.4
Author: Juliobox
Author URI: http://www.BoiteaWeb.fr
License: GPLv2
*/

define( 'BAWMPP__FILE__', __FILE__ );
define( 'BAWMPP_SLUG', 'baw-multiple-pass-for-protected-pages' );
define( 'BAWMPP_FULLNAME', 'Multipass for Pages' );
define( 'BAWMPP_VERSION', '1.4' );
define( 'BAWMPP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', 'bawmpp_bootstrap' );
function bawmpp_bootstrap()
{
	$filename  = 'inc/';
	$filename .= is_admin() ? 'backend-' : 'frontend-';
	$filename .= defined( 'DOING_AJAX' ) && DOING_AJAX ? '' : 'no';
	$filename .= 'ajax.inc.php';
	if( file_exists( plugin_dir_path( __FILE__ ) . $filename ) )
		include( plugin_dir_path( __FILE__ ) . $filename );
	$filename  = 'inc/';
	$filename .= 'bothend-';
	$filename .= defined( 'DOING_AJAX' ) && DOING_AJAX ? '' : 'no';
	$filename .= 'ajax.inc.php';
	if( file_exists( plugin_dir_path( __FILE__ ) . $filename ) )
		include( plugin_dir_path( __FILE__ ) . $filename );
}