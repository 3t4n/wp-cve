<?php
/**
 * @package Clear Sucuri Cache
 */
/*
Plugin Name: Clear Sucuri Cache
Plugin URI: 
Description: Simply clears whole Sucuri cache. Clear is done from wp admin panel or plugin's page
Version: 1.4
Author: WebRangers
Author URI: http://webrangers.agency/
License: GPLv2 or later
Text Domain: sucuri-clear
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

Copyright 20015-2017 WebRangers
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}


define( 'SUCURIPURGER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SUCURIPURGER_DELETE_LIMIT', 100000 );
define( 'SUCURIPURGER_PLUGIN_BASENAME', plugin_basename(__FILE__));

register_activation_hook( __FILE__, array( 'SucuriClear', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'SucuriClear', 'plugin_deactivation' ) );

require_once( SUCURIPURGER__PLUGIN_DIR . 'class.sucuriclear.php' );


add_action( 'init', array( 'SucuriClear', 'init' ) );
