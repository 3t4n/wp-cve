<?php
namespace logged_in_as;

/**
  Logged In As
 
  @package           
  @author            Jerry Stewart
  @copyright         2020 Jerry Stewart
  @license           GPL-2.0-or-later
 
  @wordpress-plugin
  Plugin Name:       Logged In As
  Plugin URI:        
  Description:       Provides the ability to change a menu's title (text) when a user is logged in. The new menu title can include user meta. Optionally add an Avatar to the menu.
  Version:           1.1.0
  Requires at least: 5.4
  Requires PHP:      7.0
  Author:            Jerry Stewart
  Author URI:        https://webworkz.nz
  Text Domain:       logged-in-as
  License:           GPL v2 or later
  License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 
	Logged In As is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.
	 
	Logged In As is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
	 
	You should have received a copy of the GNU General Public License
	along with Logged In As. If not, see http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Prevent direct file access
if ( !defined( 'ABSPATH' ) ) exit;

// useful for db cleanup on uninstalling 
if ( !defined( 'LIA_PREFIX' ) )
	define ( 'LIA_PREFIX', 'liam-' );

// the css class to add to the target menu item
if ( !defined( 'LIA_ICON_CSS_CLASS' ) )
	define ( 'LIA_ICON_CSS_CLASS', 'liam-icon' );


require_once __DIR__ . '/includes/class-lia-options.php';
require_once __DIR__ . '/includes/class-lia-action.php';
require_once __DIR__ . '/includes/lia-css.php';

// instantiate the admin menu
new Logged_In_As_Options();

// .. and the business end
new Logged_In_As_Action();

/**
 * Clean up database on uninstall
 */
function liam_uninstall() {
	delete_option( Logged_in_As_Options::$target_menu_id_option );
	delete_option( Logged_in_As_Options::$menu_name_format_option );
	delete_option( Logged_in_As_Options::$avatar_size_option );
}
register_uninstall_hook( __FILE__, '\logged_in_as\liam_uninstall' ); 



/**
 * For debugging
 *
 * https://www.php.net/manual/en/function.var-dump.php
 * /
function log_dump( $mixed ) {

  ob_start();
  var_dump( $mixed );
  $content = ob_get_contents();
  ob_end_clean();
  
  error_log( $content );
  return $content;
}

function log_print( $mixed, $label = null ) {
	
	$str = print_r( $mixed, true );
	
	if ( null !== $label && is_string( $label ) )
		$str = $label . ' ' . $str;
		
	error_log( $str );
}
//*/


