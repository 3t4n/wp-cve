<?php
/**
 * Plugin Name: Expand Divi
 * Plugin URI: https://wajba.club/ed
 * Description: A plugin that adds more functionlity to the Divi theme
 * Version: 1.6.0
 * Author: Faycal Boutam
 * Text Domain: expand-divi
 * License: GPLv2 or later
 * @package ExpandDivi
 */

/* 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// define plugin url constant
if ( ! defined( 'EXPAND_DIVI_URL' ) ) {
	define( 'EXPAND_DIVI_URL', plugin_dir_url( __FILE__ ) );
}

// define plugin path constant
if ( ! defined( 'EXPAND_DIVI_PATH' ) ) {
	define( 'EXPAND_DIVI_PATH', plugin_dir_path( __FILE__ ) );
}

// require setup class
require_once( EXPAND_DIVI_PATH . 'inc/ExpandDiviSetup.php' );

// localization
function expand_divi_localization() {
	load_plugin_textdomain( 'expand-divi', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}
add_action('init', 'expand_divi_localization');