<?php
/*
Plugin Name: Mojo Under Construction
Plugin URI: http://www.mojowill.com/developer/the-mojo-admin-toolbox/
Description: Easily create a "Coming Soon" page for your WordPress site.
Version: 1.1.2
Author: mojowill
Author URI: http://www.mojowill.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define Plugin URL
 */
 if ( ! defined( 'MOJO_UC_BASE_URL' ) )
	 define( 'MOJO_UC_BASE_URL', plugin_dir_url( __FILE__ ) );
	 
/**
 * Define Plugin Path
 */
 if ( ! defined( 'MOJO_UC_BASE_PATH' ) )
 	define( 'MOJO_UC_BASE_PATH', plugin_dir_path( __FILE__ ) );
 
/**
 * Bring in the classes
 */
require_once( MOJO_UC_BASE_PATH . '/includes/mojo-contruction.class.php' );

/**
 * Start Plugin
 */ 
$mojoContruction = new mojoContruction();

register_activation_hook( __FILE__, array( $mojoContruction, 'activate' ) );
register_deactivation_hook( __FILE__, array( $mojoContruction, 'deactivate' ) );
register_uninstall_hook( __FILE__, array( $mojoContruction, 'underConstructionPlugin_delete' ) );

add_action( 'wp_ajax_uc_get_ip_address', 'uc_get_ip_address' );

function uc_get_ip_address(){
	echo $_SERVER['REMOTE_ADDR'];
	die();
}