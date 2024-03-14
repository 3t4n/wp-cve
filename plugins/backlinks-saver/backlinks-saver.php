<?php
/*
Plugin Name: Backlinks Saver
Plugin URI: http://nimrodflores.com/backlinks-saver
Description: Based on the greatly useful but long outdated plugin "Link Juice Keeper", this helps you save the link juice from existing backlinks to non-existent pages on your site by redirecting them to the home page with 301 status code.
Author: Nimrod Flores
Version: 1.0
Author: Nimrod Flores
Author URI: http://nimrodflores.com
License: GPL2
*/

/*  Copyright 2014  Nimrod Flores, nimrodflores.com  (contact@nimrodflores.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Add action hook:
add_action('wp', 'backlinks_saver', 100);

// Straightforward, plain & simple function that does the trick:
function backlinks_saver() {
	global $wp_query;
	if ( $wp_query->is_404 ) {
		
		$root = '/';
		if ( preg_match( '#^http://[^/]+(/.+)$#', get_option( 'siteurl' ), $matches ) ) {
			$root = $matches[1];
		}
		
		// Make sure it ends with slash
		if ( $root[ strlen($root) - 1 ] != '/' ) {
			$root .= '/';
		}
		
		// Check if request is not for GWT verification file
		if ( strpos( $_SERVER['REQUEST_URI'], $root.'noexist_' ) !== 0 ) {
			wp_redirect( get_bloginfo( 'siteurl' ) , 301 );
			exit();
		}
	}
}

?>